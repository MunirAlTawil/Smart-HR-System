<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $departments = [
            'Human Resources', 'Engineering', 'IT', 'Finance', 'Accounting',
            'Sales', 'Marketing', 'Customer Support', 'Operations', 'Procurement',
            'Legal', 'Administration', 'Product', 'Data', 'Security',
        ];

        $types = ['full-time', 'part-time', 'contract'];
        $locations = [
            'Rabat', 'Casablanca', 'Marrakesh', 'Tangier',
            'Remote', 'Hybrid',
        ];

        $title = $this->faker->unique()->jobTitle();
        $department = $this->faker->randomElement($departments);
        $type = $this->faker->randomElement($types);
        $location = $this->faker->randomElement($locations);

        $salaryMin = $this->faker->numberBetween(4000, 18000);
        $salaryMax = $salaryMin + $this->faker->numberBetween(1500, 12000);

        $skills = $this->faker->randomElements([
            'Communication', 'Leadership', 'Problem Solving', 'Time Management',
            'Laravel', 'PHP', 'MySQL', 'Python', 'REST APIs',
            'Excel', 'Payroll', 'Recruitment', 'Training', 'Performance Reviews',
            'Data Analysis', 'Reporting', 'Security', 'Docker', 'Git',
        ], $this->faker->numberBetween(4, 8));

        $requirements = [
            'Bachelor’s degree or equivalent experience',
            $this->faker->randomElement(['1+ years experience', '2+ years experience', '3+ years experience', '5+ years experience']),
            'Strong written and verbal communication',
            'Ability to work independently and in a team',
        ];

        $description = implode("\n\n", [
            $this->faker->sentence(14),
            $this->faker->paragraph(3),
            'Key responsibilities:',
            '- ' . implode("\n- ", $this->faker->sentences(4)),
        ]);

        $requirementsText = implode("\n", array_map(fn ($r) => "- {$r}", $requirements));

        return [
            'title' => $title,
            'department' => $department,
            'type' => $type,
            'description' => $description,
            'requirements' => $requirementsText,
            'skills_required' => implode(', ', $skills),
            'location' => $location,
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']),
        ];
    }
}

