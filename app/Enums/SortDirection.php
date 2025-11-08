<?php declare(strict_types=1);


namespace App\Enums;


enum SortDirection: string
{
    case Asc = 'asc';
    case Desc = 'desc';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
