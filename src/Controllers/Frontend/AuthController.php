<?php

namespace Qubiqx\QcommerceCore\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Qubiqx\QcommerceCore\Models\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Qubiqx\QcommerceCore\Models\Translation;
use Qubiqx\QcommerceCore\Mail\PasswordResetMail;
use Qubiqx\QcommerceCore\Requests\Frontend\LoginRequest;
use Qubiqx\QcommerceCore\Requests\Frontend\RegisterRequest;
use Qubiqx\QcommerceCore\Requests\Frontend\ResetPasswordRequest;
use Qubiqx\QcommerceCore\Requests\Frontend\ForgotPasswordRequest;

class AuthController extends FrontendController
{
    public function login()
    {
        if (Auth::check()) {
            return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists('qcommerce.login.show')) {
            SEOTools::setTitle(Translation::get('login-page-meta-title', 'login', 'Login'));
            SEOTools::setDescription(Translation::get('login-page-meta-description', 'login', 'Login to your account'));
            SEOTools::opengraph()->setUrl(url()->current());

            return view('qcommerce.login.show');
        } else {
            return $this->pageNotFound();
        }
    }

    public function loginPost(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->back()->with('error', Translation::get('no-user-found', 'login', 'We could not find a user matching these criteria'));
        }

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', Translation::get('no-user-found', 'login', 'We could not find a user matching these criteria'));
        }

        Auth::login($user, $request->remember_me);

        return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('succesfully-logged-in', 'login', 'You are logged in!'));
    }

    public function registerPost(RegisterRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user, $request->remember_me);

        return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('succesfully-registered', 'login', 'You are registered!'));
    }

    public function logout()
    {
        if (! Auth::check()) {
            return redirect(route('qcommerce.frontend.auth.login'))->with('success', Translation::get('already-logged-out', 'login', 'You are already logged out'));
        }

        Auth::logout();

        return redirect(route('qcommerce.frontend.auth.login'))->with('success', Translation::get('succesfully-logged-out', 'login', 'You are logged out!'));
    }

    public function forgotPassword()
    {
        if (Auth::check()) {
            return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists('qcommerce.forgot-password.show')) {
            SEOTools::setTitle(Translation::get('forgot-password-page-meta-title', 'login', 'Forgot password'));
            SEOTools::setDescription(Translation::get('forgot-password-page-meta-description', 'login', 'Forgot your password?'));
            SEOTools::opengraph()->setUrl(url()->current());

            return view('qcommerce.forgot-password.show');
        } else {
            return $this->pageNotFound();
        }
    }

    public function forgotPasswordPost(ForgotPasswordRequest $request)
    {
        if (Auth::check()) {
            return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password_reset_token = Str::random(64);
            $user->password_reset_requested = Carbon::now();
            $user->save();
            Mail::to($user->email)->send(new PasswordResetMail($user));
        }

        return redirect()->back()->with('success', Translation::get('forgot-password-post-success', 'login', 'If we can find an account with your email you will receive a email to reset your password.'));
    }

    public function resetPassword($passwordResetToken)
    {
        if (Auth::check()) {
            return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists('qcommerce.reset-password.show')) {
            SEOTools::setTitle(Translation::get('reset-password-page-meta-title', 'login', 'Reset password'));
            SEOTools::setDescription(Translation::get('reset-password-page-meta-description', 'login', 'Reset your password'));
            SEOTools::opengraph()->setUrl(url()->current());

            $user = User::where('password_reset_token', $passwordResetToken)->first();

            if (! $user) {
                return redirect(route('qcommerce.frontend.auth.forgot-password'))->with('success', Translation::get('reset-token-invalid', 'login', 'The token that was provided is invalid'));
            }

            View::share('user', $user);

            return view('qcommerce.reset-password.show');
        } else {
            return $this->pageNotFound();
        }
    }

    public function resetPasswordPost(ResetPasswordRequest $request, $passwordResetToken)
    {
        if (Auth::check()) {
            return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        $user = User::where('password_reset_token', $passwordResetToken)->first();

        if (! $user) {
            return redirect(route('qcommerce.frontend.auth.forgot-password'))->with('success', Translation::get('reset-token-invalid', 'login', 'The token that was provided is invalid'));
        }

        $user->password_reset_token = null;
        $user->password_reset_requested = null;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect(route('qcommerce.frontend.account'))->with('success', Translation::get('reset-password-post-success', 'login', 'Your password has been reset!'));
    }
}