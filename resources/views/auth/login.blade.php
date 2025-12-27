@extends('layouts.app')

@section('content')
<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <!-- Dummy fields to prevent autofill -->
        <input type="text" name="fake_username" autocomplete="username" style="display:none">
        <input type="password" name="fake_password" autocomplete="current-password" style="display:none">

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Enter your email" autocomplete="off" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
        </div>

        <div class="form-group">
            <label for="role">Login as</label>
            <select name="role" id="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>
</div>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #08eee2ff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background: #fff;
    padding: 30px 25px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

h2 {
    margin-bottom: 25px;
    font-weight: 600;
    color: #333;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    margin-bottom: 5px;
    font-size: 0.9rem;
    font-weight: 500;
}

.form-group input,
.form-group select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

button.btn-login {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.3s;
}

button.btn-login:hover {
    background-color: #0056b3;
}
</style>
@endsection
