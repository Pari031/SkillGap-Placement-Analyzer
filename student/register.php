<?php 
require_once '../includes/db_connect.php';
include '../includes/header.php'; 
?>

<style>
    /* Fix for Registration Sizing in halved windows */
    .reg-hero {
        padding: 4rem 10% !important;
    }
    .glass-card-wide {
        max-width: 800px !important;
        margin: 0 auto;
    }
    @media (max-width: 1000px) {
        .reg-hero { padding: 3rem 5% !important; }
        .reg-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="hero-bg reg-hero">
        <div class="glass-card glass-card-wide">
            <div class="auth-header">
                <div style="background: var(--primary-light); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fas fa-user-plus" style="font-size: 1.8rem; color: var(--primary);"></i>
                </div>
                <h2>Student Registration</h2>
                <p style="color:#64748b; font-size: 0.85rem;">Create your profile to start AI skill matching</p>
            </div>
            
            <form action="process_register.php" method="POST">
                <div class="reg-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <div class="input-group">
                            <label><i class="fas fa-id-badge"></i> Full Name</label>
                            <input type="text" name="name" placeholder="Enter Full Name" required>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-hashtag"></i> Roll Number</label>
                            <input type="text" name="roll_number" placeholder="University Roll No" required>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-envelope"></i> Mail ID</label>
                            <input type="email" name="email" placeholder="student@niet.co.in" required>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-lock"></i> Password</label>
                            <input type="password" name="password" placeholder="Create Password" required>
                        </div>
                    </div>

                    <div>
                        <div class="input-group">
                            <label><i class="fas fa-layer-group"></i> Current Year</label>
                            <select name="year" required>
                                <option value="">Choose Year...</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-calendar-check"></i> Passing Year</label>
                            <select name="passing_year" required>
                                <option value="">Choose Year...</option>
                                <?php for($i=2026; $i<=2030; $i++) echo "<option value='$i'>$i</option>"; ?>
                            </select>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-graduation-cap"></i> Current CGPA</label>
                            <input type="number" step="0.01" name="cgpa" placeholder="e.g. 8.50" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-block" style="margin-top: 30px; padding: 18px; font-size: 1.1rem; box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.3);">
                    Create Professional Profile <i class="fas fa-user-check" style="margin-left: 8px;"></i>
                </button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; text-align: center;">
                <p style="font-size: 0.9rem; color: #64748b;">
                    Already registered? <a href="login.php" class="link-alt" style="font-weight: 700;">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>