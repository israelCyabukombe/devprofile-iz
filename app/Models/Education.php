<?php

namespace App\Models;

use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One degree in the Education section of the profile sidebar.
 *
 * @property int $id
 * @property int $personal_info_id
 * @property string $degree
 * @property string $school
 * @property string|null $minor
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['personal_info_id', 'degree', 'school', 'minor', 'sort_order'])]
class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<PersonalInfo, $this>
     */
    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
