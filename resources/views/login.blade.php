<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Form</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <header>
        <div><img src="{{ asset('images/logo.png') }}" class="logo" alt="logo" width="100" height="100"></div>
        <div class="wisehunt">
            <h2>WISEHUNT COMPANY</h2>
        </div>
    </header>

    <main>
        <div class="top-left">
            <div class="taas-tleft">Login to Wisehunt</div>
            <div class="taas-tleft">Inventory Management System</div>
            <div class="horizontal-line"></div>
            {{-- <div class="baba-tleft">Enter your username and password to use the system.</div> --}}
        </div>
        <div class="wrapper">
            {{-- <form action="{{ route('loginStore') }}" method="POST"> --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Login</h2>
                <div class="input-field">
                    <input type="email" class="has-value" name="email" id="email" value="{{ old('email') }}" required>
                    <label>Enter your email</label>
                </div>
                {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
                @if ($errors->has('email'))
                    <div class="text-danger">{{ $errors->first('email') }}</div>
                @endif
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Enter your password</label>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />


                </div>

                <button type="submit">Log In</button>
                <div class="register">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif                
            </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="baba">
            <div><i class="far fa-copyright" style="color: #000000;"></i></div>
            <div>2023 WiseHunt. All rights reserved.</div>
        </div>
    </footer>

   <!-- Add this script inside your HTML file, preferably just before the closing </body> tag -->
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select the email input and add input and blur event listeners
        var emailInput = document.getElementById('email');
        emailInput.addEventListener('input', handleInputChange);
        emailInput.addEventListener('blur', handleBlur);

        // Set the initial state of the label based on the input value
        updateLabelPosition();

        // Handle input event (when user types)
        function handleInputChange() {
            updateLabelPosition();
        }

        // Handle blur event (when the input loses focus)
        function handleBlur() {
            // If the email input is empty, move the label back down
            if (emailInput.value.trim() === '') {
                emailInput.classList.remove('has-value');
            }
        }

        // Update label position based on input value
        function updateLabelPosition() {
            if (emailInput.value.trim() !== '') {
                emailInput.classList.add('has-value');
            } else {
                emailInput.classList.remove('has-value');
            }
        }
    });
</script>





</body>

</html>
