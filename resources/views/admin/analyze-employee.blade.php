@extends('admin.layout')

@section('title', 'Employee Performance Analysis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-graph-up-arrow"></i> Smart Employee Analysis</h2>
        <p class="text-muted">Start analyzing employee performance using Artificial Intelligence</p>
    </div>
    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> Back to Employees
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <!-- Employee Basic Data -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Employee Name:</strong> {{ $employee->name }}</p>
                        <p><strong>Email:</strong> {{ $employee->email }}</p>
                        <p><strong>Phone Number:</strong> {{ $employee->phone ?? '-' }}</p>
                        <p><strong>Employee ID:</strong> {{ $employee->employee_id ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Position:</strong> {{ $employee->position }}</p>
                        <p><strong>Department:</strong> {{ $employee->department ?? '-' }}</p>
                        <p><strong>Salary:</strong> {{ $employee->salary ? '$' . number_format($employee->salary, 2) : '-' }}</p>
                        <p><strong>Hire Date:</strong> {{ $employee->hire_date->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Employment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Current Status:</strong> 
                            <span class="badge bg-{{ $employee->status == 'active' ? 'success' : ($employee->status == 'on_leave' ? 'warning' : 'danger') }}">
                                @if($employee->status == 'active') Active
                                @elseif($employee->status == 'on_leave') On Leave
                                @else Inactive
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Days in Service:</strong> 
                            {{ number_format($employee->days_of_service) }} days
                        </p>
                    </div>
                </div>
                @if($employee->notes)
                <div class="mt-3">
                    <p><strong>Notes:</strong></p>
                    <p class="text-muted">{{ $employee->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Analysis Actions -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cpu"></i> Smart Analysis</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Start performance analysis using Artificial Intelligence</p>
                
                <form action="{{ route('admin.employees.runAnalysis', $employee->id) }}" method="POST" id="analysisForm">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100" id="analyzeBtn">
                        <i class="bi bi-robot"></i> Start Smart Analysis
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Analysis Results</h5>
            </div>
            <div class="card-body" id="analysisResults">
                @if(session('analysis_result'))
                    @php
                        $result = session('analysis_result');
                        $turnoverRisk = $result['turnover_risk'] ?? 'Unknown';
                        $promotionChance = $result['promotion_chance'] ?? 'Unknown';
                        $turnoverScore = $result['turnover_score'] ?? 0;
                        $promotionScore = $result['promotion_score'] ?? 0;
                        $confidence = $result['confidence'] ?? 0;
                        $method = $result['method'] ?? 'ai';
                    @endphp
                @else
                    {{-- Mock Data for automatic display --}}
                    @php
                        $result = [
                            'turnover_risk' => 'Medium',
                            'promotion_chance' => 'Medium',
                            'turnover_score' => 55,
                            'promotion_score' => 45,
                            'confidence' => 75,
                            'method' => 'mock',
                            'sub_analysis' => [
                                'technical_performance' => 82,
                                'leadership_score' => 68,
                                'attendance_commitment' => 90,
                            ],
                            'turnover_explanation' => ['Medium risk due to:', '• Some factors may affect stability'],
                            'promotion_explanation' => ['Potential candidate:', '• Needs to develop some skills'],
                            'turnover_recommendation' => '🟡 Recommend regular monitoring and additional incentives',
                            'promotion_recommendation' => '📅 Can consider promotion within 6-12 months',
                            'data_points' => 24,
                            'employee_satisfaction' => 72,
                            'productivity_index' => 78,
                            'skill_development_level' => 70,
                        ];
                        $turnoverRisk = $result['turnover_risk'];
                        $promotionChance = $result['promotion_chance'];
                        $turnoverScore = $result['turnover_score'];
                        $promotionScore = $result['promotion_score'];
                        $confidence = $result['confidence'];
                        $method = $result['method'];
                    @endphp
                @endif
                
                @php
                    if(!session('analysis_result')) {
                        session(['analysis_result' => $result]);
                    }
                @endphp
                
                {{-- Turnover probability result --}}
                <div class="mb-4">
                        <h6><i class="bi bi-exclamation-triangle"></i> Turnover Probability</h6>
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar 
                                @if($turnoverRisk == 'High') bg-danger
                                @elseif($turnoverRisk == 'Medium') bg-warning
                                @else bg-success
                                @endif
                                progress-bar-striped progress-bar-animated" 
                                role="progressbar" 
                                style="width: {{ $turnoverScore }}%"
                                aria-valuenow="{{ $turnoverScore }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $turnoverScore }}%
                            </div>
                        </div>
                        <span class="badge 
                            @if($turnoverRisk == 'High') bg-danger
                            @elseif($turnoverRisk == 'Medium') bg-warning
                            @else bg-success
                            @endif
                            fs-6 mb-2">
                            @if($turnoverRisk == 'High') 🔴 High Risk
                            @elseif($turnoverRisk == 'Medium') 🟡 Medium Risk
                            @else 🟢 Low Risk
                            @endif
                        </span>
                        
                        @if(isset($result['turnover_explanation']))
                        <div class="alert 
                            @if($turnoverRisk == 'High') alert-danger
                            @elseif($turnoverRisk == 'Medium') alert-warning
                            @else alert-success
                            @endif
                            mt-2 small">
                            @foreach($result['turnover_explanation'] as $exp)
                                <div>{{ $exp }}</div>
                            @endforeach
                        </div>
                        @endif
                        
                        @if(isset($result['turnover_recommendation']))
                        <div class="alert alert-info mt-2 mb-0">
                            <strong>📌 Recommendation:</strong><br>
                            {{ $result['turnover_recommendation'] }}
                        </div>
                        @endif
                    </div>
                    
                    {{-- Promotion probability result --}}
                    <div class="mb-4">
                        <h6><i class="bi bi-trophy"></i> Promotion Probability</h6>
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar 
                                @if($promotionChance == 'High') bg-success
                                @elseif($promotionChance == 'Medium') bg-info
                                @else bg-secondary
                                @endif
                                progress-bar-striped progress-bar-animated" 
                                role="progressbar" 
                                style="width: {{ $promotionScore }}%"
                                aria-valuenow="{{ $promotionScore }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $promotionScore }}%
                            </div>
                        </div>
                        <span class="badge 
                            @if($promotionChance == 'High') bg-success
                            @elseif($promotionChance == 'Medium') bg-info
                            @else bg-secondary
                            @endif
                            fs-6 mb-2">
                            @if($promotionChance == 'High') 🟢 Ready for Promotion
                            @elseif($promotionChance == 'Medium') 🟡 Potential Candidate
                            @else 🔴 Needs More Time
                            @endif
                        </span>
                        
                        @if(isset($result['promotion_explanation']))
                        <div class="alert 
                            @if($promotionChance == 'High') alert-success
                            @elseif($promotionChance == 'Medium') alert-info
                            @else alert-secondary
                            @endif
                            mt-2 small">
                            @foreach($result['promotion_explanation'] as $exp)
                                <div>{{ $exp }}</div>
                            @endforeach
                        </div>
                        @endif
                        
                        @if(isset($result['promotion_recommendation']))
                        <div class="alert alert-success mt-2 mb-0">
                            <strong>📌 Recommendation:</strong><br>
                            {{ $result['promotion_recommendation'] }}
                        </div>
                        @endif
                    </div>
                    
                    {{-- Confidence percentage --}}
                    <div class="alert alert-info">
                        <strong><i class="bi bi-shield-check"></i> Analysis Confidence:</strong> {{ $confidence }}%
                        @if(isset($result['data_points']))
                            <br><small class="text-muted">Analysis based on {{ $result['data_points'] }} data points</small>
                        @endif
                        @if($method == 'simple')
                            <br><small class="text-muted">Simple algorithm used (Python not available)</small>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- Sub-analyses --}}
@if(isset($result['sub_analysis']))
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart-line"></i> Detailed Analysis</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Technical Performance --}}
                    <div class="col-md-4 mb-3">
                        <h6 class="text-primary"><i class="bi bi-code-slash"></i> Technical Performance</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $result['sub_analysis']['technical_performance'] }}%">
                                {{ $result['sub_analysis']['technical_performance'] }}%
                            </div>
                        </div>
                    </div>
                    
                    {{-- Leadership --}}
                    <div class="col-md-4 mb-3">
                        <h6 class="text-success"><i class="bi bi-people-fill"></i> Leadership & Cooperation</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $result['sub_analysis']['leadership_score'] }}%">
                                {{ $result['sub_analysis']['leadership_score'] }}%
                            </div>
                        </div>
                    </div>
                    
                    {{-- Commitment --}}
                    <div class="col-md-4 mb-3">
                        <h6 class="text-info"><i class="bi bi-calendar-check"></i> Attendance Commitment</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ $result['sub_analysis']['attendance_commitment'] }}%">
                                {{ $result['sub_analysis']['attendance_commitment'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Additional Analyses --}}
@if(isset($result['employee_satisfaction']) || isset($result['productivity_index']) || isset($result['skill_development_level']))
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Additional Analyses</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @if(isset($result['employee_satisfaction']))
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h2 class="text-{{ $result['employee_satisfaction'] > 70 ? 'success' : ($result['employee_satisfaction'] > 50 ? 'warning' : 'danger') }}">
                                    {{ $result['employee_satisfaction'] }}%
                                </h2>
                                <p class="mb-0">💬 Employee Satisfaction</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($result['productivity_index']))
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h2 class="text-{{ $result['productivity_index'] > 80 ? 'success' : ($result['productivity_index'] > 60 ? 'info' : 'warning') }}">
                                    {{ $result['productivity_index'] }}%
                                </h2>
                                <p class="mb-0">⏱ Productivity Index</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($result['skill_development_level']))
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h2 class="text-primary">
                                    {{ $result['skill_development_level'] }}%
                                </h2>
                                <p class="mb-0">🧠 Skill Development</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($result['attendance_commitment']))
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h2 class="text-success">
                                    {{ $result['sub_analysis']['attendance_commitment'] }}%
                                </h2>
                                <p class="mb-0">📈 Commitment Index</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!-- Employee Statistics Section -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Quick Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded">
                    <h3 class="text-primary">{{ $employee->years_of_service }}</h3>
                    <p class="mb-0">Years of Service</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded">
                    <h3 class="text-success">{{ $employee->status == 'active' ? 'Active' : '-' }}</h3>
                    <p class="mb-0">Current Status</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded">
                    <h3 class="text-info">{{ $employee->department ?? 'Not Specified' }}</h3>
                    <p class="mb-0">Department</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded">
                    <h3 class="text-warning">{{ $employee->position }}</h3>
                    <p class="mb-0">Position</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="mt-4">
    @if(session('analysis_result'))
    <form action="{{ route('admin.employees.runAnalysis', $employee->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-warning">
            <i class="bi bi-arrow-clockwise"></i> Re-run Analysis
        </button>
    </form>
    @endif
    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> Back to Employees List
    </a>
    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Edit Employee Data
    </a>
</div>

{{-- Charts using Chart.js --}}
@if(isset($result))
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Key Indicators</h5>
            </div>
            <div class="card-body">
                <canvas id="barChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Performance Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="pieChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Final Recommendation Card --}}
<div class="card mt-4 border-3 
    @if($result['turnover_risk'] == 'Low' && $result['promotion_chance'] == 'High') 
        border-success 
    @elseif($result['turnover_risk'] == 'Medium' || $result['promotion_chance'] == 'Medium') 
        border-warning 
    @else 
        border-danger 
    @endif
">
    <div class="card-header 
        @if($result['turnover_risk'] == 'Low' && $result['promotion_chance'] == 'High') 
            bg-success text-white
        @elseif($result['turnover_risk'] == 'Medium' || $result['promotion_chance'] == 'Medium') 
            bg-warning text-dark
        @else 
            bg-danger text-white
        @endif
    ">
        <h5 class="mb-0"><i class="bi bi-lightbulb-fill"></i> Final Recommendation</h5>
    </div>
    <div class="card-body">
        <p class="lead">
            @if($result['turnover_risk'] == 'Low' && $result['promotion_chance'] == 'High')
                ✅ Recommend promotion within the next 3-6 months. Employee shows outstanding performance and high stability.
            @elseif($result['promotion_chance'] == 'High')
                🟢 Employee is ready for promotion. Recommend evaluating available opportunities and preparing for increased responsibilities.
            @elseif($result['turnover_risk'] == 'High')
                🔴 There are indicators of potential employee resignation. Recommend immediate interview to understand needs and retain the employee.
            @elseif($result['promotion_chance'] == 'Medium')
                🟡 Deserves promotion opportunity within 6-12 months with focus on professional development and training.
            @else
                🔵 Recommend keeping employee in current position and focusing on skill development.
            @endif
        </p>
    </div>
</div>
@endif

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Add loading spinner when clicking analysis
document.getElementById('analysisForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('analyzeBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Analyzing...';
});

@if(session('analysis_result'))
// Chart data
const barChartData = {
    labels: ['Turnover Probability', 'Promotion Probability', 'Commitment Index', 'Skill Development', 'Employee Satisfaction'],
    datasets: [{
        label: 'Percentage',
        data: [
            {{ $result['turnover_score'] }},
            {{ $result['promotion_score'] }},
            {{ $result['sub_analysis']['attendance_commitment'] ?? 80 }},
            {{ $result['sub_analysis']['technical_performance'] ?? 70 }},
            {{ $result['employee_satisfaction'] ?? 75 }}
        ],
        backgroundColor: [
            'rgba(220, 53, 69, 0.8)',
            'rgba(25, 135, 84, 0.8)',
            'rgba(13, 110, 253, 0.8)',
            'rgba(255, 193, 7, 0.8)',
            'rgba(108, 117, 125, 0.8)'
        ],
        borderColor: [
            'rgb(220, 53, 69)',
            'rgb(25, 135, 84)',
            'rgb(13, 110, 253)',
            'rgb(255, 193, 7)',
            'rgb(108, 117, 125)'
        ],
        borderWidth: 2
    }]
};

const pieChartData = {
    labels: ['Technical Performance', 'Leadership', 'Commitment', 'Satisfaction'],
    datasets: [{
        data: [
            {{ $result['sub_analysis']['technical_performance'] ?? 85 }},
            {{ $result['sub_analysis']['leadership_score'] ?? 72 }},
            {{ $result['sub_analysis']['attendance_commitment'] ?? 92 }},
            {{ $result['employee_satisfaction'] ?? 73 }}
        ],
        backgroundColor: [
            'rgba(13, 110, 253, 0.8)',
            'rgba(25, 135, 84, 0.8)',
            'rgba(255, 193, 7, 0.8)',
            'rgba(108, 117, 125, 0.8)'
        ],
        borderColor: [
            'rgb(13, 110, 253)',
            'rgb(25, 135, 84)',
            'rgb(255, 193, 7)',
            'rgb(108, 117, 125)'
        ],
        borderWidth: 2
    }]
};

// Bar chart
const barCtx = document.getElementById('barChart');
if (barCtx) {
    new Chart(barCtx, {
        type: 'bar',
        data: barChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Performance Overview'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Pie chart
const pieCtx = document.getElementById('pieChart');
if (pieCtx) {
    new Chart(pieCtx, {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
@endif
</script>

@endsection
