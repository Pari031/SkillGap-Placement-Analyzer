from flask import Flask, request, jsonify
from flask_cors import CORS 
import PyPDF2
import re

app = Flask(__name__)
CORS(app)

def extract_text_from_pdf(file):
    try:
        reader = PyPDF2.PdfReader(file)
        text = ""
        for page in reader.pages:
            text += page.extract_text()
        return text.lower()
    except:
        return ""

@app.route('/parse_resume', methods=['POST'])
def parse_resume():
    file = request.files['resume']
    defined_skills = request.form.getlist('skills')
    text = extract_text_from_pdf(file)
    found_skills = [skill for skill in defined_skills if re.search(r'\b' + re.escape(skill.lower()) + r'\b', text)]
    return jsonify({"extracted_skills": list(set(found_skills))})

@app.route('/calculate_readiness', methods=['POST'])
def calculate_readiness():
    data = request.json
    mastered_skills = data.get('mastered_skills', [])
    required_skills = data.get('required_skills', [])
    cgpa = float(data.get('cgpa', 0))
    min_cgpa = float(data.get('min_cgpa', 0))

    # 1. Skill Score (70% weight)
    # Simple matching: (Count of mastered required skills / Total required skills)
    if not required_skills:
        skill_score = 0
    else:
        matched_required = [s for s in mastered_skills if s in required_skills]
        skill_score = (len(matched_required) / len(required_skills)) * 70

    # 2. CGPA Score (30% weight)
    # If CGPA < min_cgpa, it heavily penalizes, otherwise it scales
    if cgpa < min_cgpa:
        cgpa_score = (cgpa / 10) * 15 # Penalized
    else:
        cgpa_score = (cgpa / 10) * 30

    final_score = round(skill_score + cgpa_score, 2)
    
    return jsonify({
        "readiness_score": final_score,
        "breakdown": {
            "skill_contribution": round(skill_score, 2),
            "cgpa_contribution": round(cgpa_score, 2)
        }
    })

if __name__ == '__main__':
    app.run(port=5000, debug=True)