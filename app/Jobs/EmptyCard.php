<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmptyCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tickets;

    public $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($tickets, $userId)
    {
        $this->tickets = $tickets;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->tickets as $ticket) {
            $ticket->cartElements()
                ->where('user_id', $this->userId)
                ->delete();
        }
    }
}
