<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('admin');

// 1. Fetch Students with mastered count
$query = "SELECT s.*, 
          (SELECT GROUP_CONCAT(skill_id) FROM student_skills ss WHERE ss.student_id = s.student_id AND ss.status = 'Mastered') as mastered_skill_ids 
          FROM students s ORDER BY s.roll_number ASC";
$students = $pdo->query($query)->fetchAll();

// 2. Fetch Job Roles and their required skill sets
$roles_query = "SELECT r.role_id, r.role_name, GROUP_CONCAT(rs.skill_id) as required_skill_ids 
                FROM job_roles r 
                LEFT JOIN role_skills rs ON r.role_id = rs.role_id 
                GROUP BY r.role_id ORDER BY r.role_name ASC";
$roles = $pdo->query($roles_query)->fetchAll();

// 3. Clustering & Readiness Logic
$clusters = ['High' => [], 'Moderate' => [], 'Low' => []];
foreach($students as &$s) {
    $mastered_ids = $s['mastered_skill_ids'] ? explode(',', $s['mastered_skill_ids']) : [];
    $s['mastered_count'] = count($mastered_ids);
    
    $max_expected_skills = 4; 
    $skill_ratio = min($s['mastered_count'] / $max_expected_skills, 1);
    $skill_score = $skill_ratio * 70; 
    $cgpa_score = ($s['cgpa'] / 10) * 30;
    
    $final_score = round($skill_score + $cgpa_score, 1);
    $s['readiness'] = $final_score;

    if($final_score >= 75) {
        $tier = 'High';
        $clusters['High'][] = $s;
    } elseif($final_score >= 45) {
        $tier = 'Moderate';
        $clusters['Moderate'][] = $s;
    } else {
        $tier = 'Low';
        $clusters['Low'][] = $s;
    }
    $s['tier_label'] = $tier;

    $eligible_roles = [];
    foreach($roles as $r) {
        if(!$r['required_skill_ids']) continue;
        $req_ids = explode(',', $r['required_skill_ids']);
        if (!array_diff($req_ids, $mastered_ids)) {
            $eligible_roles[] = $r['role_id'];
        }
    }
    $s['role_matches'] = implode(',', $eligible_roles);
}

include '../includes/header.php';
?>

<style>
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 25px;
        flex-wrap: wrap; /* CRITICAL FIX: Allows stacking on narrow screens */
    }
    .filter-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 200px; /* Prevents shrinking too much */
    }
    .filter-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-select {
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--secondary);
        transition: 0.3s;
        cursor: pointer;
        width: 100%;
    }
    .filter-select:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
    }
    
    /* ENHANCED RESET BUTTON */
    .btn-reset-modern {
        background: #fef2f2;
        color: #ef4444;
        border: 2px solid #fee2e2;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        align-self: flex-end;
    }
    .btn-reset-modern:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2);
    }
    
    .divider { width: 1px; height: 50px; background: #e2e8f0; }
    @media (max-width: 850px) {
        .divider { display: none; }
        .btn-reset-modern { width: 100%; justify-content: center; }
    }
</style>

<div class="welcome-banner">
    <h1>Student Talent Pool 🎓</h1>
    <p>Clustered analysis of candidate readiness based on <?= count($students) ?> active profiles.</p>
</div>

<div class="filter-card">
    <div class="filter-section">
        <span class="filter-label"><i class="fas fa-layer-group" style="color: var(--primary);"></i> Readiness Tier</span>
        <select id="tierFilter" class="filter-select" onchange="filterStudents()">
            <option value="all">Show All Students</option>
            <option value="High">Tier 1: High Readiness</option>
            <option value="Moderate">Tier 2: Moderate</option>
            <option value="Low">Tier 3: Needs Training</option>
        </select>
    </div>
    
    <div class="divider"></div>

    <div class="filter-section">
        <span class="filter-label"><i class="fas fa-code" style="color: var(--accent);"></i> Eligible for Role</span>
        <select id="roleFilter" class="filter-select" onchange="filterStudents()">
            <option value="all">Any Specialization</option>
            <?php foreach($roles as $r): ?>
                <option value="<?= $r['role_id'] ?>"><?= $r['role_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn-reset-modern" onclick="resetFilters()">
        <i class="fas fa-sync-alt"></i> Reset Filters
    </button>
</div>

<div class="dashboard-grid" style="margin-top: 2rem;">
    <div class="card stat-mini border-green">
        <div class="large-icon" style="color: var(--accent); top: 30px;"><i class="fas fa-crown"></i></div>
        <h2 style="margin-top:10px;"><?= count($clusters['High']) ?></h2>
        <p style="font-size:0.7rem; font-weight:700; color:#64748b;">HIGH READINESS</p>
    </div>
    <div class="card stat-mini border-blue">
        <div class="large-icon" style="color: var(--primary); top: 30px;"><i class="fas fa-user-check"></i></div>
        <h2 style="margin-top:10px;"><?= count($clusters['Moderate']) ?></h2>
        <p style="font-size:0.7rem; font-weight:700; color:#64748b;">MODERATE</p>
    </div>
    <div class="card stat-mini border-darkred">
        <div class="large-icon" style="color: #ef4444; top: 30px;"><i class="fas fa-tools"></i></div>
        <h2 style="margin-top:10px;"><?= count($clusters['Low']) ?></h2>
        <p style="font-size:0.7rem; font-weight:700; color:#64748b;">NEEDS TRAINING</p>
    </div>
</div>

<div class="card" style="margin-top: 2rem; padding: 0; overflow: hidden;">
    <table class="modern-table" style="width: 100%;" id="studentTable">
        <thead>
            <tr style="background: #f8fafc; text-align: left;">
                <th style="padding: 1.2rem;">Roll No</th>
                <th>Student Name</th>
                <th>CGPA</th>
                <th>Mastered</th>
                <th>Readiness %</th>
                <th>Cluster Tier</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $stu): 
                $tag_class = ($stu['tier_label'] == 'High') ? 'mastered' : (($stu['tier_label'] == 'Moderate') ? 'working' : '');
                $tag_style = ($stu['tier_label'] == 'Low') ? 'background:#fee2e2; color:#ef4444; border-color:#fca5a5;' : '';
            ?>
            <tr class="student-row" 
                data-tier="<?= $stu['tier_label'] ?>" 
                data-roles="<?= $stu['role_matches'] ?>"
                style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 1.2rem;"><strong>#<?= $stu['roll_number'] ?></strong></td>
                <td><?= $stu['name'] ?></td>
                <td><?= $stu['cgpa'] ?></td>
                <td><span style="color:var(--primary); font-weight:600;"><?= $stu['mastered_count'] ?> Skills</span></td>
                <td><strong><?= $stu['readiness'] ?>%</strong></td>
                <td><span class="tag <?= $tag_class ?>" style="<?= $tag_style ?>"><?= $stu['tier_label'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="noResults" style="display:none; padding: 4rem; text-align: center; color: #94a3b8;">
        <i class="fas fa-search fa-3x" style="margin-bottom: 1.5rem; opacity: 0.3;"></i>
        <p style="font-size: 1.1rem; font-weight: 600;">No students match these criteria.</p>
    </div>
</div>

<script>
function filterStudents() {
    const tier = document.getElementById('tierFilter').value;
    const roleId = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('.student-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowTier = row.getAttribute('data-tier');
        const eligibleRoles = row.getAttribute('data-roles').split(',');

        const tierMatch = (tier === 'all' || rowTier === tier);
        const roleMatch = (roleId === 'all' || eligibleRoles.includes(roleId));

        if (tierMatch && roleMatch) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    document.getElementById('noResults').style.display = visibleCount === 0 ? "block" : "none";
    document.getElementById('studentTable').style.display = visibleCount === 0 ? "none" : "table";
}

function resetFilters() {
    document.getElementById('tierFilter').value = 'all';
    document.getElementById('roleFilter').value = 'all';
    filterStudents();
}
</script>

<?php include '../includes/footer.php'; ?>