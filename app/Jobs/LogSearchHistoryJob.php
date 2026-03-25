<?php

namespace App\Jobs;

use App\Models\SearchHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class LogSearchHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $searchData;

    public function __construct(array $searchData)
    {
        $this->searchData = $searchData;
    }

    public function handle(): void
    {
        try {
            SearchHistory::create($this->searchData);
        } catch (Exception $e) {
            Log::error('Failed to log search history: ' . $e->getMessage());
        }
    }
}
