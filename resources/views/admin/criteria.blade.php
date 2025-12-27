@extends('layouts.app')

@section('content')

<style>
    .page-container {
        max-width: 960px;
        margin: 20px auto;
        padding: 0 16px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .section-title {
        margin-bottom: 16px;
        font-size: 24px;
        font-weight: 600;
    }

    .card-box {
        border: 1px solid #dddddd;
        border-radius: 6px;
        background-color: #ffffff;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header-box {
        padding: 10px 16px;
        border-bottom: 1px solid #eeeeee;
        font-weight: 600;
        background-color: #f7f7f7;
    }

    .card-body-box {
        padding: 16px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-label-custom {
        display: block;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .input-text {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #cccccc;
        border-radius: 4px;
        font: inherit;
        box-sizing: border-box;
    }

    .btn-main {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #0d6efd;
        background-color: #0d6efd;
        color: #ffffff;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-main:hover {
        background-color: #0b5ed7;
    }

    .btn-secondary {
        background-color: #ffffff;
        color: #333333;
        border-color: #cccccc;
    }

    .btn-secondary:hover {
        background-color: #e9ecef;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #bb2d3b;
    }

    .btn-small {
        padding: 4px 8px;
        font-size: 12px;
    }

    .inline-form {
        display: inline-block;
        margin-left: 4px;
    }

    .table-basic {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    .table-basic th,
    .table-basic td {
        padding: 8px;
        border-bottom: 1px solid #eeeeee;
        text-align: left;
    }

    .table-basic tbody tr:hover {
        background-color: #fafafa;
    }

    .table-actions-column {
        width: 180px;
    }

    .muted-text {
        color: #6c757d;
        font-size: 14px;
    }

    .alert-success-custom {
        padding: 8px 12px;
        border-radius: 4px;
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
        margin-bottom: 16px;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-overlay.modal-open {
        display: flex;
    }

    .modal-box {
        background-color: #ffffff;
        border-radius: 6px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header-box,
    .modal-footer-box {
        padding: 10px 14px;
        border-bottom: 1px solid #eeeeee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-footer-box {
        border-top: 1px solid #eeeeee;
        border-bottom: none;
    }

    .modal-body-box {
        padding: 14px;
    }

    .modal-title-text {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .modal-close-button {
        background: none;
        border: none;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
    }
</style>

<div class="page-container">

    <h2 class="section-title">Manage Evaluation Criteria</h2>

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    <div class="card-box">
        <div class="card-header-box">Add New Criteria</div>
        <div class="card-body-box">
            <form method="POST" action="{{ route('admin.criteria.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label-custom">Criteria Name</label>
                    <input type="text" name="criteria" class="input-text" required>
                </div>

                <button type="submit" class="btn-main">Add Criteria</button>
            </form>
        </div>
    </div>

    <div class="card-box">
        <div class="card-header-box">Existing Criteria</div>
        <div class="card-body-box">

            @if($criterias->count() == 0)
                <p class="muted-text">No criteria found.</p>
            @else
            <table class="table-basic">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Criteria</th>
                        <th class="table-actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $criteria)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $criteria->criteria }}</td>

                            <td>
                                <button type="button"
                                        class="btn-main btn-small btn-secondary"
                                        onclick="openEditModal({{ $criteria->id }})">
                                    Edit
                                </button>

                                <form action="{{ route('admin.criteria.delete', $criteria->id) }}"
                                      method="POST"
                                      class="inline-form"
                                      onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-main btn-small btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div id="edit-modal-{{ $criteria->id }}" class="modal-overlay">
                            <div class="modal-box">

                                <div class="modal-header-box">
                                    <h5 class="modal-title-text">Edit Criteria</h5>
                                    <button type="button"
                                            class="modal-close-button"
                                            onclick="closeEditModal({{ $criteria->id }})">
                                        ×
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('admin.criteria.update', $criteria->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body-box">
                                        <label class="form-label-custom">Criteria Name</label>
                                        <input type="text"
                                               name="criteria"
                                               class="input-text"
                                               value="{{ $criteria->criteria }}"
                                               required>
                                    </div>

                                    <div class="modal-footer-box">
                                        <button type="button"
                                                class="btn-main btn-secondary"
                                                onclick="closeEditModal({{ $criteria->id }})">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn-main">
                                            Update
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
            @endif

        </div>
    </div>
</div>

<script>
    function openEditModal(id) {
        var modal = document.getElementById('edit-modal-' + id);
        if (modal) {
            modal.classList.add('modal-open');
        }
    }

    function closeEditModal(id) {
        var modal = document.getElementById('edit-modal-' + id);
        if (modal) {
            modal.classList.remove('modal-open');
        }
    }
</script>

@endsection
