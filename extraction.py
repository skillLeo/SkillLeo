
import json
import re
import os
from dotenv import load_dotenv
from crewai import Agent, Task, Crew
from langchain_openai import ChatOpenAI
from openai import OpenAI

openai.api_key = os.getenv("OPENAI_API_KEY")


client = OpenAI(api_key=os.environ["OPENAI_API_KEY"])

llm = ChatOpenAI(
    model="gpt-4o-mini",
    temperature=0.5,
    openai_api_key=os.environ["OPENAI_API_KEY"]
)

with open("resume.txt", "r", encoding="utf-8") as f:
    resume_text = f.read()
    

policy_agent = Agent(
    role="Medical Policy Structuring Assistant",
    goal="Convert unstructured medical policy text into structured JSON",
    backstory=(
        "You are an expert at reading long policy documents and converting them "
        "into clean structured JSON. You preserve all details, including nested "
        "requirements, without summarizing or omitting information."
    ),
    llm=llm,
    verbose=False
)

extract_policy = Task(
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
    expected_output="Strictly valid and pretty-formatted JSON inside triple backticks"
)

crew = Crew(
    agents=[policy_agent],
    tasks=[extract_policy],
    verbose=False
)

result = crew.kickoff()

# Extract JSON
result_str = str(result)
match = re.search(r"```(?:json)?(.*?)```", result_str, re.DOTALL)
if match:
    result_str = match.group(1).strip()

try:
    data = json.loads(result_str)
    with open("info.json", "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
    print("Saved structured JSON in pretty format")
except Exception as e:
    print("JSON parsing failed, saving raw output:", e)
    with open("info.json", "w", encoding="utf-8") as f:
        f.write(result_str)
