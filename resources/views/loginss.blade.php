<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/loginss.css') }}">

</head>

<body>
    <div class="bg-img">
        <div class="content">
            <header>Login Form</header>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <span class="fa fa-user"></span>
                    {{-- <input type="text" autofocus required placeholder="Email"> --}}
                    <input type="email" autofocus name="email" id="email" value="{{ old('email') }}"
                        placeholder="Email" required>
                </div>
                @if ($errors->has('email'))
                    <div class="text-danger">{{ $errors->first('email') }}</div>
                @endif

                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" name="password" class="pass-key" required placeholder="Password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    {{-- <span class="show">SHOW</span> --}}
                </div>
                <div class="field button">
                    <input type="submit" value="LOGIN">
                </div>

                <div class="pass">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                    {{-- <a href="#">Forgot Password?</a> --}}
                </div>
               
            </form>
            {{-- <div class="login">
               Or login with
            </div>
            <div class="links">
               <div class="facebook">
                  <i class="fab fa-facebook-f"><span>Facebook</span></i>
               </div>
               <div class="instagram">
                  <i class="fab fa-instagram"><span>Instagram</span></i>
               </div>
            </div>
            <div class="signup">
               Don't have account?
               <a href="#">Signup Now</a>
            </div> --}}
        </div>
    </div>
    <script>
        const pass_field = document.querySelector('.pass-key');
        const showBtn = document.querySelector('.show');
        showBtn.addEventListener('click', function() {
            if (pass_field.type === "password") {
                pass_field.type = "text";
                showBtn.textContent = "HIDE";
                showBtn.style.color = "#3498db";
            } else {
                pass_field.type = "password";
                showBtn.textContent = "SHOW";
                showBtn.style.color = "#222";
            }
        });
    </script>
</body>

</html>
