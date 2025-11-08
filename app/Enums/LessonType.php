<?php declare(strict_types=1);


namespace App\Enums;


enum LessonType: string
{
    case Video = 'video';
    case Text = 'text';
    case Podcast = 'podcast';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


    public static function labels(): array
    {
        return [
            self::Video->value => 'Video',
            self::Text->value => 'Text',
            self::Podcast->value => 'Podcast',
        ];
    }
}
