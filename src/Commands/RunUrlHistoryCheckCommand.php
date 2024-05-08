<?php

namespace Dashed\DashedCore\Commands;

use Cassandra\Custom;
use Dashed\DashedCore\Jobs\RunUrlHistoryCheck;
use Dashed\DashedCore\Models\Customsetting;
use Illuminate\Console\Command;
use Dashed\DashedCore\Models\User;
use Illuminate\Support\Facades\Hash;

class RunUrlHistoryCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashed:run-url-history-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the url history check for the Dashed CMS!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Customsetting::get('run_history_check') == true && Customsetting::get('last_history_check', null, now()->subWeek()) < now()->subHour()) {
            RunUrlHistoryCheck::dispatch();
        }
    }
}
