@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Evaluate {{ $teacher->name }} ({{ $teacher->course }})</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluate.store') }}" method="POST">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Criteria</th>
                    <th>Rating (1–5)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criterias as $key => $criteria)
                <tr>
                    <td>{{ $criteria->criteria }}</td>
                    <td>
                        <select name="criteria{{ $key + 1 }}" class="form-select" required>
                            <option value="">Select rating</option>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <label class="fw-bold mt-3">Feedback (Optional):</label>
        <textarea name="feedback" class="form-control" rows="3"></textarea>

        <button type="submit" class="btn btn-success mt-3">Submit Evaluation</button>
    </form>
</div>
@endsection
