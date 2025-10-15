<!DOCTYPE html>
<html>
  <body>
    <h2>Upload Resume</h2>

    <form id="uploadForm" action="{{ route('tenant.onboarding.cv.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx" required>
      <button type="submit" id="uploadBtn">Upload</button>
    </form>
    
    @if ($errors->any())
      <pre style="color:red">{{ $errors->first('file') }}</pre>
    @endif

    <pre id="output"></pre>

    <script>
      const API_BASE = "http://127.0.0.1:8000";          // <-- backend
      const ENDPOINT = `${API_BASE}/upload_resume/`;     // trailing slash matches FastAPI route

      const btn = document.getElementById("uploadBtn");
      const out = document.getElementById("output");

      btn.onclick = async () => {
        const file = document.getElementById("fileInput").files[0];
        if (!file) { alert("Choose a file first"); return; }

        const fd = new FormData();
        fd.append("file", file); // name MUST be "file"

        btn.disabled = true; out.textContent = "Uploading...";
        try {
          const res = await fetch(ENDPOINT, { method: "POST", body: fd });
          const text = await res.text(); // handle non-JSON errors gracefully
          try {
            const json = JSON.parse(text);
            out.textContent = JSON.stringify(json, null, 2);
          } catch {
            out.textContent = text; // server might return plain text on error
          }
        } catch (e) {
          out.textContent = "Network error: " + e.message;
        } finally {
          btn.disabled = false;
        }
      };
    </script>
  </body>
</html>
