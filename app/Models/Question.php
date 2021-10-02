<?php

namespace App\Models;

// use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // function __construct()
    // {
    // }
    protected $fillable =   ['question', 'answer'];
    public function rules()
    {
        return [
            'question' => 'required|unique:question'
        ];
    }
    function qa()
    {
        return $this->hasOne('App\Models\Answer', 'question_id', 'id');
    }
}