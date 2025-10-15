<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CvAiClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Throwable;

class CvUploadController extends Controller
{
    public function __construct(protected CvAiClient $ai) {}

    public function uploadJson(Request $request)
    {
        // PHP often dies at 30s by default; extend for this heavy call
        @set_time_limit(180);

        $data = $request->validate([
            'file' => ['required','file','mimes:pdf,doc,docx','max:8192'],
        ]);

        $file = $data['file'];
        $path = $file->store('cv_uploads', 'local');

        try {
            $parsed = $this->ai->parseResume($file);

            Storage::disk('local')->put(
                'cv_json/'.pathinfo($path, PATHINFO_FILENAME).'.json',
                json_encode($parsed, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            );

            $request->session()->put('cv_output', $parsed);

            return response()->json([
                'ok'       => true,
                'redirect' => route('tenant.onboarding.cv.output'),
            ]);
        } catch (Throwable $e) {
            report($e); // also see storage/logs/laravel.log
            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage() ?: 'Could not process your CV.',
            ], 422);
        }
    }

    /**
     * Display the parsed CV output for review/editing.
     */
    public function output(Request $request)
    {
        $raw = $request->session()->get('cv_output');

        if (!$raw || !is_array($raw)) {
            return redirect()->route('tenant.onboarding.welcome')
                ->with('status', 'Please upload a CV first.');
        }

        // Normalizer helper: build a lowercase-key map for easy lookup
        $normalizeItem = function ($item) {
            if (!is_array($item)) return $item;

            $map = [];
            foreach ($item as $k => $v) {
                $map[strtolower((string) $k)] = $v;
            }
            return $map;
        };

        // Map any experience-like item into a consistent shape
        $normalizeExperience = function ($item) use ($normalizeItem) {
            $m = $normalizeItem($item);

            // Accept many variants for keys
            $title = $m['title'] ?? $m['job_title'] ?? $m['position'] ?? $m['role'] ?? $m['name'] ?? null;
            $company = $m['company'] ?? $m['employer'] ?? null;
            $duration = $m['duration'] ?? $m['period'] ?? $m['year'] ?? null;
            $desc = $m['description'] ?? $m['details'] ?? $m['bullets'] ?? null;

            // If description is a string, keep it. If array => keep array. If newline separated string, explode.
            if (is_string($desc) && preg_match('/\n/', $desc)) {
                $desc = array_filter(array_map('trim', preg_split("/\r?\n/", $desc)));
            }

            // If description is not array or string, try to cast it to array of strings
            if (is_array($desc)) {
                // normalize inner items to strings
                $desc = array_values(array_map(function($d){
                    if (is_string($d)) return trim($d);
                    if (is_array($d)) return trim(implode(' ', array_map('strval', $d)));
                    return trim((string) $d);
                }, $desc));
            }

            return [
                'title' => $title ? trim($title) : null,
                'company' => $company ? trim($company) : null,
                'duration' => $duration ? trim($duration) : null,
                'description' => $desc,
            ];
        };

        $normalizeEducation = function ($item) use ($normalizeItem) {
            $m = $normalizeItem($item);
            $degree = $m['degree'] ?? $m['qualification'] ?? $m['course'] ?? null;
            $institution = $m['institution'] ?? $m['school'] ?? $m['college'] ?? $m['university'] ?? null;
            $year = $m['year'] ?? $m['duration'] ?? $m['graduation'] ?? null;
            return [
                'degree' => $degree ? trim($degree) : null,
                'institution' => $institution ? trim($institution) : null,
                'year' => $year ? trim($year) : null,
            ];
        };

        $normalizeProject = function ($item) use ($normalizeItem) {
            $m = $normalizeItem($item);
            $name = $m['name'] ?? $m['title'] ?? null;
            $description = $m['description'] ?? $m['summary'] ?? null;
            $technologies = $m['technologies'] ?? $m['tech'] ?? $m['tools'] ?? null;

            if (is_string($technologies)) {
                // try comma-separated list
                $technologies = array_map('trim', preg_split('/[,;|]/', $technologies));
            }
            if (!is_array($technologies)) {
                $technologies = $technologies ? [$technologies] : [];
            }
            $technologies = array_values(array_filter(array_map('strval', $technologies)));

            return [
                'name' => $name ? trim($name) : null,
                'description' => $description ? (is_string($description) ? trim($description) : json_encode($description)) : null,
                'technologies' => $technologies,
            ];
        };

        // Normalize top-level keys robustly (case-insensitive)
        $top = [];
        foreach ($raw as $k => $v) {
            $top[strtolower((string) $k)] = $v;
        }

        // Pull top-level fields with common names
        $name = $top['name'] ?? $top['fullname'] ?? $top['full_name'] ?? $top['candidate'] ?? $top['person'] ?? null;
        $about = $top['about'] ?? $top['summary'] ?? $top['profile'] ?? $top['bio'] ?? null;

        $rawSkills = $top['skills'] ?? $top['skillset'] ?? $top['technologies'] ?? [];
        if (!is_array($rawSkills)) {
            if (is_string($rawSkills)) {
                $rawSkills = array_map('trim', preg_split('/[,;|]/', $rawSkills));
            } else {
                $rawSkills = [$rawSkills];
            }
        }

        // Experiences
        $rawExperience = $top['experience'] ?? $top['work_experience'] ?? $top['work'] ?? [];
        if (!is_array($rawExperience) || Arr::isAssoc($rawExperience)) {
            // sometimes the AI returns an object instead of list, convert to list
            $rawExperience = is_array($rawExperience) ? array_values($rawExperience) : [];
        }
        $experience = array_map($normalizeExperience, $rawExperience);

        // Education
        $rawEducation = $top['education'] ?? $top['qualifications'] ?? [];
        if (!is_array($rawEducation) || Arr::isAssoc($rawEducation)) {
            $rawEducation = is_array($rawEducation) ? array_values($rawEducation) : [];
        }
        $education = array_map($normalizeEducation, $rawEducation);

        // Projects
        $rawProjects = $top['projects'] ?? $top['project'] ?? [];
        if (!is_array($rawProjects) || Arr::isAssoc($rawProjects)) {
            $rawProjects = is_array($rawProjects) ? array_values($rawProjects) : [];
        }
        $projects = array_map($normalizeProject, $rawProjects);

        // Languages
        $rawLanguages = $top['languages'] ?? $top['language'] ?? [];
        if (!is_array($rawLanguages)) {
            if (is_string($rawLanguages)) {
                $rawLanguages = array_map('trim', preg_split('/[,;|]/', $rawLanguages));
            } else {
                $rawLanguages = [$rawLanguages];
            }
        }

        // Final normalized array sent to blade
        $cv = [
            'Name' => is_string($name) ? trim($name) : ($raw['Name'] ?? null),
            'About' => is_string($about) ? trim($about) : ($raw['About'] ?? null),
            'Skills' => array_values(array_filter(array_map(function($s){
                if (is_array($s)) {
                    // if AI returns object like { "name": "PHP" }
                    $smap = array_change_key_case($s, CASE_LOWER);
                    return $smap['name'] ?? $smap['skill'] ?? json_encode($s);
                }
                return trim((string)$s);
            }, $rawSkills))),
            'Experience' => array_values(array_filter($experience, function($i){
                return !empty($i['title']) || !empty($i['company']) || !empty($i['description']);
            })),
            'Education' => array_values(array_filter($education, function($e){
                return !empty($e['degree']) || !empty($e['institution']);
            })),
            'Projects' => array_values(array_filter($projects, function($p){
                return !empty($p['name']) || !empty($p['description']);
            })),
            'Languages' => array_values(array_filter(array_map('strval', $rawLanguages))),
        ];

        // Save normalized for debugging (optional)
        // Storage::disk('local')->put('cv_json/normalized_'.(time()).'.json', json_encode($cv, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        return view('tenant.onboarding.cv-output', compact('cv'));
    }
}
