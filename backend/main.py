import os, re, json
from fastapi import FastAPI, UploadFile, File
from fastapi.middleware.cors import CORSMiddleware

from crew_setup import create_extract_policy_task, create_crew
from text_extractor import extract_text_from_file

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

for folder in ["resumes", "resume_txt", "info_json"]:
    os.makedirs(folder, exist_ok=True)

@app.get("/")
def root():
    return {"ok": True, "service": "cv-ai", "upload": "/upload_resume/"}

@app.get("/health")
def health():
    return {"status": "ok"}

@app.post("/upload_resume/")
async def upload_resume(file: UploadFile = File(...)):
    # save file
    path = os.path.join("resumes", file.filename)
    with open(path, "wb") as f:
        f.write(await file.read())

    # extract
    resume_text = extract_text_from_file(path)
    txt_path = os.path.join("resume_txt", file.filename.rsplit(".", 1)[0] + ".txt")
    with open(txt_path, "w", encoding="utf-8") as f:
        f.write(resume_text)

    # run crew
    task = create_extract_policy_task(resume_text)
    crew = create_crew(task)
    result = crew.kickoff()

    result_str = str(result)
    m = re.search(r"```(?:json)?(.*?)```", result_str, re.DOTALL)
    result_str = (m.group(1) if m else result_str).strip()

    # store json
    json_path = os.path.join("info_json", file.filename.rsplit(".", 1)[0] + ".json")
    with open(json_path, "w", encoding="utf-8") as f:
        f.write(result_str)

    # return
    return {"message": "Resume processed successfully.", "json_output": json.loads(result_str)}
