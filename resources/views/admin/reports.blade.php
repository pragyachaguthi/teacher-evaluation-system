@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Teacher Reports</h2>

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->course }}</td>
                <td>
                    <a href="{{ route('admin.report', $teacher->id) }}" class="btn btn-primary">
                        View Report
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
