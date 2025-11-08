<?php declare(strict_types=1);


namespace App\Models;


use App\Enums\LessonType;
use App\Enums\SortDirection;
use App\Enums\SortField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Lesson extends Model
{
    use HasFactory;


    protected $fillable = [
        'title', 'price', 'type',
    ];


    protected $casts = [
        'type' => LessonType::class,
        'price' => 'decimal:2',
    ];


    /** Relationships */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }


    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }


    /** Scopes (SRP: each scope = one responsibility) */
    public function scopeSearchTitle(Builder $q, ?string $term): Builder
    {
        return $term ? $q->where('title', 'like', "%".trim($term)."%") : $q;
    }


    public function scopeFilterCategories(Builder $q, ?array $ids): Builder
    {
        if (!$ids || count($ids) === 0) return $q;
        return $q->whereHas('categories', fn (Builder $sub) => $sub->whereIn('categories.id', $ids));
    }


    public function scopeIsFree(Builder $q, $isFree): Builder
    {
        if ($isFree === null || $isFree === '') return $q;
        return $isFree ? $q->where('price', 0) : $q->where('price', '>', 0);
    }


    public function scopeOfType(Builder $q, ?LessonType $type): Builder
    {
        return $type ? $q->where('type', $type->value) : $q;
    }


    public function scopeMinSections(Builder $q, ?int $n): Builder
    {
        if ($n === null) return $q;
        return $q->withCount('sections')->having('sections_count', '>=', max(0, $n));
    }


    public function scopeSortBy(Builder $q, ?SortField $field, ?SortDirection $dir): Builder
    {
        $f = $field ?? SortField::CreatedAt; // default
        $d = $dir ?? SortDirection::Desc;


        if ($f === SortField::SectionsCount) {
            $q->withCount('sections')->orderBy('sections_count', $d->value);
        } else {
            $q->orderBy($f->value, $d->value);
        }
        return $q;
    }
}
