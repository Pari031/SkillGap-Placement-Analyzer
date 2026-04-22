<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('admin');

$success = "";
$roles = $pdo->query("SELECT * FROM job_roles ORDER BY role_name ASC")->fetchAll();

// 1. DELETE ACTION
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM job_openings WHERE job_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $success = "Opening deleted successfully.";
}

// 2. ADD / UPDATE ACTION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company = $_POST['company_name'];
    $role_id = $_POST['role_id'];
    $cgpa = $_POST['min_cgpa'];
    $package = $_POST['package'];
    $type = $_POST['company_type'];

    if (isset($_POST['update_job'])) {
        $stmt = $pdo->prepare("UPDATE job_openings SET company_name=?, role_id=?, min_cgpa=?, package=?, company_type=? WHERE job_id=?");
        $stmt->execute([$company, $role_id, $cgpa, $package, $type, $_POST['job_id']]);
        $success = "Job details updated.";
    } else {
        $domain = $_POST['domain'];
        $logo = "https://www.google.com/s2/favicons?sz=128&domain=" . $domain;
        $stmt = $pdo->prepare("INSERT INTO job_openings (company_name, role_id, min_cgpa, package, logo_url, company_type) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$company, $role_id, $cgpa, $package, $logo, $type]);
        $success = "New job opening published!";
    }
}

// 3. FETCH FOR EDIT
$edit = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM job_openings WHERE job_id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit = $stmt->fetch();
}

$jobs = $pdo->query("SELECT jo.*, r.role_name FROM job_openings jo LEFT JOIN job_roles r ON jo.role_id = r.role_id ORDER BY job_id DESC")->fetchAll();

include '../includes/header.php';
?>

<style>
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }
    .form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 2rem;
        color: var(--secondary);
    }
    .form-header i {
        background: var(--primary-light);
        color: var(--primary);
        padding: 12px;
        border-radius: 12px;
        font-size: 1.2rem;
    }
    .compact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
    }
    .custom-input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .custom-input-group label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .custom-input, .custom-select {
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--secondary);
        transition: 0.3s;
    }
    .custom-input:focus, .custom-select:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
    }

    /* ENHANCED BUTTON */
    .btn-publish {
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        border: none;
        padding: 14px 35px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(67, 56, 202, 0.2);
    }
    .btn-publish:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 20px rgba(67, 56, 202, 0.3);
    }

    .centered-column { text-align: center !important; }
    .action-btn-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
        align-items: center;
    }
</style>

<div class="welcome-banner">
    <h1>Manage Opportunities ⚙️</h1>
    <p>Post new job listings or modify existing recruitment drives.</p>
</div>

<?php if($success): ?>
    <div class="card" style="background:#dcfce7; color:#15803d; border-color:#10b981; margin-top:1.5rem;"><?= $success ?></div>
<?php endif; ?>

<div class="form-card">
    <div class="form-header">
        <i class="fas <?= $edit ? 'fa-edit' : 'fa-bullhorn' ?>"></i>
        <h3 style="margin:0;"><?= $edit ? 'Update Job Details' : 'Publish New Opening' ?></h3>
    </div>
    
    <form method="POST">
        <?php if($edit): ?><input type="hidden" name="job_id" value="<?= $edit['job_id'] ?>"><?php endif; ?>
        
        <div class="compact-grid">
            <div class="custom-input-group">
                <label>Company Name</label>
                <input type="text" name="company_name" class="custom-input" value="<?= $edit['company_name'] ?? '' ?>" required>
            </div>

            <?php if(!$edit): ?>
            <div class="custom-input-group">
                <label>Website Domain</label>
                <input type="text" name="domain" class="custom-input" placeholder="e.g. apple.com" required>
            </div>
            <?php endif; ?>

            <div class="custom-input-group">
                <label>Job Role</label>
                <select name="role_id" class="custom-select" required>
                    <?php foreach($roles as $r): ?>
                        <option value="<?= $r['role_id'] ?>" <?= ($edit && $edit['role_id'] == $r['role_id'])?'selected':'' ?>><?= $r['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="custom-input-group">
                <label>Package (LPA)</label>
                <input type="number" step="0.01" name="package" class="custom-input" value="<?= $edit['package'] ?? '' ?>" required>
            </div>

            <div class="custom-input-group">
                <label>Min CGPA</label>
                <input type="number" step="0.01" name="min_cgpa" class="custom-input" value="<?= $edit['min_cgpa'] ?? '' ?>" required>
            </div>

            <div class="custom-input-group">
                <label>Company Type</label>
                <select name="company_type" class="custom-select">
                    <option value="Product Based" <?= ($edit && $edit['company_type'] == 'Product Based')?'selected':'' ?>>Product Based</option>
                    <option value="Service Based" <?= ($edit && $edit['company_type'] == 'Service Based')?'selected':'' ?>>Service Based</option>
                </select>
            </div>
        </div>

        <div class="action-btn-group">
            <?php if($edit): ?>
                <a href="manage_job_openings.php" style="text-decoration:none; color:#94a3b8; font-weight:700; font-size:0.9rem; margin-right:10px;">Cancel</a>
            <?php endif; ?>
            <button type="submit" name="<?= $edit ? 'update_job' : 'add_job' ?>" class="btn-publish">
                <i class="fas <?= $edit ? 'fa-save' : 'fa-paper-plane' ?>"></i> 
                <?= $edit ? 'Update Opening' : 'Publish Opening' ?>
            </button>
        </div>
    </form>
</div>

<div class="card" style="margin-top: 2.5rem; padding: 0; overflow: hidden;">
    <table class="modern-table" style="width: 100%;">
        <thead style="background: #f8fafc;">
            <tr>
                <th style="padding: 1.2rem;">Company</th>
                <th class="centered-column">Job Role</th>
                <th class="centered-column">Package</th>
                <th class="centered-column">CGPA Req.</th>
                <th style="text-align:right; padding-right:2rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($jobs as $job): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 1.2rem; display:flex; align-items:center; gap:12px;">
                    <img src="<?= $job['logo_url'] ?>" width="32" height="32" style="object-fit:contain; border-radius:6px;">
                    <strong><?= $job['company_name'] ?></strong>
                </td>
                <td class="centered-column"><span class="tag working" style="margin:0 auto;"><?= $job['role_name'] ?></span></td>
                <td class="centered-column" style="font-weight: 700;"><?= $job['package'] ?> LPA</td>
                <td class="centered-column"><?= $job['min_cgpa'] ?>+</td>
                <td style="text-align:right; padding-right:2rem;">
                    <a href="?edit_id=<?= $job['job_id'] ?>" class="btn" style="padding: 6px 12px; background:var(--primary-light); color:var(--primary);"><i class="fas fa-edit"></i></a>
                    <a href="?delete_id=<?= $job['job_id'] ?>" class="btn" style="padding: 6px 12px; background:#fee2e2; color:#ef4444;" onclick="return confirm('Delete this job?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>