@extends('layouts.app')

@section('content')
<style>
    /* Your existing CSS stays the same + new chart styles */
    * { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .dashboard-container { max-width: 1400px; margin: 0 auto; padding: 30px; }
    
    /* Your existing header, stats-grid, stat-card, panel-card, feedback-item CSS stays EXACTLY the same */
    .header-section { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 25px; padding: 35px; margin-bottom: 30px; box-shadow: 0 25px 50px rgba(0,0,0,0.15); text-align: center; }
    .page-title { font-size: 2.8rem; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; font-weight: 800; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin: 30px 0; }
    .stat-card { background: rgba(255,255,255,0.9); border-radius: 20px; padding: 30px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transition: all 0.4s ease; position: relative; overflow: hidden; }
    .stat-card:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.2); }
    .stat-card.primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
    .stat-card.success { background: linear-gradient(135deg, #48bb78, #38a169); color: white; }
    .stat-card.info { background: linear-gradient(135deg, #4299e1, #3182ce); color: white; }
    .stat-icon { font-size: 3.5rem; margin-bottom: 15px; opacity: 0.9; }
    .stat-number { font-size: 3.2rem; font-weight: 800; margin: 0; }
    .stat-label { font-size: 1.2rem; margin-top: 10px; opacity: 0.95; }
    .panel-card { background: rgba(255,255,255,0.9); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); margin-bottom: 25px; backdrop-filter: blur(10px); }
    .feedback-item { background: #f8fafc; border-left: 5px solid #667eea; padding: 20px; border-radius: 15px; margin-bottom: 20px; transition: all 0.3s ease; }
    .feedback-item:hover { box-shadow: 0 10px 30px rgba(102,126,234,0.2); transform: translateX(8px); border-left-color: #764ba2; }
    
    /* 🔥 NEW CHART STYLES */
    .charts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin: 30px 0; }
    .chart-container { text-align: center; }
    .chart-wrapper { position: relative; height: 250px; width: 250px; margin: 0 auto; }
    .chart-score { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 2.2rem; font-weight: 800; color: #4a5568; z-index: 10; }
    
    /* 🔥 WORD CLOUD */
    .word-cloud { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; padding: 20px 0; }
    .word-tag { padding: 10px 20px; border-radius: 25px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; border: none; }
    .word-tag.strength { background: linear-gradient(135deg, #48bb78, #38a169); color: white; }
    .word-tag.weakness { background: linear-gradient(135deg, #f56565, #e53e3e); color: white; }
    .word-tag.neutral { background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; }
    .word-tag:hover { transform: scale(1.1) rotate(5deg); }
    
    @media (max-width: 768px) { .charts-grid { grid-template-columns: repeat(2, 1fr); } .chart-wrapper { height: 200px; width: 200px; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-container">
    <!-- Header (unchanged) -->
    <div class="header-section">
        <h1 class="page-title">👩‍🏫 {{ $teacher->name }} Dashboard</h1>
        <p style="font-size: 1.3rem; color: #64748b; margin-top: 15px;">
            📊 {{ date('F Y', mktime(0,0,0,$month,1,$year)) }} • {{ $totalEvaluations }} evaluations
        </p>
    </div>

    <!-- Stats (unchanged) -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">⭐</div>
            <div class="stat-number">{{ round($overall, 1) }}</div>
            <div class="stat-label">Overall Average /5</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">👥</div>
            <div class="stat-number">{{ $totalEvaluations }}</div>
            <div class="stat-label">Total Evaluations</div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">💬</div>
            <div class="stat-number">{{ count($feedbacks) }}</div>
            <div class="stat-label">Student Comments</div>
        </div>
    </div>

    @if($totalEvaluations == 0)
        <!-- Empty state (unchanged) -->
        <div class="panel-card" style="text-align: center; color: #a0aec0;">
            <div style="font-size: 5rem; margin-bottom: 20px;">📊</div>
            <h3>No evaluations yet</h3>
            <p style="font-size: 1.1rem;">Encourage students to submit feedback!</p>
        </div>
    @else
        <!-- 🔥 DOUGHNUT CHARTS -->
        @if(!empty($criteriaScores))
        <div class="panel-card">
            <h3 style="margin-bottom: 25px; color: #2d3748; font-size: 1.6rem; text-align: center;">📈 Criteria Performance</h3>
            <div class="charts-grid">
                @foreach($criteriaScores as $index => $criteria)
                    <div class="chart-container">
                        <h5 style="margin-bottom: 15px; color: #4a5568;">{{ $criteria['name'] }}</h5>
                        <div class="chart-wrapper">
                            <canvas id="chart{{ $index }}"></canvas>
                            <div class="chart-score">{{ number_format($criteria['avg'], 1) }}</div>
                        </div>
                        <div style="margin-top: 10px; color: #718096;">
                            {{ $criteria['count'] }} evaluations
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 🔥 WORD CLOUD + STRENGTHS/WEAKNESSES -->
        <div class="panel-card">
            <h3 style="margin-bottom: 25px; color: #2d3748; font-size: 1.6rem;">🔤 Student Feedback Analysis</h3>
            
            <!-- Word Cloud -->
            @if(!empty($topWords))
                <div style="margin-bottom: 30px;">
                    <h5 style="color: #667eea; margin-bottom: 20px;">Word Cloud</h5>
                    <div class="word-cloud">
                        @foreach($topWords as $word => $count)
                            @php
                                $isStrength = in_array($word, $strengths ?? []);
                                $isWeakness = in_array($word, $weaknesses ?? []);
                            @endphp
                            <button class="word-tag {{ $isStrength ? 'strength' : ($isWeakness ? 'weakness' : 'neutral') }}" 
                                    style="font-size: {{ 14 + ($count * 3) }}px;">
                                {{ ucfirst($word) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Strengths & Weaknesses -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <h5 style="color: #48bb78; margin-bottom: 15px;">✅ Strengths ({{ count($strengths ?? []) }})</h5>
                    @if(!empty($strengths))
                        <ul style="list-style: none; padding: 0;">
                            @foreach(array_slice($strengths ?? [], 0, 6) as $strength)
                                <li style="background: #c6f6d5; padding: 10px 15px; margin-bottom: 8px; border-radius: 10px; font-weight: 600; color: #22543d;">
                                    ✨ {{ ucfirst($strength) }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #a0aec0;">No strengths identified yet</p>
                    @endif
                </div>
                
                <div>
                    <h5 style="color: #f56565; margin-bottom: 15px;">⚠️ Areas for Improvement ({{ count($weaknesses ?? []) }})</h5>
                    @if(!empty($weaknesses))
                        <ul style="list-style: none; padding: 0;">
                            @foreach(array_slice($weaknesses ?? [], 0, 6) as $weakness)
                                <li style="background: #fed7d7; padding: 10px 15px; margin-bottom: 8px; border-radius: 10px; font-weight: 600; color: #742a2a;">
                                    🔧 {{ ucfirst($weakness) }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #a0aec0;">No areas identified</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback (unchanged) -->
        <div class="panel-card">
            <h3 style="margin-bottom: 25px; color: #2d3748; font-size: 1.5rem;">💬 Recent Feedback</h3>
            @foreach($feedbacks->take(5) as $i => $feedback)
                <div class="feedback-item">
                    <div style="font-size: 0.9rem; color: #718096; margin-bottom: 10px;">Anonymous #{{ $i + 1 }}</div>
                    <div style="font-size: 1.1rem; line-height: 1.6;">{{ $feedback }}</div>
                </div>
            @endforeach
        </div>

        <!-- Month Selector (unchanged) -->
        <div class="panel-card" style="text-align: center;">
            <form method="GET" style="display: inline-flex; gap: 15px; align-items: center;">
                <label style="color: #4a5568; font-weight: 600;">View other periods:</label>
                <select name="month" onchange="this.form.submit()" style="padding: 12px 20px; border-radius: 12px; border: 2px solid #e2e8f0; font-size: 1rem;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
                <select name="year" onchange="this.form.submit()" style="padding: 12px 20px; border-radius: 12px; border: 2px solid #e2e8f0; font-size: 1rem;">
                    @for($y = 2024; $y <= 2026; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    @endif
</div>

<!-- 🔥 DOUGHNUT CHARTS SCRIPT -->
<script>
@foreach($criteriaScores ?? [] as $index => $criteria)
    donutChart('chart{{ $index }}', {{ $criteria['avg'] ?? 0 }});
@endforeach

function donutChart(canvasId, score) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [score, 5 - score],
                backgroundColor: ['#667eea', '#e2e8f0'],
                borderWidth: 0,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            },
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });
}
</script>
@endsection
