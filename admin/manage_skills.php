<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('admin');

$success = "";

// 1. HANDLE ADD NEW GLOBAL SKILL
if (isset($_POST['add_new_skill'])) {
    $name = trim($_POST['skill_name']);
    $stmt = $pdo->prepare("INSERT IGNORE INTO skills (skill_name) VALUES (?)");
    $stmt->execute([$name]);
    $success = "New skill added to global database.";
}

// 2. HANDLE DELETE GLOBAL SKILL
if (isset($_GET['delete_skill_id'])) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE skill_id = ?");
    $stmt->execute([$_GET['delete_skill_id']]);
    $success = "Skill removed from database.";
}

// 3. HANDLE LINKING SKILL TO ROLE
if (isset($_POST['link_skill'])) {
    $r_id = $_POST['role_id'];
    $s_id = $_POST['skill_id'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO role_skills (role_id, skill_id) VALUES (?, ?)");
    $stmt->execute([$r_id, $s_id]);
    $success = "Skill linked to role successfully.";
}

// 4. HANDLE UNLINKING SKILL FROM ROLE
if (isset($_GET['unlink_role_id']) && isset($_GET['unlink_skill_id'])) {
    $stmt = $pdo->prepare("DELETE FROM role_skills WHERE role_id = ? AND skill_id = ?");
    $stmt->execute([$_GET['unlink_role_id'], $_GET['unlink_skill_id']]);
    $success = "Skill unlinked from role.";
}

// Fetch Data
$all_skills = $pdo->query("SELECT * FROM skills ORDER BY skill_name ASC")->fetchAll();
$roles = $pdo->query("SELECT * FROM job_roles ORDER BY role_name ASC")->fetchAll();

include '../includes/header.php';
?>

<style>
    .skill-manager-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }
    .section-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* FIXED INPUT BOX */
    .skill-input-container {
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
    }
    .skill-input-container input {
        width: 100%;
        background: #f8fafc;
        padding: 14px 80px 14px 18px; /* Extra right padding for the button */
        border-radius: 15px;
        border: 2px solid #f1f5f9;
        font-weight: 600;
        outline: none;
        transition: 0.3s;
    }
    .skill-input-container input:focus {
        border-color: var(--primary);
        background: white;
    }
    .btn-add-inline {
        position: absolute;
        right: 6px;
        top: 6px;
        bottom: 6px;
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        border: none;
        padding: 0 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .btn-add-inline:hover {
        transform: scale(1.02);
        filter: brightness(1.1);
    }

    /* MAPPING FORM */
    .mapping-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 20px;
        background: #f8fafc;
        padding: 1.8rem;
        border-radius: 20px;
        border: 2px solid #f1f5f9;
        margin-bottom: 2rem;
        align-items: flex-end;
    }
    .btn-gradient-wide {
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        border: none;
        height: 48px;
        padding: 0 25px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.15);
    }
    .btn-gradient-wide:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 56, 202, 0.25);
    }

    .role-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .role-requirement-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 1.2rem;
    }
    .mapped-skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0fdf4;
        color: #166534;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        margin: 4px;
        border: 1px solid #dcfce7;
    }
    .custom-select {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: white;
        font-weight: 600;
        outline: none;
    }
</style>

<div class="welcome-banner">
    <h1>Skill & Role Mapping 🛠️</h1>
    <p>Define technical benchmarks for each job specialization.</p>
</div>

<?php if($success): ?>
    <div class="card" style="background:#dcfce7; color:#15803d; border-color:#10b981; margin-top:1.5rem;"><?= $success ?></div>
<?php endif; ?>

<div class="dashboard-grid" style="grid-template-columns: 320px 1fr; margin-top: 2rem; align-items: start;">
    
    <div class="skill-manager-card">
        <h4 class="section-title"><i class="fas fa-database" style="color:var(--primary);"></i> Global Skills</h4>
        
        <form method="POST" class="skill-input-container">
            <input type="text" name="skill_name" placeholder="Add Skill..." required>
            <button type="submit" name="add_new_skill" class="btn-add-inline">
                <i class="fas fa-plus"></i> Add
            </button>
        </form>

        <div style="max-height: 450px; overflow-y: auto; padding-right: 5px;">
            <?php foreach($all_skills as $s): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; margin-bottom: 8px;">
                    <span style="font-weight: 600; font-size: 0.85rem; color: var(--secondary);"><?= $s['skill_name'] ?></span>
                    <a href="?delete_skill_id=<?= $s['skill_id'] ?>" style="color: #cbd5e1;" onclick="return confirm('Remove skill from DB?')">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <div class="skill-manager-card">
            <h4 class="section-title"><i class="fas fa-link" style="color:var(--accent);"></i> Link Skill to Role</h4>
            <form method="POST" class="mapping-form">
                <div>
                    <label style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block;">Select Role</label>
                    <select name="role_id" class="custom-select" required>
                        <?php foreach($roles as $r): ?>
                            <option value="<?= $r['role_id'] ?>"><?= $r['role_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block;">Select Skill</label>
                    <select name="skill_id" class="custom-select" required>
                        <?php foreach($all_skills as $s): ?>
                            <option value="<?= $s['skill_id'] ?>"><?= $s['skill_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="link_skill" class="btn-gradient-wide">
                    <i class="fas fa-check-double"></i> Link Requirement
                </button>
            </form>

            <div class="role-grid">
                <?php foreach($roles as $r): 
                    $stmt = $pdo->prepare("SELECT s.skill_name, s.skill_id FROM role_skills rs JOIN skills s ON rs.skill_id = s.skill_id WHERE rs.role_id = ?");
                    $stmt->execute([$r['role_id']]);
                    $role_reqs = $stmt->fetchAll();
                ?>
                <div class="role-requirement-card">
                    <h5 style="margin-bottom: 12px; color: var(--secondary); display: flex; align-items: center; justify-content: space-between;">
                        <?= $r['role_name'] ?>
                        <span style="font-size: 0.65rem; background: var(--bg); padding: 2px 8px; border-radius: 5px;"><?= count($role_reqs) ?> Skills</span>
                    </h5>
                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                        <?php foreach($role_reqs as $rr): ?>
                            <span class="mapped-skill-tag">
                                <?= $rr['skill_name'] ?>
                                <a href="?unlink_role_id=<?= $r['role_id'] ?>&unlink_skill_id=<?= $rr['skill_id'] ?>"><i class="fas fa-times"></i></a>
                            </span>
                        <?php endforeach; ?>
                        <?php if(empty($role_reqs)) echo "<small style='color:#94a3b8; font-style: italic;'>No requirements set.</small>"; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>