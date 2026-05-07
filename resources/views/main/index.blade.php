@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold text-slate-700">Pesanan Pekerjaan Hari Ini</h1>
            <p class="text-gray-500 mt-2">Lihat dan kelola semua pesanan pekerjaan hari ini</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary">
                Tambah Pekerjaan
            </a>
            <a href="{{ route('jobs.all') }}" class="btn btn-outline btn-primary">
                Lihat Semua
            </a>
        </div>
    </div>

    <!-- Overview Section -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-slate-700 mb-6">Ringkasan Status</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Jobs -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total Pesanan</p>
                    <p class="text-4xl font-bold text-indigo-600">{{ $jobs->count() }}</p>
                </div>
            </div>

            <!-- Waiting Approval -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Menunggu Persetujuan</p>
                    <p class="text-4xl font-bold text-blue-500">{{ $todayStatusCounts['waiting_acc'] ?? 0 }}</p>
                </div>
            </div>

            <!-- On Progress -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Sedang Berjalan</p>
                    <p class="text-4xl font-bold text-amber-500">{{ $todayStatusCounts['on_progress'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Done -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Selesai</p>
                    <p class="text-4xl font-bold text-emerald-500">{{ $todayStatusCounts['done'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    @if($jobs->count() > 0)
    <div class="mb-8">
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-slate-700 mb-6">Distribusi Status</h3>
            <div class="flex justify-center">
                <div style="width: 100%; max-width: 400px;">
                    <canvas id="todayStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Jobs Table -->
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Ditugaskan Ke</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Mulai</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Selesai</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->number_of_the_day }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->job_description }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->creator->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->assignedUser->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->start_at->format('H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->end_at->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $job->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($job->status === 'on_progress' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                <span class="w-2 h-2 rounded-full {{ $job->status === 'done' ? 'bg-emerald-500' : ($job->status === 'on_progress' ? 'bg-amber-500' : 'bg-blue-500') }}"></span>
                                {{ $job->status === 'done' ? 'Selesai' : ($job->status === 'on_progress' ? 'Sedang Berjalan' : 'Menunggu Persetujuan') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button class="btn btn-xs btn-outline">Lihat</button>
                                <button class="btn btn-xs btn-outline">Edit</button>
                                <button class="btn btn-xs btn-outline">Ubah Status</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada pesanan pekerjaan hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const todayStatusData = {
            'Menunggu Persetujuan': {{ $todayStatusCounts['waiting_acc'] ?? 0 }},
            'Sedang Berjalan': {{ $todayStatusCounts['on_progress'] ?? 0 }},
            'Selesai': {{ $todayStatusCounts['done'] ?? 0 }}
        };

        const ctx = document.getElementById('todayStatusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(todayStatusData),
                datasets: [{
                    data: Object.values(todayStatusData),
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 12 },
                            padding: 15
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
