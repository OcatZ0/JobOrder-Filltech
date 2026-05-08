@extends('layout.app')

@section('title', 'Detail Dokumentasi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold text-slate-700">Detail Dokumentasi</h1>
            <p class="text-gray-500 mt-2">Informasi lengkap dokumentasi pekerjaan</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dokumentasi.edit', $jobDetail) }}" class="btn btn-outline">Edit</a>
            <a href="{{ route('dokumentasi.index') }}" class="btn btn-outline btn-primary">Kembali</a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-8 max-w-2xl">
        <!-- Pekerjaan -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Pekerjaan</h3>
            <p class="text-lg text-slate-700 font-semibold">{{ $jobDetail->job->job_description ?? 'N/A' }}</p>
            <p class="text-sm text-gray-500 mt-1">No: {{ $jobDetail->job->number_of_the_day ?? 'N/A' }}</p>
        </div>

        <div class="divider"></div>

        <!-- Gambar -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Gambar</h3>
            @if($jobDetail->picture_filename && file_exists(storage_path('app/public/job-details/' . $jobDetail->picture_filename)))
                <img src="{{ asset('storage/job-details/' . $jobDetail->picture_filename) }}" alt="Gambar Dokumentasi" class="w-full max-w-md rounded-lg border border-gray-200">
            @else
                <p class="text-base text-gray-500">File gambar tidak ditemukan</p>
            @endif
        </div>

        <div class="divider"></div>

        <!-- Nama File Gambar -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Nama File Gambar</h3>
            <p class="text-base text-slate-700">{{ $jobDetail->picture_filename }}</p>
        </div>

        <div class="divider"></div>

        <!-- Deskripsi Gambar -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Deskripsi Gambar</h3>
            <p class="text-base text-slate-700 whitespace-pre-wrap">{{ $jobDetail->picture_description }}</p>
        </div>

        <div class="divider"></div>

        <!-- Delete Button -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('job-documentation.edit', $jobDetail) }}" class="btn btn-outline">Edit</a>
            <form action="{{ route('job-documentation.destroy', $jobDetail) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error" onclick="return confirm('Yakin ingin menghapus dokumentasi ini?')">Hapus Dokumentasi</button>
            </form>
        </div>
    </div>
</div>
@endsection
