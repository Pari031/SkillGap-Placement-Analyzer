<?php include('../includes/header.php'); ?>

<div class="container" style="margin-top: 20px;">
    <div class="hero-bg" style="background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920');">
        <div class="glass-card" style="border-top: 6px solid var(--secondary);">
            <div class="auth-header">
                <div style="background: #f1f5f9; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fas fa-user-shield" style="font-size: 1.8rem; color: var(--secondary);"></i>
                </div>
                <h2>Admin Access</h2>
                <p style="color:#64748b; font-size: 0.9rem;">Placement Cell Management Console</p>
            </div>
            
            <form action="process_admin_login.php" method="POST">
                <div class="input-group">
                    <label style="color: var(--secondary);"><i class="fas fa-user-tie"></i> Admin Email</label>
                    <input type="email" name="email" placeholder="admin@niet.co.in" required>
                </div>
                <div class="input-group">
                    <label style="color: var(--secondary);"><i class="fas fa-fingerprint"></i> Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-block" style="background: var(--secondary); margin-top:25px; padding: 16px;">
                    Secure Login <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                </button>
            </form>
            
            <p class="text-center" style="margin-top:25px;">
                <a href="../index.php" class="link-alt" style="color: var(--secondary); opacity: 0.8;"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </p>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>