<?php

namespace App\Enums;

/**
 * The groups that skills are shown under on the home page.
 */
enum SkillCategory: string
{
    case FrontEnd = 'front_end';
    case BackEnd = 'back_end';
    case Framework = 'framework';
    case Other = 'other';

    /**
     * The heading shown above this group of skills.
     */
    public function label(): string
    {
        return match ($this) {
            self::FrontEnd => 'Front End',
            self::BackEnd => 'Back End',
            self::Framework => 'Frameworks',
            self::Other => 'Other Tools',
        };
    }
}
