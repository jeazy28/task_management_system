<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class TasksForUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            \DB::table('tasks')->insert([
                'user_id' => 1,
                'title' => $faker->realText(40),
                'content' => $faker->realText(230),
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
