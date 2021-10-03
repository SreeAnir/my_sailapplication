<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use Illuminate\Database\QueryException;

// use App\Console\Traits\ProgressionBarOutput;
use App\Traits\QATrait;

// use App\Models\Question;
// use App\Models\Answer;

class QAInt extends Command
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
    protected $signature = 'qanda:interactive';

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
                // $this->loadQuestions();
                $this->call('qanda:list');
                $this->handle();

                break;
            case  self::STATS:
                // $this->loadStats();
                $this->call('qanda:stats');
                $this->call('qanda:interactive');

                break;
            case  self::RESET:
                $this->call('qanda:reset');
                $this->call('qanda:interactive');

                break;

            case  self::EXIT_SESSION:
                if ($this->confirm("Want to Exit ??")) {
                    $this->info('**********Quitting******************');
                } else {
                    $this->handle();
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
}
