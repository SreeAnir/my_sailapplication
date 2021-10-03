<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;

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
    public function testQuestionListing()
    {

        $question = new Question();
        $question->id = 1;
        $question->question = "Spell 9?";
        $question->answer = "nine";
        $question->save();
        $q =  Question::first();

        // $this->artisan('qanda:list')
        //     ->expectsOutput('+----+-----------+--------+')
        //     ->expectsOutput('| ID | Questions | Answer |')
        //     ->expectsOutput('+----+-----------+--------+')
        //     ->expectsOutput('| 4  | Spell 9?  | nine   |')
        //     ->expectsOutput('+----+-----------+--------+')
        //     ->assertExitCode(0);

        $this->artisan('qanda:list')
            ->expectsTable(['ID', 'Questions', 'Answer'], [[1,  $q->question,  $q->answer]])
            ->assertExitCode(0);

        $this->assertDatabaseHas('questions', [
            'answer' => $q->answer,
            'question' => $q->question,
        ]);
    }
    /**
     * @return void
     */
    public function testReset()
    {
        $this->artisan('qanda:reset')
            ->expectsConfirmation('Do you want to remove all the questions & Answers ??', 'yes')
            ->assertExitCode(0);
    }
    /**
     * @return void
     */
    public function testStats()
    {
        $this->artisan('qanda:stats')
            ->expectsOutput('The total amount of questions : 0')
            ->expectsOutput('% of questions that have an answer  : 0%')
            ->expectsOutput('% of questions that have a correct answer  : 0%')

            ->assertExitCode(0);
    }
    /**
     * @return void
     */
    public function testExit()
    {
        $this->artisan('qanda:test')
            ->expectsQuestion('Choose an Option', SELF::EXIT_SESSION)
            ->expectsQuestion('Want to Exit ??', 'yes')
            ->expectsOutput('**********Quitting******************')
            ->assertExitCode(0);
    }
}