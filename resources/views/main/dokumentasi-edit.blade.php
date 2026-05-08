@extends('layout.app')

@section('title', 'Edit Dokumentasi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-slate-700">Edit Dokumentasi</h1>
        <p class="text-gray-500 mt-2">Perbarui dokumentasi gambar dan keterangan</p>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('job-documentation.update', $jobDetail) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Picture File -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium text-slate-700">File Gambar</span>
                </label>
                <input type="file" name="picture_file" accept=".png,.jpg,.jpeg" class="file-input file-input-bordered w-full @error('picture_file') file-input-error @enderror">
                <label class="label">
                    <span class="label-text-alt text-gray-500">Format: PNG, JPG, JPEG | Maksimal: 5MB | Kosongkan jika tidak ingin mengubah</span>
                </label>
                @error('picture_file')
                <label class="label">
                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                </label>
                @enderror
                @if($jobDetail->picture_filename)
                <label class="label">
                    <span class="label-text-alt text-blue-600">File saat ini: {{ $jobDetail->picture_filename }}</span>
                </label>
                @endif
            </div>

            <!-- Picture Description -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium text-slate-700">Deskripsi Gambar</span>
                </label>
                <textarea name="picture_description" placeholder="Jelaskan isi atau konten gambar... (opsional)" class="textarea textarea-bordered h-24 resize-none w-full @error('picture_description') textarea-error @enderror">{{ old('picture_description', $jobDetail->picture_description) }}</textarea>
                @error('picture_description')
                <label class="label">
                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                </label>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="#" onclick="if(history.length > 1){ history.back(); } else { window.location='{{ route('job.details', $jobDetail->job->id) }}'; }" class="btn btn-outline">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
