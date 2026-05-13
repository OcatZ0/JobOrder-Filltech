@extends('layout.app')

@section('title', 'Detail Pekerjaan')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold text-slate-700">Detail Pekerjaan</h1>
            <p class="text-gray-500 mt-2">{{ $job->job_description }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline">
                Kembali
            </a>
        </div>
    </div>

    <!-- Job Info Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Job Number -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">No Pekerjaan</h3>
            <p class="text-2xl font-semibold text-slate-700">{{ $job->number_of_the_day }}</p>
        </div>

        <!-- Status -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Status</h3>
            <div class="mt-2">
                @php
                    $statusColors = [
                        'waiting_acc' => 'badge-info',
                        'on_progress' => 'badge-warning',
                        'pending' => 'badge-error',
                        'done' => 'badge-success',
                    ];
                    $statusLabels = [
                        'waiting_acc' => 'Menunggu Persetujuan',
                        'on_progress' => 'Sedang Dikerjakan',
                        'pending' => 'Pending',
                        'done' => 'Selesai',
                    ];
                @endphp
                <span class="badge {{ $statusColors[$job->status] ?? 'badge-ghost' }}">
                    {{ $statusLabels[$job->status] ?? $job->status }}
                </span>
                @if($job->status === 'pending' && $job->pending_reason)
                <div class="text-sm text-gray-600 mt-2">{{ $job->pending_reason }}</div>
                @endif
            </div>
        </div>

        <!-- Assigned User -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Ditugaskan Ke</h3>
            <p class="text-slate-700">{{ $job->job_details[0]?->job_assigments[0]?->user?->name ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Tanggal Mulai</h3>
            <p class="text-slate-700">{{ $job->start_at?->format('d M Y H:i') ?? 'N/A' }}</p>
        </div>

        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Tanggal Selesai</h3>
            <p class="text-slate-700">{{ $job->job_details[0]?->end_at?->format('d M Y H:i') ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Documentation Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-700">Dokumentasi</h2>
        <a href="{{ route('job-documentation.create', $job) }}" class="btn btn-primary btn-sm">
            + Tambah Dokumentasi
        </a>
    </div>

    <!-- Documentation Grid -->
    @if($jobDetails->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobDetails as $detail)
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <!-- Image -->
                <div class="bg-gray-100 h-48 flex items-center justify-center overflow-hidden">
                    @if($detail->picture_filename && file_exists(storage_path('app/public/job-details/' . $detail->picture_filename)))
                        <img src="{{ asset('storage/job-details/' . $detail->picture_filename) }}" alt="Dokumentasi" class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm">Gambar tidak ditemukan</p>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-semibold text-slate-700 mb-1 truncate" title="{{ $detail->picture_filename }}">
                        {{ $detail->picture_filename }}
                    </h3>
                    @if($detail->picture_description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ $detail->picture_description }}
                    </p>
                    @else
                    <p class="text-sm text-gray-400 mb-4 italic">Tidak ada deskripsi</p>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('job-documentation.show', $detail) }}" class="btn btn-xs btn-outline flex-1">Lihat</a>
                        <a href="{{ route('job-documentation.edit', $detail) }}" class="btn btn-xs btn-outline flex-1">Edit</a>
                        <form action="{{ route('job-documentation.destroy', $detail) }}" method="POST" class="inline flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-outline btn-error w-full" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="text-gray-500 text-lg mb-4">Belum ada dokumentasi untuk pekerjaan ini</p>
        <a href="{{ route('job-documentation.create', $job) }}" class="btn btn-primary">
            Tambah Dokumentasi Pertama
        </a>
    </div>
    @endif
</div>
@endsection
