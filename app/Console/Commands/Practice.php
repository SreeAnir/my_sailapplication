<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\QATrait;
use App\Models\Question;
use App\Models\Answer;

class Practice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:practice';
    protected const PRACTICE = "Practice";

    use QATrait;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User can practice the questions';
    protected $total_question_count =  0;
    protected $right_ans_count =  0;



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
        $this->qandalist();
        $this->bringQuestions();
        // $this->practiseScreen();
    }
    /**
     * @param  
     * Lists the Questions ,Answers 
     * Allows user to choose a question and enter answer 
     */
    protected function bringQuestions()
    {
        $answer_picked_question = "";
        if ($this->total_question_count == 0) {
            $this->error("Add Questions");
        } else if ($this->total_question_count == $this->right_ans_count) {
            $this->error("Reset to Continue the Practice!");
        } else {
            $picked_question = $this->ask("Enter a Question (QNo) from the list or --x to go back to previous menu");
            $this->promptQuestion($picked_question);
        }
    }

    public function promptQuestion($picked_question)
    {
        $check_details = QATrait::checkCurrentAnswer($picked_question);
        if ($check_details == null) {
            $this->error("Invalid Selection");
            $this->bringQuestions();
        } else {
            if (!$check_details) {
                $this->error("You have selected an invalid question!!!");
                $this->bringQuestions();
            } else {
                if (isset($check_details->qa->status) &&   $check_details->qa->status == 1) {
                    $this->error("You cannot change the correct answer !");
                    $this->bringQuestions();
                }
                $answer_picked_question = $this->ask($check_details->question . " >>> ");
                if ($answer_picked_question  == "--x") {
                    $this->call('qanda:test');
                }
                $status = (strcasecmp($answer_picked_question, $check_details->answer) == 0 ? 1 : 0);

                $store_answer =   Answer::updateOrCreate(
                    ['question_id' => $picked_question],
                    ['answer_text' =>  $answer_picked_question, 'question_id' => $picked_question, 'status' =>   $status],

                );

                if ($status == 1) {
                    $this->info("Recorded a Correct Answer !");
                    $this->right_ans_count++;
                    $this->bringQuestions();
                    if ($this->total_question_count == $this->right_ans_count) {
                        $this->alert('You have successfully Completed the Practise.Reset and start practicing again with `php artisan qanda:reset` !');
                    }
                } else {
                    $this->error("Incorrect Answer Recorded!");
                    $this->bringQuestions();
                }
            }
        }
    }

    /**
     * @param  
     * Lists the Questions ,Answers 
     * Allows user to choose a question and enter answer 
     */
    protected function qandalist()
    {

        $question_collection = QATrait::getQuestionWithAnswers();
        $this->total_question_count = count($question_collection);
        $this->right_ans_count = QATrait::getRightAnswersCount();

        $this->table(
            ['QNo', 'Questions', 'Answer'],
            $question_collection,
        );
        $bar = $this->output->createProgressBar(count($question_collection));

        $bar->advance($this->right_ans_count);


        $this->alert("You can start practicing now.Type --x to exit the process");
    }
    /**
     * @param  
     * Lists the Questions ,Answers 
     * Allows user to choose a question and enter answer 
     */
    protected function practiseScreen()
    {
        $answer_picked_question = "";
        if ($this->total_question_count == $this->right_ans_count) {
            $this->error("Reset to Continue the Practice!");
        }
        while ($this->total_question_count > $this->right_ans_count) {

            $picked_question = $this->ask("Enter a Question (QNo) from the list or --x to go back to previous menu");
            if ($picked_question  == "--x") {
                $this->call('qanda:test');
                // break;
            }
            if (!is_numeric($picked_question)) {
                $this->error("Please choose a a valid option!");
                $this->practiseScreen();
            } else {

                $check_details = QATrait::checkCurrentAnswer($picked_question);

                if (!$check_details) {
                    $this->error("You have selected an invalid question!!!");
                    $this->practiseScreen();
                } else {
                    if (isset($check_details->qa->status) &&   $check_details->qa->status == 1) {
                        $this->error("You cannot change the correct answer !");
                        $this->practiseScreen();
                    }
                    $answer_picked_question = $this->ask($check_details->question . " >>> ");
                    if ($answer_picked_question  == "--x") {
                        $this->call('qanda:test');
                    }
                    $status = (strcasecmp($answer_picked_question, $check_details->answer) == 0 ? 1 : 0);

                    $store_answer =   Answer::updateOrCreate(
                        ['question_id' => $picked_question],
                        ['answer_text' =>  $answer_picked_question, 'question_id' => $picked_question, 'status' =>   $status],

                    );

                    if ($status == 1) {
                        $this->info("Recorded a Correct Answer !");
                        $this->right_ans_count++;
                        $this->practiseScreen();
                        if ($this->total_question_count == $this->right_ans_count) {
                            $this->alert('You have successfully Completed the Practise.Reset and start practicing again with `php artisan qanda:reset` !');
                        }
                    } else {
                        $this->error("Incorrect Answer Recorded!");
                        // $this->practiseScreen();
                    }
                }
            }
        }
        $this->call('qanda:test');
    }
}