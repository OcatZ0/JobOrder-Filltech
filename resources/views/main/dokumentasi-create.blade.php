@extends('layout.app')

@section('title', 'Tambah Dokumentasi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-slate-700">Tambah Dokumentasi</h1>
        <p class="text-gray-500 mt-2">
            <span class="font-medium">Pekerjaan:</span> {{ $job->job_description }} (No: {{ $job->number_of_the_day }})
        </p>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('job-documentation.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Files Container -->
            <div id="filesContainer" class="space-y-6">
                <!-- File Row Template -->
                <div class="file-row p-4 border border-gray-200 rounded-lg space-y-4">
<!-- Picture File -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-slate-700">File Gambar <span class="text-red-500">*</span></span>
                    </label>
                    <input type="file" name="files[0][picture_file]" accept=".png,.jpg,.jpeg" class="file-input file-input-bordered w-full" required>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Format: PNG, JPG, JPEG | Maksimal: 5MB</span>
                    </label>
                    </div>

                    <!-- Picture Description -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-slate-700">Deskripsi Gambar</span>
                        </label>
                        <textarea name="files[0][picture_description]" placeholder="Jelaskan isi atau konten gambar... (opsional)" class="textarea textarea-bordered h-20 resize-none w-full"></textarea>
                    </div>

                    <!-- Remove Button -->
                    <div class="flex justify-end">
                        <button type="button" class="btn btn-sm btn-outline btn-error remove-file-btn hidden">Hapus Baris</button>
                    </div>
                </div>
            </div>

            <!-- Add More Button -->
            <div class="flex justify-end">
                <button type="button" id="addFileBtn" class="btn btn-outline">
                    + Tambah File Lainnya
                </button>
            </div>

            @if ($errors->any())
            <div class="alert alert-error">
                <div>
                    <span>Terjadi kesalahan validasi</span>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="#" onclick="if(history.length > 1){ history.back(); } else { window.location='{{ route('jobs.all') }}'; }" class="btn btn-outline">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">Simpan Dokumentasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    let fileRowCount = 1;
    
    document.getElementById('addFileBtn').addEventListener('click', function() {
        const container = document.getElementById('filesContainer');
        const newRow = document.querySelector('.file-row').cloneNode(true);
        
        // Update input names
        newRow.querySelector('input[type="file"]').name = `files[${fileRowCount}][picture_file]`;
        newRow.querySelector('textarea').name = `files[${fileRowCount}][picture_description]`;
        
        // Clear values
        newRow.querySelector('input[type="file"]').value = '';
        newRow.querySelector('textarea').value = '';
        
        // Show remove button
        newRow.querySelector('.remove-file-btn').classList.remove('hidden');
        
        // Add remove listener
        newRow.querySelector('.remove-file-btn').addEventListener('click', function() {
            newRow.remove();
            updateRemoveButtons();
        });
        
        container.appendChild(newRow);
        fileRowCount++;
        updateRemoveButtons();
    });
    
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.file-row');
        rows.forEach(row => {
            const btn = row.querySelector('.remove-file-btn');
            if (rows.length > 1) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        });
    }
    
    // Initialize remove buttons for first row
    document.querySelector('.file-row .remove-file-btn').addEventListener('click', function() {
        this.closest('.file-row').remove();
        updateRemoveButtons();
    });
</script>
@endsection
