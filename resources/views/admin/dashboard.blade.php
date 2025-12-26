@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin!</p>

    <div class="row">
        <div class="col">
            <div class="card p-3">
                <h4>Total Teachers: {{ $teachers }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h4>Total Students: {{ $students }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h4>Total Evaluations: {{ $evaluations }}</h4>
            </div>
        </div>
    </div>

    <br>
    <a href="{{ route('admin.teachers') }}" class="btn btn-primary">Manage Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="btn btn-info">Manage Students</a>
    <a href="{{ route('admin.criteria') }}" class="btn btn-warning">Manage Criteria</a>
    <a href="{{ route('admin.reports') }}" class="btn btn-success">View Reports</a>
</div>
@endsection
