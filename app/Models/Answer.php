<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['answer_text', 'question_id', 'status'];

    use HasFactory;
    function quest()
    {
        return $this->belongsTo('App\Models\Question', 'question_id', 'id');
    }
}