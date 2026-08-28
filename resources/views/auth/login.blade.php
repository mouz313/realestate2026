<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f4f5f7;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1f2937;
        }
        .card {
            background: #fff;
            width: 100%;
            max-width: 360px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
        .card h1 {
            font-size: 1.4rem;
            margin-bottom: .25rem;
        }
        .card p.sub {
            color: #6b7280;
            font-size: .9rem;
            margin-bottom: 1.5rem;
        }
        .field { margin-bottom: 1rem; }
        .field label {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: .35rem;
        }
        .field input {
            width: 100%;
            padding: .65rem .8rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            outline: none;
        }
        .field input:focus { border-color: #4f46e5; }
        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }
        .row a { color: #4f46e5; text-decoration: none; }
        .btn {
            width: 100%;
            padding: .7rem;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { background: #4338ca; }
        .alert {
            background: #fee2e2;
            color: #b91c1c;
            padding: .6rem .8rem;
            border-radius: 8px;
            font-size: .85rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sign In</h1>
        <p class="sub">Enter your credentials to continue.</p>

        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $message)
                    <div>{{ $message }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Your password">
            </div>

            <div class="row">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="{{ route('password.request') }}">Forgot?</a>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html>
