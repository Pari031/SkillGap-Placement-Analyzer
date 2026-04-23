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
    except Exception as e:
        print(f"Extraction Error: {e}")
        return ""

@app.route('/parse-resume', methods=['POST'])
def parse_resume():
    try:
        if 'file' not in request.files:
            return jsonify({"status": "error", "message": "No file part"}), 400
        
        file = request.files['file']
        
        defined_skills = request.form.getlist('skills') 
        if not defined_skills:
            # Default list if PHP doesn't send one
            defined_skills = ['python', 'sql', 'php', 'javascript', 'html', 'css', 'aws', 'docker', 'git']

        text = extract_text_from_pdf(file)
        
        if not text:
            return jsonify({"status": "error", "message": "Could not extract text"}), 500

        found_skills = [skill for skill in defined_skills if re.search(r'\b' + re.escape(skill.lower()) + r'\b', text)]
        
        return jsonify({
            "status": "success",
            "extracted_skills": list(set(found_skills))
        })
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

@app.route('/calculate_readiness', methods=['POST'])
def calculate_readiness():
    data = request.json
    mastered_skills = data.get('mastered_skills', [])
    required_skills = data.get('required_skills', [])
    cgpa = float(data.get('cgpa', 0))
    min_cgpa = float(data.get('min_cgpa', 0))

    if not required_skills:
        skill_score = 0
    else:
        matched_required = [s for s in mastered_skills if s in required_skills]
        skill_score = (len(matched_required) / len(required_skills)) * 70

    if cgpa < min_cgpa:
        cgpa_score = (cgpa / 10) * 15 
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
    # Running on 0.0.0.0 makes it easier to access locally
    app.run(host='0.0.0.0', port=5000, debug=True)