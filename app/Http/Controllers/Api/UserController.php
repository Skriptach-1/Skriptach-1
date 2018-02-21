<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Report;

class UserController extends Controller
{
	/**
	 * Size of report page
	 */
	const REPORT_SIZE = 20;
	/**
	 * This trait intercepts response
	 * It injects metadata for client side application and implements methods to add payload
	 */
	use ApiResponse;

	/**
	 * Implements authentication and add JWT token to response payload
	 */
	public function authenticate(Request $request)
	{
		$credentials = $request->only('email', 'password');
		try {
			if (!$token = JWTAuth::attempt($credentials)) {
				$this->setMeta('status', 'fail');
                $this->setMeta('message', 'Invalid Login details');
                return response()->json($this->setResponse());
			}
		} catch (JWTException $e) {
			$this->setMeta('status', 'fail');
            $this->setMeta('message', 'Server Error. Retry.');
            return response()->json($this->setResponse());
		}
		$this->setMeta('status', 'ok');
        $this->setMeta('message', 'Logged in successfully.');
        $user = JWTAuth::setToken($token)->toUser()->makeHidden(['created_at', 'updated_at', 'id']);
        $this->setData('token', $token);
        $this->setData('user', $user);
        return response()->json($this->setResponse());
	}

	/**
	 * Just register/create User
	 */
	public function register(Request $request)
	{
		$user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $this->setMeta('status', 'ok');
        $this->setMeta('message', 'Successfully Registered');
        return response()->json($this->setResponse());
	}

	/**
	 * Returns current user
	 */
	public function getUser()
	{
		if (!$user = JWTAuth::parseToken()->authenticate()) {
			$this->setMeta('status', 'fail');
            return response()->json($this->setResponse());
		}
		$this->setMeta('status', 'ok');
        $this->setData('user', $user->makeHidden(['created_at', 'updated_at', 'id']));
        return response()->json($this->setResponse());
	}

	/**
	 * Returns questions list of 3|4 items selected in random order
	 */
	public function getQuestion()
	{
		if (!$user = JWTAuth::parseToken()->authenticate()) {
			$this->setMeta('status', 'fail');
            return response()->json($this->setResponse());
		}
		$faker = \Faker\Factory::create();
		$questions = Question::inRandomOrder()
			->limit($faker->numberBetween(3, 4))
			->get();
		if ($questions) {
			$this->setMeta('status', 'ok');
			$data = [];
			foreach ($questions as $question) {
				$data[] = [
		        	'title' => $question->title,
		        	'answers' => $question->answers->makeHidden([
		        		'created_at',
		        		'updated_at',
		        	])
		        ];
			}
			$this->setData('questions', $data);
		} else {
	    	$this->setMeta('status', 'fail');
	    	$this->setMeta('message', 'Questions not found');
	    }
        return response()->json($this->setResponse());
	}

	/**
	 * Post and save selected answers for questions into `reports` table 
	 * Then select from `reports` latest (max. 20) answers
	 */
	public function postQuestion(Request $request)
	{
		if (!$user = JWTAuth::parseToken()->authenticate()) {
			$this->setMeta('status', 'fail');
            return response()->json($this->setResponse());
		}
		try {
			$payload = json_decode($request->getContent());
			if (sizeof($payload) > 0) {
				foreach ($payload as $item) {
					$report = new Report;
					$report->question_id = intval($item->question_id);
					$report->answer_id = intval($item->answer_id);
					$report->save();
					$user->report()->save($report);
				}
			}
			$reports = \DB::table('reports')
				->select(['questions.title AS question', 'answers.title AS answer', 'reports.created_at'])
				->join('questions', 'reports.question_id', '=', 'questions.id')
				->join('answers', 'reports.answer_id', '=', 'answers.id')
				->where('user_id', $user->id)
				->orderBy('reports.created_at', 'DESC')
				->limit(self::REPORT_SIZE)
				->get();
			$this->setData('report', $reports);
		} catch (Exception $e) {
			$this->setMeta('status', 'fail');
			$this->setMeta('message', $e->getMessage());
            return response()->json($this->setResponse());
		}
		$this->setMeta('status', 'ok');
		return response()->json($this->setResponse());
	}
}
