<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OtpForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
        $access_token_exp_date = Carbon::now()->addMinute()->timestamp;
        $refresh_token_exp_date = Carbon::now()->addDays(7)->timestamp;
        try {
            $token = auth()->guard('api')->attempt($credentials, ['exp' => $access_token_exp_date]);
            if(!$token) {
                return response()->json(['message' => 'Login credential invalid'], 400);
            }

            $refresh_token = Str::random(128);
            
            return response()->json([
                'access_token' => $token,
                'access_token_exp' => $access_token_exp_date,
                'refresh_token' => $refresh_token,
                'refresh_token_exp' => $refresh_token_exp_date,
                'success' => true,
                'message' => 'Login credential is valid'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function forget_password(Request $request) {
        $validate = $request->validate([
            'email' => 'required|email'
        ]);

        $email = $validate['email'];

        $email_db = User::where('email', $email)->first()->email;
        if ($email == $email_db) {
            $otp = '594805';
            $new_otp = new OtpForgetPassword($email, $otp);
            Mail::to($email)->send($new_otp);

            Cache::put('otp', $otp, now()->addMinutes(2));
            Cache::put('reset_email', $email, now()->addMinutes(2));

            return response()->json(['message' => 'OTP sent to email'], 200);
        } else {
            // return cant find email
            return response()->json(['message' => 'Email not found'], 404);
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
            'confirmed_password' => 'required|min:8|max:20'
        ]);

        $regex = "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;\"'<>,.?\/\\|-]).{1,}$/";
        $pass = $data['password'];
        $confirmed_pass = $data['confirmed_password'];

        $passwordRequirement = preg_match($regex, $pass);
        if(!$passwordRequirement) {
            return redirect()->back()->json(['error' => 'Masukkan password dengan syarat kombinasi huruf besar, kecil, angka, tanda baca, dan terdapat minimal 1 karakter'], 400);
        }

        if ($pass != $confirmed_pass) {
            return redirect()->back()->json(['message' => 'please type same password with confirmed'], 400);
        }

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
        $refresh_token = $request->refresh_token;
        if (!$refresh_token) {
            return response()->json(['error' => 'refresh token is missing'], 400);
        }

    }

    public function logout() {

    }
}
