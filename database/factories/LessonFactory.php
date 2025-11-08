<?php declare(strict_types=1);


namespace Database\Factories;


use App\Enums\LessonType;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;


/** @extends Factory<Lesson> */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;


    public function definition(): array
    {
        $types = LessonType::values();
        $price = $this->faker->boolean(35) ? 0 : $this->faker->randomFloat(2, 9.99, 299.99);


        return [
            'title' => $this->faker->unique()->sentence(3),
            'price' => $price,
            'type' => $this->faker->randomElement($types),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
