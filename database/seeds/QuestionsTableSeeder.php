<?php

use Illuminate\Database\Seeder;

use App\Models\Question;
use App\Models\Answer;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('questions')->delete();
        /**
         * Here you can add questions and answers as many as needed
         */
    	$questions = [
    		'How you feel' => [
    			'Fine',
    			'Good',
    			'Not too good', 
    		],
    		'What you did today' => [
    			'Worked',
    			'Slept',
    			'Nothing',
    		],
    		'What you had for breakfast' => [
    			'Toast',
    			'Omelet',
    			'Coffee',
    			'Tea',
    			'Nothing',
    		],
    	];
    	foreach ($questions as $title => $answers) {
    		$question = new Question;
    		$question->title = $title;
    		$question->save();
    		foreach ($answers as $value) {
    			$answer = new Answer;
    			$answer->title = $value;
    			$question->answers()->save($answer);
    		}
    	}
    }
}
