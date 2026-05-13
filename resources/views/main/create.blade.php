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
                <form id="jobForm" action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Job Classification -->
                    <div>
                        <label for="job_classification_id" class="block text-sm font-medium text-slate-700 mb-2">
                            Klasifikasi Pekerjaan
                        </label>
                        <select
                            id="job_classification_id"
                            name="job_classification_id"
                            class="select select-bordered w-full @error('job_classification_id') select-error @enderror"
                        >
                            @foreach($classifications as $classification)
                                @php
                                    $days = intdiv($classification->duration, 1440);
                                    $remaining = $classification->duration % 1440;
                                    $hours = intdiv($remaining, 60);
                                    $minutes = $remaining % 60;
                                    
                                    if ($days > 0) {
                                        $durationDisplay = "$days hari $hours jam $minutes menit";
                                    } elseif ($hours > 0) {
                                        $durationDisplay = "$hours jam $minutes menit";
                                    } else {
                                        $durationDisplay = "$minutes menit";
                                    }
                                @endphp
                                <option value="{{ $classification->id }}" data-duration="{{ $classification->duration }}" {{ old('job_classification_id') == $classification->id ? 'selected' : '' }}>
                                    {{ $classification->job_name }} ({{ $durationDisplay }})
                                </option>
                            @endforeach
                        </select>
                        @error('job_classification_id')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

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
                            Ditugaskan Ke (Pilih satu atau lebih)
                        </label>
                        <select
                            id="assigned_to"
                            name="assigned_to[]"
                            class="select select-bordered w-full @error('assigned_to') select-error @enderror"
                            multiple
                        >
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('assigned_to', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                        <span id="assigned_to_error" class="text-error text-sm mt-1 block hidden">Minimal satu pengguna harus dipilih</span>
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

                    <!-- Duration with Day, Hour, Minute -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Durasi
                        </label>
                        
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="duration_day" class="block text-xs font-medium text-gray-600 mb-1">
                                    Hari
                                </label>
                                <input
                                    type="number"
                                    id="duration_day"
                                    class="input input-bordered input-sm w-full"
                                    placeholder="0"
                                    value="0"
                                    min="0"
                                    max="365"
                                />
                            </div>
                            <div>
                                <label for="duration_hour" class="block text-xs font-medium text-gray-600 mb-1">
                                    Jam
                                </label>
                                <input
                                    type="number"
                                    id="duration_hour"
                                    class="input input-bordered input-sm w-full"
                                    placeholder="0"
                                    value="1"
                                    min="0"
                                    max="23"
                                />
                            </div>
                            <div>
                                <label for="duration_minute" class="block text-xs font-medium text-gray-600 mb-1">
                                    Menit
                                </label>
                                <input
                                    type="number"
                                    id="duration_minute"
                                    class="input input-bordered input-sm w-full"
                                    placeholder="0"
                                    value="0"
                                    min="0"
                                    max="59"
                                />
                            </div>
                        </div>

                        <!-- Hidden duration field for form submission -->
                        <input
                            type="hidden"
                            id="duration"
                            name="duration"
                            value="60"
                        />
                        @error('duration')
                            <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Estimated End Time (Readonly) -->
                    <div>
                        <label for="estimated_end" class="block text-sm font-medium text-slate-700 mb-2">
                            Estimasi Selesai
                        </label>
                        <input
                            type="datetime-local"
                            id="estimated_end"
                            name="estimated_end"
                            class="input input-bordered w-full bg-gray-100"
                            readonly
                        />
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

                <script>
                    const startAtInput = document.getElementById('start_at');
                    const classificationSelect = document.getElementById('job_classification_id');
                    const durationDayInput = document.getElementById('duration_day');
                    const durationHourInput = document.getElementById('duration_hour');
                    const durationMinuteInput = document.getElementById('duration_minute');
                    const durationInput = document.getElementById('duration');
                    const estimatedEndInput = document.getElementById('estimated_end');

                    function convertMinutesToDayHourMinute(totalMinutes) {
                        const days = Math.floor(totalMinutes / 1440);
                        const remainingMinutes = totalMinutes % 1440;
                        const hours = Math.floor(remainingMinutes / 60);
                        const minutes = remainingMinutes % 60;
                        
                        return { days, hours, minutes };
                    }

                    function setDurationFromMinutes(totalMinutes) {
                        const { days, hours, minutes } = convertMinutesToDayHourMinute(totalMinutes);
                        durationDayInput.value = days;
                        durationHourInput.value = hours;
                        durationMinuteInput.value = minutes;
                        updateEstimatedEnd();
                    }

                    function calculateTotalMinutes() {
                        const days = parseInt(durationDayInput.value) || 0;
                        const hours = parseInt(durationHourInput.value) || 0;
                        const minutes = parseInt(durationMinuteInput.value) || 0;
                        
                        const totalMinutes = (days * 1440) + (hours * 60) + minutes;
                        return totalMinutes > 0 ? totalMinutes : 1;
                    }

                    function updateEstimatedEnd() {
                        if (!startAtInput.value) return;

                        const startDate = new Date(startAtInput.value);
                        const duration = calculateTotalMinutes();
                        durationInput.value = duration;
                        
                        const endDate = new Date(startDate.getTime() + duration * 60000);

                        const year = endDate.getFullYear();
                        const month = String(endDate.getMonth() + 1).padStart(2, '0');
                        const day = String(endDate.getDate()).padStart(2, '0');
                        const hours = String(endDate.getHours()).padStart(2, '0');
                        const mins = String(endDate.getMinutes()).padStart(2, '0');

                        estimatedEndInput.value = `${year}-${month}-${day}T${hours}:${mins}`;
                    }

                    // Classification change handler
                    classificationSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption.value) {
                            const duration = parseInt(selectedOption.getAttribute('data-duration'));
                            setDurationFromMinutes(duration);
                        }
                    });

                    startAtInput.addEventListener('change', updateEstimatedEnd);
                    startAtInput.addEventListener('input', updateEstimatedEnd);
                    durationDayInput.addEventListener('change', updateEstimatedEnd);
                    durationDayInput.addEventListener('input', updateEstimatedEnd);
                    durationHourInput.addEventListener('change', updateEstimatedEnd);
                    durationHourInput.addEventListener('input', updateEstimatedEnd);
                    durationMinuteInput.addEventListener('change', updateEstimatedEnd);
                    durationMinuteInput.addEventListener('input', updateEstimatedEnd);

                    // Initial calculation on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        // If a classification is already selected, auto-fill duration
                        if (classificationSelect.value) {
                            const selectedOption = classificationSelect.options[classificationSelect.selectedIndex];
                            const duration = parseInt(selectedOption.getAttribute('data-duration'));
                            setDurationFromMinutes(duration);
                        } else {
                            // Otherwise just calculate from current inputs
                            const totalMinutes = calculateTotalMinutes();
                            durationInput.value = totalMinutes;
                            updateEstimatedEnd();
                        }
                    });

                    // Fallback for immediate calculation (in case DOMContentLoaded already fired)
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', function() {
                            if (classificationSelect.value) {
                                const selectedOption = classificationSelect.options[classificationSelect.selectedIndex];
                                const duration = parseInt(selectedOption.getAttribute('data-duration'));
                                setDurationFromMinutes(duration);
                            } else {
                                updateEstimatedEnd();
                            }
                        });
                    } else {
                        if (classificationSelect.value) {
                            const selectedOption = classificationSelect.options[classificationSelect.selectedIndex];
                            const duration = parseInt(selectedOption.getAttribute('data-duration'));
                            setDurationFromMinutes(duration);
                        } else {
                            updateEstimatedEnd();
                        }
                    }

                    // Form validation for assigned_to
                    const jobForm = document.getElementById('jobForm');
                    const assignedToSelect = document.getElementById('assigned_to');
                    const assignedToError = document.getElementById('assigned_to_error');

                    jobForm.addEventListener('submit', function(e) {
                        if (assignedToSelect.selectedOptions.length === 0) {
                            e.preventDefault();
                            assignedToError.classList.remove('hidden');
                            assignedToSelect.classList.add('select-error');
                        } else {
                            assignedToError.classList.add('hidden');
                            assignedToSelect.classList.remove('select-error');
                        }
                    });

                    assignedToSelect.addEventListener('change', function() {
                        if (this.selectedOptions.length > 0) {
                            assignedToError.classList.add('hidden');
                            this.classList.remove('select-error');
                        }
                    });
                </script>
            </div>
        </div>

        <!-- Info Section -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Informasi</h3>
                <ul class="space-y-3 text-sm text-blue-800">
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
