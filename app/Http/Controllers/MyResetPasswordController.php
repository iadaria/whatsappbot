<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class MyResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function showResetPasswordForm() {
        
        $user = auth()->user();

        return view('auth.passwords.reset', compact('user'));
    }

    public function resetpassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->email = $request->email;
        $user->password = bcrypt($request->new_password);

        $user->save();
        
        return back()->with('success', 'Парололь был успешно изменен.');
    }

}
