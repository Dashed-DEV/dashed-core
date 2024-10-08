<div class="bg-white py-16 sm:py-24">
    <x-container>
        <div class="py-0 sm:py-8 mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            <form class="flex flex-col space-y-6" wire:submit.prevent="register">
                <h2 class="font-display text-primary font-bold text-xl">{{Translation::get('register', 'login', 'Registreren')}}</h2>

                <x-fields.input required type="email" model="registerEmail" id="email" class="w-full" :label="Translation::get('email', 'login', 'E-mail')" />
                <x-fields.input required type="password" model="registerPassword" id="password" class="w-full" :label="Translation::get('password', 'login', 'Wachtwoord')" />
                <x-fields.input required type="password" model="registerPasswordConfirmation" id="password_confirmation" class="w-full" :label="Translation::get('repeat-password', 'login', 'Wachtwoord herhalen')" />
                <x-fields.checkbox model="registerRememberMe" id="register_remember_me" :label="Translation::get('remember-me', 'login', 'Herinner mij')" />

                <button class="button button--primary-dark w-full">{{Translation::get('register', 'login', 'Registreer')}}</button>
            </form>
            <form wire:submit.prevent="login"
                  class="space-y-6 flex flex-col mt-auto">
                <h2 class="font-display font-bold text-xl text-primary">{{Translation::get('login', 'login', 'Login')}}</h2>

                <x-fields.input required type="email" model="loginEmail" id="email" class="w-full" :label="Translation::get('email', 'login', 'E-mail')" />
                <x-fields.input required type="password" model="loginPassword" id="password" class="w-full" :label="Translation::get('password', 'login', 'Wachtwoord')" />
                <x-fields.checkbox model="loginRememberMe" id="login_remember_me" :label="Translation::get('remember-me', 'login', 'Herinner mij')" />

                <button class="button button--primary-dark mt-auto">{{Translation::get('login-now', 'login', 'Inloggen')}}</button>
            </form>
            <div class="lg:col-span-2 ml-auto -mt-4">
                <a class="text-primary-500 hover:text-primary-800 trans"
                   href="{{AccountHelper::getForgotPasswordUrl()}}">{{Translation::get('forgot-password', 'login', 'Wachtwoord vergeten?')}}</a>
            </div>
        </div>
    </x-container>
</div>
