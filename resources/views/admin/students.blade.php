@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="header">
                <h2>Students List</h2>
                <!-- Optional: Add Student button -->
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search & Filters -->
            <form method="GET" class="filters">
                <div class="filter-item">
                    <label>Search</label>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or ID">
                </div>

                <div class="filter-item">
                    <label>Program</label>
                    <select name="program">
                        <option value="">All</option>
                        <option value="BIM" {{ request('program')=='BIM' ? 'selected' : '' }}>BIM</option>
                        <option value="BBA" {{ request('program')=='BBA' ? 'selected' : '' }}>BBA</option>
                        <option value="CSIT" {{ request('program')=='CSIT' ? 'selected' : '' }}>CSIT</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label>Semester</label>
                    <select name="semester">
                        <option value="">All</option>
                        @for($i=1;$i<=8;$i++)
                            <option value="{{ $i }}" {{ request('semester')=="$i" ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-link">Reset</a>
                </div>
            </form>

            <!-- Students Table -->
            <div class="table-responsive">
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->program }}</td>
                            <td><span class="badge">{{ $student->semester }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openEditModal({{ $student->id }})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="openDeleteModal({{ $student->id }})">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal" id="editModal{{ $student->id }}">
                            <div class="modal-content">
                                <span class="close" onclick="closeEditModal({{ $student->id }})">&times;</span>
                                <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required>

                                    <label>Program</label>
                                    <input type="text" name="program" value="{{ old('program', $student->program) }}">

                                    <label>Semester</label>
                                    <select name="semester">
                                        @for($i=1;$i<=8;$i++)
                                            <option value="{{ $i }}" {{ (old('semester', $student->semester) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeEditModal({{ $student->id }})">Close</button>
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal" id="deleteModal{{ $student->id }}">
                            <div class="modal-content">
                                <span class="close" onclick="closeDeleteModal({{ $student->id }})">&times;</span>
                                <p>Are you sure you want to delete <strong>{{ $student->name }}</strong> (ID: {{ $student->id }})?</p>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" onclick="closeDeleteModal({{ $student->id }})">Cancel</button>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-info">
                <div>Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() ?? 0 }}</div>
                <div>{{ $students->withQueryString()->links() }}</div>
            </div>

        </div>
    </div>
</div>

<style>
/* ===== General ===== */
body { font-family: 'Poppins', sans-serif; background: #08eee2ff ; color: #333; }
.container { max-width: 900px; margin: 40px auto; }
.card { background: #fff; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
.card-body { padding: 20px; }

/* ===== Header ===== */
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }

/* ===== Alerts ===== */
.alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
.alert-success { background-color: #d4edda; color: #155724; }

/* ===== Filters ===== */
.filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
.filter-item { flex: 1; min-width: 120px; display: flex; flex-direction: column; }
.filter-item label { font-size: 0.8rem; margin-bottom: 3px; }
.filter-actions { display: flex; gap: 5px; align-items: flex-end; }

/* ===== Table ===== */
.student-table { width: 100%; border-collapse: collapse; }
.student-table th, .student-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
.student-table th { background-color: #007bff; color: #fff; }
.badge { background-color: #17a2b8; color: #000; padding: 3px 6px; border-radius: 5px; }

/* ===== Buttons ===== */
.btn { padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
.btn-primary { background-color: #007bff; color: #fff; }
.btn-success { background-color: #28a745; color: #fff; }
.btn-danger { background-color: #dc3545; color: #fff; }
.btn-secondary { background-color: #6c757d; color: #fff; }
.btn:hover { opacity: 0.9; }

/* ===== Modals ===== */
.modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
.modal-content { background: #fff; padding: 20px; border-radius: 10px; width: 400px; position: relative; }
.close { position: absolute; top: 10px; right: 15px; font-size: 25px; cursor: pointer; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }

/* ===== Pagination ===== */
.pagination-info { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; font-size: 0.85rem; color: #555; }
</style>

<script>
function openEditModal(id) {
    document.getElementById('editModal'+id).style.display = 'flex';
}
function closeEditModal(id) {
    document.getElementById('editModal'+id).style.display = 'none';
}
function openDeleteModal(id) {
    document.getElementById('deleteModal'+id).style.display = 'flex';
}
function closeDeleteModal(id) {
    document.getElementById('deleteModal'+id).style.display = 'none';
}
// Close modal on clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if(event.target === modal) modal.style.display = 'none';
    });
}
</script>
@endsection
