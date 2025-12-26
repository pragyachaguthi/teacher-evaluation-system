@extends('layouts.app')

@section('content')
<style>
.dashboard-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
}
.stats-box {
    background: #f7faff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    border-left: 5px solid #0d6efd;
}
.profile-box {
    background: #f1f8ff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
    border-left: 5px solid #198754;
}
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: white;
}
.status-completed { background: #198754; }
.status-pending { background: #dc3545; }
</style>

<div class="container">
    <h2 class="mb-4">📚 Student Dashboard</h2>

    {{-- Student Profile --}}
    <div class="profile-box">
        <h5 class="mb-1"><strong>Student:</strong> {{ auth()->user()->name }}</h5>
        <p class="mb-0">
            <strong>Program:</strong> {{ $student->program ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Semester:</strong> {{ $student->semester ?? 'N/A' }}
        </p>
    </div>

    {{-- Quick Stats --}}
    <div class="stats-box">
        <p class="mb-1"><strong>Total Teachers:</strong> {{ $teachers->count() }}</p>
        <p class="mb-1"><strong>Evaluated:</strong> {{ $uniqueEvaluated }}</p>
        <p class="mb-0"><strong>Pending:</strong> {{ $pending }}</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- List of Teachers --}}
    <div class="dashboard-card">
        <h4 class="mb-3">🧑‍🏫 List of Teachers</h4>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="30%">Teacher</th>
                    <th width="30%">Course</th>
                    <th width="20%">Status</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->course }}</td>

                    {{-- Status --}}
                    <td>
                        @if($evaluated->contains($teacher->id))
                            <span class="status-badge status-completed">✔ Completed</span>
                        @else
                            <span class="status-badge status-pending">⏳ Pending</span>
                        @endif
                    </td>

                    {{-- Action --}}
                    <td>
                        @if($evaluated->contains($teacher->id))
                            <button class="btn btn-secondary btn-sm" disabled>Evaluated</button>
                        @else
                            <a href="{{ route('evaluate.show', $teacher->id) }}" 
                               class="btn btn-primary btn-sm">Evaluate</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        No teachers assigned to you yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
