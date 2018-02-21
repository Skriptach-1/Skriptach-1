<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Question;

class UserController extends Controller
{
	use ApiResponse;

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

	public function getQuestion()
	{
		if (!$user = JWTAuth::parseToken()->authenticate()) {
			$this->setMeta('status', 'fail');
            return response()->json($this->setResponse());
		}
		$question = Question::inRandomOrder()->first();
		$this->setMeta('status', 'ok');
        $this->setData('question', [
        	'title' => $question->title,
        	'answers' => $question->answers->makeHidden([
        		'created_at',
        		'updated_at',
        	])
        ]);
        return response()->json($this->setResponse());
	}

	public function postQuestion(Request $request)
	{
		if (!$user = JWTAuth::parseToken()->authenticate()) {
			$this->setMeta('status', 'fail');
            return response()->json($this->setResponse());
		}
		dd($request->question, $request->answer, $user->id);
	}
}
