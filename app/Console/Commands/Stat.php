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
        $per_with_answer =  ($question_collection != null ?  round($answered / $question_collection)  * 100 : 0);

        $per_with_correct_answer = ($answered != null ?  round($correct / $answered)  * 100 : 0);
        $this->info(vsprintf("The total amount of questions : %u", [$question_collection]));

        $this->info(vsprintf("%% of questions that have an answer  : %u%s", array($per_with_answer, '%')));
        $this->info(vsprintf("%% of questions that have a correct answer  : %u%s", [$per_with_correct_answer, '%']));
    }
}