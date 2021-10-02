<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
use App\Models\Answer;

class QATest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    use RefreshDatabase;
    protected const NEW_QUESTION = 'Create a question';
    protected const PRACTICE = "Practice";
    protected const EXIT_PRACTICE = "Exit Practice";
    protected const DID_NOTHING = "Invalid Option";
    protected const LIST_QUESTIONS = "List all questions";
    protected const RESET = "Reset";

    protected const STATS = "Stats";
    protected const EXIT_SESSION = "Exit the Program";
    /**
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }


    /**
     * @return void
     */
    public function testQuestionAdd()
    {
        $this->artisan('qanda:newquestion')
            ->expectsQuestion('Enter the Question', "Spell 9?")
            ->expectsQuestion('Enter the answer', "nine")
            ->expectsConfirmation('Choose an Option', self::NEW_QUESTION)
            ->assertExitCode(0);
    }
    /**
     * @return void
     */
    public function testPracticeCorrect()
    {
        $question = new Question();
        $question->question = "Spell 9?";
        $question->answer = "nine";
        $question->save();
        $q =  Question::first();

        $this->artisan('qanda:practice')
            ->expectsQuestion('Enter a Question (QNo) from the list or --x to go back to previous menu', $q->id)
            ->expectsQuestion($q->question . ' >>> ', $q->answer)
            // ->expectsConfirmation('Choose an Option', self::PRACTICE)
            ->assertExitCode(0);


        $this->assertDatabaseHas('answers', [
            'answer_text' => 'nine',
            'question_id' => $q->id,
            'status' => 1,
        ]);
    }
    /**
     * @return void
     */
    public function testPracticewrong()
    {

        $question = new Question();
        $question->question = "Spell 9?";
        $question->answer = "nine";
        $question->save();
        $q =  Question::first();
        $this->artisan('qanda:practice')
            ->expectsQuestion('Enter a Question (QNo) from the list or --x to go back to previous menu', $q->id)
            ->expectsQuestion($q->question . ' >>> ', 's')
            ->expectsOutput("Incorrect Answer Recorded!")
            ->assertExitCode(0);
    }
    // /**
    //  * @return void
    //  */
    // public function testPracticeWrong()
    // {
    //     $question = new Question();
    //     $question->question = "Spell 9?";
    //     $question->answer = "nine";
    //     $question->save();
    //     $q =  $question->first();

    //     $this->artisan('qanda:practice')
    //         ->expectsQuestion('Enter a Question (QNo) from the list or --x to go back to previous menu', $q->id)
    //         ->expectsQuestion($q->question . ' >>> ', "Wrong")
    //         // ->expectsConfirmation('Choose an Option', self::PRACTICE)
    //         ->assertExitCode(0);

    //     $this->assertDatabaseHas('answers', [
    //         'answer_text' => 'Wrong',
    //         'question_id' => $q->id,
    //         'status' => 0,
    //     ]);
    // }
}