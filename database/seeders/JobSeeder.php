<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobDetail;
use App\Models\JobAssigment;
use App\Models\JobClassification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::now();
        $statuses = ['waiting_acc', 'on_progress', 'done'];
        
        // Get job classifications
        $classifications = JobClassification::all();
        
        // ISP Service descriptions
        $jobDescriptions = [
            'PSB (Pemberian Sambungan Baru)' => [
                'Instalasi koneksi internet di Jl. Merdeka No. 42',
                'Pemasangan sambungan baru untuk kantor PT ABC',
                'Setup koneksi fiber di perumahan Graha Indah',
                'Aktifasi sambungan internet residential',
            ],
            'Maintenance' => [
                'Perawatan rutin peralatan network di tower pusat',
                'Inspeksi dan cleaning panel fiber optik',
                'Checking kondisi kabel dan splice point',
                'Maintenance server DNS berkala',
            ],
            'Tarik Kabel' => [
                'Penarikan kabel fiber ke lokasi baru',
                'Instalasi outdoor cable dari ODP ke rumah pelanggan',
                'Penggantian kabel yang rusak di jalur utama',
                'Penarikan kabel indoor ke ruang server',
            ],
            'Upgrade' => [
                'Upgrade paket internet dari 10 Mbps ke 30 Mbps',
                'Peningkatan kecepatan koneksi pelanggan bisnis',
                'Upgrade dari ADSL ke Fiber',
            ],
            'Downgrade' => [
                'Penurunan paket internet sesuai permintaan',
                'Downgrade dari 50 Mbps ke 20 Mbps',
            ],
            'Disconnect' => [
                'Pencabutan sambungan internet atas permintaan',
                'Disconnect layanan yang sudah expired',
            ],
            'Troubleshooting' => [
                'Troubleshooting koneksi internet putus-putus',
                'Diagnosa dan perbaikan masalah kecepatan rendah',
                'Checking issue pengaksesan beberapa website',
                'Resolusi gangguan konektivitas jaringan',
            ],
            'Installation' => [
                'Instalasi ONT (Optical Network Terminal)',
                'Setup modem dan router untuk pelanggan baru',
                'Konfigurasi WiFi dan security network',
            ],
            'Relocation' => [
                'Pemindahan titik access dari lokasi lama ke baru',
                'Relokasi peralatan network ke gedung yang berbeda',
            ],
            'Equipment Replacement' => [
                'Penggantian ONT yang rusak dengan unit baru',
                'Upgrade modem ke model terbaru',
                'Replacement optical splitter di ODP',
            ],
        ];

        // Create jobs for today
        for ($i = 1; $i <= 5; $i++) {
            $startTime = $today->copy()->addHours(8 + ($i - 1) * 2);
            $classification = $classifications[($i - 1) % $classifications->count()];
            $descriptions = $jobDescriptions[$classification->job_name] ?? [$classification->job_name];

            $job = Job::create([
                'number_of_the_day' => $i,
                'job_classification_id' => $classification->id,
                'created_by' => 1,
                'job_description' => $descriptions[array_rand($descriptions)],
                'start_at' => $startTime,
                'duration' => $classification->duration,
                'status' => $statuses[array_rand($statuses)],
                'is_deleted' => false,
            ]);

            $jobDetail = $job->job_details()->create([
                'start_at' => $startTime,
                'is_deleted' => false,
            ]);

            // Assign to 1-2 technicians
            $assignedCount = rand(1, 2);
            for ($j = 0; $j < $assignedCount; $j++) {
                $jobDetail->job_assigments()->create([
                    'assigned_to' => rand(2, 4),
                    'is_delete' => false,
                ]);
            }
        }

        // Create jobs for past dates
        for ($day = 1; $day <= 3; $day++) {
            for ($i = 1; $i <= 4; $i++) {
                $dateTime = $today->copy()->subDays($day)->addHours(8 + ($i - 1) * 2);
                $classification = $classifications[($i + $day - 1) % $classifications->count()];
                $descriptions = $jobDescriptions[$classification->job_name] ?? [$classification->job_name];

                $job = Job::create([
                    'number_of_the_day' => $i,
                    'job_classification_id' => $classification->id,
                    'created_by' => 1,
                    'job_description' => $descriptions[array_rand($descriptions)],
                    'start_at' => $dateTime,
                    'duration' => $classification->duration,
                    'status' => 'done',
                    'is_deleted' => false,
                ]);

                $jobDetail = $job->job_details()->create([
                    'start_at' => $dateTime,
                    'is_deleted' => false,
                ]);

                $jobDetail->job_assigments()->create([
                    'assigned_to' => rand(2, 4),
                    'is_delete' => false,
                ]);
            }
        }

        // Create jobs for future dates
        for ($day = 1; $day <= 3; $day++) {
            for ($i = 1; $i <= 4; $i++) {
                $dateTime = $today->copy()->addDays($day)->addHours(8 + ($i - 1) * 2);
                $classification = $classifications[($i + $day - 1) % $classifications->count()];
                $descriptions = $jobDescriptions[$classification->job_name] ?? [$classification->job_name];

                $job = Job::create([
                    'number_of_the_day' => $i,
                    'job_classification_id' => $classification->id,
                    'created_by' => 1,
                    'job_description' => $descriptions[array_rand($descriptions)],
                    'start_at' => $dateTime,
                    'duration' => $classification->duration,
                    'status' => 'waiting_acc',
                    'is_deleted' => false,
                ]);

                $jobDetail = $job->job_details()->create([
                    'start_at' => $dateTime,
                    'is_deleted' => false,
                ]);

                $jobDetail->job_assigments()->create([
                    'assigned_to' => rand(2, 4),
                    'is_delete' => false,
                ]);
            }
        }
    }
}
