<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OtpForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //

    public function register_page() {
        return view('register');
    }

    public function create_new_account(Request $request) {
        try {

            $validate = $request->validate([
                // 'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|email',
                // 'address' => 'required|string|max:100',
            ]);

            $password = $validate['password'];

            $regex = "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;\"'<>,.?\/\\|-]).{1,}$/";

            $passwordRequirement = preg_match($regex, $password);

            if (!$passwordRequirement) {
                return redirect()->back()->withErrors(['error' => 'Masukkan password dengan syarat kombinasi huruf besar, kecil, angka, tanda baca, dan terdapat minimal 1 karakter']);
            }

            $user = new User();
            $user->email = $validate['email'];
            $user->username = $validate['username'];
            $user->name = $validate['name'];
            $user->password = $password;
            $user->save();

            if($user) {
                return response()->json([
                    'success' => true,
                    'user'    => $user,  
                ], 201);
            }

        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function authenticated(Request $request) {
        $credentials = $request->only('email', 'password');
        $exp_date_access_token = Carbon::now()->addMinute();
        $access_token_exp_date = $exp_date_access_token->timestamp;
        $exp_date_refresh_token = Carbon::now()->addDays(7);
        $refresh_token_exp_date = $exp_date_refresh_token->timestamp;
        try {
            $token = auth()->guard('api')->attempt($credentials, ['exp' => $access_token_exp_date]);
            if(!$token) {
                return response()->json(['message' => 'Login credential invalid'], 400);
            }

            $email = $request->email;

            $refresh_token = Str::random(128);
            $encrypted_refresh_token = bcrypt($refresh_token);

            $check_refresh_token = DB::table('refresh_tokens')->where('email', $email)->get(['refresh_token', 'refresh_token_exp']);

            do {
                $generated_uuid_refresh_token = Str::uuid();
            } while (DB::table('refresh_tokens')->where('id_refresh_token', $generated_uuid_refresh_token)->first());

            if (count($check_refresh_token) == 0) {
                DB::table('refresh_tokens')->insert([
                    'id_refresh_token' => $generated_uuid_refresh_token,
                    'refresh_token' => $encrypted_refresh_token,
                    'email' => $email,
                    'refresh_token_exp' => $exp_date_refresh_token
                ]);
            } else {
                $parsed_refresh_token_exp = Carbon::parse($check_refresh_token[0]->refresh_token_exp);

                if ($parsed_refresh_token_exp->timestamp <= Carbon::now()->timestamp) {
                    DB::table('refresh_tokens')->update([
                        // 'id_refresh_token' => Str::uuid(),
                        // 'email' => $email,
                        'refresh_token' => $encrypted_refresh_token,
                        'refresh_token_exp' => $exp_date_refresh_token
                    ]);

                    return response()->json([
                        'access_token' => $token,
                        'access_token_exp' => $access_token_exp_date,
                        'success' => true,
                        'message' => 'Login credential is valid'], 200)->withCookie(cookie('token', $token, config('jwt.ttl'), '/', null, true, true, false, 'Strict'));
                }
            }
            
            return response()->json([
                'access_token' => $token,
                'access_token_exp' => $access_token_exp_date,
                'refresh_token' => $refresh_token,
                'refresh_token_exp' => $refresh_token_exp_date,
                'success' => true,
                'message' => 'Login credential is valid'], 200)->withCookie(cookie('token', $token, config('jwt.ttl'), '/', null, true, true, false, 'Strict'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function forget_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Email address required',
                'success' => false
            ], 403);
        }

        $email = $request->email;

        if (Cache::get('otp')) {
            return response()->json([
                'message' => 'OTP has been sent to your email or wait 2 minutes for resend OTP',
                'success' => false
            ], 403);
        }

        $email_db = User::where('email', $email)->first();
        if ($email_db) {
            $otp = random_int(100000, 999999);
            $new_otp = new OtpForgetPassword($email, $otp);
            Mail::to($email)->send($new_otp);

            Cache::put('otp', $otp, now()->addMinutes(2));
            Cache::put('reset_email', $email);
            
            return response()->json([
                'message' => 'OTP sent to email',
                'success' => true
            ], 200);
        } else {
            // return cant find email
            return response()->json([
                'message' => 'Email not found',
                'success' => false
            ], 404);
        }
    }

    public function check_otp(Request $request) {
        $otp = Cache::get('otp', 'default');
        if($otp == 'default') {
            return response()->json(['success' => false, 'message' => 'otp not found or timeout'], 404);
        }

        $validator = $request->validate([
            'otp' => 'required|integer'
        ]);

        if($otp != $validator['otp']) {
            return response()->json(['success' => false, 'message' => 'otp is not matching'], 400);
        }

        return response()->json(['success' => true, 'message' => 'otp is matching'], 200);
    }

    public function change_password(Request $request) {
        $data = $request->validate([
            'password' => 'required|min:8|max:20',
            // 'otp' => 'required|integer',
            // 'confirmed_password' => 'required|min:8|max:20'
        ]);

        // if (!$data['otp']) {
        //     return response()->json(['message' => 'Forbidden'], 403);
        // }

        $regex = "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;\"'<>,.?\/\\|-]).{1,}$/";
        $pass = $data['password'];
        // $confirmed_pass = $data['confirmed_password'];

        $passwordRequirement = preg_match($regex, $pass);
        if(!$passwordRequirement) {
            return redirect()->back()->json(['error' => 'Masukkan password dengan syarat kombinasi huruf besar, kecil, angka, tanda baca, dan terdapat minimal 1 karakter'], 400);
        }

        // if ($pass != $confirmed_pass) {
        //     return redirect()->back()->json(['message' => 'please type same password with confirmed'], 400);
        // }

        $email = Cache::get('reset_email', 'default');

        if ($email == 'default') {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Update password
        $user->update(['password' => bcrypt($pass)]);
    
        // Hapus email dan OTP dari cache
        Cache::forget('reset_email');
        Cache::forget('otp');
    
        return response()->json(['message' => 'Password successfully changed'], 200);
    }

    public function refresh_token(Request $request) {
        $refresh_token_from_request = $request->refresh_token;
        if (!$refresh_token_from_request) {
            return response()->json(['error' => 'refresh token is missing'], 400);
        }   

        try {
            $payload = $request->get('jwt_payload');
            $refreshed_token_email = $payload['sub'];
            $refresh_token_exp_db = DB::table('refresh_tokens')->where('email', $refreshed_token_email)->pluck('refresh_token_exp')[0];
            $refresh_token_db = DB::table('refresh_tokens')->where('email', $refreshed_token_email)->pluck('refresh_token')[0];
            
            if ($refresh_token_db == null && $refresh_token_exp_db == null) {
                return response()->json([
                    'status_code' => 401, 
                    'message' => 'refresh token not found'
                ], 401);
            }

            if (!Hash::check($refresh_token_from_request, $refresh_token_db)) {
                return response()->json([
                    'status_code' => 400, 
                    'message' => 'Refresh token from request does not match the one in the database'
                ], 400);
            }
    
            if (Carbon::parse($refresh_token_exp_db)->timestamp <= Carbon::now()->timestamp) {
                return response()->json([
                    'status_code' => 400, 
                    'message' => 'refresh token expired'
                ], 400);
            }

            $current_token = JWTAuth::getToken();
            $refreshed_access_token = JWTAuth::refresh($current_token);
    
            return response()->json([
                'access_token' => $refreshed_access_token,
                'access_token_exp_in' =>Carbon::now()->addHour(),
                'access_token_exp_in_with_timestamp' =>Carbon::now()->addHour()->timestamp,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout() {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status_code' => 200,
                'message' => 'user logout and access token will be blacklist'
            ], 200)->withCookie(cookie()->forget('token'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
