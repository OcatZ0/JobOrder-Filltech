<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with today's job orders.
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();

        $jobs = Job::with(['user', 'job_details.job_assigments.user'])
            ->where('is_deleted', false)
            ->whereDate('start_at', $today)
            ->orderBy('number_of_the_day', 'asc')
            ->get();

        // Get today's status counts
        $todayStatusCounts = Job::where('is_deleted', false)
            ->whereDate('start_at', $today)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('main.index', compact('jobs', 'todayStatusCounts'));
    }

    /**
     * Show all job orders.
     */
    public function all()
    {
        $jobs = Job::with(['user', 'job_details.job_assigments.user'])
            ->where('is_deleted', false)
            ->orderBy('start_at', 'desc')
            ->get();

        // Get all status counts
        $allStatusCounts = Job::where('is_deleted', false)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('main.all', compact('jobs', 'allStatusCounts'));
    }

    /**
     * Show the create job form.
     */
    public function create()
    {
        $users = User::where('is_deleted', false)
            ->where('id', '!=', auth()->id())
            ->get();

        $classifications = \App\Models\JobClassification::all();

        return view('main.create', compact('users', 'classifications'));
    }

    /**
     * Store a new job.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_description' => 'required|string|max:255',
            'job_classification_id' => 'required|exists:job_classification,id',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => 'exists:user,id',
            'start_at' => 'required|date_format:Y-m-d\TH:i',
            'duration' => 'required|integer|min:1|max:1440',
        ], [
            'job_description.required' => 'Deskripsi pekerjaan tidak boleh kosong',
            'job_description.max' => 'Deskripsi pekerjaan maksimal 255 karakter',
            'job_classification_id.required' => 'Klasifikasi pekerjaan harus dipilih',
            'job_classification_id.exists' => 'Klasifikasi pekerjaan yang dipilih tidak valid',
            'assigned_to.required' => 'Minimal satu pengguna harus ditugaskan',
            'assigned_to.array' => 'Format pengguna yang ditugaskan tidak valid',
            'assigned_to.min' => 'Minimal satu pengguna harus ditugaskan',
            'assigned_to.*.exists' => 'Salah satu pengguna yang dipilih tidak valid',
            'start_at.required' => 'Waktu mulai tidak boleh kosong',
            'start_at.date_format' => 'Format waktu mulai tidak valid',
            'duration.required' => 'Durasi tidak boleh kosong',
            'duration.integer' => 'Durasi harus berupa angka',
            'duration.min' => 'Durasi minimal 1 menit',
            'duration.max' => 'Durasi maksimal 1440 menit',
        ]);

        $today = Carbon::now()->toDateString();
        $numberForToday = Job::where('is_deleted', false)
            ->whereDate('start_at', $today)
            ->count() + 1;

        $job = Job::create([
            'number_of_the_day' => $numberForToday,
            'job_classification_id' => $validated['job_classification_id'],
            'created_by' => auth()->id(),
            'job_description' => $validated['job_description'],
            'start_at' => $validated['start_at'],
            'duration' => $validated['duration'],
            'status' => 'waiting_acc',
            'is_deleted' => false,
        ]);

        // Create job detail
        $jobDetail = $job->job_details()->create([
            'start_at' => $validated['start_at'],
            'is_deleted' => false,
        ]);

        // Create job assignments for each assigned user
        foreach ($validated['assigned_to'] as $userId) {
            $jobDetail->job_assigments()->create([
                'assigned_to' => $userId,
                'is_delete' => false,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Pesanan pekerjaan berhasil ditambahkan');
    }

    /**
     * Update job status via API.
     */
    public function updateStatus(Request $request, Job $job)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_acc,on_progress,pending,done',
            'pending_reason' => 'nullable|string|max:200|required_if:status,pending',
        ], [
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
            'pending_reason.required_if' => 'Alasan pending harus diisi',
            'pending_reason.max' => 'Alasan pending maksimal 200 karakter',
        ]);

        $job->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status pekerjaan berhasil diperbarui',
            'data' => [
                'id' => $job->id,
                'status' => $job->status,
                'status_label' => $this->getStatusLabel($job->status),
            ]
        ]);
    }

    /**
     * Get status label.
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            'done' => 'Selesai',
            'on_progress' => 'Sedang Berjalan',
            'pending' => 'Ditangguhkan',
            default => 'Menunggu Persetujuan',
        };
    }
}
