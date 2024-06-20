<?php

namespace App\JustOrange\Cli;

use Illuminate\Console\Command;

class JOinit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'justorange:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("HELLo !");
    }
}
