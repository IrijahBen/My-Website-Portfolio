<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portfolio Showcase - Website Development, AI Engineering & Data Analytics</title>
    <meta name="description" content="Showcase of website development, AI engineering, and data analytics projects">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-code"></i>
                    <span>The Sight Tech Hub</span>
                </a>

                <ul class="nav-links">
                    <li><a href="#showcase">Showcase</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>

                <a href="{{ url('/admin') }}" class="btn btn-outline">Admin</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">

                <div class="badge">
                    <i class="fas fa-sparkles"></i>
                    <span>Made with Passion</span>
                </div>

                <h1 class="hero-title">
                    Discover <span class="highlight">Showcase</span> websites<br>
                    built with expertise
                </h1>

                <p class="hero-subtitle">
                    Browse, explore, and get inspired by projects in website development, 
                    AI engineering, and data analytics.
                </p>

                <!-- Search -->
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search-input" placeholder="Search projects...">
                    </div>
                </div>

                <!-- Category Tabs -->
                <div class="category-tabs">
                    <button class="tab-btn active" data-category="all">All</button>
                    <button class="tab-btn" data-category="landing-page">Landing Page</button>
                    <button class="tab-btn" data-category="multipage">Multipage</button>
                    <button class="tab-btn" data-category="data-analytics">Data Analytics</button>
                    <button class="tab-btn" data-category="agentic-ai">Agentic AI</button>
                    <button class="tab-btn" data-category="automation">Automation</button>
                    <button class="tab-btn" data-category="data-visualization">Data Visualization</button>
    
                    <button class="tab-btn" data-category="management-system">Management System</button>
                </div>

            </div>
        </div>
    </section>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="container">
            <div class="filter-content">
                <div class="filter-left">
                    <span class="results-count">Loading projects...</span>
                </div>

                <div class="filter-right">
                    <label class="toggle">
                        <input type="checkbox" id="cloneable-toggle">
                        <span class="toggle-slider"></span>
                        <span class="toggle-label">Cloneable sites only</span>
                    </label>

                    <button class="btn btn-primary btn-small" id="showcase-btn">
                        <i class="fas fa-plus"></i>
                        Showcase your work
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <section class="projects-section" id="showcase">
        <div class="container">
            <div id="projects-grid" class="projects-grid"></div>

            <div class="load-more">
                <button id="load-more-btn" class="btn btn-secondary">
                    Show more
                </button>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">

                    <h2>About My Work</h2>

                    <p>I specialize in creating innovative digital solutions across three core areas:</p>

                    <div class="skills-grid">

                        <div class="skill-card">
                            <div class="skill-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h3>Website Development</h3>
                            <p>Modern, responsive websites built with cutting-edge technologies and best practices.</p>
                        </div>

                        <div class="skill-card">
                            <div class="skill-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3>AI Engineering</h3>
                            <p>Intelligent systems and machine learning solutions that drive innovation.</p>
                        </div>

                        <div class="skill-card">
                            <div class="skill-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Data Analytics</h3>
                            <p>Transforming raw data into actionable insights and visualizations.</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="contact-content">
                <h2>Let's Work Together</h2>
                <p>Have a project in mind? Let's discuss how we can bring your ideas to life.</p>

                <div class="contact-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 20px;">
                    
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=abayomiajiboye46111@gmail.com" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-large">
                        <i class="fas fa-envelope"></i>
                        Email Me
                    </a>
                    
                    <a href="https://wa.me/2348098507180?text=Hi%20Abayomi%2C%20I%20saw%20your%20portfolio%20and%20would%20like%20to%20discuss%20a%20project%20with%20you!" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-large" style="background-color: #25D366; border-color: #25D366; color: white;">
                        <i class="fab fa-whatsapp" style="font-size: 1.2em;"></i>
                        WhatsApp
                    </a>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="logo">
                        <i class="fas fa-code"></i>
                        <span>The Sight Tech Hub</span>
                    </a>
                    <p>Showcasing the best in web development, AI, and data analytics.</p>
                </div>

                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Explore</h4>
                        <ul>
                            <li><a href="#showcase">Showcase</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Categories</h4>
                        <ul>
                            <li><a href="#" data-category="showcase">Showcase</a></li>
                            <li><a href="#" data-category="animation">Animation</a></li>
                            <li><a href="#" data-category="interactions">Interactions</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Connect</h4>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} The Sight Tech Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
