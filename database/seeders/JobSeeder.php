<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe old job-related data first (keep DB consistent)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Application::query()->delete();
        Job::query()->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();

        $jobs = [
            [
                'title' => 'Senior Laravel Developer',
                'department' => 'Engineering',
                'type' => 'full-time',
                'location' => 'Casablanca (Hybrid)',
                'description' => "We are looking for a Senior Laravel Developer to build and maintain web applications, collaborate with product teams, and deliver high-quality features.\n\nKey responsibilities:\n- Design, develop, and maintain Laravel-based services\n- Integrate REST APIs and third-party services\n- Improve performance, security, and code quality\n- Mentor junior developers and review pull requests",
                'requirements' => "- 5+ years of web development experience\n- Strong PHP and Laravel expertise\n- Solid understanding of MySQL and query optimization\n- Experience with testing, Git, and CI/CD\n- Good communication and problem-solving skills",
                'skills_required' => 'Laravel, PHP, MySQL, REST APIs, Git, Testing',
                'salary_min' => 18000,
                'salary_max' => 28000,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'HR Officer (Recruitment & Onboarding)',
                'department' => 'Human Resources',
                'type' => 'full-time',
                'location' => 'Rabat',
                'description' => "As an HR Officer, you will manage the full recruitment lifecycle and support onboarding for new hires.\n\nKey responsibilities:\n- Publish job ads and manage candidate pipelines\n- Coordinate interviews and communicate with applicants\n- Prepare onboarding documents and support new joiners\n- Maintain recruitment data and reports",
                'requirements' => "- Bachelor’s degree in HR, Business, or related field\n- 2+ years experience in recruitment\n- Strong coordination and communication skills\n- Familiarity with ATS / HR systems is a plus",
                'skills_required' => 'Recruitment, Interviewing, Communication, Reporting, HR Operations',
                'salary_min' => 7000,
                'salary_max' => 11000,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Data Analyst (HR Analytics)',
                'department' => 'Data',
                'type' => 'full-time',
                'location' => 'Remote',
                'description' => "Join our HR Analytics team to turn workforce data into insights.\n\nKey responsibilities:\n- Build dashboards and recurring HR reports\n- Analyze trends in hiring, attendance, and performance\n- Define KPIs and data quality checks\n- Collaborate with HR stakeholders to answer business questions",
                'requirements' => "- 2+ years experience in analytics\n- Strong SQL skills\n- Experience with Excel and a BI tool (Power BI/Tableau)\n- Ability to communicate insights clearly",
                'skills_required' => 'SQL, Excel, Power BI, Data Analysis, Reporting',
                'salary_min' => 9000,
                'salary_max' => 15000,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Add many more real job postings (total >= 50)
        $templates = [
            ['title' => 'Frontend Developer (React)', 'dept' => 'Engineering', 'type' => 'full-time', 'loc' => 'Casablanca', 'min' => 12000, 'max' => 20000, 'skills' => 'React, JavaScript, HTML, CSS, REST APIs'],
            ['title' => 'Backend Developer (PHP)', 'dept' => 'Engineering', 'type' => 'full-time', 'loc' => 'Rabat', 'min' => 10000, 'max' => 18000, 'skills' => 'PHP, Laravel, MySQL, REST APIs, Git'],
            ['title' => 'DevOps Engineer', 'dept' => 'IT', 'type' => 'full-time', 'loc' => 'Hybrid', 'min' => 15000, 'max' => 24000, 'skills' => 'Docker, Linux, CI/CD, Nginx, Monitoring'],
            ['title' => 'IT Support Specialist', 'dept' => 'IT', 'type' => 'full-time', 'loc' => 'Tangier', 'min' => 6000, 'max' => 9000, 'skills' => 'Troubleshooting, Windows, Networking, Ticketing'],
            ['title' => 'Payroll Specialist', 'dept' => 'Finance', 'type' => 'full-time', 'loc' => 'Rabat', 'min' => 8000, 'max' => 13000, 'skills' => 'Payroll, Excel, Compliance, Attention to Detail'],
            ['title' => 'Accountant', 'dept' => 'Accounting', 'type' => 'full-time', 'loc' => 'Casablanca', 'min' => 7000, 'max' => 12000, 'skills' => 'Accounting, Excel, Reconciliation, Reporting'],
            ['title' => 'Sales Representative', 'dept' => 'Sales', 'type' => 'full-time', 'loc' => 'Marrakesh', 'min' => 6000, 'max' => 14000, 'skills' => 'Sales, Negotiation, CRM, Communication'],
            ['title' => 'Marketing Specialist', 'dept' => 'Marketing', 'type' => 'full-time', 'loc' => 'Remote', 'min' => 7000, 'max' => 14000, 'skills' => 'Digital Marketing, Content, Analytics, SEO'],
            ['title' => 'Customer Support Agent', 'dept' => 'Customer Support', 'type' => 'full-time', 'loc' => 'Casablanca', 'min' => 5000, 'max' => 8000, 'skills' => 'Customer Service, Communication, Problem Solving'],
            ['title' => 'Operations Coordinator', 'dept' => 'Operations', 'type' => 'full-time', 'loc' => 'Rabat', 'min' => 6500, 'max' => 10000, 'skills' => 'Coordination, Reporting, Process Improvement'],
            ['title' => 'Procurement Officer', 'dept' => 'Procurement', 'type' => 'full-time', 'loc' => 'Tangier', 'min' => 7500, 'max' => 12000, 'skills' => 'Procurement, Negotiation, Vendor Management'],
            ['title' => 'Legal Assistant', 'dept' => 'Legal', 'type' => 'full-time', 'loc' => 'Casablanca', 'min' => 7000, 'max' => 11000, 'skills' => 'Legal Research, Documentation, Communication'],
            ['title' => 'Office Administrator', 'dept' => 'Administration', 'type' => 'full-time', 'loc' => 'Rabat', 'min' => 5500, 'max' => 8500, 'skills' => 'Administration, Scheduling, Communication'],
            ['title' => 'Product Manager', 'dept' => 'Product', 'type' => 'full-time', 'loc' => 'Hybrid', 'min' => 16000, 'max' => 26000, 'skills' => 'Product Management, Roadmaps, Stakeholder Management'],
            ['title' => 'Machine Learning Engineer', 'dept' => 'Data', 'type' => 'full-time', 'loc' => 'Remote', 'min' => 18000, 'max' => 30000, 'skills' => 'Python, Machine Learning, NLP, APIs'],
            ['title' => 'Cybersecurity Analyst', 'dept' => 'Security', 'type' => 'full-time', 'loc' => 'Casablanca', 'min' => 15000, 'max' => 24000, 'skills' => 'Security, SIEM, Incident Response, Networking'],
        ];

        $responsibilities = [
            "Key responsibilities:\n- Deliver tasks on time with high quality\n- Collaborate with cross-functional teams\n- Document work and follow best practices\n- Proactively identify and solve problems",
            "Key responsibilities:\n- Manage day-to-day activities related to the role\n- Maintain accurate records and reports\n- Ensure compliance with internal policies\n- Support continuous improvement initiatives",
        ];

        $baseReqs = [
            "- Bachelor’s degree or equivalent experience\n- Strong communication skills\n- Ability to work in a team\n- Attention to detail and ownership mindset",
            "- Relevant experience in a similar role\n- Good organizational skills\n- Comfortable with deadlines and priorities\n- Willingness to learn and improve",
        ];

        // Expand templates to reach 50 unique postings by varying titles (Senior/Junior) and locations.
        $levels = ['Junior', 'Mid-level', 'Senior'];
        $extraLocations = ['Rabat', 'Casablanca', 'Marrakesh', 'Tangier', 'Remote', 'Hybrid'];

        foreach ($templates as $t) {
            foreach ($levels as $lvl) {
                $jobs[] = [
                    'title' => "{$lvl} {$t['title']}",
                    'department' => $t['dept'],
                    'type' => $t['type'],
                    'location' => $t['loc'] === 'Hybrid' ? 'Casablanca (Hybrid)' : $t['loc'],
                    'description' => "{$t['title']} role focused on delivering measurable impact.\n\n" .
                        $this->randomParagraph() . "\n\n" .
                        $responsibilities[array_rand($responsibilities)],
                    'requirements' => $baseReqs[array_rand($baseReqs)] . "\n- Experience with: {$t['skills']}",
                    'skills_required' => $t['skills'],
                    'salary_min' => $t['min'],
                    'salary_max' => $t['max'],
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Make sure we have at least 50 distinct rows (trim if too many)
        $jobs = array_slice($jobs, 0, 50);

        DB::table('job_postings')->insert($jobs);
    }

    private function randomParagraph(): string
    {
        $paras = [
            "You will work with a collaborative team to build reliable processes and deliver outcomes that improve the employee and candidate experience.",
            "This position requires a proactive mindset, strong ownership, and the ability to communicate clearly with stakeholders.",
            "You will contribute to continuous improvement by identifying gaps, proposing solutions, and helping implement better workflows.",
            "You will help maintain high standards of quality, security, and performance while working in a fast-paced environment.",
        ];
        return $paras[array_rand($paras)];
    }
}

