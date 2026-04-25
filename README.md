# Smart HR System

A web-based human resources and recruitment system developed with Laravel and Python to support job publishing, candidate applications, CV analysis, application review, and employee record management within a single platform.

## Overview

Smart HR System is an academic full-stack prototype designed to improve recruitment workflows through structured job management, CV submission, automated skill matching, and administrative review tools. The platform combines Laravel for application logic, authentication, database management, and server-rendered views with Python scripts for CV analysis and employee-related analytical functions.

The system centralizes the following processes:
- Job posting and vacancy management
- Candidate applications with CV uploads
- CV screening and skill matching
- Administrative review of applications
- Employee record management
- Contact message handling

## Objectives

- Provide a centralized recruitment and HR management platform
- Reduce manual effort in early-stage candidate screening
- Support candidate-to-job matching through automated analysis
- Present understandable screening results using match percentage and skill breakdown
- Provide an administrative workspace for jobs, candidates, applications, contacts, and employees
- Maintain system usability through fallback logic when advanced analysis is unavailable

## Core Features

### Public Features
- Home page with active vacancies
- Searchable and filterable jobs listing
- Job details page with application form
- CV upload workflow
- Application result page with match percentage and identified skills
- Public contact form

### Recruitment and Analysis Features
- CV text extraction for uploaded resumes
- Skill comparison against job requirements
- Python-based CV analysis when available
- PHP fallback matching when external analysis is unavailable
- Match percentage calculation with matched and missing skills output

### Administrative Features
- Role-based authentication
- Dashboard with summary metrics and recent activity
- Job creation, update, and deletion
- Candidate review, approval, rejection, and deletion
- Application status management
- Contact message review and management
- Employee creation, update, deletion, and analysis view

## System Roles

### Public User / Candidate
- Browse active job postings
- View job details
- Submit applications with CV attachments
- View application result summaries
- Send messages through the contact form

### Admin
- Access the admin dashboard
- Manage jobs, candidates, applications, contacts, and employees
- Review application analysis output
- Update application and candidate statuses
- Convert approved candidates into employee records where applicable

### Staff User
- Access the protected area according to the assigned role
- Use seeded login credentials during development and demonstration

## Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade templates, Vite |
| Database | MySQL (default) / SQLite for tests |
| ORM | Eloquent |
| Authentication | Laravel session-based authentication |
| CV Parsing | smalot/pdfparser |
| Analysis | Python 3 scripts for CV analysis and employee analytics |
| Python Libraries | pdfplumber, python-docx, numpy, pandas, scikit-learn, joblib |
| Testing | PHPUnit 11 |

## System Architecture

The application follows a monolithic Laravel architecture with server-rendered views.

```text
Browser
   |
   v
Laravel Routes
   |
   v
Controllers
   |
   +--> Eloquent Models --> Database
   |
   +--> Python Scripts --> JSON Analysis Output
```

### Processing Model
- Laravel handles routing, validation, authentication, storage, and persistence
- Uploaded CV files are stored on the configured filesystem disk
- Controllers invoke Python scripts when deeper analysis is available
- PHP fallback logic preserves system functionality if Python execution fails
- Administrative views present stored application and employee data through Blade templates

## Core Modules

- **Job Management**: create, update, and manage job postings
- **Candidate Management**: store applicant information, CV paths, and approval status
- **Application Management**: link candidates to jobs and persist screening results
- **CV Analysis**: parse and evaluate CV content against required skills
- **Contact Management**: store and review external messages
- **Employee Management**: maintain employee records and expose analysis actions
- **Authentication and Authorization**: protect routes and restrict admin access

## Project Structure

```text
app/
  Http/Controllers/
  Models/
bootstrap/
config/
database/
  migrations/
  seeders/
docs/
  diagrams/
public/
python/
resources/
  css/
  js/
  views/
routes/
storage/
tests/
tools/
artisan
composer.json
package.json
vite.config.js
```

## Installation and Setup

### Requirements

- PHP 8.2 or later
- Composer
- Node.js and npm
- MySQL or another supported Laravel database
- Python 3 and pip

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Smart-HR-System
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create the Environment File

```bash
cp .env.example .env
```

### 5. Configure Environment Variables

Update the database connection and application settings inside `.env`.

Example configuration:

```env
APP_NAME="Smart HR System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr_system
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Generate the Application Key

```bash
php artisan key:generate
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

### 8. Seed Demo Data

```bash
php artisan db:seed
```

### 9. Create the Public Storage Link

```bash
php artisan storage:link
```

### 10. Install Python Dependencies

```bash
cd python
pip install -r requirements.txt
cd ..
```

### 11. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 12. Start the Application

```bash
php artisan serve
```

Recommended development workflow:

```bash
composer run dev
```

## Application Workflow

### Candidate Application Flow
1. A user browses available jobs
2. The user opens a job details page
3. The user submits the application form with a CV file
4. The system stores the CV and extracts relevant text
5. Required job skills are loaded
6. Python analysis is attempted
7. If Python analysis is unavailable, fallback matching is executed in PHP
8. Candidate and application records are stored
9. The result page displays the screening output

### Administrative Review Flow
1. An admin signs in to the system
2. The admin opens the dashboard and application listings
3. The admin reviews candidates and screening results
4. The admin updates application status
5. Accepted candidates can be converted into employee records
6. Employees can be maintained through the employee management area

## Default Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hr.local | password |
| User | user@hr.local | password |

## Useful Commands

```bash
composer run setup
composer run dev
composer run test
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run dev
npm run build
php artisan serve
```

## Testing

Run the automated test suite with:

```bash
composer run test
```

Or directly:

```bash
php artisan test
```

## Current Limitations

- Python analysis is executed synchronously during requests
- The project is an academic prototype and not a production-hardened deployment
- Some analytical functionality depends on synthetic or illustrative data
- Advanced fairness auditing is discussed conceptually rather than fully implemented
- Automated test coverage is limited compared with a production-ready system

## Future Enhancements

- Queue-based asynchronous analysis
- Expanded feature and integration testing
- Improved DOC and DOCX parsing consistency
- API layer for third-party integrations
- Stronger fairness auditing and reporting mechanisms
- Scalability improvements for production deployment

## Author

**Muhammed Munir Al Tawil**
