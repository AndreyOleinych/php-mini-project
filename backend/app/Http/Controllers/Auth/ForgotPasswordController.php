<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Show Forget password Form
     *
     * @return response()
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    /**
     * Send forgot password form
     *
     * @return response()
     */
    public function submitForgotPasswordForm(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        dd($status);
//        var_dump($status === Password::RESET_LINK_SENT);
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

//        $request->validate([
//            'email' => 'required|email|exists:users',
//        ]);
//
//        $token = Str::random(64);
//
//        DB::table('password_resets')->insert([
//            'email' => $request->email,
//            'token' => $token,
//            'created_at' => Carbon::now()
//        ]);
//
//        Mail::send('email.forgotPassword', ['token' => $token], function($message) use($request){
//            $message->to($request->email);
//            $message->subject('Reset Password');
//        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
    /**
     * @param string $token
     *
     * @return response()
     */
    public function showResetPasswordForm($token) {
        return view('auth.forgotPasswordLink', ['token' => $token]);
    }

    /**
     * @param Request $request
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}
