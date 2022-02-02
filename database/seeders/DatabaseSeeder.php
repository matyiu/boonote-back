<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Category;
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
                'password' => bcrypt('weakPassword'),
                'email_verified_at' => now(),
            ]);
        }

        $notes = Note::factory()->count(30)->create();
        $categories = Category::factory()->count(7)->create();

        $user = User::find(1);
        $user->notes()->saveMany($notes);
        $user->categories()->saveMany($categories);

        $notes->each(function ($note) use ($categories) {
            $note->category()->associate($categories->random());
            $note->save();
        });
    }
}
