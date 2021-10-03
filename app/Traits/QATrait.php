<?php

namespace App\Traits;

use App\Models\Answer;
use App\Models\Question;
use DB;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Database\QueryException;

trait QATrait
{
    static function getQuestionWithAnswers()
    {
        $question_collection = Question::select('id', 'question', 'answer')->with(['qa' => function ($inner) {
            return $inner->select('answers.status', 'question_id');
        }])->get(['id', 'question', 'answer']);
        // dd($question_collection);
        $table_data = [];
        foreach ($question_collection as $eachquestion) {
            $table_data[]  = [
                $eachquestion->id,
                $eachquestion->question,
                ($eachquestion->qa ?
                    ($eachquestion->qa->status == 1  ? "Correct" : "Incorrect")
                    : "Not answered")
            ];
        }
        return  $table_data;
    }
    public function handleInvalidSelection()
    {
        $this->error("Picked An Valid Question Number");
        if ($this->confirm("Do you want to continue the practice?")) {
            $this->call('qanda:practice');
        } else {
            $this->call('qanda:interactive');
        }
    }
    static function getRightAnswersCount()
    {
        return  Answer::select('id')->where('status', 1)->count();
    }
    static function checkCurrentAnswer(int $question_id)
    {
        return  Question::with('qa')->find($question_id);
    }

    /**
     * @param string $option
     * Shows screen to create a question & add answer to it.
     * Question is unique
     */
    protected function createQuestion()
    {
        $this->info('Enter your Question and answer');
        $question = $this->ask("Enter the Question");
        $answer = $this->ask("Enter the answer");
        try {

            $Question = new Question;

            $Question->question = $question;
            $Question->answer = $answer;

            if ($Question->save()) {
                $this->alert('Question Added Successfully');
            } else {
                $this->eror('Failed to Add');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $this->error('Sorry..Question Already exists.Add another question..');
            } else {
                $this->error("Your Question wasn't saved !");
            }
        }

        $this->call('qanda:interactive');
    }
}
