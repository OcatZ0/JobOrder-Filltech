@extends('layout.app')

@section('title', 'Tambah Pesanan Pekerjaan')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold text-slate-700">Tambah Pesanan Pekerjaan</h1>
            <p class="text-gray-500 mt-2">Buat pesanan pekerjaan baru</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-primary">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Description -->
                    <div>
                        <label for="job_description" class="block text-sm font-medium text-slate-700 mb-2">
                            Deskripsi Pekerjaan
                        </label>
                        <textarea
                            id="job_description"
                            name="job_description"
                            class="textarea textarea-bordered w-full @error('job_description') textarea-error @enderror"
                            placeholder="Masukkan deskripsi pekerjaan"
                            rows="3"
                            required
                        >{{ old('job_description') }}</textarea>
                        @error('job_description')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-slate-700 mb-2">
                            Ditugaskan Ke
                        </label>
                        <select
                            id="assigned_to"
                            name="assigned_to"
                            class="select select-bordered w-full @error('assigned_to') select-error @enderror"
                            required
                        >
                            <option value="">Pilih pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start Date & Time -->
                    <div>
                        <label for="start_at" class="block text-sm font-medium text-slate-700 mb-2">
                            Mulai
                        </label>
                        <input
                            type="datetime-local"
                            id="start_at"
                            name="start_at"
                            class="input input-bordered w-full @error('start_at') input-error @enderror"
                            value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}"
                            required
                        />
                        @error('start_at')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date & Time -->
                    <div>
                        <label for="end_at" class="block text-sm font-medium text-slate-700 mb-2">
                            Selesai
                        </label>
                        <input
                            type="datetime-local"
                            id="end_at"
                            name="end_at"
                            class="input input-bordered w-full @error('end_at') input-error @enderror"
                            value="{{ old('end_at', now()->format('Y-m-d\TH:i')) }}"
                            required
                        />
                        @error('end_at')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            class="btn btn-primary flex-1"
                        >
                            Simpan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline flex-1">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Informasi</h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex gap-3">
                        <span class="text-lg">📋</span>
                        <span><strong>Nomor Pekerjaan:</strong> Nomor urut pekerjaan untuk hari ini</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-lg">📝</span>
                        <span><strong>Deskripsi:</strong> Jelaskan detail pekerjaan yang akan dilakukan</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-lg">👤</span>
                        <span><strong>Ditugaskan Ke:</strong> Pilih pengguna yang akan mengerjakan pekerjaan ini</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-lg">⏰</span>
                        <span><strong>Waktu:</strong> Tentukan kapan pekerjaan dimulai dan selesai</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-lg">🎯</span>
                        <span><strong>Status:</strong> Pilih status awal pekerjaan (biasanya "Menunggu Persetujuan")</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
