@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="page-title">Change Password</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.change') }}" class="password-form">
        @csrf

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="new_password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>

<style>
/* ===== General ===== */
body { font-family: 'Poppins', sans-serif; background: #08eee2ff; color: #333; }
.container { max-width: 500px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }

/* ===== Page Title ===== */
.page-title { margin-bottom: 20px; font-weight: 600; }

/* ===== Alerts ===== */
.alert { padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
.alert-success { background-color: #d4edda; color: #155724; }
.alert-danger { background-color: #f8d7da; color: #721c24; }
.alert ul { margin: 0; padding-left: 20px; }

/* ===== Form ===== */
.password-form { display: flex; flex-direction: column; gap: 15px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 0.9rem; font-weight: 500; }
.form-group input { padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem; }

/* ===== Button ===== */
.btn { padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: #fff; background-color: #007bff; }
.btn:hover { opacity: 0.9; width: 100%; }
</style>
@endsection
