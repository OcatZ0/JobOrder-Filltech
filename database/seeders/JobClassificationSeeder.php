<?php

namespace Database\Seeders;

use App\Models\JobClassification;
use Illuminate\Database\Seeder;

class JobClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            // ISP Service Classifications
            ['job_name' => 'PSB (Pemberian Sambungan Baru)', 'duration' => 120],
            ['job_name' => 'Maintenance', 'duration' => 60],
            ['job_name' => 'Tarik Kabel', 'duration' => 180],
            ['job_name' => 'Upgrade', 'duration' => 90],
            ['job_name' => 'Downgrade', 'duration' => 60],
            ['job_name' => 'Disconnect', 'duration' => 60],
            ['job_name' => 'Troubleshooting', 'duration' => 120],
            ['job_name' => 'Installation', 'duration' => 150],
            ['job_name' => 'Relocation', 'duration' => 180],
            ['job_name' => 'Equipment Replacement', 'duration' => 90],
        ];

        foreach ($classifications as $classification) {
            JobClassification::create($classification);
        }
    }
}
