<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cv' => 'required|mimes:pdf,doc,docx|max:5120',
            'job_id' => 'required|exists:job_postings,id',
        ]);

        // Handle CV upload
        $cvPath = $request->file('cv')->store('cvs', 'public');
        
        // Extract CV text (basic extraction)
        $cvText = $this->extractTextFromCV($request->file('cv'));

        // Create candidate
        $candidate = Candidate::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cv_path' => $cvPath,
            'cv_text' => $cvText,
        ]);

        // Get job requirements
        $job = Job::find($request->job_id);
        $jobSkills = $job->skills_required ?? '';

        // Analyze CV using Python script
        $analysis = $this->analyzeCV($cvPath, $jobSkills, $job->title, $cvText);

        // Create application
        $application = Application::create([
            'candidate_id' => $candidate->id,
            'job_posting_id' => $request->job_id,
            'match_percentage' => $analysis['match_percentage'] ?? $analysis['match'] ?? 0,
            'matched_skills' => $analysis['matched_skills'] ?? '',
            'missing_skills' => $analysis['missing_skills'] ?? '',
            'analysis_details' => json_encode($analysis),
            'status' => 'pending',
        ]);

        return redirect()->route('result', $application->id);
    }

    public function result($id)
    {
        $application = Application::with(['candidate', 'jobPosting'])->findOrFail($id);
        return view('result', compact('application'));
    }

    private function extractTextFromCV($file)
    {
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'pdf') {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($file->getRealPath());
                return trim($pdf->getText());
            } catch (\Throwable $e) {
                Log::warning('PDF text extraction failed: ' . $e->getMessage());
                return '';
            }
        } else {
            // For DOCX, you might use PhpOffice\PhpWord
            return 'DOCX content extracted...';
        }
    }

    private function analyzeCV($cvPath, $jobSkills, $jobTitle, $cvText = '')
    {
        // Get the full path to the uploaded CV file
        $fullCvPath = storage_path('app/public/' . $cvPath);
        $pythonScript = base_path('python/analyze_cv.py');
        
        // Check if CV file exists
        if (!file_exists($fullCvPath)) {
            return $this->simpleCVAnalysis($cvText, $jobSkills);
        }
        
        // Check if Python script exists
        if (!file_exists($pythonScript)) {
            return $this->simpleCVAnalysis($cvText, $jobSkills);
        }
        
        // Try to call Python script with custom skills.
        // On Windows, "py" is often available even when "python" isn't.
        // Format: python analyze_cv.py <cv_path> <job_title> <skills_string>
        $python = trim((string) shell_exec("python --version 2>&1")) !== '' ? 'python' : 'py';
        $command = $python . " " . escapeshellarg($pythonScript) . " " 
                    . escapeshellarg($fullCvPath) . " " 
                    . escapeshellarg($jobTitle) . " " 
                    . escapeshellarg($jobSkills) . " 2>&1";
        
        // Execute Python script
        $output = shell_exec($command);
        
        // Log for debugging
        Log::info('Python Output: ' . $output);
        
        // Parse JSON output from Python
        $result = json_decode($output, true);
        
        // If Python script failed, use simple matching
        if (!$result || isset($result['error'])) {
            Log::warning('Python script failed, using simple analysis');
            return $this->simpleCVAnalysis($cvText, $jobSkills);
        }
        
        // Map Python output to Laravel format
        return [
            'match_percentage' => $result['match'] ?? 0,
            'matched_skills' => is_array($result['matched_skills']) 
                ? implode(', ', $result['matched_skills']) 
                : ($result['matched_skills'] ?? ''),
            'missing_skills' => is_array($result['missing_skills']) 
                ? implode(', ', $result['missing_skills']) 
                : ($result['missing_skills'] ?? ''),
        ];
    }

    private function simpleCVAnalysis($cvText, $jobSkills)
    {
        $cvText = strtolower($cvText);
        $jobSkillsArray = array_map('trim', explode(',', strtolower($jobSkills)));
        
        $matched = [];
        $missing = [];
        
        foreach ($jobSkillsArray as $skill) {
            if (str_contains($cvText, $skill)) {
                $matched[] = $skill;
            } else {
                $missing[] = $skill;
            }
        }
        
        $totalSkills = count($jobSkillsArray);
        $matchCount = count($matched);
        $matchPercentage = $totalSkills > 0 ? ($matchCount / $totalSkills) * 100 : 0;
        
        return [
            'match_percentage' => round($matchPercentage, 2),
            'matched_skills' => implode(', ', $matched),
            'missing_skills' => implode(', ', $missing),
        ];
    }
}
