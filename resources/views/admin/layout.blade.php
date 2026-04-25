<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }

        .btn-admin-action {
            padding: .25rem .55rem;
            border-radius: .6rem;
            font-weight: 600;
            line-height: 1.2;
        }
        .btn-admin-action i {
            vertical-align: -0.1em;
        }

        /* Colorful admin action buttons */
        .btn-admin-action.btn-aa {
            border: 0;
            color: #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, .14);
            transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
            letter-spacing: .1px;
        }
        .btn-admin-action.btn-aa:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, .18);
            filter: saturate(1.08);
            color: #fff;
        }
        .btn-admin-action.btn-aa:active {
            transform: translateY(0);
            box-shadow: 0 6px 14px rgba(15, 23, 42, .14);
        }
        .btn-admin-action.btn-aa:focus-visible {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .25), 0 10px 22px rgba(15, 23, 42, .18);
        }

        .btn-aa-view { background: linear-gradient(135deg, #2563eb, #06b6d4); }
        .btn-aa-analyze { background: linear-gradient(135deg, #7c3aed, #db2777); }
        .btn-aa-download { background: linear-gradient(135deg, #16a34a, #22c55e); }
        .btn-aa-delete { background: linear-gradient(135deg, #ef4444, #f97316); }
        .btn-aa-accept { background: linear-gradient(135deg, #10b981, #22c55e); }
        .btn-aa-reject { background: linear-gradient(135deg, #f43f5e, #a855f7); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <h4 class="text-white mb-4">Admin Dashboard</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/jobs">
                        <i class="bi bi-briefcase"></i> Manage Jobs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/candidates">
                        <i class="bi bi-people"></i> Candidates
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/applications">
                        <i class="bi bi-file-earmark-text"></i> Applications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/employees">
                        <i class="bi bi-person-badge"></i> Employees
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/contacts">
                        <i class="bi bi-envelope"></i> Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Main Site
                    </a>
                </li>
                <li class="nav-item mt-3 pt-3 border-top border-secondary">
                    <span class="nav-link text-white-50 small">{{ auth()->user()->name }}</span>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white border-0 w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>
</html>

