# backend/crew_setup.py

import os
from crewai import Agent, Task, Crew
from langchain_openai import ChatOpenAI
from dotenv import load_dotenv
from openai import OpenAI





load_dotenv()  # NEW
api_key = os.getenv("OPENAI_API_KEY")
if not api_key:
    raise RuntimeError("OPENAI_API_KEY missing in backend/.env")

client = OpenAI(api_key=api_key)

llm = ChatOpenAI(
    model="gpt-4o-mini",
    temperature=0.5,
    openai_api_key=api_key
)


# --- Agent definition ---
policy_agent = Agent(
    role="Resume Information Extractor",
    goal="Extract structured information from resumes and return valid JSON.",
    backstory=(
        "You are an expert assistant that reads resumes carefully "
        "and converts all relevant data (name, skills, education, experience, projects) "
        "into clean structured JSON without losing details."
    ),
    llm=llm,
    verbose=False
)


def create_extract_policy_task(resume_text: str):
    return Task(
        description=(
            f"You are given the following resume text:\n\n{resume_text}\n\n"
            "Your task is to fully and exhaustively extract all relevant information from this resume and convert it into "
            "structured JSON. The JSON should have the following fields:\n\n"
            "1. **Name**: The full name of the person.\n"
            "2. **About**: A short professional summary or profile.\n"
            "3. **Education**: A list of educational qualifications (degree, institution, year, etc.).\n"
            "4. **Skills**: A list of skills and technologies.\n"
            "5. **Experience**: A list of previous job experiences (title, company, duration, description).\n"
            "6. **Projects**: A list of projects with name, description, and technologies used.\n\n"
            "### EXTRACTION RULES ###\n"
            "- Each field must be extracted exactly as it appears in the text.\n"
            "- Preserve all bullet points or list items in arrays.\n"
            "- If a section is missing in the resume, include it as an empty array or empty string.\n"
            "- Output must be strictly valid JSON inside triple backticks.\n\n"
            "### Example JSON Output ###\n"
            "```json\n"
            "{\n"
            '  "Name": "John Doe",\n'
            '  "About": "Software engineer with 5 years of experience...",\n'
            '  "Education": [\n'
            '    {"Degree": "B.Sc. in Computer Science", "Institution": "ABC University", "Year": "2018"}\n'
            '  ],\n'
            '  "Skills": ["Python", "JavaScript", "SQL"],\n'
            '  "Experience": [\n'
            '    {"Title": "Software Engineer", "Company": "TechCorp", "Duration": "2018-2022", "Description": "Worked on backend systems..."}\n'
            '  ],\n'
            '  "Projects": [\n'
            '    {"Name": "Resume Parser", "Description": "Built a resume parsing tool", "Technologies": ["Python", "NLP"]}\n'
            '  ]\n'
            "}\n"
            "```"
        ),
        agent=policy_agent,
        expected_output="A valid JSON object inside triple backticks",
    )


#  Crew Factory Function
def create_crew(task):
    return Crew(
        agents=[policy_agent],
        tasks=[task],
        verbose=False
    )
