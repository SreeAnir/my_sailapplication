<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Answer;
use DB;

class ResetPractice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the Questions & Answers';

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
        $this->reset();
    }
    function reset()
    {
        if ($this->confirm("Do you want to remove all the questions & Answers  ?? ")) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Answer::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->alert("Process Reset completed");
        } else {
            $this->warn("Reset Cancelled.Back to Main Menu");
            $this->call('qanda:test');

            ///call interactive command
        }
    }
}