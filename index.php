<?php include('includes/header.php'); ?>

<style>
    /* Force side-by-side on half window for top portals */
    .home-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important; 
        gap: 2rem !important;
        margin-top: 3rem !important;
    }

    /* Shared Lift & Glow Logic for all 5 cards */
    .index-card {
        background: white !important;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease !important;
        cursor: pointer;
        padding: 2.5rem !important;
        border-radius: 16px !important;
        border: 1px solid var(--border-color) !important;
    }

    .index-card:hover {
        transform: translateY(-12px) !important;
        z-index: 10;
    }

    /* Specific Hover Glows by Category */
    .glow-indigo:hover { 
        box-shadow: 0 20px 30px -5px rgba(67, 56, 202, 0.25) !important; 
        border-color: var(--primary) !important; 
    }
    .glow-green:hover { 
        box-shadow: 0 20px 30px -5px rgba(16, 185, 129, 0.25) !important; 
        border-color: var(--accent) !important; 
    }
    .glow-orange:hover { 
        box-shadow: 0 20px 30px -5px rgba(245, 158, 11, 0.25) !important; 
        border-color: #f59e0b !important; 
    }

    /* Typography fixes */
    .card-title {
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--secondary);
        font-size: 1.25rem;
    }

    @media (max-width: 650px) {
        .home-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div class="container" style="margin-top: 20px;">
    <div class="hero-bg">
        <div class="container text-center" style="color: white; padding: 0 1rem;">
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: 1rem;">Bridge Your <span style="color: #818cf8;">Skill Gap</span></h1>
            <p style="font-size: 1.1rem; margin-bottom: 3rem; opacity: 0.9;">Identify exactly what skills you need to land your dream job.</p>
            
            <div class="home-grid">
                <div class="index-card glow-indigo">
                    <div style="background: var(--primary-light); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
                        <i class="fas fa-user-graduate fa-2x" style="color: var(--primary);"></i>
                    </div>
                    <h3 class="card-title">Student Portal</h3>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 2rem;">Compare your profile against top company requirements.</p>
                    <a href="student/login.php" class="btn btn-block">Login as Student</a>
                </div>
                
                <div class="index-card glow-indigo">
                    <div style="background: #f1f5f9; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
                        <i class="fas fa-user-shield fa-2x" style="color: var(--secondary);"></i>
                    </div>
                    <h3 class="card-title">Admin Portal</h3>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 2rem;">Manage skills, companies, and batch analytics.</p>
                    <a href="admin/login.php" class="btn btn-block" style="background: var(--secondary);">Login as Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>

<section style="padding: 6rem 0; background: #fff;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 4rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <h6 style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem;">The Platform</h6>
                <h2 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; color: var(--secondary);">
                    Closing the loop between <br><span style="color: var(--primary);">Academics & Industry</span>
                </h2>
                <p style="color: #64748b; font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                    SkillGap isn't just a portal; it's a career architect. We analyzed thousands of job descriptions to build an engine that understands what companies actually want. By comparing your current academic standing and technical stack against real-time industry requirements, we eliminate the guesswork from your placement preparation.
                </p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--accent);"></i>
                        <span style="font-weight: 600; color: var(--secondary);">AI Resume Parsing</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--accent);"></i>
                        <span style="font-weight: 600; color: var(--secondary);">Predictive Scoring</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--accent);"></i>
                        <span style="font-weight: 600; color: var(--secondary);">Company Benchmarks</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--accent);"></i>
                        <span style="font-weight: 600; color: var(--secondary);">Gap Visualization</span>
                    </div>
                </div>
            </div>

            <div style="flex: 1; min-width: 300px; background: var(--primary-light); padding: 3rem; border-radius: 30px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 10rem; color: rgba(67, 56, 202, 0.05); font-weight: 900; pointer-events: none;">3</div>
                
                <h3 style="margin-bottom: 2rem; color: var(--primary); font-weight: 800;">Simple 3-Step Success</h3>
                
                <div style="display: flex; gap: 20px; margin-bottom: 2rem;">
                    <div style="background: var(--primary); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 800;">1</div>
                    <div>
                        <h5 style="color: var(--secondary); margin-bottom: 5px; font-weight: 700;">Sync Profile</h5>
                        <p style="font-size: 0.9rem; color: #64748b;">Upload your resume and let our AI extract your current technical skills automatically.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 2rem;">
                    <div style="background: var(--primary); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 800;">2</div>
                    <div>
                        <h5 style="color: var(--secondary); margin-bottom: 5px; font-weight: 700;">Choose Target</h5>
                        <p style="font-size: 0.9rem; color: #64748b;">Select the company or job role you are aiming for (e.g., Google, Amazon, or Full Stack Dev).</p>
                    </div>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div style="background: var(--primary); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 800;">3</div>
                    <div>
                        <h5 style="color: var(--secondary); margin-bottom: 5px; font-weight: 700;">Analyze & Learn</h5>
                        <p style="font-size: 0.9rem; color: #64748b;">Get your readiness score and a generated roadmap of missing skills you need to master.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="background: var(--secondary); padding: 4rem 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; text-align: center;">
            <div>
                <h2 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">50+</h2>
                <p style="color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; font-weight: 700;">Partner Companies</p>
            </div>
            <div>
                <h2 style="color: var(--accent); font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">100%</h2>
                <p style="color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; font-weight: 700;">Data Accuracy</p>
            </div>
            <div>
                <h2 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">AI</h2>
                <p style="color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; font-weight: 700;">Powered Analysis</p>
            </div>
            <div>
                <h2 style="color: #818cf8; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">Real-time</h2>
                <p style="color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; font-weight: 700;">Role Benchmarks</p>
            </div>
        </div>
    </div>
</section>

<section id="about" style="padding: 5rem 0; text-align: center;">
    <div class="container">
        <h2 style="font-size: 2.5rem; margin-bottom: 3.5rem; font-weight: 800;">Why SkillGap?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <div class="index-card glow-indigo" style="border-bottom: 4px solid var(--primary) !important;">
                <i class="fas fa-bullseye fa-2x" style="color: var(--primary); margin-bottom: 1rem;"></i>
                <h4 class="card-title" style="font-size: 1.1rem;">Precision Matching</h4>
                <p style="font-size: 0.9rem; color: #64748b;">Compare current profile with real company criteria.</p>
            </div>
            <div class="index-card glow-green" style="border-bottom: 4px solid var(--accent) !important;">
                <i class="fas fa-road fa-2x" style="color: var(--accent); margin-bottom: 1rem;"></i>
                <h4 class="card-title" style="font-size: 1.1rem;">Learning Roadmap</h4>
                <p style="font-size: 0.9rem; color: #64748b;">Get a clear list of specific skills you need to learn.</p>
            </div>
            <div class="index-card glow-orange" style="border-bottom: 4px solid #f59e0b !important;">
                <i class="fas fa-chart-pie fa-2x" style="color: #f59e0b; margin-bottom: 1rem;"></i>
                <h4 class="card-title" style="font-size: 1.1rem;">Deep Analytics</h4>
                <p style="font-size: 0.9rem; color: #64748b;">Visualize progress through interactive charts.</p>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>