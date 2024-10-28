<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\QuestController; 
use Log;

class FetchQuestLabResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:questLabResults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch pending lab results from Quest';

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
        Log::channel('questResults')->info('Fetching Results...');
        $quest_obj=new QuestController();
        $quest_obj->fetchPendingResults();
        Log::channel('questResults')->info('--Completed--');
        // return 0;
    }
}
