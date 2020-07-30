<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class mataching extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search in data base in items lost and found and make matching between them';

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
