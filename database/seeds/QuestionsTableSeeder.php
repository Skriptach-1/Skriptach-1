<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    	\DB::table('questions')->delete();
    	foreach ($questions as $question => $answers) {
    		\DB::table('questions')->insert([
    			'title' => $question,
    		]);
    	}
    }
}
