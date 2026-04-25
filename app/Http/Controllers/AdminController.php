<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Contact;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_jobs' => Job::count(),
            'total_candidates' => Candidate::count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'average_match' => Application::whereNotNull('match_percentage')->avg('match_percentage') ?? 0,
        ];

        $recentApplications = Application::with(['candidate', 'jobPosting'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentApplications'));
    }

    public function jobs()
    {
        $jobs = Job::latest()->paginate(15);
        return view('admin.jobs', compact('jobs'));
    }

    public function createJob()
    {
        return view('admin.create-job');
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'skills_required' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        Job::create($request->all());

        return redirect()->route('admin.jobs')->with('success', 'Job added successfully');
    }

    public function editJob($id)
    {
        $job = Job::findOrFail($id);
        return view('admin.edit-job', compact('job'));
    }

    public function updateJob(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'skills_required' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs')->with('success', 'Job updated successfully');
    }

    public function deleteJob($id)
    {
        Job::findOrFail($id)->delete();
        return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully');
    }

    public function candidates()
    {
        $candidates = Candidate::withCount('applications')->latest()->paginate(15);
        return view('admin.candidates', compact('candidates'));
    }

    public function candidateDetails($id)
    {
        $candidate = Candidate::with('applications.jobPosting')->findOrFail($id);
        return view('admin.candidate-details', compact('candidate'));
    }

    public function applications()
    {
        $applications = Application::with(['candidate', 'jobPosting'])
            ->latest()
            ->paginate(15);
        return view('admin.applications', compact('applications'));
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => $request->status]);
        
        // If accepted, convert candidate to employee
        if ($request->status == 'accepted') {
            $candidate = $application->candidate;
            
            // Update candidate approval status
            $candidate->update(['approval_status' => 'approved']);
            
            // Check no employee exists with same email
            $employee = Employee::where('email', $candidate->email)->first();
            if (!$employee) {
                Employee::create([
                    'name' => $candidate->name,
                    'email' => $candidate->email,
                    'phone' => $candidate->phone,
                    'position' => $application->jobPosting->title ?? 'New Employee',
                    'department' => $application->jobPosting->department ?? null,
                    'hire_date' => now(),
                    'status' => 'active',
                ]);
                return redirect()->back()->with('success', 'Application accepted and candidate added as new employee successfully');
            }
        }
        
        return redirect()->back()->with('success', 'Application status updated successfully');
    }

    public function applicationAnalysis($id)
    {
        $application = Application::with(['candidate', 'jobPosting'])->findOrFail($id);

        $analysis = [];
        if (!empty($application->analysis_details)) {
            $decoded = json_decode($application->analysis_details, true);
            $analysis = is_array($decoded) ? $decoded : [];
        }

        return view('admin.application-analysis', compact('application', 'analysis'));
    }

    public function downloadApplicationCv($id)
    {
        $application = Application::with('candidate')->findOrFail($id);
        $candidate = $application->candidate;

        $cvPath = $candidate?->cv_path;
        if (!$cvPath) {
            return redirect()->back()->with('success', 'No CV file found for this candidate.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($cvPath)) {
            return redirect()->back()->with('success', 'CV file is missing from storage.');
        }

        $ext = pathinfo($cvPath, PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]+/', '_', (string) ($candidate->name ?? 'candidate'));
        $filename = $safeName . '_CV' . ($ext ? ('.' . $ext) : '');

        return $disk->download($cvPath, $filename);
    }

    public function deleteApplicationCv($id)
    {
        $application = Application::with('candidate')->findOrFail($id);
        $candidate = $application->candidate;

        $cvPath = $candidate?->cv_path;
        if (!$cvPath) {
            return redirect()->back()->with('success', 'No CV file found for this candidate.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if ($disk->exists($cvPath)) {
            $disk->delete($cvPath);
        }

        $candidate->update([
            'cv_path' => null,
            'cv_text' => null,
        ]);

        return redirect()->back()->with('success', 'CV deleted successfully.');
    }

    public function deleteCandidate($id)
    {
        Candidate::findOrFail($id)->delete();
        return redirect()->route('admin.candidates')->with('success', 'Candidate deleted successfully');
    }

    public function approveCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update(['approval_status' => 'approved']);
        
        // Convert candidate to employee
        $employee = Employee::where('email', $candidate->email)->first();
        if (!$employee) {
            Employee::create([
                'name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'position' => 'New Employee', // Can be modified later
                'department' => null,
                'hire_date' => now(),
                'status' => 'active',
            ]);
        }
        
        return redirect()->back()->with('success', 'Candidate approved and added as employee successfully');
    }

    public function rejectCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update(['approval_status' => 'rejected']);
        return redirect()->back()->with('success', 'Candidate rejected');
    }

    public function contacts()
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts', compact('contacts'));
    }

    public function markContactAsRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'read']);
        return redirect()->back()->with('success', 'Message status updated successfully');
    }

    public function deleteContact($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('admin.contacts')->with('success', 'Message deleted successfully');
    }

    // Employees Management
    public function employees()
    {
        $employees = Employee::latest()->paginate(15);
        return view('admin.employees', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.create-employee');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'department' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'hire_date' => 'required|date',
            'employee_id' => 'nullable|string|unique:employees',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Employee::create($request->all());

        return redirect()->route('admin.employees')->with('success', 'Employee added successfully');
    }

    public function editEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.edit-employee', compact('employee'));
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'department' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'hire_date' => 'required|date',
            'employee_id' => 'nullable|string|unique:employees,employee_id,' . $id,
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $employee->update($request->all());

        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully');
    }

    public function deleteEmployee($id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully');
    }

    // Employee Analysis
    public function analyzeEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.analyze-employee', compact('employee'));
    }

    public function runAnalysis(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        // Prepare employee data for analysis
        $analysisData = [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'position' => $employee->position,
            'department' => $employee->department ?? 'Unknown',
            'status' => $employee->status,
            'hire_date' => $employee->hire_date ? $employee->hire_date->format('Y-m-d') : now()->format('Y-m-d'),
            'years_of_service' => $employee->years_of_service,
            'age' => 30 + $employee->years_of_service, // Estimated age
            'attendance_rate' => 0.9, // Default, can be from separate DB
            'performance_score' => 75, // Default, can be from separate DB
            'salary_level' => $this->getSalaryLevel($employee->salary),
            'last_promotion_years' => 2, // Default
        ];
        
        try {
            // Prepare additional data for advanced analysis
            $advancedData = [
                'employee_id' => $employee->id,
                'performance_score' => rand(70, 95), // Will be from real DB later
                'attendance_rate' => rand(85, 98) / 100,
                'skill_level' => rand(60, 90),
                'projects_completed' => rand(3, 15),
                'training_hours' => rand(20, 80),
                'years_experience' => $analysisData['years_of_service'],
                'salary_level' => $analysisData['salary_level'] == 'Low' ? 1 : ($analysisData['salary_level'] == 'High' ? 3 : 2),
                'last_promotion_years' => 2,
            ];
            
            // Call Python script for smart analysis
            $pythonScript = base_path('python/employee_analysis.py');
            $jsonData = json_encode($advancedData, JSON_UNESCAPED_UNICODE);
            
            // Call Python
            $command = "python \"" . $pythonScript . "\" " . escapeshellarg($jsonData);
            $output = shell_exec($command);
            
            // Parse result
            if ($output) {
                $result = json_decode($output, true);
                
                if ($result && isset($result['status']) && $result['status'] == 'success') {
                    // Format result for UI
                    $formattedResult = [
                        'turnover_risk' => $result['turnover_probability'] > 70 ? 'High' : ($result['turnover_probability'] > 40 ? 'Medium' : 'Low'),
                        'promotion_chance' => $result['promotion_probability'] > 70 ? 'High' : ($result['promotion_probability'] > 40 ? 'Medium' : 'Low'),
                        'turnover_score' => $result['turnover_probability'],
                        'promotion_score' => $result['promotion_probability'],
                        'confidence' => $result['confidence'],
                        'status' => 'success',
                        'method' => 'ml',
                        'sub_analysis' => [
                            'technical_performance' => min(100, $result['skill_growth'] + 10),
                            'leadership_score' => min(100, ($result['promotion_probability'] * 0.8) + 20),
                            'attendance_commitment' => $result['commitment_index'],
                        ],
                        'turnover_explanation' => [
                            $result['turnover_probability'] > 70 ? 'High risk due to:' : ($result['turnover_probability'] > 40 ? 'Medium risk due to:' : 'Good stability:'),
                            $result['turnover_probability'] > 70 ? '• High indicators for potential resignation' : ($result['turnover_probability'] > 40 ? '• Some factors may affect stability' : '• Employee is satisfied and productive')
                        ],
                        'promotion_explanation' => [
                            $result['promotion_probability'] > 70 ? 'Ready for promotion:' : ($result['promotion_probability'] > 40 ? 'Potential candidate:' : 'Needs more time:'),
                            $result['promotion_probability'] > 70 ? '• Outstanding performance and sufficient experience' : ($result['promotion_probability'] > 40 ? '• Needs to develop some skills' : '• Still in learning phase')
                        ],
                        'turnover_recommendation' => $result['recommendation'],
                        'promotion_recommendation' => $result['recommendation'],
                        'data_points' => 27,
                        'employee_satisfaction' => min(100, $result['confidence'] - 10),
                        'productivity_index' => $result['commitment_index'],
                        'skill_development_level' => $result['skill_growth'],
                        'analysis_date' => $result['analysis_date'] ?? now()->format('Y-m-d H:i:s'),
                    ];
                    
                    return redirect()->back()->with('analysis_result', $formattedResult);
                } else {
                    // If analysis failed, use simple prediction
                    $result = $this->simplePrediction($employee);
                    return redirect()->back()->with('analysis_result', $result);
                }
            } else {
                // If Python call failed, use simple prediction
                $result = $this->simplePrediction($employee);
                return redirect()->back()->with('analysis_result', $result);
            }
            
        } catch (\Exception $e) {
            // On error, use simple prediction
            $result = $this->simplePrediction($employee);
            return redirect()->back()->with('analysis_result', $result);
        }
    }
    
    private function getSalaryLevel($salary)
    {
        if (!$salary) {
            return 'Medium';
        }
        
        if ($salary < 10000) {
            return 'Low';
        } elseif ($salary > 20000) {
            return 'High';
        } else {
            return 'Medium';
        }
    }
    
    private function simplePrediction($employee)
    {
        $yearsOfService = $employee->years_of_service;
        
        // Calculate turnover probability
        $turnoverScore = 30;
        if ($yearsOfService < 2) {
            $turnoverScore += 25;
        }
        if ($employee->status != 'active') {
            $turnoverScore += 30;
        }
        
        // Calculate promotion probability
        $promotionScore = 40;
        if ($yearsOfService > 3) {
            $promotionScore += 30;
        }
        if (strpos(strtolower($employee->position), 'senior') !== false || 
            strpos(strtolower($employee->position), 'lead') !== false) {
            $promotionScore -= 10;
        }
        
        $turnoverRisk = $turnoverScore > 70 ? 'High' : ($turnoverScore > 40 ? 'Medium' : 'Low');
        $promotionChance = $promotionScore > 70 ? 'High' : ($promotionScore > 40 ? 'Medium' : 'Low');
        
        return [
            'turnover_risk' => $turnoverRisk,
            'promotion_chance' => $promotionChance,
            'turnover_score' => round($turnoverScore, 1),
            'promotion_score' => round($promotionScore, 1),
            'confidence' => 75,
            'status' => 'success',
            'method' => 'simple'
        ];
    }
}
