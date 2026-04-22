<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('student');

$student_id = $_SESSION['user_id'];

// 1. Fetch student's current skill statuses for the match logic
$stmt = $pdo->prepare("SELECT skill_id, status FROM student_skills WHERE student_id = ?");
$stmt->execute([$student_id]);
$student_skills = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [skill_id => status]

// 2. Fetch all job openings joined with Roles
$query = "SELECT jo.*, r.role_name, r.role_id 
          FROM job_openings jo 
          JOIN job_roles r ON jo.role_id = r.role_id 
          ORDER BY jo.package DESC";
$jobs = $pdo->query($query)->fetchAll();

// 3. Fetch Master Data for Filters
$roles = $pdo->query("SELECT * FROM job_roles ORDER BY role_name ASC")->fetchAll();
$all_skills = $pdo->query("SELECT * FROM skills ORDER BY skill_name ASC")->fetchAll();

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
        flex-wrap: wrap;
    }
    .filter-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 180px;
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
        padding: 10px 15px;
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
    }
    .divider { width: 1px; height: 50px; background: #e2e8f0; }
    @media (max-width: 850px) {
        .divider { display: none; }
        .btn-reset-modern { width: 100%; justify-content: center; }
    }
</style>

<div class="welcome-banner">
    <h1>Job Opportunities 💼</h1>
    <p>AI-powered matching based on your verified technical skills.</p>
</div>

<div class="filter-card">
    <div class="filter-section">
        <span class="filter-label"><i class="fas fa-briefcase" style="color: var(--primary);"></i> Job Role</span>
        <select id="roleFilter" class="filter-select" onchange="filterJobs()">
            <option value="all">All Roles</option>
            <?php foreach($roles as $r): ?>
                <option value="<?= $r['role_name'] ?>"><?= $r['role_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="divider"></div>

    <div class="filter-section">
        <span class="filter-label"><i class="fas fa-chart-line" style="color: var(--accent);"></i> Max CGPA Req</span>
        <select id="cgpaFilter" class="filter-select" onchange="filterJobs()">
            <option value="all">Any CGPA</option>
            <option value="6">6.0 & Below</option>
            <option value="7">7.0 & Below</option>
            <option value="8">8.0 & Below</option>
            <option value="9">9.0 & Below</option>
        </select>
    </div>

    <div class="divider"></div>

    <div class="filter-section">
        <span class="filter-label"><i class="fas fa-tools" style="color: #f59e0b;"></i> Required Skill</span>
        <select id="skillFilter" class="filter-select" onchange="filterJobs()">
            <option value="all">Any Skill</option>
            <?php foreach($all_skills as $s): ?>
                <option value="<?= $s['skill_name'] ?>"><?= $s['skill_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn-reset-modern" onclick="resetFilters()">
        <i class="fas fa-sync-alt"></i> Reset
    </button>
</div>

<div class="dashboard-grid" id="jobsGrid" style="margin-top: 3rem;">
    <?php foreach($jobs as $j): 
        // Fetch skills required for this specific role
        $stmt = $pdo->prepare("SELECT s.skill_id, s.skill_name FROM role_skills rs 
                               JOIN skills s ON rs.skill_id = s.skill_id 
                               WHERE rs.role_id = ?");
        $stmt->execute([$j['role_id']]);
        $required_skills = $stmt->fetchAll();
        
        $req_skill_names = array_column($required_skills, 'skill_name');

        // Calculate Match %
        $match_count = 0;
        foreach($required_skills as $rs) {
            if(isset($student_skills[$rs['skill_id']]) && $student_skills[$rs['skill_id']] == 'Mastered') $match_count++;
        }
        $total_req = count($required_skills);
        $match_percent = ($total_req > 0) ? round(($match_count / $total_req) * 100) : 0;
    ?>
    <div class="card index-card job-card-item" 
         data-role="<?= $j['role_name'] ?>" 
         data-cgpa="<?= $j['min_cgpa'] ?>"
         data-skills="<?= implode(',', $req_skill_names) ?>"
         style="text-align: left; display: flex; flex-direction: column; border-radius: 20px; padding: 1.8rem;">
        
        <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 1.5rem;">
            <img src="<?= $j['logo_url'] ?>" width="45" height="45" style="object-fit: contain; border-radius: 8px;">
            <span class="tag <?= $match_percent >= 60 ? 'mastered' : 'working' ?>" style="font-size: 0.75rem; font-weight: 700; border-radius: 10px; padding: 6px 12px;">
                <?= $match_percent ?>% Match
            </span>
        </div>

        <h3 style="margin-bottom: 2px; font-size: 1.25rem; color: var(--secondary);"><?= $j['company_name'] ?></h3>
        <p style="color: var(--primary); font-weight: 700; font-size: 0.9rem; margin-bottom: 15px;"><?= $j['role_name'] ?></p>
        
        <div style="background: #f8fafc; padding: 12px 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #f1f5f9;">
            <p style="font-size: 0.85rem; margin-bottom: 6px; color: var(--text);"><strong>Package:</strong> <?= $j['package'] ?> LPA</p>
            <p style="font-size: 0.85rem; color: var(--text);"><strong>Min CGPA:</strong> <?= $j['min_cgpa'] ?></p>
        </div>

        <p style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">Skill Requirements:</p>
        <div class="tag-container" style="gap: 8px; margin-top: 0; justify-content: flex-start;">
            <?php foreach($required_skills as $sk): 
                $status = $student_skills[$sk['skill_id']] ?? 'Missing';
                $tag_style = "background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5;";
                if($status == 'Mastered') $tag_style = "background: #dcfce7; color: #16a34a; border: 1px solid #86efac;";
                if($status == 'Working On') $tag_style = "background: #fef9c3; color: #a16207; border: 1px solid #fde047;";
            ?>
                <span class="tag" style="font-size: 0.7rem; padding: 5px 10px; font-weight: 600; border-radius: 8px; <?= $tag_style ?>">
                    <?= $sk['skill_name'] ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div id="noJobsFound" style="display:none; padding: 5rem; text-align: center; color: #94a3b8;">
    <i class="fas fa-search fa-4x" style="margin-bottom: 1.5rem; opacity: 0.2;"></i>
    <h3 style="color: var(--secondary);">No Matching Opportunities</h3>
    <p>Try adjusting your filters to find more job openings.</p>
</div>

<script>
function filterJobs() {
    const role = document.getElementById('roleFilter').value;
    const cgpaLimit = document.getElementById('cgpaFilter').value;
    const skill = document.getElementById('skillFilter').value;
    const cards = document.querySelectorAll('.job-card-item');
    let visibleCount = 0;

    cards.forEach(card => {
        const cardRole = card.getAttribute('data-role');
        const cardCgpa = parseFloat(card.getAttribute('data-cgpa'));
        const cardSkills = card.getAttribute('data-skills').split(',');

        const roleMatch = (role === 'all' || cardRole === role);
        const cgpaMatch = (cgpaLimit === 'all' || cardCgpa <= parseFloat(cgpaLimit));
        const skillMatch = (skill === 'all' || cardSkills.includes(skill));

        if (roleMatch && cgpaMatch && skillMatch) {
            card.style.display = "flex";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });

    document.getElementById('noJobsFound').style.display = visibleCount === 0 ? "block" : "none";
    document.getElementById('jobsGrid').style.display = visibleCount === 0 ? "none" : "grid";
}

function resetFilters() {
    document.getElementById('roleFilter').value = 'all';
    document.getElementById('cgpaFilter').value = 'all';
    document.getElementById('skillFilter').value = 'all';
    filterJobs();
}
</script>

<?php include '../includes/footer.php'; ?>