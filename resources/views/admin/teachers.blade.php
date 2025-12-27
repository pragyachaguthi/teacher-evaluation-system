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
    <form id="addTeacherForm" action="{{ route('admin.teachers.store') }}" method="POST" class="form-card">
        @csrf
        <input type="text" name="name" placeholder="Teacher Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="course" placeholder="Course" required>

        <select name="faculty" required>
            <option value="">Select Faculty</option>
            <option value="Management">Management</option>
            <option value="Science">Science</option>
            <option value="Humanities">Humanities</option>
        </select>

        <select name="program" required>
            <option value="">Select Program</option>
            <option value="BIM">BIM</option>
            <option value="BCA">BCA</option>
            <option value="BBA">BBA</option>
            <option value="BSc CSIT">BSc CSIT</option>
        </select>

        <select name="semester" required>
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

        <button type="submit" class="btn btn-success">Add Teacher</button>
    </form>

    <h4 class="mt-4">Teacher List</h4>
    <table class="teacher-table">
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
                    <button class="btn btn-primary" 
                        onclick="openEditModal({{ $teacher->id }}, '{{ $teacher->name }}', '{{ $teacher->course }}', '{{ $teacher->faculty }}', '{{ $teacher->program }}', '{{ $teacher->semester }}')">
                        Edit
                    </button>

                    <form action="{{ route('admin.teachers.delete', $teacher->id) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ================= EDIT MODAL ================= --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>

        <form id="editForm" method="POST" class="form-card">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_id" name="id">
            
            <label>Name</label>
            <input type="text" id="edit_name" name="name" required>

            <label>Course</label>
            <input type="text" id="edit_course" name="course" required>

            <label>Faculty</label>
            <select id="edit_faculty" name="faculty" required>
                <option value="Management">Management</option>
                <option value="Science">Science</option>
                <option value="Humanities">Humanities</option>
            </select>

            <label>Program</label>
            <select id="edit_program" name="program" required>
                <option value="BIM">BIM</option>
                <option value="BCA">BCA</option>
                <option value="BBA">BBA</option>
                <option value="BSc CSIT">BSc CSIT</option>
            </select>

            <label>Semester</label>
            <select id="edit_semester" name="semester" required>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
                <option value="3">3rd Semester</option>
                <option value="4">4th Semester</option>
                <option value="5">5th Semester</option>
                <option value="6">6th Semester</option>
                <option value="7">7th Semester</option>
                <option value="8">8th Semester</option>
            </select>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

<style>
/* ======= GENERAL ======= 
body { font-family: 'Poppins', sans-serif; margin: 0; background:  #08eee2ff; color: #333; }*/
.container { max-width: 900px; margin: 40px auto; padding: 20px; background: #e2ededff; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }

/* ======= FORM ======= */
.form-card { display: flex; flex-direction: column; gap: 10px; margin-bottom: 30px; }
.form-card input, .form-card select { padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
.form-card button { padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
.btn-success { background-color: #28a745; color: #fff; }
.btn-primary { background-color: #007bff; color: #fff; }
.btn-danger { background-color: #dc3545; color: #fff; }
.btn:hover { opacity: 0.9; }

/* ======= TABLE ======= */
.teacher-table { width: 100%; border-collapse: collapse; }
.teacher-table th, .teacher-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
.teacher-table th { background-color: #007bff; color: #fff; }

/* ======= INLINE FORM ======= */
.inline-form { display: inline; }

/* ======= MODAL ======= */
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
.modal-content { background-color: #fff; padding: 20px; border-radius: 10px; width: 400px; position: relative; }
.close { position: absolute; top: 10px; right: 15px; font-size: 25px; font-weight: bold; cursor: pointer; }
</style>

<script>
function openEditModal(id, name, course, faculty, program, semester) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_course').value = course;
    document.getElementById('edit_faculty').value = faculty;
    document.getElementById('edit_program').value = program;
    document.getElementById('edit_semester').value = semester;

    document.getElementById('editForm').action = "/admin/teachers/" + id + "/update";
    
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal on clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}
</script>
@endsection
