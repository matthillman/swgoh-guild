<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GuildScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:scrape {guild}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $proc = new Process('node resources/scripts/main.js ' . $this->argument('guild'));
        $status = $proc->run(
			function ($type, $data) {
				if ($type == Process::OUT) {
					$this->line($data);
				} else {
					$this->error($data);
				}
			}
        );
        
		if ($status) {
			$this->error("Failed to scrape guild.");
			return 1;
        }
        
        return 0;
    }
}
