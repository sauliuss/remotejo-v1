<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeWWR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:new_jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape WeWorkRemotely for new posts';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
