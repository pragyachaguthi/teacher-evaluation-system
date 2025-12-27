@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="page-title">Teacher Reports</h2>

    <div class="table-responsive">
        <table class="teacher-table">
            <thead>
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
                        <a href="{{ route('admin.report', $teacher->id) }}" class="btn btn-primary">View Report</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
/* ===== General ===== 
body { font-family: 'Poppins', sans-serif; background: #f4f6f8; color: #333; }*/
.container { max-width: 900px; margin: 40px auto; padding: 0 15px; }

/* ===== Page Title ===== */
.page-title { margin-bottom: 20px; font-weight: 600; }

/* ===== Table ===== */
.table-responsive { overflow-x: auto; }
.teacher-table { width: 100%; border-collapse: collapse; }
.teacher-table th, .teacher-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
.teacher-table th { background-color: #007bff; color: #fff; }
.teacher-table tr:hover { background-color: #f1f1f1; }

/* ===== Buttons ===== */
.btn { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-weight: bold; display: inline-block; }
.btn-primary { background-color: #007bff; color: #fff; }
.btn-primary:hover { opacity: 0.9; }
</style>
@endsection
