<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Carbon\Carbon;
use Mailin;
use App;

class AuthController extends Controller
{

	protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

	public function register(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|unique:users|email',
			'password' => 'required|min:6' ]);

		$request->merge(['password' =>app('hash')->make($request->password)]);

		$input = $request->only(['email','password']);

		$input['activation_id'] = sha1(mt_rand(10000,99999).time().$input['email']);

		$input['create_at'] = Carbon::now();

		$user = app('db')->table('users')
						->insert($input);
	}

	public function login(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email|max:255',
			'password' => 'required',]);

		try{
			if(! $token = $this->jwt->attempt($request->only('email','password'))){
				return response()->json([
					'status' => 'fail',
					'message' => 'email atau password salah']);
			}
		}catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
			 return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem.']);
		}

		 $user = App\User::where('email', $request->input('email'))->first();

		return response()->json([
            'status' => 'success',
            'token' => $token,
            'data' => $user
        ]);
	}
}


?>