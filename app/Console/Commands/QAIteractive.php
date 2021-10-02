<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

use App\Console\Traits\ProgressionBarOutput;
use App\Traits\QATrait;

use App\Models\Question;
use App\Models\Answer;

class QAInteractive extends Command
{
    // use ProgressionBarOutput;
    protected const NEW_QUESTION = 'Create a question';
    protected const PRACTICE = "Practice";
    protected const EXIT_PRACTICE = "Exit Practice";
    protected const DID_NOTHING = "Invalid Option";
    protected const LIST_QUESTIONS = "List all questions";
    protected const RESET = "Reset";

    protected const STATS = "Stats";
    protected const EXIT_SESSION = "Exit the Program";

    use QATrait;

    protected const MENU = [self::NEW_QUESTION, self::PRACTICE, self::LIST_QUESTIONS, self::STATS, self::RESET, self::EXIT_SESSION];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Question Ans Practice Session';

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
        // $this->loadMenu();
        $choice = $this->choice("Choose an Option", SELF::MENU);
        $this->handleClick($choice);
    }

    /**
     * @param string $option
     * Loads the Menu Options 
     */

    protected function handleClick(string $option)
    {
        switch ($option) {
            case  self::NEW_QUESTION:
                $this->call('qanda:newquestion');
                // $this->createQuestion();
                break;
            case  self::PRACTICE:
                // $this->practiseScreen();
                $this->call('qanda:practice');
                break;
            case  self::LIST_QUESTIONS:
                $this->loadQuestions();
                break;
            case  self::STATS:
                $this->loadStats();
                break;
            case  self::RESET:
                $this->call('qanda:reset');
                break;

            case  self::EXIT_SESSION:
                if ($this->confirm("Want to Exit ?? ")) {
                    exit();
                }
                break;
        }
    }

    /**
     * @param  
     * Shows the Menu Option
     * Allows user to choose an option and go to the 
     * selected screen
     */

    protected function loadMenu()
    {
        $choice = $this->choice("Choose an Option", SELF::MENU);
        $this->handleClick($choice);
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
        $this->loadMenu();
    }
    /**
     * @param  
     * Lists all the created questions with the correct answer.  
     * 
     */

    protected function loadQuestions()
    {
        $this->alert("Showing List of Questions Added ");
        $question_collection = Question::all(['question', 'answer'])->toArray();

        $this->table(
            ['Questions', 'Answer'],
            $question_collection
        );

        $this->loadMenu();
    }
}