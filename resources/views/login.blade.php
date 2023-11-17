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
            <form action="{{ route('loginStore') }}" method="POST">
                @csrf
                <h2>Login</h2>
                <div class="input-field">
                    <input type="text" name="username" value="{{ old('username') }}" required>
                    <label>Enter your username</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Enter your password</label>
                </div>

                <button type="submit">Log In</button>
                <div class="register">
                    <a href="#">Forgot password?</a>
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
</body>

</html>
