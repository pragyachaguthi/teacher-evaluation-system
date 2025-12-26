@extends('layouts.app')



@if($errors->any())
    <div style="color:red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input id="name" type="text" 
                   class="form-control" name="name" required autofocus>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" type="email" 
                   class="form-control" name="email" required>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input id="password" type="password" 
                   class="form-control" name="password" required>
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" 
                   class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Program & Semester Code</label>
            <input type="text" name="program_code" class="form-control" required placeholder="e.g., BIM-5-2025">
            @error('program_code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="d-grid">
            <button type="submit" class="btn btn-success">Register</button>
        </div>
    </form>
</div>
@endsection
