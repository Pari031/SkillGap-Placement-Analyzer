SkillGap: Placement Skill Gap Analyzer

SkillGap is a professional-grade, AI-driven platform designed to bridge the disparity between academic technical competencies and rigorous industry expectations. By leveraging Natural Language Processing (NLP) and a multi-tier system architecture, the platform automates the identification of technical deficiencies and provides students with a personalized "Readiness Score" to streamline placement preparation.

🚀 Key Features

AI Resume Parsing: Automatically extracts technical skills from PDF resumes using an NLP-based Python Flask API.

Suitability Algorithm: Calculates a weighted "Readiness Score" by evaluating technical competencies (60%) alongside academic standing/CGPA (40%).

Gap Visualization: Identifies "Critical Gaps" and provides visual roadmaps to help students target specific missing skills like Git, Docker, or Testing.

Interactive Dashboard: Features dynamic Chart.js visualizations, including an "Eligibility Overview" doughnut chart and clustered talent pool analytics.

Smart Recommendations: AI-generated insights that highlight high-impact skills capable of unlocking multiple job opportunities simultaneously.

Admin Analytics: Empower placement officers to cluster the student body into High Readiness, Moderate, and Needs Training tiers for efficient resource allocation.

🛠️ Technical Stack

Frontend: HTML5, CSS3, JavaScript, Bootstrap, Chart.js.

Web Server: PHP 8.x.

AI Engine: Python Flask API with NLP libraries (PyPDF2/BERT).

Database: MySQL.

Environment: XAMPP / VS Code.

💻 Local Installation Guide
To run this project on your local machine, follow these steps:

1. Prerequisites
Install XAMPP (or any PHP/MySQL server).

Install Python 3.9+.

Install VS Code.

2. Database Setup
Open phpMyAdmin.

Create a new database named placement_db.

Import the provided SQL dump file (containing the 7 core tables: admins, students, skills, job_openings, job_roles, role_skills, and student_skills).

3. PHP Web Server
Move the project folder to your XAMPP htdocs directory (e.g., C:/xampp/htdocs/MiniProject/).

Configure includes/db_connect.php with your local database credentials.

4. AI API Server
Navigate to the AI engine directory in your terminal.

Install required Python libraries:

Bash
pip install flask flask-cors PyPDF2 pdfminer.six
Run the Flask server:

Bash
python app.py
5. Access the Portal
Start Apache and MySQL from the XAMPP Control Panel.

Open your browser and go to http://localhost/MiniProject/index.php.

📄 References & Research
This project is benchmarked against the research conducted by Radermacher et al. (2014) on "Investigating the Skill Gap between Graduating Students and Industry Expectations". By addressing core deficiencies in Configuration Management and Software Testing, SkillGap provides a quantifiable improvement in candidate readiness.