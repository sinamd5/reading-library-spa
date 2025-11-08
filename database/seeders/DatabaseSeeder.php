<?php declare(strict_types=1);


namespace Database\Seeders;


use App\Models\Category;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
// Base categories
        $base = collect(['Programming','Design','Marketing','DevOps','AI','Business'])
            ->map(fn($t) => Category::factory()->create(['title' => $t]));


// Extra random categories
        $extra = Category::factory(6)->create();
        $categories = $base->merge($extra);


// Lessons 100 +/-
        Lesson::factory(random_int(80, 120))
            ->create()
            ->each(function (Lesson $lesson) use ($categories) {
// 3–20 sections
                Section::factory(random_int(3, 20))
                    ->for($lesson)
                    ->create();


// 1–3 categories per lesson (ensure at least one)
                $attach = Arr::random($categories->pluck('id')->all(), random_int(1, 3));
                $lesson->categories()->sync($attach);
            });
    }
}
