<?php declare(strict_types=1);


namespace App\Enums;


enum SortField: string
{
    case Price = 'price';
    case CreatedAt = 'created_at';
    case SectionsCount = 'sections_count';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
