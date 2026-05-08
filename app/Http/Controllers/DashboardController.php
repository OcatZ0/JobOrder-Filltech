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

        $jobs = Job::with(['creator', 'assignedUser'])
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
        $jobs = Job::with(['creator', 'assignedUser'])
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

        return view('main.create', compact('users'));
    }

    /**
     * Store a new job.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_description' => 'required|string|max:255',
            'assigned_to' => 'required|exists:user,id',
            'start_at' => 'required|date_format:Y-m-d\TH:i',
            'end_at' => 'required|date_format:Y-m-d\TH:i|after:start_at',
        ], [
            'job_description.required' => 'Deskripsi pekerjaan tidak boleh kosong',
            'job_description.max' => 'Deskripsi pekerjaan maksimal 255 karakter',
            'assigned_to.required' => 'Pengguna yang ditugaskan tidak boleh kosong',
            'assigned_to.exists' => 'Pengguna yang dipilih tidak valid',
            'start_at.required' => 'Waktu mulai tidak boleh kosong',
            'start_at.date_format' => 'Format waktu mulai tidak valid',
            'end_at.required' => 'Waktu selesai tidak boleh kosong',
            'end_at.date_format' => 'Format waktu selesai tidak valid',
            'end_at.after' => 'Waktu selesai harus setelah waktu mulai',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_deleted'] = false;

        Job::create($validated);

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
