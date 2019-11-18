<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
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
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['web']);
    }

    public function showResetPasswordForm() {
        
        $user = auth()->user();
        if ($user == null) {
            $user = new User();
        }

        return view('auth.passwords.reset', compact('user'));
    }

    public function resetpassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user = auth()->user();
        if ($user == null) {
            $user = User::all()->first();
        }
        $user->email = $request->email;
        $user->password = bcrypt($request->new_password);
        $user->save();
        
        
        return back()->with('success', 'Парололь был успешно изменен.');
    }

}
