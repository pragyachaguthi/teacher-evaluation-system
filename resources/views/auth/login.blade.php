@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <!-- Dummy fields to prevent autofill -->
        <input type="text" name="fake_username" autocomplete="username" style="display:none">
        <input type="password" name="fake_password" autocomplete="current-password" style="display:none">

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" 
                class="form-control" placeholder="Enter your email" 
                value="" autocomplete="off" required autofocus>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" 
                class="form-control" placeholder="Enter your password" 
                autocomplete="current-password" required>
        </div>

        <div class="form-group mb-3">
            <label for="role">Login as</label>
            <select name="role" id="role" class="form-control" required>
                <option value="" disabled selected>Select your role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>

</div>
@endsection
