@extends('layouts.app')

@section('content')

<style>
    body {
        background: #eef1f7;
        font-family: "Poppins", sans-serif;
    }

    .report-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .header-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #4b9cff, #667eea);
        color: white;
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        flex: 1;
        min-width: 150px;
    }

    .stat-number {
        font-size: 2.5em;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.95em;
        opacity: 0.95;
    }

    h2, h3 {
        color: #1e1e2f;
        font-weight: 700;
        text-align: center;
    }

    h2 {
        margin-bottom: 10px;
    }

    .teacher-info {
        background: linear-gradient(135deg, #4b9cff15, #667eea15);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid #4b9cff20;
    }

    .charts-row {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 2rem;
    }

    .chart-box {
        width: 48%;
        background: #fafafa;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        margin-bottom: 20px;
        text-align: center;
        position: relative;
    }

    .chart-box canvas {
        width: 220px !important;
        height: 220px !important;
        margin: auto;
    }

    .chart-score {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.2em;
        font-weight: 700;
        color: #4b9cff;
        pointer-events: none;
        z-index: 10;
    }

    .chart-label {
        margin-top: 15px;
        font-size: 0.9em;
        color: #666;
        font-weight: 500;
    }

    .word-cloud-box,
    .strengths-box,
    .weaknesses-box {
        background: #fbfbfb;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        margin-top: 30px;
    }

    .feedback-card {
        background: #f8f9fc;
        border-left: 4px solid #4b9cff;
        padding: 15px;
        margin-bottom: 12px;
        border-radius: 10px;
    }

    .footer-note {
        text-align: center;
        color: #777;
        margin-top: 2rem;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    @media (max-width: 768px) {
        .charts-row {
            flex-direction: column;
        }
        .chart-box {
            width: 100%;
        }
        .header-stats {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="report-container">
    <!-- Header Stats -->

   <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.07); text-align: center;">
        <form method="GET" style="display: inline-flex; gap: 10px; align-items: center;">
            {{-- do NOT include hidden teacher_id if you are using /admin/report/{teacher} --}}
            <select name="month" onchange="this.form.submit()">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ ($month ?? now()->month) == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>

            <select name="year" onchange="this.form.submit()">
                @for($y = 2025; $y <= 2026; $y++)
                    <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>

    </div>

    <div class="header-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $totalEvaluations ?? 0 }}</div>
            <div class="stat-label">Total Evaluations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ round($overall, 1) }}/5</div>
            <div class="stat-label">Overall Score</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ count($feedbacks) }}</div>
            <div class="stat-label">Student Comments</div>
        </div>
    </div>

    <!-- Teacher Info -->
    <div class="teacher-info">
        <h2>📊 Teacher Evaluation Report{{ isset($reportPeriod) ? ' - ' . $reportPeriod : '' }}</h2>
        <h3>{{ $teacher->name ?? 'Unknown' }} ({{ $teacher->course ?? 'N/A' }})</h3>
    </div>


    <!-- Dynamic Donut Charts -->
    <div class="charts-row">
        @foreach($criteriaScores as $index => $c)
            <div class="chart-box">
                <h4>{{ $c['name'] }}</h4>
                <div style="position: relative; height: 220px;">
                    <canvas id="chart_{{ $index }}"></canvas>
                    <div class="chart-score">{{ number_format($c['avg'], 1) }}</div>
                </div>
                <div class="chart-label">{{ $c['count'] ?? 0 }} evaluations</div>
            </div>
        @endforeach

        <div class="chart-box">
            <h4>Overall Score</h4>
            <div style="position: relative; height: 220px;">
                <canvas id="chartOverall"></canvas>
                <div class="chart-score">{{ round($overall, 1) }}</div>
            </div>
            <div class="chart-label">{{ $totalEvaluations ?? 0 }} total evaluations</div>
        </div>
    </div>

    <!-- Word Cloud -->
    <div class="word-cloud-box">
        <h3>🔤 Word Cloud Summary</h3>
        @if(count($topWords))
            <div class="word-cloud" style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                @foreach($topWords as $word => $count)
                    @php
                        $color = in_array($word, $strengthKeywords ?? []) ? '#2e7d32'
                            : (in_array($word, $weaknessKeywords ?? []) ? '#c62828'
                            : '#1e1e2f');
                    @endphp
                    <span style="font-size: {{ 12 + ($count * 4) }}px;
                                background: #4b9cff20;
                                padding: 6px 12px;
                                border-radius: 20px;
                                color: {{ $color }};
                                font-weight: 600;">
                        {{ $word }}
                    </span>
                @endforeach

            </div>
        @else
            <p>No feedback words available.</p>
        @endif
    </div>

    <!-- Strengths -->
    <div class="strengths-box">
        <h3>⭐ Strengths Mentioned by Students</h3>
        @if(count($strengths))
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($strengths as $s)
                    <li style="margin-bottom: 8px; color: #2d7d32;">{{ ucfirst($s) }}</li>
                @endforeach
            </ul>
        @else
            <p>No strong positive keywords detected.</p>
        @endif
    </div>

    <!-- Weaknesses -->
    <div class="weaknesses-box">
        <h3>⚠️ Areas for Improvement</h3>
        @if(count($weaknesses))
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($weaknesses as $w)
                    <li style="margin-bottom: 8px; color: #d32f2f;">{{ ucfirst($w) }}</li>
                @endforeach
            </ul>
        @else
            <p>No major weaknesses identified.</p>
        @endif
    </div>

    <!-- Anonymous Feedback -->
    <div class="strengths-box">
        <h3>💬 Anonymous Feedback</h3>
        @forelse($feedbacks as $i => $fb)
            <div class="feedback-card">
                <strong>Anonymous #{{ $i + 1 }}:</strong>
                <p style="margin: 8px 0 0 0;">{{ Str::limit($fb, 200) }}</p>
            </div>
        @empty
            <p>No feedback submitted.</p>
        @endforelse
    </div>

    <div class="footer-note">
        Report generated on {{ now()->format('F d, Y h:i A') }}
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    @foreach($criteriaScores as $index => $c)
        donutChart('chart_{{ $index }}', {{ $c['avg'] }});
    @endforeach
    donutChart('chartOverall', {{ round($overall,1) }});

    function donutChart(id, value) {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Score', 'Remaining'],
                datasets: [{
                    data: [value, 5 - value],
                    backgroundColor: ['#4b9cff', '#d6e4ff'],
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.toFixed(1);
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1500
                }
            }
        });
    }
</script>

@endsection
