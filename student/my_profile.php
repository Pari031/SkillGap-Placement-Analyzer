<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('student');

$student_id = $_SESSION['user_id'];
$success = "";

// 1. Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $sql = "UPDATE students SET 
            name = ?, year = ?, cgpa = ?, passing_year = ? 
            WHERE student_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['name'], $_POST['year'], 
        $_POST['cgpa'], $_POST['passing_year'],
        $student_id
    ]);
    
    $_SESSION['name'] = $_POST['name'];
    $success = "Academic profile synchronized successfully!";
}

// 2. Fetch Current Data
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$user = $stmt->fetch();

include '../includes/header.php';
?>

<style>
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        height: 100%;
    }
    .card-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-title i {
        background: var(--primary-light);
        color: var(--primary);
        padding: 10px;
        border-radius: 10px;
        font-size: 1rem;
    }
    .custom-input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 1.5rem;
    }
    .custom-input-group label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 4px;
    }
    .custom-input, .custom-select {
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--secondary);
        transition: 0.3s;
        outline: none;
    }
    .custom-input:focus, .custom-select:focus {
        border-color: var(--primary);
        background: white;
    }
    .custom-input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }
    
    /* GRADIENT BUTTON */
    .btn-update {
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        border: none;
        padding: 15px 35px;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 20px rgba(67, 56, 202, 0.2);
        margin-top: 1rem;
    }
    .btn-update:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(67, 56, 202, 0.3);
        filter: brightness(1.1);
    }
    .btn-update:active {
        transform: translateY(0);
    }
</style>

<div class="welcome-banner">
    <h1>My Profile 👤</h1>
    <p>Manage your academic identity and core credentials.</p>
</div>

<?php if($success): ?>
    <div class="card" style="background: #dcfce7; color: #15803d; border-color: #10b981; margin: 1.5rem 0; padding: 1rem 1.5rem; border-radius: 15px;">
        <i class="fas fa-check-circle"></i> <?= $success ?>
    </div>
<?php endif; ?>

<form method="POST" style="margin-top: 2rem;">
    <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr;">
        
        <div class="profile-card">
            <h4 class="card-title"><i class="fas fa-id-card"></i> Basic Information</h4>
            
            <div class="custom-input-group">
                <label>Full Name</label>
                <input type="text" name="name" class="custom-input" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="custom-input-group">
                <label>Email Address (Primary)</label>
                <input type="text" class="custom-input" value="<?= $user['email'] ?>" disabled>
            </div>

            <div class="custom-input-group">
                <label>University Roll Number</label>
                <input type="text" class="custom-input" value="<?= $user['roll_number'] ?>" disabled>
            </div>
        </div>

        <div class="profile-card">
            <h4 class="card-title"><i class="fas fa-user-graduate"></i> Academic Standing</h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="custom-input-group">
                    <label>Current Year</label>
                    <select name="year" class="custom-select">
                        <?php for($i=1; $i<=4; $i++): ?>
                            <option value="<?= $i ?>" <?= $user['year'] == $i ? 'selected' : '' ?>>Year <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="custom-input-group">
                    <label>Passing Year</label>
                    <select name="passing_year" class="custom-select">
                        <?php for($y=2024; $y<=2030; $y++): ?>
                            <option value="<?= $y ?>" <?= $user['passing_year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="custom-input-group">
                <label style="color: var(--primary);">Cumulative CGPA</label>
                <input type="number" step="0.01" name="cgpa" class="custom-input" value="<?= $user['cgpa'] ?>" style="border-color: rgba(67, 56, 202, 0.2); font-size: 1.1rem; color: var(--primary);">
                <small style="color: #94a3b8; font-style: italic; margin-top: 5px;">Update this after every semester for accurate matching.</small>
            </div>
        </div>

        <div style="grid-column: span 2; text-align: left;">
            <button type="submit" name="update_profile" class="btn-update">
                <i class="fas fa-sync-alt"></i> Update Profile Details
            </button>
        </div>
    </div>
</form>

<?php include '../includes/footer.php'; ?>