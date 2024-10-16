<?php

namespace Dashed\DashedCore\Controllers\Frontend;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Dashed\DashedCore\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Dashed\DashedCore\Mail\PasswordResetMail;
use Dashed\DashedTranslations\Models\Translation;
use Dashed\DashedCore\Livewire\Frontend\Auth\Login;
use Dashed\DashedCore\Requests\Frontend\LoginRequest;
use Dashed\DashedCore\Requests\Frontend\RegisterRequest;
use Dashed\DashedCore\Livewire\Frontend\Auth\ResetPassword;
use Dashed\DashedCore\Livewire\Frontend\Auth\ForgotPassword;
use Dashed\DashedCore\Requests\Frontend\ResetPasswordRequest;
use Dashed\DashedCore\Requests\Frontend\ForgotPasswordRequest;

class AuthController extends FrontendController
{
    public function login()
    {
        if (Auth::check()) {
            return redirect(route('dashed.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists(env('SITE_THEME', 'dashed').'.auth.login')) {
            seo()->metaData('metaTitle', Translation::get('login-page-meta-title', 'login', 'Login'));
            seo()->metaData('metaDescription', Translation::get('login-page-meta-description', 'login', 'Login to your account'));

            return view('dashed-core::layouts.livewire-master', [
                'livewireComponent' => Login::class,
            ]);

            return view(env('SITE_THEME', 'dashed').'.auth.login');
        } else {
            return $this->pageNotFound();
        }
    }

    public function loginPost(LoginRequest $request)
    {
        throw new Exception('The loginPost method is outdated, use Livewire instead');

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->back()->with('error', Translation::get('no-user-found', 'login', 'We could not find a user matching these criteria'));
        }

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', Translation::get('no-user-found', 'login', 'We could not find a user matching these criteria'));
        }

        Auth::login($user, $request->remember_me);

        return redirect(route('dashed.frontend.account'))->with('success', Translation::get('succesfully-logged-in', 'login', 'You are logged in!'));
    }

    public function registerPost(RegisterRequest $request)
    {
        throw new Exception('The registerPost method is outdated, use Livewire instead');

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user, $request->remember_me);

        return redirect(route('dashed.frontend.account'))->with('success', Translation::get('succesfully-registered', 'login', 'You are registered!'));
    }

    public function logout()
    {
        if (! Auth::check()) {
            return redirect(route('dashed.frontend.auth.login'))->with('success', Translation::get('already-logged-out', 'login', 'You are already logged out'));
        }

        Auth::logout();

        return redirect(route('dashed.frontend.auth.login'))->with('success', Translation::get('succesfully-logged-out', 'login', 'You are logged out!'));
    }

    public function forgotPassword()
    {
        if (Auth::check()) {
            return redirect(route('dashed.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists(env('SITE_THEME', 'dashed').'.auth.forgot-password')) {
            seo()->metaData('metaTitle', Translation::get('forgot-password-page-meta-title', 'login', 'Forgot password'));
            seo()->metaData('metaDescription', Translation::get('forgot-password-page-meta-description', 'login', 'Forgot your password?'));

            return view('dashed-core::layouts.livewire-master', [
                'livewireComponent' => ForgotPassword::class,
            ]);

            return view(env('SITE_THEME', 'dashed').'.auth.forgot-password');
        } else {
            return $this->pageNotFound();
        }
    }

    public function forgotPasswordPost(ForgotPasswordRequest $request)
    {
        throw new Exception('The forgotPasswordPost method is outdated, use Livewire instead');

        if (Auth::check()) {
            return redirect(route('dashed.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
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
            return redirect(route('dashed.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        if (View::exists(env('SITE_THEME', 'dashed').'.auth.reset-password')) {
            seo()->metaData('metaTitle', Translation::get('reset-password-page-meta-title', 'login', 'Reset password'));
            seo()->metaData('metaDescription', Translation::get('reset-password-page-meta-description', 'login', 'Reset your password'));

            $user = User::where('password_reset_token', $passwordResetToken)->first();

            if (! $user) {
                return redirect(route('dashed.frontend.auth.forgot-password'))->with('success', Translation::get('reset-token-invalid', 'login', 'The token that was provided is invalid'));
            }

            View::share('user', $user);
            View::share('passwordResetToken', $passwordResetToken);

            return view('dashed-core::layouts.livewire-master', [
                'livewireComponent' => ResetPassword::class,
                'parameters' => [
                    'passwordResetToken' => $passwordResetToken,
                    'user' => $user,
                ],
            ]);

            return view(env('SITE_THEME', 'dashed').'.auth.reset-password');
        } else {
            return $this->pageNotFound();
        }
    }

    public function resetPasswordPost(ResetPasswordRequest $request, $passwordResetToken)
    {
        throw new Exception('The resetPasswordPost method is outdated, use Livewire instead');

        if (Auth::check()) {
            return redirect(route('dashed.frontend.account'))->with('success', Translation::get('already-logged-in', 'login', 'You are already logged in'));
        }

        $user = User::where('password_reset_token', $passwordResetToken)->first();

        if (! $user) {
            return redirect(route('dashed.frontend.auth.forgot-password'))->with('success', Translation::get('reset-token-invalid', 'login', 'The token that was provided is invalid'));
        }

        $user->password_reset_token = null;
        $user->password_reset_requested = null;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect(route('dashed.frontend.account'))->with('success', Translation::get('reset-password-post-success', 'login', 'Your password has been reset!'));
    }
}
