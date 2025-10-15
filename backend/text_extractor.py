# backend/text_extractor.py

import pdfplumber
import docx

def extract_text_from_file(file_path: str) -> str:
    """
    Extracts text content from a .pdf or .docx file.
    """
    if file_path.endswith(".pdf"):
        with pdfplumber.open(file_path) as pdf:
            all_text = ""
            for page in pdf.pages:
                text = page.extract_text()
                if text:
                    all_text += text + "\n"
            return all_text.strip()

    elif file_path.endswith(".docx"):
        doc = docx.Document(file_path)
        return "\n".join(p.text for p in doc.paragraphs).strip()

    else:
        raise ValueError("Unsupported file format. Only .pdf and .docx are allowed.")
