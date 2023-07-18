<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Session;
use Twilio\Rest\Client;

class RegisterController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me ? true : false)) {
                $user = Auth::user();
                $tokenResult = $user->createToken('MyApp', ['']);
                $success['token'] =  $tokenResult->accessToken;
                $success['token_type'] =  'Bearer';
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $success['expires_at'] =  Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                $success['name'] =  $user->name;
                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function logout(Request $request)
    {
        try {
            Session::flush();
            Auth::logout();
            return response(['message' => 'You have been successfully logged out.'], 200);
        } catch (Exception $ex) {
            return $this->sendLogError($ex->getMessage(), "RegisterController@logout");
        }
    }

    public function showlogin()
    {
        return view('auth.login');
    }

    public function loginWithOtp(Request $request)
    {
        Log::info($request);
        $user  = User::where([['mobile', '=', request('mobile')], ['otp', '=', request('otp')]])->first();
        if ($user) {
            Auth::login($user, true);
            User::where('mobile', '=', $request->mobile)->update(['otp' => null]);
            return view('home');
        } else {
            return Redirect::back();
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            $otp = rand(1000, 9999);
            Log::info("otp = " . $otp);
            $user = User::where('mobile', '=', $request->mobile)->update(['otp' => $otp]);
            // send otp to mobile no using sms api
            $this->sendsms($user);
            return response()->json([$user], 200);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function sendsms($user)
    {
        try {
            $account_sid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
            $auth_token = config('app.twilio')['TWILIO_AUTH_TOKEN'];
            $twilio_number = config('app.twilio')['TWILIO_NUMBER'];

            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $user->mobile,
                array(
                    'from' => $twilio_number,
                    'body' => "The secret code is: " .  $user->otp
                )
            );
            return response()->json(['success' => true, 'message' => 'success']);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }
}
