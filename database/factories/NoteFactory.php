<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'note' => $this->faker->paragraphs(rand(5, 10), true),
            'rate' => $this->faker->numberBetween(0, 10),
            'state' => $this->faker->numberBetween(0, 5),
            'permission' => 0,
        ];
    }
}
