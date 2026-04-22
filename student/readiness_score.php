<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('student');

$student_id = $_SESSION['user_id'];

// Fetch Student Data
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Fetch Student Mastered Skills
$stmt = $pdo->prepare("SELECT s.skill_name FROM student_skills ss JOIN skills s ON ss.skill_id = s.skill_id WHERE ss.student_id = ? AND ss.status = 'Mastered'");
$stmt->execute([$student_id]);
$mastered_skill_names = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch All Job Roles for the Dropdown
$roles = $pdo->query("SELECT * FROM job_roles ORDER BY role_name ASC")->fetchAll();

$selected_role_id = $_GET['role_id'] ?? null;
$analysis_data = null;
$matching_jobs = [];

if ($selected_role_id) {
    // 1. Get Role Skills
    $stmt = $pdo->prepare("SELECT s.skill_name, s.skill_id FROM role_skills rs JOIN skills s ON rs.skill_id = s.skill_id WHERE rs.role_id = ?");
    $stmt->execute([$selected_role_id]);
    $role_skills = $stmt->fetchAll();
    $role_skill_names = array_column($role_skills, 'skill_name');

    // 2. Fetch Job Openings for this role
    $stmt = $pdo->prepare("SELECT * FROM job_openings WHERE role_id = ? ORDER BY package DESC");
    $stmt->execute([$selected_role_id]);
    $matching_jobs = $stmt->fetchAll();
    
    $min_req_cgpa = (!empty($matching_jobs)) ? $matching_jobs[0]['min_cgpa'] : 7.0;

    // 3. Call Python API
    $payload = json_encode([
        "mastered_skills" => $mastered_skill_names,
        "required_skills" => $role_skill_names,
        "cgpa" => $student['cgpa'],
        "min_cgpa" => $min_req_cgpa
    ]);

    $ch = curl_init('http://127.0.0.1:5000/calculate_readiness');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    $response = curl_exec($ch);
    $analysis_data = json_decode($response, true);
    curl_close($ch);
}

include '../includes/header.php';
?>

<style>
    .selection-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .selection-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 250px;
    }
    .selection-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .custom-select {
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--secondary);
        transition: 0.3s;
        cursor: pointer;
    }
    .custom-select:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
    }
    .btn-analyze {
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(67, 56, 202, 0.2);
        align-self: flex-end;
    }
    .btn-analyze:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 56, 202, 0.3);
    }

    .score-value {
        font-size: 2.8rem !important;
        color: var(--primary);
        margin-top: 5px;
    }
    .large-icon-up {
        top: 30px !important;
    }
</style>

<div class="welcome-banner">
    <h1>Readiness Analysis 🎯</h1>
    <p>Simulate your eligibility for specific industry roles using AI predictive scoring.</p>
</div>

<div class="selection-card">
    <form method="GET" action="readiness_score.php" style="display:contents;">
        <div class="selection-group">
            <span class="selection-label"><i class="fas fa-crosshairs"></i> Target Job Specialization</span>
            <select name="role_id" class="custom-select" required>
                <option value="">-- Choose a Role to Analyze --</option>
                <?php foreach($roles as $r): ?>
                    <option value="<?= $r['role_id'] ?>" <?= $selected_role_id == $r['role_id'] ? 'selected' : '' ?>><?= $r['role_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-analyze">
            <i class="fas fa-brain"></i> Analyze Readiness
        </button>
    </form>
</div>

<?php if ($analysis_data): ?>
<div class="dashboard-grid" style="margin-top: 2rem;">
    <div class="card stat-mini border-blue">
        <div class="large-icon large-icon-up" style="color: var(--primary);"><i class="fas fa-bullseye"></i></div>
        <p style="font-size:0.75rem; color:#64748b; font-weight:700; letter-spacing:1px; margin-top: 15px;">READINESS SCORE</p>
        <h2 class="score-value"><?= $analysis_data['readiness_score'] ?>%</h2>
    </div>

    <div class="card" style="grid-column: span 2; text-align: left; padding: 2rem;">
        <h4 style="color: var(--secondary); margin-bottom: 1.5rem;"><i class="fas fa-chart-pie" style="color: var(--primary); margin-right: 10px;"></i> Score Breakdown</h4>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <div style="display:flex; justify-content:space-between; font-size: 0.85rem; margin-bottom: 8px;">
                    <span style="font-weight:600;">Technical Skills (70% Weight)</span>
                    <strong style="color:var(--primary);"><?= $analysis_data['breakdown']['skill_contribution'] ?> / 70</strong>
                </div>
                <div style="height: 10px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                    <div style="width: <?= ($analysis_data['breakdown']['skill_contribution']/70)*100 ?>%; height: 100%; background: var(--primary);"></div>
                </div>
            </div>
            <div>
                <div style="display:flex; justify-content:space-between; font-size: 0.85rem; margin-bottom: 8px;">
                    <span style="font-weight:600;">Academic Performance (30% Weight)</span>
                    <strong style="color:var(--accent);"><?= $analysis_data['breakdown']['cgpa_contribution'] ?> / 30</strong>
                </div>
                <div style="height: 10px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                    <div style="width: <?= ($analysis_data['breakdown']['cgpa_contribution']/30)*100 ?>%; height: 100%; background: var(--accent);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<h3 style="margin: 3.5rem 0 1.5rem; text-align: left; color: var(--secondary); font-size: 1.2rem;">Recommended Openings for this Role</h3>
<div class="dashboard-grid">
    <?php if (empty($matching_jobs)): ?>
        <p style="grid-column: span 3; color: #94a3b8; background: white; padding: 2rem; border-radius: 15px; border: 1px dashed var(--border-color); text-align: center;">No current openings found for this specialization.</p>
    <?php else: foreach($matching_jobs as $j): ?>
        <div class="card" style="text-align: left; border-radius: 18px; padding: 1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 1.2rem;">
                <img src="<?= $j['logo_url'] ?>" width="40" height="40" style="object-fit: contain; border-radius: 8px;">
                <span class="tag mastered" style="font-size: 0.65rem; background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; padding: 4px 10px;">Active</span>
            </div>
            <h4 style="margin-bottom: 4px; color: var(--secondary);"><?= $j['company_name'] ?></h4>
            <p style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-bottom: 15px;"><?= $j['package'] ?> LPA</p>
            
            <p style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px;">Role Skill Match:</p>
            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                <?php 
                $stmt = $pdo->prepare("SELECT s.skill_id, s.skill_name FROM role_skills rs JOIN skills s ON rs.skill_id = s.skill_id WHERE rs.role_id = ?");
                $stmt->execute([$j['role_id']]);
                $reqs = $stmt->fetchAll();

                $stmt = $pdo->prepare("SELECT skill_id, status FROM student_skills WHERE student_id = ?");
                $stmt->execute([$student_id]);
                $s_skills = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

                foreach($reqs as $r_skill): 
                    $status = $s_skills[$r_skill['skill_id']] ?? 'Missing';
                    $style = "background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5;"; // Red
                    if($status == 'Mastered') $style = "background: #dcfce7; color: #16a34a; border: 1px solid #86efac;"; // Green
                    if($status == 'Working On') $style = "background: #fef9c3; color: #a16207; border: 1px solid #fde047;"; // Yellow
                ?>
                    <span class="tag" style="font-size: 0.65rem; padding: 4px 8px; font-weight:700; border-radius:8px; <?= $style ?>"><?= $r_skill['skill_name'] ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>