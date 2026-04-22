<?php include('../includes/header.php'); ?>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="hero-bg">
        <div class="glass-card">
            <div class="auth-header">
                <div style="background: var(--primary-light); width: 55px; height: 55px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; transform: rotate(-5deg);">
                    <i class="fas fa-user-graduate" style="font-size: 1.5rem; color: var(--primary); transform: rotate(5deg);"></i>
                </div>
                <h2 style="font-weight: 800; color: var(--secondary);">Student Login</h2>
                <p style="color:#64748b; font-size:0.85rem;">Access your placement dashboard</p>
            </div>
            
            <form action="process_login.php" method="POST">
                <div class="input-group">
                    <label><i class="fas fa-envelope-open" style="margin-right:6px; font-size: 0.7rem;"></i> Email Address</label>
                    <input type="email" name="email" placeholder="e.g. alex@niet.co.in" required>
                </div>
                <div class="input-group" style="margin-top: 1.5rem;">
                    <label><i class="fas fa-key" style="margin-right:6px; font-size: 0.7rem;"></i> Account Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-block" style="margin-top:2.5rem; padding: 14px; font-size: 0.95rem; box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.3);">
                    Sign In to Portal <i class="fas fa-chevron-right" style="margin-left:8px; font-size:0.7rem;"></i>
                </button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; text-align: center;">
                <p style="font-size: 0.9rem; color: #64748b;">
                    New to SkillGap? <a href="register.php" class="link-alt" style="color: var(--primary); font-weight: 700;">Register Now</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>