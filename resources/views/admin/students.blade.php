{{-- screenshot: /mnt/data/40d18e1b-e9ca-491e-9606-c09db1155aef.png --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4" >
                <h2 class="mb-0">Students List</h2>

                <!-- Add Student button (optional) -->
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search & Filters -->
            <form method="GET" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                <div class="flex-grow-1 min-w-150px">
                    <label class="form-label small">Search</label>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name or id">
                </div>

                <div class="flex-grow-1 min-w-120px">
                    <label class="form-label small">Program</label>
                    <select name="program" class="form-select">
                        <option value="">All</option>
                        <option value="BIM" {{ request('program')=='BIM' ? 'selected' : '' }}>BIM</option>
                        <option value="BBA" {{ request('program')=='BBA' ? 'selected' : '' }}>BBA</option>
                        <option value="CSIT" {{ request('program')=='CSIT' ? 'selected' : '' }}>CSIT</option>
                    </select>
                </div>

                <div class="flex-grow-1 min-w-120px">
                    <label class="form-label small">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">All</option>
                        @for($i=1;$i<=8;$i++)
                            <option value="{{ $i }}" {{ request('semester')=="$i" ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="d-grid" style="min-width: 120px;">
                    <button class="btn btn-primary mb-1">Filter</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-link text-decoration-none">Reset</a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-left">
                    <thead class="table-light ">
                        <tr>
                            <th>Name</th>
                            <th style="width:140px">Program</th>
                            <th style="width:110px">Semester</th>
                            
                            <th style="width:190px">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <!--@if(!empty($student->avatar))
                                        <img src="{{ asset('storage/'.$student->avatar) }}" alt="avatar" class="rounded-circle me-2" width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;">
                                            {{ strtoupper(substr($student->name,0,1)) }}
                                        </div>
                                    @endif
                                    <div>-->
                                        <div class="fw-semibold ">{{ $student->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $student->program }}</td>
                            <td><span class="badge bg-info text-dark">{{ $student->semester }}</span></td>
                           

                            <td>
                                <!-- Edit button opens modal -->
                                <button class="btn btn-sm btn-primary me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $student->id }}">
                                    Edit
                                </button>

                                <!-- Delete triggers a confirmation modal -->
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal (form inside modal) -->
                        <div class="modal fade" id="editModal{{ $student->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Student — {{ $student->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Program</label>
                                                    <input type="text" name="program" class="form-control" value="{{ old('program', $student->program) }}">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Semester</label>
                                                    <select name="semester" class="form-select">
                                                        @for($i=1;$i<=8;$i++)
                                                            <option value="{{ $i }}" {{ (old('semester', $student->semester) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                

                                                <!--<div class="col-md-6">
                                                    <label class="form-label">Avatar (optional)</label>
                                                    <input type="file" name="avatar" class="form-control">
                                                </div>-->

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete Student</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong>{{ $student->name }}</strong> (ID: {{ $student->id }})? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No students found.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() ?? 0 }}</div>
                <div>
                    {{ $students->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
