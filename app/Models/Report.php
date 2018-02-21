<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;

class Report extends Model
{
	protected $fillable = [
        'question_id', 'answer_id',
    ];

    /**
     * Relation to user who answer question
     */
    public function user()
	{
		return $this->belongsTo(User::class);
	}
}
