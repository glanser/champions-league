<?php

namespace App\Domain\Entities\Fixture;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Team\Team;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Domain\Entities\Fixture\Fixture
 *
 * @property int            $id
 * @property int            $week
 * @property int            $first_team_id
 * @property int            $second_team_id
 * @property int|null       $first_team_goals
 * @property int|null       $second_team_goals
 * @property int|null       $won_team_id
 * @property FixtureStatus  $status
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property-read Team|null $firstTeam
 * @property-read Team|null $secondTeam
 * @method static Builder|Fixture newModelQuery()
 * @method static Builder|Fixture newQuery()
 * @method static Builder|Fixture query()
 * @method static Builder|Fixture whereCreatedAt($value)
 * @method static Builder|Fixture whereFirstTeamGoals($value)
 * @method static Builder|Fixture whereFirstTeamId($value)
 * @method static Builder|Fixture whereId($value)
 * @method static Builder|Fixture whereSecondTeamGoals($value)
 * @method static Builder|Fixture whereSecondTeamId($value)
 * @method static Builder|Fixture whereStatus($value)
 * @method static Builder|Fixture whereUpdatedAt($value)
 * @method static Builder|Fixture whereWeek($value)
 * @method static Builder|Fixture whereWonTeamId($value)
 * @mixin Eloquent
 */
class Fixture extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => FixtureStatus::class,
    ];

    protected $with = [
        'firstTeam',
        'secondTeam',
    ];

    public function firstTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'first_team_id', 'id');
    }

    public function secondTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'second_team_id', 'id');
    }
}
