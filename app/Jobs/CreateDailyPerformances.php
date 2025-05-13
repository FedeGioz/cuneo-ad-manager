<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\DailyPerformance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateDailyPerformances implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    private $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DailyPerformance::create([
            'date' => now()->format('Y-m-d'),
            'impressions' => 0,
            'views' => 0,
            'clicks' => 0,
            'campaign_id' => $this->campaign->id
        ]);
    }
}
