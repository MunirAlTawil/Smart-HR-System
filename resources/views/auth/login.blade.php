@extends('layout')

@section('title', 'Login')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark mb-2">Login</h2>
                            <p class="text-muted">HR AI System</p>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus placeholder="example@email.com">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                                <label for="remember" class="form-check-label">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Login</button>
                        </form>

                        <hr class="my-4">
                        <p class="text-center text-muted small mb-3">Quick login (for testing)</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('quick-login', ['role' => 'admin']) }}" class="btn btn-outline-danger btn-lg">
                                <i class="bi bi-shield-lock-fill me-2"></i> Login as Admin
                            </a>
                            <a href="{{ route('quick-login', ['role' => 'user']) }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-person-fill me-2"></i> Login as User
                            </a>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-right me-1"></i> Back to Home</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
