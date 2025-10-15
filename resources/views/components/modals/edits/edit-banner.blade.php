@props(['user'])

<x-modals.edits.base-modal id="editBannerModal" title="Edit Banner Image" size="xl">
  <form id="editBannerForm" action="{{ route('tenant.banner.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;gap:20px;">
      <!-- Preview -->
      <div style="position:relative;width:100%;height:240px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;overflow:hidden;border:1px solid var(--border);">
        <canvas id="bannerCanvas" style="position:absolute;inset:0;width:100%;height:100%;cursor:move;"></canvas>

        <!-- Controls (shown once an image exists) -->
        <div id="imageControls" style="position:absolute;left:0;right:0;bottom:0;background:rgba(0,0,0,.75);padding:16px;display:none;backdrop-filter:blur(8px);">
          <div style="display:flex;gap:16px;align-items:center;justify-content:center;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;color:white;">
              <span style="font-size:14px;opacity:.85;">Zoom</span>
              <input type="range" id="zoomSlider" min="10" max="200" value="100" style="width:160px;accent-color:#667eea;">
              <span id="zoomValue" style="font-size:14px;min-width:48px;text-align:right;">100%</span>
            </div>
            <button type="button" id="resetBtn" style="padding:6px 14px;background:#667eea;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
              Reset Position
            </button>
          </div>
        </div>

        <!-- Upload prompt -->
        <div id="uploadPrompt" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:white;">
          <div style="text-align:center;">
            <div style="font-weight:700;margin-bottom:6px;">Add a banner image</div>
            <div style="opacity:.85;">Click "Choose Photo" below to get started</div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <label for="bannerImageInput" style="padding:10px 20px;background:#667eea;color:white;border-radius:8px;cursor:pointer;font-weight:600;">
          Choose Photo
        </label>
        <input type="file" id="bannerImageInput" name="banner_image" accept="image/*" style="display:none;">
        <button type="button" id="deleteBtn" style="display:none;padding:10px 20px;background:#ef4444;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
          Delete Banner
        </button>
      </div>

      <!-- Advanced -->
      <div id="advancedControls" style="display:none;border-top:1px solid var(--border);padding-top:14px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;">
          <div>
            <label style="font-weight:600;font-size:14px;margin-bottom:6px;display:block;">Position</label>
            <select id="bannerPosition" name="banner_position" style="width:100%;height:40px;border:1px solid var(--border);border-radius:8px;padding:0 10px;">
              <option value="center center">Center</option>
              <option value="left center">Left</option>
              <option value="right center">Right</option>
              <option value="center top">Top</option>
              <option value="center bottom">Bottom</option>
            </select>
          </div>
          <div>
            <label style="font-weight:600;font-size:14px;margin-bottom:6px;display:block;">Fit</label>
            <select id="bannerFit" name="banner_fit" style="width:100%;height:40px;border:1px solid var(--border);border-radius:8px;padding:0 10px;">
              <option value="cover">Fill (Crop to fit)</option>
              <option value="contain">Fit (Show full image)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Hidden state -->
      <input type="hidden" id="bannerClear"   name="banner_clear"    value="0">
      <input type="hidden" id="bannerZoom"    name="banner_zoom"     value="100">
      <input type="hidden" id="bannerOffsetX" name="banner_offset_x" value="0">
      <input type="hidden" id="bannerOffsetY" name="banner_offset_y" value="0">
    </div>
  </form>

  @slot('footer')
    <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editBannerModal')">Cancel</button>
    <button type="submit" class="btn-modal btn-save" id="bannerSaveBtn" form="editBannerForm">
      <span id="saveBtnText">Save Changes</span>
    </button>
  @endslot

  <script>
  (() => {
    // ===== INITIAL VALUES FROM SERVER =====
    const initialUrl       = @json($user->banner_url ? ($user->banner_url.'?v='.$user->banner_version) : null);
    const initialFit       = @json($user->banner_fit ?? 'cover');
    const initialPosition  = @json($user->banner_position ?? 'center center');
    const initialZoom      = Number(@json($user->banner_zoom ?? 100));
    const initialOffsetX   = Number(@json($user->banner_offset_x ?? 0));
    const initialOffsetY   = Number(@json($user->banner_offset_y ?? 0));

    console.log('🖼️ Banner Initial Data:', {
      url: initialUrl,
      fit: initialFit,
      position: initialPosition,
      zoom: initialZoom,
      offsetX: initialOffsetX,
      offsetY: initialOffsetY
    });

    // ===== DOM =====
    const canvas         = document.getElementById('bannerCanvas');
    const ctx            = canvas.getContext('2d');
    const fileInput      = document.getElementById('bannerImageInput');
    const uploadPrompt   = document.getElementById('uploadPrompt');
    const imageControls  = document.getElementById('imageControls');
    const advancedCtrls  = document.getElementById('advancedControls');
    const deleteBtn      = document.getElementById('deleteBtn');
    const zoomSlider     = document.getElementById('zoomSlider');
    const zoomValue      = document.getElementById('zoomValue');
    const resetBtn       = document.getElementById('resetBtn');
    const bannerClear    = document.getElementById('bannerClear');
    const bannerZoom     = document.getElementById('bannerZoom');
    const bannerOffsetX  = document.getElementById('bannerOffsetX');
    const bannerOffsetY  = document.getElementById('bannerOffsetY');
    const fitSel         = document.getElementById('bannerFit');
    const posSel         = document.getElementById('bannerPosition');
    const form           = document.getElementById('editBannerForm');

    // ===== STATE =====
    let imgEl = null;
    let scale = Math.max(10, initialZoom) / 100;
    let offX  = initialOffsetX;
    let offY  = initialOffsetY;
    let modalInitialized = false;

    // Pre-fill controls
    zoomSlider.value = Math.round(scale * 100);
    zoomValue.textContent = `${zoomSlider.value}%`;
    bannerZoom.value = zoomSlider.value;
    bannerOffsetX.value = offX;
    bannerOffsetY.value = offY;
    fitSel.value = initialFit;
    posSel.value = initialPosition;

    // HiDPI crisp canvas
    function resizeCanvas() {
      const dpr = window.devicePixelRatio || 1;
      const rect = canvas.getBoundingClientRect();
      canvas.width = Math.round(rect.width * dpr);
      canvas.height = Math.round(rect.height * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      draw();
    }

    // Map "object-position" to anchor [0..1]
    function parseAnchor(pos) {
      const [hx, vy] = (pos || 'center center').split(' ');
      const mapX = { left:0, center:.5, right:1 };
      const mapY = { top:0, center:.5, bottom:1 };
      return [mapX[hx] ?? .5, mapY[vy] ?? .5];
    }

    // Draw current image with cover/contain + position + zoom + offsets
    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      if (!imgEl) return;

      const cw = canvas.clientWidth;
      const ch = canvas.clientHeight;
      const iw = imgEl.naturalWidth;
      const ih = imgEl.naturalHeight;
      
      if (!iw || !ih || !cw || !ch) return;

      const [ax, ay] = parseAnchor(fitSel.value === 'contain' ? 'center center' : posSel.value);

      const scaleBase = (fitSel.value === 'contain')
        ? Math.min(cw / iw, ch / ih)
        : Math.max(cw / iw, ch / ih);

      const finalScale = scaleBase * scale;
      const drawW = iw * finalScale;
      const drawH = ih * finalScale;

      const extraX = cw - drawW;
      const extraY = ch - drawH;
      const anchorX = extraX * ax;
      const anchorY = extraY * ay;

      const dx = anchorX + offX;
      const dy = anchorY + offY;

      ctx.drawImage(imgEl, dx, dy, drawW, drawH);
    }

    // Load an image src into canvas
    function loadImage(src) {
      if (!src) {
        console.log('❌ No image source provided');
        return;
      }
      
      console.log('📥 Loading banner image:', src);
      
      const image = new Image();
      image.crossOrigin = 'anonymous';
      
      image.onload = () => {
        console.log('✅ Banner image loaded successfully');
        imgEl = image;
        uploadPrompt.style.display = 'none';
        imageControls.style.display = 'block';
        advancedCtrls.style.display = 'block';
        deleteBtn.style.display = 'inline-block';
        resizeCanvas();
      };
      
      image.onerror = (e) => {
        console.error('❌ Failed to load banner image:', src, e);
      };
      
      image.src = src;
    }

    // Initialize modal when it opens
    function initializeModal() {
      if (modalInitialized) return;
      
      console.log('🚀 Initializing banner modal...');
      modalInitialized = true;
      
      // Resize canvas first
      resizeCanvas();
      
      // Load existing banner if available
      if (initialUrl) {
        setTimeout(() => {
          loadImage(initialUrl);
        }, 150);
      }
    }

    // Listen for modal open event
    window.addEventListener('modal:opened', function(e) {
      if (e.detail === 'editBannerModal') {
        console.log('🎯 Banner modal opened');
        initializeModal();
      }
    });

    // Also handle direct modal open (fallback)
    const modalElement = document.getElementById('editBannerModal');
    if (modalElement) {
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          if (mutation.attributeName === 'style') {
            const display = window.getComputedStyle(modalElement).display;
            if (display === 'flex' && !modalInitialized) {
              console.log('🎯 Banner modal detected as visible');
              initializeModal();
            }
          }
        });
      });
      observer.observe(modalElement, { attributes: true });
    }

    // Window resize handler
    window.addEventListener('resize', () => {
      if (modalInitialized) {
        resizeCanvas();
      }
    });

    // File chosen
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      
      console.log('📁 New file selected:', file.name);
      
      const reader = new FileReader();
      reader.onload = (ev) => {
        loadImage(ev.target.result);
        offX = 0; offY = 0; scale = 1;
        zoomSlider.value = 100; zoomValue.textContent = '100%';
        bannerZoom.value = 100; bannerOffsetX.value = 0; bannerOffsetY.value = 0;
        bannerClear.value = '0';
      };
      reader.readAsDataURL(file);
    });

    // Drag to move
    let dragging = false, sx = 0, sy = 0;
    canvas.addEventListener('mousedown', (e) => {
      if (!imgEl) return;
      dragging = true;
      sx = e.clientX - offX;
      sy = e.clientY - offY;
      canvas.style.cursor = 'grabbing';
    });
    
    window.addEventListener('mousemove', (e) => {
      if (!dragging) return;
      offX = e.clientX - sx;
      offY = e.clientY - sy;
      bannerOffsetX.value = Math.round(offX);
      bannerOffsetY.value = Math.round(offY);
      draw();
    });
    
    window.addEventListener('mouseup', () => {
      dragging = false;
      canvas.style.cursor = 'move';
    });

    // Zoom slider
    zoomSlider.addEventListener('input', () => {
      scale = Math.max(0.1, Number(zoomSlider.value) / 100);
      zoomValue.textContent = `${zoomSlider.value}%`;
      bannerZoom.value = zoomSlider.value;
      draw();
    });

    // Fit/Position changes
    fitSel.addEventListener('change', draw);
    posSel.addEventListener('change', draw);

    // Reset
    resetBtn.addEventListener('click', () => {
      offX = 0; offY = 0; scale = 1;
      bannerOffsetX.value = 0; bannerOffsetY.value = 0; bannerZoom.value = 100;
      zoomSlider.value = 100; zoomValue.textContent = '100%';
      draw();
    });

    // Delete
    deleteBtn.addEventListener('click', () => {
      if (!confirm('Delete banner image?')) return;
      imgEl = null;
      bannerClear.value = '1';
      fileInput.value = '';
      uploadPrompt.style.display = 'flex';
      imageControls.style.display = 'none';
      advancedCtrls.style.display = 'none';
      deleteBtn.style.display = 'none';
      resizeCanvas();
    });

    // Submit
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('bannerSaveBtn');
      const btnText = document.getElementById('saveBtnText');
      btn.disabled = true; btnText.textContent = 'Saving...';

      const fd = new FormData(form);
      try {
        const res = await fetch(form.action, { 
          method: 'POST', 
          headers: { 'X-Requested-With': 'XMLHttpRequest' }, 
          body: fd 
        });
        
        if (!res.ok) throw new Error('HTTP ' + res.status);
        
        const data = await res.json();
        
        if (data?.success) {
          const hero = document.getElementById('heroBannerImage');
          if (hero && data.url) {
            hero.src = `${data.url}?v=${Date.now()}`;
            hero.style.objectFit = data.fit || 'cover';
            hero.style.objectPosition = data.position || 'center center';
            hero.style.transform = `scale(${(data.zoom||100)/100}) translate(${data.offset_x||0}px, ${data.offset_y||0}px)`;
          }
          closeModal('editBannerModal');
          return;
        }
        form.submit();
      } catch (err) {
        console.error('❌ Save error:', err);
        form.submit();
      } finally {
        btn.disabled = false; btnText.textContent = 'Save Changes';
      }
    });
  })();
  </script>
</x-modals.edits.base-modal>