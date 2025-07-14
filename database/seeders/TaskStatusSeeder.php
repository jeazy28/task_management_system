<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['name' => 'To-do'],
            ['name' => 'In-progress'],
            ['name' => 'Done']
        ];
        \DB::table('task_status')
            ->insert($data);
    }
}
