#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
AI-powered CV analysis script
Analyzes resumes and compares candidate skills with job requirements
"""

import sys
import json
import re
import os
from pathlib import Path

# Optional libraries - need to be installed
try:
    import pdfplumber
    PDF_AVAILABLE = True
except ImportError:
    PDF_AVAILABLE = False

try:
    from docx import Document
    DOCX_AVAILABLE = True
except ImportError:
    DOCX_AVAILABLE = False


def load_job_skills(file_path='job_skills.json'):
    """Load the job required skills file"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except FileNotFoundError:
        print(json.dumps({
            "error": "job_skills.json file not found",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    except json.JSONDecodeError:
        print(json.dumps({
            "error": "Error reading job_skills.json file",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)


def extract_text_from_pdf(pdf_path):
    """Extract text from a PDF file"""
    if not PDF_AVAILABLE:
        print(json.dumps({
            "error": "pdfplumber library is not installed. Install it using: pip install pdfplumber",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    try:
        text = ""
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                text += page.extract_text() or ""
        return text
    except Exception as e:
        print(json.dumps({
            "error": f"Error reading PDF file: {str(e)}",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)


def extract_text_from_docx(docx_path):
    """Extract text from a DOCX file"""
    if not DOCX_AVAILABLE:
        print(json.dumps({
            "error": "python-docx library is not installed. Install it using: pip install python-docx",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    try:
        doc = Document(docx_path)
        text = "\n".join([para.text for para in doc.paragraphs])
        return text
    except Exception as e:
        print(json.dumps({
            "error": f"Error reading DOCX file: {str(e)}",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)


def clean_text(text):
    """Clean text from symbols and numbers (numbers preserved)"""
    # Convert to lowercase first
    text = text.lower()
    # Remove multiple spaces
    text = re.sub(r'\s+', ' ', text)
    return text.strip()


def normalize_skill(skill):
    """
    Clean the skill from special symbols
    
    Args:
        skill: raw skill
    
    Returns:
        str: normalized skill
    """
    # Convert to lowercase
    skill = skill.lower().strip()
    # Remove trailing dots
    skill = skill.rstrip('.')
    # Replace multiple spaces with a single space
    skill = re.sub(r'\s+', ' ', skill)
    return skill


def extract_skills(text, required_skills):
    """
    Extract matching skills from the text
    
    Args:
        text: full text (already lowercase after clean_text)
        required_skills: list of required skills
    
    Returns:
        tuple: (matched_skills, missing_skills)
    """
    # Ensure text is lowercase
    text_lower = text.lower() if text == text.lower() else text.lower()
    
    matched = []
    missing = []
    
    for skill in required_skills:
        # Normalize skill
        skill_normalized = normalize_skill(skill)
        
        # Remove symbols from skill for search
        skill_for_search = re.sub(r'[^\w\s]', ' ', skill_normalized)
        skill_for_search = re.sub(r'\s+', ' ', skill_for_search).strip()
        
        # Search in multiple ways to ensure matching
        found = False
        
        # Method 1: normal search with escape
        try:
            pattern = r'\b' + re.escape(skill_for_search) + r'\b'
            if re.search(pattern, text_lower):
                found = True
        except:
            pass
        
        # Method 2: search without word boundaries (for symbolic skills)
        if not found:
            if skill_for_search in text_lower or skill_normalized in text_lower:
                found = True
        
        # Method 3: search without spaces (for skills like "React" vs "react")
        if not found:
            skill_no_spaces = re.sub(r'[\s\-_]', '', skill_for_search)
            if skill_no_spaces:
                # Search skill without symbols in the text
                text_no_spaces = re.sub(r'[\s\-_\.,;!?()\[\]{}]+', '', text_lower)
                if skill_no_spaces in text_no_spaces:
                    found = True
        
        if found:
            matched.append(skill)  # Add the original skill
        else:
            missing.append(skill)
    
    return matched, missing


def calculate_match_percentage(matched_count, total_skills):
    """Calculate match percentage"""
    if total_skills == 0:
        return 0
    return round((matched_count / total_skills) * 100, 2)


def analyze_cv(cv_path, job_title, skills_file='job_skills.json'):
    """
    Main function to analyze the resume
    
    Args:
        cv_path: path to the CV file
        job_title: job title
        skills_file: path to the skills file
    
    Returns:
        dict: analysis result in JSON format
    """
    # Load required skills
    job_skills_data = load_job_skills(skills_file)
    
    # Check if the job exists in the data
    if job_title not in job_skills_data:
        print(json.dumps({
            "error": f"Job title '{job_title}' does not exist in the database",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    required_skills = job_skills_data[job_title]
    
    # Check if file exists
    if not os.path.exists(cv_path):
        print(json.dumps({
            "error": f"File not found: {cv_path}",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    # Extract text based on file type
    file_extension = Path(cv_path).suffix.lower()
    
    if file_extension == '.pdf':
        text = extract_text_from_pdf(cv_path)
    elif file_extension == '.docx':
        text = extract_text_from_docx(cv_path)
    elif file_extension == '.txt' or file_extension == '':
        # Support plain text files for testing
        try:
            with open(cv_path, 'r', encoding='utf-8') as f:
                text = f.read()
        except:
            print(json.dumps({
                "error": "Error reading text file",
                "match": 0,
                "matched_skills": [],
                "missing_skills": []
            }, ensure_ascii=False))
            sys.exit(1)
    else:
        print(json.dumps({
            "error": f"Unsupported file type: {file_extension}. Only PDF, DOCX, and TXT are supported",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    # Clean text
    cleaned_text = clean_text(text)
    
    # Extract skills
    matched_skills, missing_skills = extract_skills(cleaned_text, required_skills)
    
    # Calculate match percentage
    total_skills = len(required_skills)
    matched_count = len(matched_skills)
    match_percentage = calculate_match_percentage(matched_count, total_skills)
    
    # Prepare result
    result = {
        "match": match_percentage,
        "matched_skills": matched_skills,
        "missing_skills": missing_skills,
        "total_required_skills": total_skills,
        "matched_count": matched_count
    }
    
    return result


def analyze_with_custom_skills(cv_path, required_skills_string):
    """
    Analyze CV with custom skills (from Laravel)
    
    Args:
        cv_path: path to the CV file
        required_skills_string: comma-separated skills string
    
    Returns:
        dict: analysis result
    """
    # Check if file exists
    if not os.path.exists(cv_path):
        print(json.dumps({
            "error": f"File not found: {cv_path}",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    # Extract text based on file type
    file_extension = Path(cv_path).suffix.lower()
    
    if file_extension == '.pdf':
        text = extract_text_from_pdf(cv_path)
    elif file_extension == '.docx':
        text = extract_text_from_docx(cv_path)
    elif file_extension == '.txt' or file_extension == '':
        # Support plain text files for testing
        try:
            with open(cv_path, 'r', encoding='utf-8') as f:
                text = f.read()
        except:
            print(json.dumps({
                "error": "Error reading text file",
                "match": 0,
                "matched_skills": [],
                "missing_skills": []
            }, ensure_ascii=False))
            sys.exit(1)
    else:
        print(json.dumps({
            "error": f"Unsupported file type: {file_extension}",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    # Clean text
    cleaned_text = clean_text(text)
    
    # Split skills from string
    required_skills = [skill.strip() for skill in required_skills_string.split(',')]
    
    # Extract skills
    matched_skills, missing_skills = extract_skills(cleaned_text, required_skills)
    
    # Calculate match percentage
    total_skills = len(required_skills)
    matched_count = len(matched_skills)
    match_percentage = calculate_match_percentage(matched_count, total_skills)
    
    # Prepare result
    result = {
        "match": match_percentage,
        "matched_skills": matched_skills,
        "missing_skills": missing_skills,
        "total_required_skills": total_skills,
        "matched_count": matched_count
    }
    
    return result


def main():
    """Main function"""
    # Check arguments count
    if len(sys.argv) < 3:
        print(json.dumps({
            "error": "Correct usage: python analyze_cv.py <path_to_cv> <job_title> [skills]",
            "match": 0,
            "matched_skills": [],
            "missing_skills": []
        }, ensure_ascii=False))
        sys.exit(1)
    
    cv_path = sys.argv[1]
    job_title = sys.argv[2]
    
    # If custom skills are provided (from Laravel)
    if len(sys.argv) >= 4 and sys.argv[3]:
        required_skills = sys.argv[3]
        result = analyze_with_custom_skills(cv_path, required_skills)
    else:
        # Use job_skills.json (legacy method)
        result = analyze_cv(cv_path, job_title)
    
    # Print result as JSON
    print(json.dumps(result, ensure_ascii=False, indent=2))


if __name__ == '__main__':
    main()
    
    