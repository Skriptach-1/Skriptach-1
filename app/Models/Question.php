<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Report;
use App\Models\Answer;

class Question extends Model
{
	protected $fillable = [
		'title'
	];

	public function answers()
	{
		return $this->hasMany(Answer::class);
	}
}
