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
        <!-- Status Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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

            <!-- Pending -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Ditangguhkan</p>
                    <p class="text-4xl font-bold text-red-500">{{ $todayStatusCounts['pending'] ?? 0 }}</p>
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
        <!-- Total Jobs Row -->
        <div class="grid grid-cols-1 gap-6">
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total Pesanan</p>
                    <p class="text-4xl font-bold text-indigo-600">{{ $jobs->count() }}</p>
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
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->job_details[0]?->job_assigments[0]?->user?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->start_at->format('H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $job->job_details[0]?->end_at?->format('H:i') ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="status-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $job->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($job->status === 'on_progress' ? 'bg-amber-100 text-amber-700' : ($job->status === 'pending' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}" data-job-id="{{ $job->id }}" data-status="{{ $job->status }}">
                                <span class="w-2 h-2 rounded-full {{ $job->status === 'done' ? 'bg-emerald-500' : ($job->status === 'on_progress' ? 'bg-amber-500' : ($job->status === 'pending' ? 'bg-red-500' : 'bg-blue-500')) }}"></span>
                                <span class="status-text">{{ $job->status === 'done' ? 'Selesai' : ($job->status === 'on_progress' ? 'Sedang Berjalan' : ($job->status === 'pending' ? 'Ditangguhkan' : 'Menunggu Persetujuan')) }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('job.details', $job) }}" class="btn btn-xs btn-outline">Lihat Detail</a>
                                <a href="{{ route('job-documentation.create', $job) }}" class="btn btn-xs btn-outline">Tambah Dokumentasi</a>
                                <button class="btn btn-xs btn-outline btn-change-status" data-job-id="{{ $job->id }}" data-current-status="{{ $job->status }}">Ubah Status</button>
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

<!-- Status Change Modal -->
<dialog id="statusModal" class="modal">
    <div class="modal-box w-full max-w-md">
        <form method="dialog">
            <button type="submit" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg text-slate-700 mb-4">Ubah Status Pekerjaan</h3>
        <form id="statusForm" class="space-y-4">
            @csrf
            <input type="hidden" id="jobId" name="job_id">
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium text-slate-700">Status</span>
                </label>
                <select id="statusSelect" name="status" class="select select-bordered w-full" required>
                    <option value="waiting_acc">Menunggu Persetujuan</option>
                    <option value="on_progress">Sedang Berjalan</option>
                    <option value="pending">Ditangguhkan</option>
                    <option value="done">Selesai</option>
                </select>
            </div>

            <div id="pendingReasonContainer" class="form-control hidden">
                <label class="label">
                    <span class="label-text font-medium text-slate-700">Alasan Ditangguhkan</span>
                </label>
                <textarea id="pendingReason" name="pending_reason" class="textarea textarea-bordered h-24 w-100" placeholder="Jelaskan alasan penundaan..."></textarea>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-outline">Batal</button>
                </form>
                <button type="submit" id="submitBtn" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const todayStatusData = {
            'Menunggu Persetujuan': {{ $todayStatusCounts['waiting_acc'] ?? 0 }},
            'Sedang Berjalan': {{ $todayStatusCounts['on_progress'] ?? 0 }},
            'Ditangguhkan': {{ $todayStatusCounts['pending'] ?? 0 }},
            'Selesai': {{ $todayStatusCounts['done'] ?? 0 }}
        };

        const ctx = document.getElementById('todayStatusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(todayStatusData),
                datasets: [{
                    data: Object.values(todayStatusData),
                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'],
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

        // Status change modal handling
        const statusModal = document.getElementById('statusModal');
        const statusForm = document.getElementById('statusForm');
        const jobIdInput = document.getElementById('jobId');
        const statusSelect = document.getElementById('statusSelect');
        const pendingReasonContainer = document.getElementById('pendingReasonContainer');
        const pendingReason = document.getElementById('pendingReason');
        const submitBtn = document.getElementById('submitBtn');

        // Open modal when "Ubah Status" button is clicked
        document.querySelectorAll('.btn-change-status').forEach(button => {
            button.addEventListener('click', function() {
                const jobId = this.dataset.jobId;
                const currentStatus = this.dataset.currentStatus;
                jobIdInput.value = jobId;
                statusSelect.value = currentStatus;
                statusForm.reset();
                // Re-set the status select after reset
                statusSelect.value = currentStatus;
                // Trigger change event to show/hide pending reason field
                statusSelect.dispatchEvent(new Event('change'));
                statusModal.showModal();
            });
        });

        // Show pending reason field only when status is "pending"
        statusSelect.addEventListener('change', function() {
            if (this.value === 'pending') {
                pendingReasonContainer.classList.remove('hidden');
                pendingReason.required = true;
            } else {
                pendingReasonContainer.classList.add('hidden');
                pendingReason.required = false;
                pendingReason.value = '';
            }
        });

        // Handle form submission
        statusForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const jobId = jobIdInput.value;
            const status = statusSelect.value;
            const reason = pendingReason.value;

            if (status === 'pending' && !reason) {
                alert('Alasan penundaan harus diisi');
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            try {
                const response = await fetch(`/jobs/${jobId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        status: status,
                        pending_reason: reason || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update the status badge in the table row
                    const statusBadge = document.querySelector(`[data-job-id="${jobId}"].status-badge`);
                    if (statusBadge) {
                        const statusText = statusBadge.querySelector('.status-text');
                        const dot = statusBadge.querySelector('.w-2');

                        // Update text
                        statusText.textContent = data.data.status_label;

                        // Remove all status classes
                        statusBadge.className = 'status-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold';
                        dot.className = 'w-2 h-2 rounded-full';

                        // Add new status classes based on status value
                        switch(status) {
                            case 'done':
                                statusBadge.classList.add('bg-emerald-100', 'text-emerald-700');
                                dot.classList.add('bg-emerald-500');
                                break;
                            case 'on_progress':
                                statusBadge.classList.add('bg-amber-100', 'text-amber-700');
                                dot.classList.add('bg-amber-500');
                                break;
                            case 'pending':
                                statusBadge.classList.add('bg-red-100', 'text-red-700');
                                dot.classList.add('bg-red-500');
                                break;
                            default:
                                statusBadge.classList.add('bg-blue-100', 'text-blue-700');
                                dot.classList.add('bg-blue-500');
                        }

                        // Update the data-status attribute
                        statusBadge.dataset.status = status;
                    }

                    // Close modal and show success message
                    statusModal.close();
                    
                    // Show toast notification
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success shadow-lg fixed bottom-4 right-4 max-w-xs';
                    toast.innerHTML = `
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>${data.message}</span>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                } else {
                    alert('Gagal mengubah status: ' + (data.message || 'Error tidak diketahui'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            } finally {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
            }
        });
    });
</script>
@endsection
