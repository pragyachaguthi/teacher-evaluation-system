@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Teachers</h2>

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    {{-- Add Teacher --}}
    <form action="{{ route('admin.teachers.store') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Teacher Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>

        <input type="text" name="course" placeholder="Course" class="form-control mb-2" required>

        <!-- Faculty -->
        <select name="faculty" class="form-control mb-2" required>
            <option value="">Select Faculty</option>
            <option value="Management">Management</option>
            <option value="Science">Science</option>
            <option value="Humanities">Humanities</option>
        </select>

        <!-- Program -->
        <select name="program" class="form-control mb-2" required>
            <option value="">Select Program</option>
            <option value="BIM">BIM</option>
            <option value="BCA">BCA</option>
            <option value="BBA">BBA</option>
            <option value="BSc CSIT">BSc CSIT</option>
        </select>

        <!-- Semester -->
        <select name="semester" class="form-control mb-2" required>
            <option value="">Select Semester</option>
            <option value="1">1st Semester</option>
            <option value="2">2nd Semester</option>
            <option value="3">3rd Semester</option>
            <option value="4">4th Semester</option>
            <option value="5">5th Semester</option>
            <option value="6">6th Semester</option>
            <option value="7">7th Semester</option>
            <option value="8">8th Semester</option>
        </select>

        <button type="submit" class="btn btn-success w-100">Add Teacher</button>
    </form>


    <h4 class="mt-4">Teacher List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->course }}</td>
                <td>
                    {{-- EDIT BUTTON --}}
                    <button 
                        class="btn btn-primary btn-sm"
                        onclick="openEditModal({{ $teacher->id }}, '{{ $teacher->name }}', '{{ $teacher->course }}')"
                    >
                        Edit
                    </button>

                    {{-- DELETE --}}
                    <form action="{{ route('admin.teachers.delete', $teacher->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ================= EDIT MODAL ================= --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id">

                    <label>Name</label>
                    <input type="text" id="edit_name" name="name" class="form-control mb-2">

                
                    <label>Course</label>
                    <input type="text" id="edit_course" name="course" class="form-control mb-2">

                    <!-- Faculty -->
                    <label>Faculty</label>
                    <select name="faculty" id="edit_faculty" class="form-control mb-2" required>
                        <option value="Management">Management</option>
                        <option value="Science">Science</option>
                        <option value="Humanities">Humanities</option>
                    </select>

                    <!-- Program -->
                    <label>Program</label>
                    <select name="program" id="edit_program" class="form-control mb-2" required>
                        <option value="BIM">BIM</option>
                        <option value="BCA">BCA</option>
                        <option value="BBA">BBA</option>
                        <option value="BSc CSIT">BSc CSIT</option>
                    </select>

                    <!-- Semester -->
                    <label>Semester</label>
                    <select name="semester" id="edit_semester" class="form-control mb-2" required>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                        <option value="3">3rd Semester</option>
                        <option value="4">4th Semester</option>
                        <option value="5">5th Semester</option>
                        <option value="6">6th Semester</option>
                        <option value="7">7th Semester</option>
                        <option value="8">8th Semester</option>
                    </select>

                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function openEditModal(id, name, course, faculty, program, semester) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_course').value = course;
    document.getElementById('edit_faculty').value = faculty;
    document.getElementById('edit_program').value = program;
    document.getElementById('edit_semester').value = semester;

    // Set FORM ACTION
    document.getElementById('editForm').action = "/admin/teachers/" + id + "/update";

    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>

@endsection
