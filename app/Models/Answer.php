<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Answer extends Model
{
	protected $fillable = [
		'title'
	];

	public function question()
	{
		return $this->belongsTo(Question::class);
	}
}
