<?php

namespace App\Models;

use Database\Factories\PersonalInfoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * The profile shown on the home page: name, job title, summary and links.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $job_title
 * @property string $summary_text
 * @property string|null $profile_photo_path
 * @property string|null $resume_path
 * @property string|null $github_url
 * @property string|null $linkedin_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'first_name',
    'last_name',
    'email',
    'job_title',
    'summary_text',
    'profile_photo_path',
    'resume_path',
    'github_url',
    'linkedin_url',
])]
class PersonalInfo extends Model
{
    /** @use HasFactory<PersonalInfoFactory> */
    use HasFactory;

    /**
     * The first and last name together, for headings.
     */
    public function fullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return HasMany<Skill, $this>
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * @return HasMany<Education, $this>
     */
    public function education(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('sort_order');
    }
}
