<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\Answer;

class Stat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:stats';

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
     * @return int
     */
    public function handle()
    {
        $this->loadStats();
    }
    /**
     * @param  
     * Displays the  stats:
     * - The total amount of questions.
     * - % of questions that have an answer.
     * -  % of questions that have a correct answer
     */
    protected function loadStats()
    {
        $this->alert("Showing Stat ");
        $question_collection = Question::count();
        $answered = Answer::count();
        $correct = Answer::where('status', 1)->count();
        $this->info(vsprintf("The total amount of questions : %u", [$question_collection]));
        $this->info(vsprintf("%% of questions that have an answer  : %u%s", array(round(($answered / ($question_collection != 0 ? $question_collection : 1)) * 100), '%')));
        $this->info(vsprintf("%% of questions that have a correct answer  : %u%s", [round(($correct / ($answered != 0 ? $answered : 1)) * 100), '%']));
    }
}