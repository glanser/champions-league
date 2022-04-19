<?php

declare(strict_types=1);

namespace App\Domain\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeekPlayed
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
