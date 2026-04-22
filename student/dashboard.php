<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('student');

$student_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// 1. DATA FETCHING
$total_jobs = $pdo->query("SELECT COUNT(*) FROM job_openings")->fetchColumn();

$stmt = $pdo->prepare("SELECT skill_id FROM student_skills WHERE student_id = ? AND status = 'Mastered'");
$stmt->execute([$student_id]);
$my_mastered_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 2. ELIGIBILITY LOGIC
$all_openings = $pdo->query("SELECT jo.*, r.role_name, 
                            (SELECT GROUP_CONCAT(skill_id) FROM role_skills WHERE role_id = jo.role_id) as req_skills 
                            FROM job_openings jo JOIN job_roles r ON jo.role_id = r.role_id")->fetchAll();

$eligible_count = 0;
$almost_eligible = 0;
$not_eligible = 0;
$recommended_list = [];

foreach($all_openings as $job) {
    $req_skills = $job['req_skills'] ? explode(',', $job['req_skills']) : [];
    $missing_skills = array_diff($req_skills, $my_mastered_ids);
    
    $cgpa_ok = ($student['cgpa'] >= $job['min_cgpa']);
    $skills_ok = empty($missing_skills);

    if($cgpa_ok && $skills_ok) {
        $eligible_count++;
        if(count($recommended_list) < 4) $recommended_list[] = $job;
    } elseif ($cgpa_ok && count($missing_skills) <= 2) {
        $almost_eligible++;
    } else {
        $not_eligible++;
    }
}

// 3. AI GAP ANALYSIS
$gap_query = "SELECT s.skill_name, COUNT(rs.role_id) as impact 
              FROM skills s 
              JOIN role_skills rs ON s.skill_id = rs.skill_id 
              WHERE s.skill_id NOT IN (SELECT skill_id FROM student_skills WHERE student_id = ? AND status = 'Mastered')
              GROUP BY s.skill_id ORDER BY impact DESC LIMIT 3";
$stmt = $pdo->prepare($gap_query);
$stmt->execute([$student_id]);
$missing_skills_data = $stmt->fetchAll();

include '../includes/header.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Compact Stats Styling */
    .stat-card-compact {
        padding: 1.2rem !important;
        text-align: left !important;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .stat-card-compact .large-icon {
        position: static !important;
        transform: none !important;
        width: 45px !important;
        height: 45px !important;
        flex-shrink: 0;
    }

    /* Enhanced Recommendation UI */
    .recommendation-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        padding: 1.5rem;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }
    .recommendation-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(67, 56, 202, 0.08);
        border-color: var(--primary-light);
    }
    .recommendation-card::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 40px; height: 40px;
        background: linear-gradient(135deg, transparent 50%, #eef2ff 50%);
        border-radius: 0 20px 0 100%;
        opacity: 0;
        transition: 0.3s;
    }
    .recommendation-card:hover::after { opacity: 1; }

    .ai-insight-box {
        background: #f5f3ff;
        border-left: 4px solid var(--primary);
        border-radius: 15px;
        padding: 1.2rem;
        height: 100%;
    }
</style>

<div class="welcome-banner">
    <h1>Welcome, <?= explode(' ', $student['name'])[0] ?>! 🚀</h1>
    <p>Roll No: <strong><?= $student['roll_number'] ?></strong> | Academic Status: <span class="tag mastered" style="padding: 2px 10px;">Verified</span></p>
</div>

<div class="dashboard-grid" style="margin-top: 2rem; grid-template-columns: 1fr 1fr 1.2fr; gap: 20px;">
    
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="card stat-card-compact">
                <div class="large-icon" style="color: var(--primary); background: var(--primary-light) !important;"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <p style="font-size: 0.7rem; color: #64748b; font-weight: 800; text-transform: uppercase;">Current CGPA</p>
                    <h3 style="font-size: 1.4rem; margin:0;"><?= $student['cgpa'] ?> <small style="font-size: 0.8rem; color: #94a3b8;">/ 10</small></h3>
                </div>
            </div>
            <div class="card stat-card-compact">
                <div class="large-icon" style="color: #10b981; background: #f0fdf4 !important;"><i class="fas fa-briefcase"></i></div>
                <div>
                    <p style="font-size: 0.7rem; color: #64748b; font-weight: 800; text-transform: uppercase;">Total Drives</p>
                    <h3 style="font-size: 1.4rem; margin:0;"><?= $total_jobs ?> <small style="font-size: 0.8rem; color: #94a3b8;">Open</small></h3>
                </div>
            </div>
        </div>

        <div class="card" style="text-align: left; flex-grow: 1;">
            <div style="display:flex; gap: 30px;">
                <div style="flex: 1;">
                    <h5 style="color: #10b981; font-size: 0.7rem; margin-bottom: 15px; font-weight: 800;"><i class="fas fa-check-circle"></i> MASTERED</h5>
                    <div class="tag-container" style="justify-content: flex-start; gap: 6px;">
                        <?php 
                        $stmt = $pdo->prepare("SELECT s.skill_name FROM student_skills ss JOIN skills s ON ss.skill_id = s.skill_id WHERE ss.student_id = ? AND ss.status = 'Mastered' LIMIT 10");
                        $stmt->execute([$student_id]);
                        while($s = $stmt->fetch()) echo "<span class='tag mastered' style='font-size: 0.65rem; padding: 4px 10px;'>{$s['skill_name']}</span>";
                        ?>
                    </div>
                </div>
                <div style="flex: 1;">
                    <h5 style="color: var(--primary); font-size: 0.7rem; margin-bottom: 15px; font-weight: 800;"><i class="fas fa-spinner"></i> LEARNING</h5>
                    <div class="tag-container" style="justify-content: flex-start; gap: 6px;">
                        <?php 
                        $stmt = $pdo->prepare("SELECT s.skill_name FROM student_skills ss JOIN skills s ON ss.skill_id = s.skill_id WHERE ss.student_id = ? AND ss.status = 'Working On' LIMIT 10");
                        $stmt->execute([$student_id]);
                        while($s = $stmt->fetch()) echo "<span class='tag working' style='font-size: 0.65rem; padding: 4px 10px;'>{$s['skill_name']}</span>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem;">
        <h4 style="font-size: 0.75rem; color: var(--secondary); margin-bottom: 1.5rem; text-transform: uppercase; font-weight: 800;">Eligibility Overview</h4>
        <canvas id="eligibilityChart" style="max-width: 150px; max-height: 150px;"></canvas>
        <div style="margin-top: 1.5rem; width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.7rem; font-weight: 700;">
            <div style="color: #10b981; background: #f0fdf4; padding: 5px; border-radius: 8px; text-align: center;">Eligible: <?= $eligible_count ?></div>
            <div style="color: #64748b; background: #f8fafc; padding: 5px; border-radius: 8px; text-align: center;">Total: <?= $total_jobs ?></div>
        </div>
    </div>

    <div class="card" style="grid-column: span 2; padding: 0; border: none; background: transparent;">
        <div class="ai-insight-box">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-robot" style="color: var(--primary);"></i>
                <h4 style="font-size: 0.8rem; color: var(--primary); font-weight: 800; text-transform: uppercase;">AI Strategy</h4>
            </div>
            <?php if(!empty($missing_skills_data)): ?>
                <p style="font-size: 0.9rem; color: #4338ca;">Master <strong><?= $missing_skills_data[0]['skill_name'] ?></strong> to instantly qualify for <strong><?= $missing_skills_data[0]['impact'] ?></strong> additional companies.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="text-align: left; padding: 1.2rem;">
        <h4 style="font-size: 0.75rem; color: #ef4444; font-weight: 800; text-transform: uppercase; margin-bottom: 10px;">Critical Gaps</h4>
        <?php foreach($missing_skills_data as $ms): ?>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 5px;">
                <span style="font-weight: 600;"><?= $ms['skill_name'] ?></span>
                <span style="color: #ef4444;">+<?= $ms['impact'] ?> Roles</span>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="grid-column: span 3; margin-top: 10px;">
        <h4 style="margin-bottom: 1.5rem; font-weight: 800; color: var(--secondary); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-star" style="color: #f59e0b;"></i> Recommended For You
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
            <?php foreach($recommended_list as $rec): ?>
                <div class="recommendation-card">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                        <div style="background: white; padding: 5px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <img src="<?= $rec['logo_url'] ?>" width="35" height="35" style="object-fit: contain;">
                        </div>
                        <h5 style="margin:0; font-size: 1.1rem; color: var(--secondary);"><?= $rec['company_name'] ?></h5>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--primary); font-weight:800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;"><?= $rec['role_name'] ?></p>
                    <p style="font-size: 1rem; font-weight: 700; margin-bottom: 15px;"><?= $rec['package'] ?> <small style="font-weight:400; font-size:0.7rem; color: #94a3b8;">LPA</small></p>
                    <div style="display: flex; align-items: center; gap: 5px; color: #10b981; font-size: 0.75rem; font-weight: 800;">
                        <i class="fas fa-check-circle"></i> ELIGIBLE
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
const ctx = document.getElementById('eligibilityChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Eligible', 'Almost', 'Locked'],
        datasets: [{
            data: [<?= $eligible_count ?>, <?= $almost_eligible ?>, <?= $not_eligible ?>],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 0,
            cutout: '80%'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<?php include '../includes/footer.php'; ?>