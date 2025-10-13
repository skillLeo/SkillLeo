@props(['skills' => [], 'softSkills' => []])

<x-modals.base-modal id="seeAllSkillsModal" title="Skills" size="lg">
    @php
        // Helpers to normalize & label levels
        $percentFrom = function ($item) {
            if (!is_array($item)) return null;

            // Prefer 'percentage' if present (0..100)
            if (isset($item['percentage']) && is_numeric($item['percentage'])) {
                $p = (float) $item['percentage'];
                return max(0, min(100, $p));
            }

            // Fallback: numeric 'level' (e.g., 0..100 or 1..10)
            if (isset($item['level']) && is_numeric($item['level'])) {
                $v = (float) $item['level'];
                // if looks like 1..10, upscale to percentage
                if ($v <= 10) $v = $v * 10;
                return max(0, min(100, $v));
            }

            return null;
        };

        $levelWordFrom = function ($item) use ($percentFrom) {
            // If a textual level exists, use it directly.
            if (is_array($item) && isset($item['level']) && !is_numeric($item['level'])) {
                return trim((string) $item['level']);
            }

            $pct = $percentFrom($item);
            if ($pct === null) return null;

            // Map percentage → word level
            if ($pct >= 95) return 'Expert';
            if ($pct >= 85) return 'Professional';
            if ($pct >= 70) return 'Advanced';
            if ($pct >= 50) return 'Intermediate';
            if ($pct >= 30) return 'Beginner';
            return 'Novice';
        };

        // Normalize technical skills → [name, levelWord, pct|null]
        $tech = collect($skills ?? [])->map(function ($s) use ($percentFrom, $levelWordFrom) {
            $name = is_array($s) ? ($s['name'] ?? (string) $s) : (string) $s;
            $pct  = $percentFrom($s);
            $lvl  = $levelWordFrom($s);
            return ['name' => trim($name), 'level' => $lvl, 'pct' => $pct];
        })->filter(fn ($r) => $r['name'] !== '');

        // Sort tech skills by pct desc when available (keeps original order if not)
        $tech = $tech->sortByDesc(function ($r) { return $r['pct'] ?? -1; })->values();

        // Normalize soft skills → names only
        $soft = collect($softSkills ?? [])->map(function ($s) {
            return is_array($s) ? ($s['name'] ?? (string) $s) : (string) $s;
        })->filter(fn ($s) => trim($s) !== '')->values();
    @endphp

    <div class="skills-grid-inline">
        {{-- Left: Technical Skills --}}
        <div class="pane">
            <h3 class="pane-title">Technical Skills</h3>
            @if($tech->isNotEmpty())
                <ul class="tech-list">
                    @foreach($tech as $row)
                        <li class="tech-line">
                            <span class="tech-name">{{ $row['name'] }}</span>
                            @if($row['level'])
                                <span class="dash">—</span>
                                <span class="tech-level">{{ $row['level'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty-mini">No technical skills added yet.</div>
            @endif
        </div>

        {{-- Right: Soft Skills --}}
        <div class="pane">
            <h3 class="pane-title">Soft Skills</h3>
            @if($soft->isNotEmpty())
                <ul class="soft-list">
                    @foreach($soft as $name)
                        <li class="soft-line">
                            <svg class="check" width="18" height="18" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="soft-name">{{ $name }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty-mini">No soft skills added yet.</div>
            @endif
        </div>
    </div>
</x-modals.base-modal>

<style>
/* ===== Grid (Horizontal Left / Right) ===== */
.skills-grid-inline{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:24px;
    padding: 8px 8px 16px;
}
@media (max-width: 900px){
    .skills-grid-inline{ grid-template-columns: 1fr; }
}

/* ===== Pane ===== */
.pane{
    background: var(--card, #fff);
    border: 1px solid var(--border, #e6e8eb);
    border-radius: 12px;
    padding: 18px 20px;
}
.pane-title{
    margin: 0 0 12px;
    font-size: 15px;
    font-weight: 700;
    color: var(--text-heading, #111827);
    letter-spacing: .2px;
}

/* ===== Technical list (PHP — Professional) ===== */
.tech-list{
    list-style: none; margin: 0; padding: 0;
    display: flex; flex-direction: column; gap: 6px;
}
.tech-line{
    display: flex; align-items: baseline; gap: 8px;
    padding: 10px 0;
    border-bottom: 1px dashed var(--border, #e6e8eb);
}
.tech-line:last-child{ border-bottom: none; }

.tech-name{
    font-size: 14px; font-weight: 600; color: var(--text-body, #111827);
    max-width: 68%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.dash{ color: var(--text-muted, #6b7280); }
.tech-level{
    font-size: 14px; font-weight: 600; color: var(--accent, #0a66c2);
}

/* ===== Soft list ===== */
.soft-list{
    list-style: none; margin: 0; padding: 0;
    display: grid; grid-template-columns: 1fr; gap: 0;
}
@media (min-width: 520px){
    .soft-list{ grid-template-columns: 1fr 1fr; gap: 4px 14px; }
}
.soft-line{
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0;
    border-bottom: 1px dashed var(--border, #e6e8eb);
}
.soft-line:nth-child(2n){ border-bottom: 1px dashed var(--border, #e6e8eb); }
.soft-line:last-child{ border-bottom: none; }
.check{ color: #10b981; flex-shrink: 0; }
.soft-name{ font-size: 14px; font-weight: 500; color: var(--text-body, #111827); }

/* ===== Empty ===== */
.empty-mini{
    font-size: 13px; color: var(--text-muted, #6b7280);
    background: var(--apc-bg, #f7f8f9);
    border: 1px dashed var(--border, #e6e8eb);
    border-radius: 10px;
    padding: 12px;
    text-align:center;
}
</style>
