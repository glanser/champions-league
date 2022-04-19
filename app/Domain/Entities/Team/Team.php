<?php

namespace App\Domain\Entities\Team;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Domain\Entities\Team\Team
 *
 * @property int         $id
 * @property string      $name
 * @property int         $played
 * @property int         $won
 * @property int         $draw
 * @property int         $lost
 * @property int         $goal_difference
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereDraw($value)
 * @method static Builder|Team whereGoalDifference($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereLost($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team wherePlayed($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @method static Builder|Team whereWon($value)
 * @mixin Eloquent
 */
class Team extends Model
{
    use HasFactory;


    public function getInitialPoints(): int
    {
        return $this->won * 3 + $this->draw;
    }
}
