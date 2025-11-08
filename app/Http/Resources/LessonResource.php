<?php declare(strict_types=1);


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class LessonResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => (float)$this->price,
            'type' => $this->type->value,
            'sections_count' => $this->whenCounted('sections', fn() => $this->sections_count),
            'categories' => $this->whenLoaded('categories', fn() => $this->categories->pluck('title')),
        ];
    }
}
