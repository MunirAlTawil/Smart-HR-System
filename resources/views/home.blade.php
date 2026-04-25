@extends('layout')

@section('title', 'Home')

@section('content')
<!-- Hero Slider Section -->
<section class="hero-slider">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1920&q=80');">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 mx-auto text-center hero-content">
                                <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInUp">HR AI System</h1>
                                <p class="lead mb-5 fs-4 animate__animated animate__fadeInUp animate__delay-1s">Discover the perfect job opportunities for you and check your compatibility with jobs using Artificial Intelligence</p>
                                <a href="/jobs" class="btn btn-primary btn-lg px-5 py-3 animate__animated animate__fadeInUp animate__delay-2s">Start Your Journey</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1920&q=80');">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 mx-auto text-center hero-content">
                                <h1 class="display-3 fw-bold mb-4">Smart CV Analysis</h1>
                                <p class="lead mb-5 fs-4">Use advanced AI technology to analyze your CV and find the best job opportunities</p>
                                <a href="/jobs" class="btn btn-primary btn-lg px-5 py-3">Browse Jobs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1920&q=80');">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 mx-auto text-center hero-content">
                                <h1 class="display-3 fw-bold mb-4">Get the Best Results</h1>
                                <p class="lead mb-5 fs-4">Accurate matching percentage between your skills and job requirements to get the best job opportunities</p>
                                <a href="/jobs" class="btn btn-primary btn-lg px-5 py-3">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Statistics Section -->
<section class="section-padding bg-gradient-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="fs-5">Available Jobs</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stat-number">2,500+</div>
                    <div class="fs-5">Active Candidates</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stat-number">150+</div>
                    <div class="fs-5">Partner Companies</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="stat-number">95%</div>
                    <div class="fs-5">Customer Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding section-animate">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">System Features</h2>
            <p class="section-subtitle">We provide you with the best smart recruitment tools</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="bi bi-search"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&q=80" alt="Advanced Search" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Advanced Search</h4>
                    <p class="text-muted">Search for jobs by department, type, or skills using advanced search algorithms</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&q=80" alt="Smart Analysis" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Smart Analysis</h4>
                    <p class="text-muted">Analyze your CV using advanced AI to extract skills and experience</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80" alt="Match Percentage" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Match Percentage</h4>
                    <p class="text-muted">Get an accurate match percentage between you and the job based on deep analysis of skills and requirements</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80" alt="Secure & Protected" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Secure & Protected</h4>
                    <p class="text-muted">Your data is protected with the latest security and privacy technologies</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&q=80" alt="Fast & Easy" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Fast & Easy</h4>
                    <p class="text-muted">Easy-to-use and fast interface to get results in minutes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="bi bi-bell"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&q=80" alt="Instant Notifications" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h4 class="fw-bold mb-3">Instant Notifications</h4>
                    <p class="text-muted">Get instant notifications when new jobs that suit you become available</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="section-padding bg-gradient-light section-animate">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple steps to get the best results</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-number">1</div>
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=300&q=80" alt="Create Account" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h5 class="fw-bold mb-3">Create Account</h5>
                    <p class="text-muted">Create a free account in minutes</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-number">2</div>
                    <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=300&q=80" alt="Upload CV" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h5 class="fw-bold mb-3">Upload Your CV</h5>
                    <p class="text-muted">Upload your CV in any format</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-number">3</div>
                    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=300&q=80" alt="Search Jobs" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h5 class="fw-bold mb-3">Search Jobs</h5>
                    <p class="text-muted">Browse hundreds of available jobs</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="process-number">4</div>
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=300&q=80" alt="Get Match" class="img-fluid rounded mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                    <h5 class="fw-bold mb-3">Get Your Match</h5>
                    <p class="text-muted">Get your match percentage with the job</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Jobs Section -->
<section class="section-padding section-animate">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Latest Available Jobs</h2>
            <p class="section-subtitle">Discover new opportunities that match your skills</p>
        </div>
        <div class="row g-4">
            @forelse($jobs as $job)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&q=80" class="card-img-top" alt="{{ $job->title }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">{{ $job->department }}</span>
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $job->type }}</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">{{ $job->title }}</h5>
                        <p class="card-text text-muted mb-4">{{ Str::limit($job->description, 100) }}</p>
                        <a href="/jobs/{{ $job->id }}" class="btn btn-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No jobs available at the moment
                </div>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="/jobs" class="btn btn-primary btn-lg px-5">View All Jobs</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section-padding bg-gradient-light section-animate">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">See what our users are saying about the system</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&q=80" alt="Sarah Ahmed" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0 fw-bold">Sarah Ahmed</h6>
                            <small class="text-muted">Software Developer</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"The system helped me find my dream job in just one week! The smart analysis was very accurate."</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&q=80" alt="Mohamed Khalid" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0 fw-bold">Mohamed Khalid</h6>
                            <small class="text-muted">Network Engineer</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"Easy to use interface and the match percentage was very accurate. I recommend everyone to try the system."</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&q=80" alt="Fatima Ali" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0 fw-bold">Fatima Ali</h6>
                            <small class="text-muted">Graphic Designer</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"The best recruitment platform I've used! Fast search and accurate results. Thanks to the team for the great work."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                <h2 class="display-5 fw-bold mb-3">Ready to Start Your Career Journey?</h2>
                <p class="lead fs-4 mb-0">Join thousands of candidates who found their perfect jobs</p>
            </div>
            <div class="col-lg-4 text-center">
                <a href="/jobs" class="btn btn-light btn-lg px-5 py-3" style="border-radius: 50px;">
                    <i class="bi bi-arrow-right ms-2"></i>
                    Get Started Free
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
