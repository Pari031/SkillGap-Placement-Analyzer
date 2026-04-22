<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$base_path = "/MiniProject/";
$current_page = basename($_SERVER['PHP_SELF']);
$is_app_page = isset($_SESSION['user_id']) && !in_array($current_page, ['index.php', 'login.php', 'register.php']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillGap | Placement Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/<?= $is_app_page ? 'style.css' : 'landing.css' ?>">
</head>
<body>

<?php if ($is_app_page): ?>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <i class="fas fa-rocket"></i> <span class="hide-on-tablet">SkillGap</span>
            </div>
            <ul class="sidebar-menu">
                <?php if ($_SESSION['role'] == 'student'): ?>
                    <li><a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>"><i class="fas fa-th-large"></i> <span class="hide-on-tablet">Dashboard</span></a></li>
                    <li><a href="my_skills.php" class="<?= $current_page=='my_skills.php'?'active':'' ?>"><i class="fas fa-file-pdf"></i> <span class="hide-on-tablet">My Skills</span></a></li>
                    <li><a href="job_openings.php" class="<?= $current_page=='job_openings.php'?'active':'' ?>"><i class="fas fa-briefcase"></i> <span class="hide-on-tablet">Job Openings</span></a></li>
                    <li><a href="readiness_score.php" class="<?= $current_page=='readiness_score.php'?'active':'' ?>"><i class="fas fa-star"></i> <span class="hide-on-tablet">Readiness Score</span></a></li>
                    <li><a href="my_profile.php" class="<?= $current_page=='my_profile.php'?'active':'' ?>"><i class="fas fa-user-edit"></i> <span class="hide-on-tablet">My Profile</span></a></li>
                <?php else: ?>
                    <li><a href="students.php" class="<?= $current_page=='students.php'?'active':'' ?>"><i class="fas fa-users"></i> <span class="hide-on-tablet">Students</span></a></li>
                    <li><a href="manage_job_openings.php" class="<?= $current_page=='manage_job_openings.php'?'active':'' ?>"><i class="fas fa-plus-square"></i> <span class="hide-on-tablet">Manage Jobs</span></a></li>
                    <li><a href="manage_skills.php" class="<?= $current_page=='manage_skills.php'?'active':'' ?>"><i class="fas fa-tools"></i> <span class="hide-on-tablet">Manage Skills</span></a></li>
                <?php endif; ?>

                <li style="margin-top: 3rem;">
                    <a href="../includes/logout.php" style="color: #fb7185;">
                        <i class="fas fa-power-off"></i> <span class="hide-on-tablet">Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
        <main class="main-layout">
            <div class="top-bar" style="padding: 0.8rem 2.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div class="header-date" style="font-size: 0.85rem; color: #64748b;"><i class="far fa-calendar-alt"></i> <?= date('l, d M Y') ?></div>
                
                <div class="user-profile-header" style="display: flex; align-items: center; gap: 12px;">
                    <div class="user-text hide-on-tablet" style="text-align: right;">
                        <h3 class="user-name-large" style="font-size: 0.95rem; margin: 0; font-weight: 700; color: var(--secondary);">
                            <?= $_SESSION['role'] == 'admin' ? 'NIET Placement Cell' : $_SESSION['name'] ?>
                        </h3>
                        <span class="user-role-badge" style="font-size: 0.7rem; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            <?= ucfirst($_SESSION['role']) ?>
                        </span>
                    </div>
                    
                    <div class="user-avatar-circle" style="width: 38px; height: 38px; background: var(--primary-light); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid rgba(67, 56, 202, 0.1);">
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <i class="fas fa-user-shield"></i>
                        <?php else: ?>
                            <i class="fas fa-user-graduate"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="dashboard-content">
<?php else: ?>
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo $base_path; ?>index.php" class="logo"><i class="fas fa-chart-line"></i> SkillGap</a>
            <ul class="nav-links">
                <li><a href="<?php echo $base_path; ?>index.php">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>
<?php endif; ?>