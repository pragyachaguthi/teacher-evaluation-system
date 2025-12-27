<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #08eee2ff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 2rem;
        font-weight: 600;
    }

    p {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .col {
        flex: 1;
        min-width: 250px;
    }

    .card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .card h4 {
        color: #495057;
        font-size: 1.3rem;
        font-weight: 500;
    }

    .btn {
        display: block;
        width: 100%;
        max-width: 300px;
        padding: 0.75rem 1.5rem;
        margin: 0.5rem auto;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #117a8b;
        transform: translateY(-1px);
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-1px);
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-success:hover {
        background-color: #1e7e34;
        transform: translateY(-1px);
    }

    br {
        display: block;
        margin: 1rem 0;
    }

    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }
        
        .container {
            padding: 1rem;
        }
        
        h2 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin!</p>

    <div class="row">
        <div class="col">
            <div class="card p-3">
                <h4>Total Teachers: <span id="teachers">12</span></h4>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h4>Total Students: <span id="students">1</span></h4>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h4>Total Evaluations: <span id="evaluations">45</span></h4>
            </div>
        </div>
    </div>

    <br>
    <a href="/admin/teachers" class="btn btn-primary">Manage Teachers</a>
    <a href="/admin/students" class="btn btn-info">Manage Students</a>
    <a href="/admin/criteria" class="btn btn-warning">Manage Criteria</a>
    <a href="/admin/reports" class="btn btn-success">View Reports</a>
</div>
