<?php

/**
 * Minimal PDF generator (no dependencies).
 * Creates a single-page PDF named test.pdf in project root.
 */

function pdf_escape(string $s): string
{
    $s = str_replace("\r", "", $s);
    $s = str_replace("\\", "\\\\", $s);
    $s = str_replace("(", "\\(", $s);
    $s = str_replace(")", "\\)", $s);
    return $s;
}

function build_pdf(array $lines): string
{
    $y = 760;
    $leading = 14;
    $content = "BT\n/F1 11 Tf\n72 {$y} Td\n";
    $first = true;
    foreach ($lines as $line) {
        $line = (string)$line;
        if ($first) {
            $content .= "(" . pdf_escape($line) . ") Tj\n";
            $first = false;
        } else {
            $content .= "0 -{$leading} Td\n(" . pdf_escape($line) . ") Tj\n";
        }
    }
    $content .= "ET\n";

    $objs = [];
    $objs[1] = "<< /Type /Catalog /Pages 2 0 R >>";
    $objs[2] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
    $objs[3] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>";
    $objs[4] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
    $objs[5] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";

    $out = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
    $offsets = [0 => 0];
    for ($i = 1; $i <= 5; $i++) {
        $offsets[$i] = strlen($out);
        $out .= "{$i} 0 obj\n{$objs[$i]}\nendobj\n";
    }

    $xrefStart = strlen($out);
    $out .= "xref\n0 6\n";
    $out .= "0000000000 65535 f \n";
    for ($i = 1; $i <= 5; $i++) {
        $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }
    $out .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n{$xrefStart}\n%%EOF\n";

    return $out;
}

$today = date('Y-m-d');
$lines = [
    "JOB APPLICATION / CV (Test File) - Target role: Senior Laravel Developer",
    "Date: {$today}",
    "",
    "Candidate: Youssef El Amrani",
    "Email: youssef.elamrani.test@example.com | Phone: +212 6 12 34 56 78",
    "Location: Casablanca, Morocco (Open to Hybrid/Remote)",
    "",
    "Professional Summary",
    "Senior PHP/Laravel developer with 6+ years experience building REST APIs and web apps.",
    "Strong background in MySQL optimization, authentication/authorization, and automated testing.",
    "Comfortable with Git workflows, CI/CD, and performance/security best practices.",
    "",
    "Core Skills (high match)",
    "Laravel, PHP 8, MySQL, REST APIs, Git, PHPUnit, Feature Tests, TDD, SOLID, OOP",
    "API Integration, Queues/Jobs, Caching (Redis), Authentication (Sanctum/JWT), Docker, Linux",
    "",
    "Relevant Experience",
    "Senior Laravel Developer | 2021 - 2026",
    "- Built and maintained Laravel services for recruitment and HR workflows (jobs, applications).",
    "- Designed REST APIs, integrated third-party services, improved performance and security.",
    "- Implemented automated tests (PHPUnit) and code reviews; mentored junior developers.",
    "",
    "Backend Developer (PHP) | 2019 - 2021",
    "- Developed APIs and web dashboards; optimized SQL queries and indexes in MySQL.",
    "- Implemented role-based access control and audit logging.",
    "",
    "Selected Projects",
    "1) HR Portal API (Laravel + MySQL): recruitment, leave, attendance modules, reporting dashboards.",
    "2) CV Parser Integration: consumed NLP service via REST, stored parsed skills/experience fields.",
    "",
    "Education",
    "BSc in Computer Science",
    "",
    "Keywords for matching (explicit)",
    "Laravel, PHP, MySQL, REST API, Git, Testing, PHPUnit, CI/CD, Performance, Security",
    "",
    "Expected Match: > 70%",
];

$pdf = build_pdf($lines);
$root = dirname(__DIR__);
file_put_contents($root . DIRECTORY_SEPARATOR . 'test.pdf', $pdf);

