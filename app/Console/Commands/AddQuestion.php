<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Traits\QATrait;

class AddQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use QATrait;
    protected $signature = 'qanda:newquestion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create new questions';

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
        // return 0;
        $this->createQuestion();
    }
}