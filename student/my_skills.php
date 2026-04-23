<?php 
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
checkLogin('student');
$student_id = $_SESSION['user_id'];

// 1. HANDLE ACTIONS (ADD, UPDATE, DELETE)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Standard Manual Actions
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_skill') {
            $skill_id = $_POST['skill_id'];
            $status = $_POST['status'];
            $ins = $pdo->prepare("INSERT INTO student_skills (student_id, skill_id, status) VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE status = ?");
            $ins->execute([$student_id, $skill_id, $status, $status]);
        } 
        elseif ($_POST['action'] == 'delete_skill') {
            $del = $pdo->prepare("DELETE FROM student_skills WHERE student_id = ? AND skill_id = ?");
            $del->execute([$student_id, $_POST['skill_id']]);
        }
        echo "success"; exit;
    }

    // AI Save Action: Maps extracted names back to IDs
    if (isset($_POST['save_extracted_skills'])) {
        $skills_to_save = $_POST['skills'];
        foreach ($skills_to_save as $skill_name) {
            $stmt = $pdo->prepare("SELECT skill_id FROM skills WHERE skill_name = ?");
            $stmt->execute([$skill_name]);
            $skill_id = $stmt->fetchColumn();
            if ($skill_id) {
                $ins = $pdo->prepare("INSERT INTO student_skills (student_id, skill_id, status) VALUES (?, ?, 'Mastered') 
                                     ON DUPLICATE KEY UPDATE student_id=student_id"); 
                $ins->execute([$student_id, $skill_id]);
            }
        }
        echo "success"; exit;
    }
}

// Data Retrieval for UI
$all_skills = $pdo->query("SELECT * FROM skills ORDER BY skill_name ASC")->fetchAll();

$stmt = $pdo->prepare("SELECT ss.*, s.skill_name FROM student_skills ss JOIN skills s ON ss.skill_id = s.skill_id WHERE ss.student_id = ? AND ss.status = 'Mastered' ORDER BY s.skill_name ASC");
$stmt->execute([$student_id]);
$mastered_skills = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT ss.*, s.skill_name FROM student_skills ss JOIN skills s ON ss.skill_id = s.skill_id WHERE ss.student_id = ? AND ss.status = 'Working On' ORDER BY s.skill_name ASC");
$stmt->execute([$student_id]);
$learning_skills = $stmt->fetchAll();

include '../includes/header.php'; 
?>

<style>
    .skill-card-main {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border-color); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }
    .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; color: var(--secondary); }
    .section-header i { background: var(--primary-light); color: var(--primary); padding: 10px; border-radius: 10px; font-size: 1.1rem; }
    
    .ai-upload-box { border: 2px dashed #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center; background: #f8fafc; transition: 0.3s; }
    .ai-upload-box:hover { border-color: var(--primary); background: white; }

    .custom-select { border: 2px solid #f1f5f9; background: #f8fafc; border-radius: 12px; padding: 10px 15px; font-weight: 600; color: var(--secondary); outline: none; transition: 0.3s; width: 100%; }
    .custom-select:focus { border-color: var(--primary); background: white; }

    .btn-skill { background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%); color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; width: 100%; justify-content: center; transition: 0.3s; }
    .btn-skill:hover { transform: translateY(-2px); filter: brightness(1.1); }

    .inventory-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 1rem; }
    .inventory-panel { background: white; border-radius: 20px; border: 1px solid var(--border-color); overflow: hidden; }
    .panel-header { padding: 1.2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; }
    
    .skill-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; border-bottom: 1px solid #f8fafc; transition: 0.2s; }
    .skill-row:hover { background: #f8fafc; }
    .skill-info { display: flex; align-items: center; gap: 15px; }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .status-mastered { background: #10b981; }
    .status-working { background: #6366f1; }

    .action-icons { display: flex; gap: 15px; align-items: center; }
    .btn-icon { background: none; border: none; cursor: pointer; color: #94a3b8; transition: 0.2s; padding: 5px; }
    .btn-icon:hover { color: var(--primary); transform: scale(1.2); }
    .btn-del:hover { color: #fb7185 !important; }
</style>

<div class="welcome-banner">
    <h1>Skill Management Hub 🧠</h1>
    <p>Refine your technical profile to improve AI placement matching.</p>
</div>

<div class="dashboard-grid" style="margin-top: 2.5rem; align-items: stretch;">
    <div class="skill-card-main">
        <div class="section-header"><i class="fas fa-robot"></i><h4 style="margin:0;">AI Resume Parser</h4></div>
        <div class="ai-upload-box">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">Upload PDF to sync skills automatically.</p>
            <form id="resumeForm">
                <input type="file" id="resumeFile" accept=".pdf" hidden>
                <button type="button" class="btn-skill" onclick="document.getElementById('resumeFile').click()" id="chooseBtn"><i class="fas fa-search"></i> Choose PDF</button>
                <button type="submit" id="analyzeBtn" class="btn-skill" style="background: var(--accent); display:none; margin-top:10px;"><i class="fas fa-magic"></i> Analyze</button>
            </form>
        </div>
        <div id="aiResults" style="display:none; margin-top:1.5rem;">
            <p style="font-weight:700; font-size:0.8rem; color:var(--secondary);">EXTRACTED SKILLS:</p>
            <div id="aiSkillTags" class="tag-container" style="margin-bottom:1rem; display:flex; flex-wrap:wrap; gap:8px;"></div>
            <button class="btn-skill" onclick="saveAISkills()">Verify & Save to Mastered</button>
        </div>
    </div>

    <div class="skill-card-main">
        <div class="section-header"><i class="fas fa-keyboard"></i><h4 style="margin:0;">Add Skill Manually</h4></div>
        <form id="manualSkillForm">
            <div style="margin-bottom:15px;">
                <label style="font-size:0.7rem; font-weight:800; color:#94a3b8; text-transform:uppercase;">Select Skill</label>
                <select id="manualSkillId" class="custom-select" required>
                    <?php foreach($all_skills as $sk): ?><option value="<?= $sk['skill_id'] ?>"><?= $sk['skill_name'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label style="font-size:0.7rem; font-weight:800; color:#94a3b8; text-transform:uppercase;">Status</label>
                <select id="manualStatus" class="custom-select">
                    <option value="Mastered">Mastered</option>
                    <option value="Working On">Working On</option>
                </select>
            </div>
            <button type="submit" class="btn-skill"><i class="fas fa-plus-circle"></i> Add to Inventory</button>
        </form>
    </div>

    <div style="grid-column: span 2; margin-top: 1rem;">
        <div class="section-header"><i class="fas fa-briefcase"></i><h4 style="margin:0;">My Professional Skills Inventory</h4></div>
        
        <div class="inventory-container">
            <div class="inventory-panel">
                <div class="panel-header" style="background: #f0fdf4;">
                    <h5 style="color: #166534; margin:0;"><i class="fas fa-check-circle"></i> Mastered Skills</h5>
                    <span class="tag mastered" style="font-size:0.7rem;"><?= count($mastered_skills) ?></span>
                </div>
                <?php foreach($mastered_skills as $ms): ?>
                    <div class="skill-row">
                        <div class="skill-info">
                            <div class="dot status-mastered"></div>
                            <span style="font-weight:600; font-size:0.95rem;"><?= $ms['skill_name'] ?></span>
                        </div>
                        <div class="action-icons">
                            <button class="btn-icon" title="Move to Learning" onclick="updateStatus(<?= $ms['skill_id'] ?>, 'Working On')"><i class="fas fa-arrow-down"></i></button>
                            <button class="btn-icon btn-del" onclick="deleteSkill(<?= $ms['skill_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($mastered_skills)) echo "<p style='padding:20px; color:#94a3b8; font-size:0.85rem; text-align:center;'>No mastered skills yet.</p>"; ?>
            </div>

            <div class="inventory-panel">
                <div class="panel-header" style="background: #f5f3ff;">
                    <h5 style="color: #4338ca; margin:0;"><i class="fas fa-spinner"></i> Currently Learning</h5>
                    <span class="tag working" style="font-size:0.7rem;"><?= count($learning_skills) ?></span>
                </div>
                <?php foreach($learning_skills as $ls): ?>
                    <div class="skill-row">
                        <div class="skill-info">
                            <div class="dot status-working"></div>
                            <span style="font-weight:600; font-size:0.95rem;"><?= $ls['skill_name'] ?></span>
                        </div>
                        <div class="action-icons">
                            <button class="btn-icon" title="Mark as Mastered" onclick="updateStatus(<?= $ls['skill_id'] ?>, 'Mastered')"><i class="fas fa-medal" style="color:#eab308;"></i></button>
                            <button class="btn-icon btn-del" onclick="deleteSkill(<?= $ls['skill_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($learning_skills)) echo "<p style='padding:20px; color:#94a3b8; font-size:0.85rem; text-align:center;'>No ongoing learning tasks.</p>"; ?>
            </div>
        </div>
    </div>
</div>

<script>
// --- UI INTERACTIONS ---
document.getElementById('resumeFile').onchange = function() {
    if(this.files.length > 0) {
        document.getElementById('chooseBtn').innerHTML = "<i class='fas fa-file-pdf'></i> " + this.files[0].name;
        document.getElementById('analyzeBtn').style.display = "inline-flex";
    }
};

// --- AI PARSING LOGIC ---
document.getElementById('resumeForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('analyzeBtn');
    btn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Analyzing...";
    btn.disabled = true;

    const formData = new FormData();
    formData.append('file', document.getElementById('resumeFile').files[0]);

    try {
        const response = await fetch('http://127.0.0.1:5000/parse-resume', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if(data.status === 'success') {
            const container = document.getElementById('aiSkillTags');
            container.innerHTML = '';
            data.extracted_skills.forEach(skill => {
                container.innerHTML += `<span class='tag mastered' style='margin:2px'>${skill}</span>`;
            });
            document.getElementById('aiResults').style.display = 'block';
        } else {
            alert("AI Error: " + data.message);
        }
    } catch (error) {
        alert("Could not connect to AI API. Ensure Flask is running on port 5000.");
    } finally {
        btn.innerHTML = "<i class='fas fa-magic'></i> Analyze";
        btn.disabled = false;
    }
};

async function saveAISkills() {
    const tags = document.querySelectorAll('#aiSkillTags .tag');
    const skills = Array.from(tags).map(t => t.innerText);
    
    const formData = new FormData();
    formData.append('save_extracted_skills', '1');
    skills.forEach(s => formData.append('skills[]', s));

    const res = await fetch('my_skills.php', { method: 'POST', body: formData });
    if(await res.text() === "success") location.reload();
}

// --- MANUAL MANAGEMENT LOGIC ---
async function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('action', 'add_skill'); 
    formData.append('skill_id', id);
    formData.append('status', status);
    const res = await fetch('my_skills.php', { method: 'POST', body: formData });
    if(await res.text() === "success") location.reload();
}

async function deleteSkill(id) {
    if(!confirm("Remove this skill from your profile?")) return;
    const formData = new FormData();
    formData.append('action', 'delete_skill');
    formData.append('skill_id', id);
    const res = await fetch('my_skills.php', { method: 'POST', body: formData });
    if(await res.text() === "success") location.reload();
}

document.getElementById('manualSkillForm').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('action', 'add_skill');
    formData.append('skill_id', document.getElementById('manualSkillId').value);
    formData.append('status', document.getElementById('manualStatus').value);
    const res = await fetch('my_skills.php', { method: 'POST', body: formData });
    if(await res.text() === "success") location.reload();
};
</script>

<?php include '../includes/footer.php'; ?>