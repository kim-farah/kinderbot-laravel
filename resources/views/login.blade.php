<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kinderbot Login</title>
    <style>
        :root {
            --dark-blue: #1E3A5F;
            --orange: #FF6B35;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --gray: #707070;
            --border-gray: #E0E0E0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', 'Segoe UI', 'Inter', sans-serif;
            background: var(--light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 420px;
            text-align: center;
        }
        h1 {
            color: var(--dark-blue);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        h2 {
            color: var(--dark-blue);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--dark-blue);
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-gray);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--orange);
        }
        .login-btn {
            background: var(--orange);
            color: var(--white);
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-btn:hover {
            opacity: 0.9;
        }
        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🤖 Kinderbot</h1>
        <h2>Welcome back!</h2>

        <!--<form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>

            @if($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>-->

    <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="form-input" autocomplete="username" required>
    </div>
    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" class="form-input" autocomplete="current-password" required >

    </div>
    <button type="submit" class="login-btn">Login</button>
    @if($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
    @endif
</form>
    </div>
</body>
</html>
