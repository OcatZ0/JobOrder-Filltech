<?php

namespace Database\Seeders;

use App\Models\Job;
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

        // Create jobs for today
        for ($i = 1; $i <= 12; $i++) {
            Job::create([
                'number_of_the_day' => $i,
                'created_by' => 1,
                'assigned_to' => rand(2, 4),
                'job_description' => 'Job description ' . $i,
                'start_at' => $today->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'end_at' => $today->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'status' => $statuses[array_rand($statuses)],
                'is_deleted' => false,
            ]);
        }

        // Create jobs for past dates
        for ($day = 1; $day <= 5; $day++) {
            for ($i = 1; $i <= 8; $i++) {
                Job::create([
                    'number_of_the_day' => $i,
                    'created_by' => 1,
                    'assigned_to' => rand(2, 4),
                    'job_description' => 'Past job description ' . $i,
                    'start_at' => $today->copy()->subDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'end_at' => $today->copy()->subDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'status' => $statuses[array_rand($statuses)],
                    'is_deleted' => false,
                ]);
            }
        }

        // Create jobs for future dates
        for ($day = 1; $day <= 5; $day++) {
            for ($i = 1; $i <= 6; $i++) {
                Job::create([
                    'number_of_the_day' => $i,
                    'created_by' => 1,
                    'assigned_to' => rand(2, 4),
                    'job_description' => 'Future job description ' . $i,
                    'start_at' => $today->copy()->addDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'end_at' => $today->copy()->addDays($day)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'status' => 'waiting_acc',
                    'is_deleted' => false,
                ]);
            }
        }
    }
}
