<?php declare(strict_types=1);


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 10, 2)->default(0);
// stored as string; casted to App\Enums\LessonType in model
            $table->string('type', 20);
            $table->timestamps();


            $table->index('title');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
