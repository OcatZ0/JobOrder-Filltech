<?php

namespace App\Http\Controllers;

use App\Models\JobDetail;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobDetailController extends Controller
{
    /**
     * Display job details with all documentation.
     */
    public function jobDetails(Job $job)
    {
        $jobDetails = JobDetail::where('job_detail_id', $job->id)
            ->where('is_deleted', false)
            ->orderBy('id', 'desc')
            ->get();

        return view('main.job', compact('job', 'jobDetails'));
    }

    /**
     * Show the form for creating a new job detail.
     */
    public function create(Job $job)
    {
        return view('main.dokumentasi-create', compact('job'));
    }

    /**
     * Store a newly created job detail in storage.
     */
    public function store(Request $request, Job $job)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*.picture_file' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'files.*.picture_description' => 'nullable|string|max:200',
        ], [
            'files.required' => 'Minimal satu file harus ditambahkan',
            'files.min' => 'Minimal satu file harus ditambahkan',
            'files.*.picture_file.required' => 'File gambar tidak boleh kosong',
            'files.*.picture_file.image' => 'File harus berupa gambar',
            'files.*.picture_file.mimes' => 'Format file harus PNG, JPG, atau JPEG',
            'files.*.picture_file.max' => 'Ukuran file maksimal 5MB',
            'files.*.picture_description.max' => 'Deskripsi gambar maksimal 200 karakter',
        ]);

        foreach ($request->file('files') as $index => $file) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file['picture_file']->getClientOriginalExtension();
            
            // Store file
            $path = $file['picture_file']->storeAs('job-details', $filename, 'public');
            
            // Save to database
            JobDetail::create([
                'job_detail_id' => $job->id,
                'picture_filename' => $filename,
                'picture_description' => $validated['files'][$index]['picture_description'] ?? null,
                'is_deleted' => false,
            ]);
        }

        return redirect()->route('job.details', $job->id)->with('success', 'Dokumentasi berhasil ditambahkan');
    }

    /**
     * Display the specified job detail.
     */
    public function show(JobDetail $jobDetail)
    {
        if ($jobDetail->is_deleted) {
            abort(404);
        }

        return view('main.dokumentasi-show', compact('jobDetail'));
    }

    /**
     * Show the form for editing the specified job detail.
     */
    public function edit(JobDetail $jobDetail)
    {
        if ($jobDetail->is_deleted) {
            abort(404);
        }

        return view('main.dokumentasi-edit', compact('jobDetail'));
    }

    /**
     * Update the specified job detail in storage.
     */
    public function update(Request $request, JobDetail $jobDetail)
    {
        if ($jobDetail->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'picture_file' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'picture_description' => 'nullable|string|max:200',
        ], [
            'picture_file.image' => 'File harus berupa gambar',
            'picture_file.mimes' => 'Format file harus PNG, JPG, atau JPEG',
            'picture_file.max' => 'Ukuran file maksimal 5MB',
            'picture_description.max' => 'Deskripsi gambar maksimal 200 karakter',
        ]);

        $updateData = [
            'picture_description' => $validated['picture_description'] ?? $jobDetail->picture_description,
        ];

        // If new file is uploaded
        if ($request->hasFile('picture_file')) {
            // Delete old file
            if ($jobDetail->picture_filename && Storage::disk('public')->exists('job-details/' . $jobDetail->picture_filename)) {
                Storage::disk('public')->delete('job-details/' . $jobDetail->picture_filename);
            }
            
            // Store new file
            $file = $request->file('picture_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('job-details', $filename, 'public');
            
            $updateData['picture_filename'] = $filename;
        }

        $jobDetail->update($updateData);

        return redirect()->route('job.details', $jobDetail->job->id)->with('success', 'Dokumentasi berhasil diperbarui');
    }

    /**
     * Remove (soft delete) the specified job detail from storage.
     */
    public function destroy(JobDetail $jobDetail)
    {
        if ($jobDetail->is_deleted) {
            abort(404);
        }

        // Delete file from storage
        if ($jobDetail->picture_filename && Storage::disk('public')->exists('job-details/' . $jobDetail->picture_filename)) {
            Storage::disk('public')->delete('job-details/' . $jobDetail->picture_filename);
        }

        $jobDetail->update(['is_deleted' => true]);

        return redirect()->route('job.details', $jobDetail->job->id)->with('success', 'Dokumentasi berhasil dihapus');
    }
}
