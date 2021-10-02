<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class QuestionList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all Questions!';

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
        return $this->loadQuestions();
    }
    /**
     * @param  
     * Lists all the created questions with the correct answer.  
     * 
     */

    protected function loadQuestions()
    {
        $this->alert("Showing List of Questions Added ");
        $question_collection = Question::all(['id', 'question', 'answer'])->toArray();

        $this->table(
            ['ID', 'Questions', 'Answer'],
            $question_collection
        );
    }
}