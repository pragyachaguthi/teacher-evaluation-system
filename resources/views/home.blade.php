@extends('layouts.app')

@section('content')
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #00c6ff, #007bff);
    color: #333;
}

nav {
    background: rgba(0, 0, 0, 0.9);
    color: #fff;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}

.brand {
    font-size: 1.5rem;
    font-weight: 700;
}

.brand span {
    display: inline-block;
    width: 10px;
    height: 10px;
    background: #fff;
    border-radius: 50%;
    margin-right: 10px;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 2rem;
    margin: 0;
    padding: 0;
}

nav a {
    color: #fff;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    transition: all 0.3s ease;
}

nav a:hover {
    background: #fff;
    color: #007bff;
}

.btn-pill {
    padding: 0.5rem 1.5rem;
    border: 1px solid #fff;
}

.btn-pill:hover {
    background: #fff;
    color: #007bff;
}


.hero {
    padding: 5rem 2rem;
    text-align: center;
    color: #fff;
}

.hero h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.hero p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-primary,
.btn-outline {
    padding: 1rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #fff;
    color: #007bff;
    border: none;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}

.btn-outline:hover {
    background: #fff;
    color: #007bff;
}

.hero-metrics {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    margin-top: 3rem;
}

.hero-metrics div {
    text-align: center;
}

.hero-metrics span {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
}


.features {
    background: #f8f9fa;
    padding: 4rem 2rem;
}

.features h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.feature {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.feature:hover {
    transform: translateY(-5px);
}

.feature-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.feature h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.feature p {
    color: #666;
}


.contact {
    background: #f8f9fa;
    padding: 4rem 2rem;
    text-align: center;
}

.contact h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
    color: #333;
}

.contact-item {
    font-size: 1.2rem;
    margin: 1rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}


footer {
    background: #333;
    color: #ccc;
    padding: 1.5rem;
    text-align: center;
}

footer span {
    color: #00c6ff;
    font-weight: 600;
}


@media (max-width: 768px) {
    nav ul {
        gap: 1rem;
        flex-wrap: wrap;
    }

    .hero h1 {
        font-size: 2rem;
        padding: 0 1rem;
    }

    .hero-metrics {
        gap: 2rem;
    }

    .buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<nav>
    <div class="brand">
        <span></span>Eval
    </div>
    <ul>
        <li><a href="#features">Features</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a class="btn-pill" href="{{ route('login') }}">Login</a></li>
        <li><a class="btn-pill" href="{{ route('register') }}">Get Started</a></li>
    </ul>
</nav>

<section class="hero">
    <div>
        <h1>Welcome to Eval</h1>
        <p>Smart evaluation platform for modern institutes. Centralize teacher evaluations and student feedback.</p>
        <div class="buttons">
            <a class="btn-primary" href="{{ route('login') }}">Login</a>
            <a class="btn-outline" href="{{ route('register') }}">Get Started</a>
        </div>
        <div class="hero-metrics">
            <div><span>Role-based</span>Admin, Teacher, Student</div>
            <div><span>Secure</span>Full authentication</div>
            <div><span>Real-time</span>Insights & Reports</div>
        </div>
    </div>
</section>

<section class="features" id="features">
    <h2>Why Choose Eval</h2>
    <div class="features-grid">
        <div class="feature">
            <div class="feature-icon">👥</div>
            <h3>User Friendly</h3>
            <p>Clean interface for all users</p>
        </div>
        <div class="feature">
            <div class="feature-icon">⚡</div>
            <h3>Fast Performance</h3>
            <p>Quick loading and smooth navigation</p>
        </div>
        <div class="feature">
            <div class="feature-icon">🔒</div>
            <h3>Secure Access</h3>
            <p>Protected evaluation data</p>
        </div>
    </div>
</section>

<section class="contact" id="contact">
    <h2>Get in Touch</h2>
    <div class="contact-item">
        📧 <strong>pragyachaguthi7@gmail.com</strong>
    </div>
    <div class="contact-item">
        📱 <strong>9847746908</strong>
    </div>
</section>

<footer>
    &copy; {{ date('Y') }} <span>Eval</span>. All rights reserved.
</footer>
@endsection
