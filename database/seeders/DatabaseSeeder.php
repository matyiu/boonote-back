<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!User::find(1)) {
            DB::table('users')->insert([
                'name' => 'Jefferson Valle',
                'email' => 'valleespinozajefferson@gmail.com',
                'username' => 'rjeffvalle',
                'password' => 'weakPassword',
                'email_verified_at' => now(),
            ]);
        }

        $notes = Note::factory()->count(30)->create();
        $tags = Tag::factory()->count(7)->create();

        $user = User::find(1);
        $user->notes()->saveMany($notes);
        $user->tags()->saveMany($tags);

        $notes->each(function ($note) {
            $note->tags()->attach(rand(1, 7));
        });
    }
}
