@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-3">Manage Evaluation Criteria</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add New Criteria --}}
    <div class="card mb-4">
        <div class="card-header">Add New Criteria</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.criteria.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Criteria Name</label>
                    <input type="text" name="criteria" class="form-control" required>
                </div>

                <button class="btn btn-primary">Add Criteria</button>
            </form>
        </div>
    </div>

    {{-- List Existing Criteria --}}
    <div class="card">
        <div class="card-header">Existing Criteria</div>
        <div class="card-body">

            @if($criterias->count() == 0)
                <p class="text-muted">No criteria found.</p>
            @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Criteria</th>
                        <th width="180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $criteria)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $criteria->criteria }}</td>

                            <td>
                                {{-- Edit Button (Modal Trigger) --}}
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $criteria->id }}">
                                    Edit
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.criteria.delete', $criteria->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModal{{ $criteria->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Criteria</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form method="POST" action="{{ route('admin.criteria.update', $criteria->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">
                                            <label class="form-label">Criteria Name</label>
                                            <input type="text"
                                                   name="criteria"
                                                   class="form-control"
                                                   value="{{ $criteria->criteria }}"
                                                   required>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-primary">Update</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
            @endif

        </div>
    </div>
</div>
@endsection
