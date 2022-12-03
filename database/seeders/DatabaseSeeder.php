<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\AnswerNormal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TestSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(ItemSubjectSeeder::class);
        $this->call(PostSeeder::class); 
        $this->call(QuestionSeeder::class);
        $this->call(AnswerNormalSeeder::class);
        $this->call(UserTestSeeder::class);
        $this->call(AnswerTestSeeder::class);
        $this->call(PropertyQuestionSeeder::class);
         $this->call(AnswerQuestionTestSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
