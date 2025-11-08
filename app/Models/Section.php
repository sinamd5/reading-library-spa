<?php declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Section extends Model
{
    use HasFactory;


    protected $fillable = ['lesson_id', 'title'];


    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
