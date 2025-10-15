<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\ProfilePage;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Skill;
use App\Models\Review;
use App\Models\Education;
use App\Models\Portfolio;
use App\Models\SoftSkill;
use App\Models\Experience;
use App\Models\UserReason;
use App\Models\UserProfile;
use App\Models\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use App\Models\UserSoftSkill;
use Illuminate\Routing\Route;
use App\Models\ExperienceSkill;
use Illuminate\Validation\Rule;
use App\Services\TimezoneService;
use App\Support\ProfileVisibility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\ViewModels\ProfileViewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Profile\ProfileService;
use App\Services\Auth\OnlineStatusService;
use App\Http\Requests\Tenant\UpdateProfileRequest;


use Intervention\Image\Laravel\Facades\Image;





class ProfileController extends Controller
{


    public function __construct(
        private readonly ProfileService $service,
        protected OnlineStatusService $onlineStatus

    ) {}









    public function updateBanner(Request $request)
    {
        $user = Auth::user();

        /** @var \App\Models\UserProfile $profile */
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        $validated = $request->validate([
            'banner_image'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:8192'],
            'banner_clear'     => ['nullable', 'boolean'],
            'banner_fit'       => ['nullable', Rule::in(['contain', 'cover'])],
            'banner_position'  => ['nullable', Rule::in([
                'center center',
                'left center',
                'right center',
                'center top',
                'center bottom'
            ])],
            'banner_zoom'      => ['nullable', 'integer', 'min:10', 'max:200'],
            'banner_offset_x'  => ['nullable', 'numeric'],
            'banner_offset_y'  => ['nullable', 'numeric'],
        ]);

        $wantsJson = $request->ajax() || $request->wantsJson();

        // merge prefs (only non-null keys)
        $prefs = $profile->banner_preference ?? [];
        $prefs = array_merge($prefs, array_filter([
            'fit'       => $validated['banner_fit']       ?? null,
            'position'  => $validated['banner_position']  ?? null,
            'zoom'      => isset($validated['banner_zoom'])     ? (int)$validated['banner_zoom']     : null,
            'offset_x'  => isset($validated['banner_offset_x']) ? (float)$validated['banner_offset_x'] : null,
            'offset_y'  => isset($validated['banner_offset_y']) ? (float)$validated['banner_offset_y'] : null,
        ], fn($v) => !is_null($v)));

        $clearing = (bool) ($validated['banner_clear'] ?? false);
        $uploaded = $request->file('banner_image');

        // remove old file helper
        $deleteOldIfLocal = function (?string $path) {
            if (!$path) return;
            $possible = $path;
            $prefix = '/storage/';
            if (str_contains($possible, $prefix)) {
                $possible = ltrim(substr($possible, strpos($possible, $prefix) + strlen($prefix)), '/');
            }
            try {
                if (Storage::disk('public')->exists($possible)) {
                    Storage::disk('public')->delete($possible);
                }
            } catch (\Throwable $e) {
                \Log::warning('Failed to delete old banner: ' . $e->getMessage());
            }
        };

        if ($uploaded) {
            // 1) write to public disk (no image lib — least moving parts)
            $ext      = strtolower($uploaded->getClientOriginalExtension() ?: 'png');
            $dir      = 'banners/' . $user->id;
            $filename = 'banner_' . $user->id . '_' . time() . '.' . $ext;

            Storage::disk('public')->makeDirectory($dir);
            Storage::disk('public')->putFileAs($dir, $uploaded, $filename);

            $newPath = $dir . '/' . $filename;

            // 2) swap files
            $deleteOldIfLocal($profile->banner);
            $profile->banner = $newPath;

            // sensible defaults if newly set
            $prefs['fit']      = $prefs['fit']      ?? 'cover';
            $prefs['position'] = $prefs['position'] ?? 'center center';
            $prefs['zoom']     = $prefs['zoom']     ?? 100;
            $prefs['offset_x'] = $prefs['offset_x'] ?? 0;
            $prefs['offset_y'] = $prefs['offset_y'] ?? 0;
        } else {
            if ($clearing) {
                $deleteOldIfLocal($profile->banner);
                $profile->banner = null;
                $prefs = [
                    'fit'      => 'cover',
                    'position' => 'center center',
                    'zoom'     => 100,
                    'offset_x' => 0,
                    'offset_y' => 0,
                ];
            } else {
                // prefs only update
                $prefs['fit']      = $prefs['fit']      ?? ($profile->banner_preference['fit']      ?? 'cover');
                $prefs['position'] = $prefs['position'] ?? ($profile->banner_preference['position'] ?? 'center center');
                $prefs['zoom']     = $prefs['zoom']     ?? (int)($profile->banner_preference['zoom']     ?? 100);
                $prefs['offset_x'] = $prefs['offset_x'] ?? (float)($profile->banner_preference['offset_x'] ?? 0);
                $prefs['offset_y'] = $prefs['offset_y'] ?? (float)($profile->banner_preference['offset_y'] ?? 0);
            }
        }

        $profile->banner_preference = $prefs;
        $profile->save();
        $profile->refresh();

        $publicUrl = $profile->banner ? Storage::disk('public')->url($profile->banner) : null;

        if ($wantsJson) {
            return response()->json([
                'success'  => true,
                'url'      => $publicUrl,
                'fit'      => $prefs['fit'] ?? 'cover',
                'position' => $prefs['position'] ?? 'center center',
                'zoom'     => (int)($prefs['zoom'] ?? 100),
                'offset_x' => (float)($prefs['offset_x'] ?? 0),
                'offset_y' => (float)($prefs['offset_y'] ?? 0),
            ]);
        }

        return back()->with('status', 'Banner updated successfully.');
    }












































    public function index(Request $request, string $username)
    {
        /** @var \App\Models\User $owner */
        $owner = User::query()
            ->with([
                'profile',
                'skills'      => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_skills.position'),
                'softSkills'  => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_soft_skills.position'),
                'languages'   => fn($q) => $q->orderBy('position'),
                'educations'  => fn($q) => $q->orderBy('position'),
                'experiences' => fn($q) => $q->orderBy('position')->with('skills'),
                'portfolios'  => fn($q) => $q->orderBy('position'),
                'services'    => fn($q) => $q->orderBy('position'),
                'reasons'     => fn($q) => $q->orderBy('position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        // ===== Basic user view-model =====
        $titleCase = static fn($s) => $s !== '' ? \Illuminate\Support\Str::of($s)->squish()->title()->toString() : '';
        $first = $owner->name ?? '';
        $last  = $owner->last_name ?? '';
        $full  = trim($titleCase($first) . ' ' . $titleCase($last)) ?: ($owner->username ?? 'User');
    
        $p = $owner->profile; // short alias to profile
        $social   = is_array($p?->social_links) ? $p->social_links : [];
        $avatar   = $owner->avatar_url ?: ('https://ui-avatars.com/api/?name=' . urlencode($full) . '&size=200&background=random');
        $location = collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ');
    
        $user = (object) [
            'first_name'     => $titleCase($first),
            'last_name'      => $titleCase($last),
            'name'           => $full,
            'email'          => $owner->email,
            'headline'       => (string) ($p?->tagline ?? ''),
            'bio'            => (string) ($p?->bio ?? ''),
            'about'          => (string) ($p?->bio ?? ''),
            'location'       => $location ?: null,
            'city'           => $p?->city,
            'state'          => $p?->state,
            'country'        => $p?->country,
            'phone'          => $p?->phone,
            'avatar'         => $avatar,
            'avatar_url'     => $avatar,
            'facebook'       => $social['facebook'] ?? null,
            'instagram'      => $social['instagram'] ?? null,
            'twitter'        => $social['twitter'] ?? null,
            'linkedin'       => $social['linkedin'] ?? null,
            'is_online'      => (bool) ($owner->is_online ?? false),
            'last_seen_at'   => $owner->last_seen_at,
            'last_seen_text' => $owner->last_seen_text ?? null,
            'open_to_work'   => false,
        ];
    
        $user->services    = $owner->services->sortBy('position')->pluck('title')->values()->all();
        $user->whyChooseMe = $owner->reasons->sortBy('position')->pluck('text')->values()->all();
    
        // ===== Skills summary =====
        $skillsData = $owner->skills
            ->sortBy('pivot.position')
            ->map(fn($s) => [
                'name'       => (string) $s->name,
                'percentage' => (int) match ((int)($s->pivot->level ?? 1)) { 3 => 95, 2 => 82, default => 68 },
            ])->values()->all();
    
        $skillNames = $owner->skills->sortBy('pivot.position')->pluck('name')->take(8)->values()->all();
    
        // ===== Experiences list =====
        $experiences = $owner->experiences->map(function ($e) {
            $period = '';
            if ($e->start_month && $e->start_year) {
                $from = \Carbon\Carbon::createFromDate($e->start_year, $e->start_month, 1)->format('M Y');
                $to   = $e->is_current ? 'Present'
                    : (($e->end_month && $e->end_year)
                        ? \Carbon\Carbon::createFromDate($e->end_year, $e->end_month, 1)->format('M Y')
                        : '');
                $period = $to ? "$from — $to" : $from;
            }
            return [
                'company'     => (string) $e->company,
                'title'       => (string) $e->title,
                'description' => (string) ($e->description ?? ''),
                'location'    => collect([$e->location_city, $e->location_country])->filter()->join(', '),
                'period'      => $period,
                'current'     => (bool) $e->is_current,
                'skills'      => collect($e->skills ?? [])->sortBy('position')->map(fn($s) => [
                    'name'  => (string) $s->name,
                    'level' => (int) ($s->level ?? 1),
                ])->values()->all(),
            ];
        })->values()->all();
    
        // ===== Filters: only skills used in projects =====
        $usedSkillIds = $owner->portfolios
            ->flatMap(function ($p) {
                $meta = is_array($p->meta) ? $p->meta : [];
                return $meta['skill_ids'] ?? [];
            })
            ->filter()->map(fn($id) => (int) $id)->unique()->values();
    
        $userSkillsForFilters = $owner->skills
            ->whereIn('id', $usedSkillIds)
            ->map(fn($s) => [
                'id'   => (int) $s->id,
                'name' => (string) $s->name,
                'slug' => \Illuminate\Support\Str::slug($s->name),
            ])
            ->unique('slug')->values()->all();
    
        // ===== Portfolios (respect sort preference) =====
        $filterPreferences = is_array($p?->filter_preferences) ? $p->filter_preferences : [];
        $sortOrder = $filterPreferences['sort_order'] ?? 'position';
    
        $portfoliosQuery = $owner->portfolios();
        $sortOrder === 'newest'
            ? $portfoliosQuery->orderBy('created_at', 'desc')
            : $portfoliosQuery->orderBy('position', 'asc');
    
        $portfolios = $portfoliosQuery->get()->map(function ($p) use ($userSkillsForFilters) {
            $meta  = is_array($p->meta) ? $p->meta : [];
            $image = $p->image_url ?: ($p->image_path ? asset('storage/' . ltrim($p->image_path, '/')) : null);
            $skillIds = $meta['skill_ids'] ?? [];
    
            $skillSlugs = collect($userSkillsForFilters)
                ->filter(fn($s) => in_array($s['id'], $skillIds))
                ->pluck('slug')
                ->all();
    
            return [
                'id'          => $p->id,
                'title'       => (string) $p->title,
                'description' => (string) ($p->description ?? ''),
                'link'        => $p->link_url ?: null,
                'image'       => $image,
                'meta'        => $meta,
                'skill_slugs' => $skillSlugs,
                'created_at'  => $p->created_at,
                'position'    => $p->position,
            ];
        })->values()->all();
    
        $categories = array_values(array_unique(array_filter([
            'All',
            ...collect($portfolios)->pluck('category')->filter()->all(),
        ])));
    
        // ===== Education =====
        $education = $owner->educations->map(function ($e) {
            $from = $e->start_year ?: '—';
            $to   = $e->is_current ? 'Present' : ($e->end_year ?: '—');
            return [
                'title'       => trim(collect([$e->degree, $e->field])->filter()->join(' · ')) ?: 'Education',
                'institution' => (string) ($e->school ?? ''),
                'period'      => "$from - $to",
                'location'    => collect([$e->city, $e->country])->filter()->join(', '),
                'recent'      => (bool) $e->is_current,
            ];
        })->values()->all();
    
        // ===== Modal data =====
        $modalSkills = $owner->skills->values()->map(function ($s, $i) {
            return [
                'id'       => (int) $s->id,
                'name'     => (string) $s->name,
                'level'    => (int) ($s->pivot->level ?? 2),
                'position' => (int) ($s->pivot->position ?? $i),
            ];
        })->all();
    
        $softSkillOptions = SoftSkill::query()
            ->orderBy('name')
            ->get(['slug', 'name', 'icon'])
            ->map(fn($s) => ['value' => $s->slug, 'label' => $s->name, 'icon' => $s->icon ?: 'sparkles'])
            ->values()->all();
    
        $selectedSoft = $owner->softSkills->pluck('slug')->values()->all();
    
        $user->softSkills = $owner->softSkills
            ->sortBy('pivot.position')
            ->map(fn($s) => [
                'name'     => $s->name,
                'slug'     => $s->slug,
                'icon'     => $s->icon ?: 'lightbulb',
                'level'    => (int) ($s->pivot->level ?? 1),
                'position' => (int) ($s->pivot->position ?? 0),
            ])->values()->all();
    
        $levelLabel = fn(int $lvl) => match (true) {
            $lvl >= 4 => 'Native or Bilingual',
            $lvl === 3 => 'Professional Working',
            $lvl === 2 => 'Limited Working',
            default    => 'Elementary',
        };
    
        $userLanguages = $owner->languages
            ->sortBy('position')
            ->map(fn($l) => [
                'name'  => (string) $l->name,
                'level' => $levelLabel((int) $l->level),
            ])->values()->all();
    
        $modalLanguages = $owner->languages
            ->sortBy('position')->values()
            ->map(function ($l, $i) {
                return [
                    'id'       => (int) $l->id,
                    'name'     => (string) $l->name,
                    'level'    => (int) ($l->level ?? 2),
                    'position' => (int) ($l->position ?? $i),
                ];
            })->all();
    
        $user->languages = $userLanguages;
    
        $modalExperiences = $owner->experiences
            ->sortBy('position')->values()
            ->map(function ($e, $i) {
                return [
                    'id'               => (int) $e->id,
                    'company_id'       => $e->company_id ? (int) $e->company_id : null,
                    'company'          => (string) ($e->company ?? ''),
                    'title'            => (string) ($e->title ?? ''),
                    'start_month'      => $e->start_month ? (int) $e->start_month : null,
                    'start_year'       => $e->start_year ? (int) $e->start_year : null,
                    'end_month'        => $e->end_month ? (int) $e->end_month : null,
                    'end_year'         => $e->end_year ? (int) $e->end_year : null,
                    'is_current'       => (bool) ($e->is_current ?? false),
                    'location_city'    => (string) ($e->location_city ?? ''),
                    'location_country' => (string) ($e->location_country ?? ''),
                    'description'      => (string) ($e->description ?? ''),
                    'skills'           => collect($e->skills ?? [])->sortBy('position')->map(fn($s) => [
                        'name'  => (string) $s->name,
                        'level' => (int) ($s->level ?? 2),
                    ])->values()->all(),
                    'position'         => (int) ($e->position ?? $i),
                ];
            })->all();
    
        $user->education = $education;
    
        $modalPortfolios = $owner->portfolios
            ->sortBy('position')->values()
            ->map(function ($p, $i) {
                $meta = is_array($p->meta) ? $p->meta : [];
                $image_url = $p->image_path ? \Illuminate\Support\Facades\Storage::disk($p->image_disk)->url($p->image_path) : null;
                $selectedSkillIds = $meta['skill_ids'] ?? [];
                return [
                    'id'          => (int) $p->id,
                    'title'       => (string) ($p->title ?? ''),
                    'description' => (string) ($p->description ?? ''),
                    'link'        => (string) ($p->link_url ?? ''),
                    'image_url'   => $image_url,
                    'image_path'  => (string) ($p->image_path ?? ''),
                    'image_disk'  => (string) ($p->image_disk ?? 'public'),
                    'skill_ids'   => array_values($selectedSkillIds),
                    'position'    => (int) ($p->position ?? $i),
                ];
            })->all();
    
        // ===== Visible/hidden skills for filters =====
        if (count($userSkillsForFilters) > 0) {
            $visibleSkillSlugs = $filterPreferences['visible_skills']
                ?? collect($userSkillsForFilters)->take(6)->pluck('slug')->all();
    
            $visibleSkills = collect($userSkillsForFilters)
                ->filter(fn($s) => in_array($s['slug'], $visibleSkillSlugs))
                ->values()->all();
    
            $hiddenSkills = collect($userSkillsForFilters)
                ->reject(fn($s) => in_array($s['slug'], $visibleSkillSlugs))
                ->values()->all();
        } else {
            $visibleSkills = [];
            $hiddenSkills  = [];
        }
    
        // ===== Banner: attach to $user (so Blade can use $user->banner_url etc.) =====
        $bannerUrl     = ($p && $p->banner) ? Storage::disk('public')->url($p->banner) : null;
        $bp            = is_array($p?->banner_preference) ? $p->banner_preference : [];
        $bannerVersion = $p && $p->updated_at ? $p->updated_at->getTimestamp() : time();
        
        // Merge onto the $user object that Blade uses
        $user->banner_url      = $bannerUrl;
        $user->banner_fit      = $bp['fit']      ?? 'cover';
        $user->banner_position = $bp['position'] ?? 'center center';
        $user->banner_zoom     = (int)   ($bp['zoom'] ?? 100);
        $user->banner_offset_x = (float) ($bp['offset_x'] ?? 0);
        $user->banner_offset_y = (float) ($bp['offset_y'] ?? 0);
        $user->banner_version  = $bannerVersion;
    
        // ===== Misc =====
        $LIMITS = ['projects' => 3];
    
        return view('tenant.profile.index', [
            'brandName'            => config('app.name', 'SkillLeo'),
            'user'                 => $user,
            'categories'           => $categories,
            'skillsData'           => $skillsData,
            'experiences'          => $experiences,
            'reviews'              => [],
            'userEducations'       => $owner->educations,
            'userExperiences'      => $owner->experiences,
            'userPortfolios'       => $owner->portfolios,
            'skillNames'           => $skillNames,
            'modalSkills'          => $modalSkills,
            'softSkillOptions'     => $softSkillOptions,
            'selectedSoft'         => $selectedSoft,
            'modalLanguages'       => $modalLanguages,
            'modalExperiences'     => $modalExperiences,
            'modalServices'        => $owner->services->sortBy('position')->pluck('title')->values()->all(),
            'modalReasons'         => $owner->reasons->sortBy('position')->pluck('text')->values()->all(),
            'modalPortfolios'      => $modalPortfolios,
            'portfolios'           => $portfolios,
            'userSkillsForFilters' => $userSkillsForFilters,
            'visibleSkills'        => $visibleSkills,
            'hiddenSkills'         => $hiddenSkills,
            'sortOrder'            => $sortOrder,
            'sortedPortfolios'     => $portfolios,
            'visibleProjects'      => collect($portfolios)->take($LIMITS['projects']),
            'totalProjects'        => count($portfolios),
            'LIMITS'               => $LIMITS,
            'userSkills'           => $owner->skills,
            'username'           => $username,

        ]);
    }
    

    public function updatePortfolio(Request $request)
    {
        $data = $request->validate([
            'portfolios' => ['required', 'string'],
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        $portfoliosData = json_decode($data['portfolios'], true);
        if (!is_array($portfoliosData)) {
            return back()->withErrors(['portfolios' => 'Invalid portfolio data'])->withInput();
        }

        DB::beginTransaction();

        try {
            $keptIds = [];

            // ✅ IMPORTANT: Loop maintains the order from drag-and-drop
            foreach ($portfoliosData as $index => $portRow) {
                $title = trim($portRow['title'] ?? '');
                $description = trim($portRow['description'] ?? '');

                if (empty($title) || empty($description)) {
                    continue;
                }

                $imagePath = null;
                $imageDisk = 'public';
                $dbId = !empty($portRow['db_id']) ? (int) $portRow['db_id'] : null;

                $existingPortfolio = null;
                if ($dbId) {
                    $existingPortfolio = Portfolio::where('id', $dbId)
                        ->where('user_id', $user->id)
                        ->first();
                }

                // Handle image (same as before)
                if (!empty($portRow['image'])) {
                    if (strpos($portRow['image'], 'data:image') === 0) {
                        preg_match('/data:image\/(\w+);base64,(.*)/', $portRow['image'], $matches);
                        if (count($matches) === 3) {
                            $imageData = base64_decode($matches[2]);
                            $extension = $matches[1];
                            $filename = 'portfolio_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                            $path = "portfolios/{$user->id}/" . $filename;
                            Storage::disk('public')->put($path, $imageData);
                            $imagePath = $path;

                            if ($existingPortfolio && $existingPortfolio->image_path) {
                                Storage::disk($existingPortfolio->image_disk)->delete($existingPortfolio->image_path);
                            }
                        }
                    } else {
                        if (filter_var($portRow['image'], FILTER_VALIDATE_URL)) {
                            $parsed = parse_url($portRow['image']);
                            $imagePath = isset($parsed['path']) ? ltrim(str_replace('/storage/', '', $parsed['path']), '/') : null;
                        } else {
                            $imagePath = ltrim($portRow['image'], '/');
                        }

                        if (empty($imagePath) && !empty($portRow['image_path'])) {
                            $imagePath = $portRow['image_path'];
                        }

                        if (empty($imagePath) && $existingPortfolio) {
                            $imagePath = $existingPortfolio->image_path;
                            $imageDisk = $existingPortfolio->image_disk;
                        }
                    }
                } elseif ($existingPortfolio) {
                    $imagePath = $existingPortfolio->image_path;
                    $imageDisk = $existingPortfolio->image_disk;
                }

                $skillIds = isset($portRow['skill_ids']) && is_array($portRow['skill_ids'])
                    ? array_values(array_map('intval', $portRow['skill_ids']))
                    : [];

                $meta = [
                    'skill_ids' => $skillIds,
                ];

                // ✅ SAVE POSITION TO DATABASE
                $portData = [
                    'user_id'     => $user->id,
                    'title'       => $title,
                    'description' => $description,
                    'link_url'    => !empty($portRow['link']) ? trim($portRow['link']) : null,
                    'image_path'  => $imagePath,
                    'image_disk'  => $imageDisk,
                    'position'    => $index, // ✅ Save drag-and-drop position
                    'meta'        => $meta,
                ];

                if ($existingPortfolio) {
                    $existingPortfolio->update($portData);
                    $keptIds[] = $existingPortfolio->id;
                } else {
                    $portfolio = Portfolio::create($portData);
                    $keptIds[] = $portfolio->id;
                }
            }

            // Delete removed portfolios
            $toDelete = Portfolio::where('user_id', $user->id);

            if (!empty($keptIds)) {
                $toDelete->whereNotIn('id', $keptIds);
            }

            $toDelete->each(function ($portfolio) {
                if ($portfolio->image_path) {
                    Storage::disk($portfolio->image_disk)->delete($portfolio->image_path);
                }
                $portfolio->delete();
            });

            DB::commit();

            return back()->with('success', 'Portfolio updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Portfolio update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update portfolio: ' . $e->getMessage());
        }
    }


    public function updateFilterPreferences(Request $request)
    {
        $data = $request->validate([
            'visible_skills'   => ['required', 'array', 'max:6'],
            'visible_skills.*' => ['string', 'max:100'],
            'sort_order'       => ['nullable', 'string', 'in:position,newest'],
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        $profile = $user->profile;
        if (!$profile) {
            $profile = $user->profile()->create([]);
        }

        $filterPreferences = is_array($profile->filter_preferences)
            ? $profile->filter_preferences
            : [];

        $filterPreferences['visible_skills'] = $data['visible_skills'];

        if (isset($data['sort_order'])) {
            $filterPreferences['sort_order'] = $data['sort_order'];
        }

        $profile->filter_preferences = $filterPreferences;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Filter preferences updated successfully'
        ]);
    }




    // public function updateFilterPreferences(Request $request)
    // {
    //     $data = $request->validate([
    //         'visible_skills' => ['required', 'array', 'max:6'],  // ✅ FIXED: was 'visible_tags'
    //         'visible_skills.*' => ['string', 'max:100'],
    //         'sort_order' => ['nullable', 'string', 'in:position,newest,title'],
    //     ]);

    //     $user = $request->user();
    //     abort_unless($user, 403);

    //     $profile = $user->profile;
    //     if (!$profile) {
    //         $profile = $user->profile()->create([]);
    //     }

    //     // Get existing filter preferences
    //     $filterPreferences = is_array($profile->filter_preferences) 
    //         ? $profile->filter_preferences 
    //         : [];

    //     // ✅ FIXED: Store as 'visible_skills' not 'visible_tags'
    //     $filterPreferences['visible_skills'] = $data['visible_skills'];

    //     if (isset($data['sort_order'])) {
    //         $filterPreferences['sort_order'] = $data['sort_order'];
    //     }

    //     $profile->filter_preferences = $filterPreferences;
    //     $profile->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Filter preferences updated successfully'
    //     ]);
    // }

























































    // app/Http/Controllers/Tenant/ProfilePage/ProfileController.php

    public function updateLanguages(Request $request)
    {
        $request->validate([
            'languages' => ['nullable', 'string'], // JSON array
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        $payload = json_decode($request->input('languages', '[]'), true) ?: [];

        // normalize + clamp
        $clean = [];
        foreach ($payload as $idx => $row) {
            $name = trim((string)($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $level = (int)($row['level'] ?? 2);
            $level = max(1, min(4, $level));
            $clean[] = ['name' => $name, 'level' => $level, 'position' => $idx];
        }

        // upsert via updateOrCreate
        $keptNames = [];
        foreach ($clean as $row) {
            \App\Models\UserLanguage::updateOrCreate(
                ['user_id' => $user->id, 'name' => $row['name']],
                ['level' => $row['level'], 'position' => $row['position']]
            );
            $keptNames[] = $row['name'];
        }

        // delete removed languages
        $query = \App\Models\UserLanguage::where('user_id', $user->id);
        if (!empty($keptNames)) {
            $query->whereNotIn('name', $keptNames);
        }
        $query->delete();

        return back()->with('status', 'Languages updated');
    }








    // Update profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validate input
        $data = $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'headline' => 'nullable|string|max:120',
            'about' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:160',
            'phone' => 'nullable|string|max:40',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'avatar' => 'nullable|image|max:5120', // 5MB
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Update user first name, last name and email
            $user->name = $data['first_name'];
            $user->last_name = $data['last_name'] ?? null;
            $user->email = $data['email'];
            $user->save();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');
                $user->avatar_url = Storage::disk('public')->url($path);
                $user->save();
            }

            // Prepare social links
            $socialLinks = [];
            if (!empty($data['linkedin'])) {
                $socialLinks['linkedin'] = $data['linkedin'];
            }
            if (!empty($data['twitter'])) {
                $socialLinks['twitter'] = $data['twitter'];
            }
            if (!empty($data['facebook'])) {
                $socialLinks['facebook'] = $data['facebook'];
            }
            if (!empty($data['instagram'])) {
                $socialLinks['instagram'] = $data['instagram'];
            }

            // Split location into city and country
            $city = null;
            $country = null;
            if (!empty($data['location'])) {
                $parts = explode(',', $data['location'], 2);
                $city = trim($parts[0]);
                $country = isset($parts[1]) ? trim($parts[1]) : null;
            }

            // Update or create profile
            $profile = UserProfile::where('user_id', $user->id)->first();

            if ($profile) {
                // Update existing profile
                $profile->phone = $data['phone'] ?? null;
                $profile->tagline = $data['headline'] ?? null;
                $profile->bio = $data['about'] ?? null;
                $profile->city = $city;
                $profile->country = $country;
                $profile->social_links = $socialLinks;
                $profile->save();
            } else {
                // Create new profile
                UserProfile::create([
                    'user_id' => $user->id,
                    'phone' => $data['phone'] ?? null,
                    'tagline' => $data['headline'] ?? null,
                    'bio' => $data['about'] ?? null,
                    'city' => $city,
                    'country' => $country,
                    'social_links' => $socialLinks,
                ]);
            }

            // Commit transaction
            DB::commit();

            // Return response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'user' => [
                        'name' => $user->name . ' ' . $user->last_name,
                        'avatar_url' => $user->avatar_url,
                    ],
                ]);
            }

            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile',
                ], 500);
            }

            return back()->with('error', 'Failed to update profile');
        }
    }


    // Helper: Get full name
    private function getFullName($firstName, $lastName)
    {
        $first = trim($firstName ?? '');
        $last = trim($lastName ?? '');

        $fullName = trim($first . ' ' . $last);

        return $fullName ?: 'User';
    }


    // Helper: Get location string
    private function getLocation($profile)
    {
        if (!$profile) {
            return null;
        }

        $parts = [];

        if ($profile->city) {
            $parts[] = $profile->city;
        }
        if ($profile->state) {
            $parts[] = $profile->state;
        }
        if ($profile->country) {
            $parts[] = $profile->country;
        }

        return !empty($parts) ? implode(', ', $parts) : null;
    }




    // ProfileController.php
    public function updateEducation(Request $request)
    {
        $request->validate([
            'education' => ['nullable', 'string'], // JSON array
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        $payload = json_decode($request->input('education', '[]'), true) ?: [];

        // Clean and normalize
        $clean = [];
        foreach ($payload as $idx => $row) {
            $school = trim((string)($row['school'] ?? ''));
            $degree = trim((string)($row['degree'] ?? ''));

            if ($school === '' || $degree === '') continue;

            $clean[] = [
                'school' => $school,
                'degree' => $degree,
                'field' => trim((string)($row['field'] ?? '')),
                'start_year' => (int)($row['startYear'] ?? 0) ?: null,
                'end_year' => (int)($row['endYear'] ?? 0) ?: null,
                'is_current' => (bool)($row['current'] ?? false),
                'institution_id' => (int)($row['institution_id'] ?? 0) ?: null,
                'position' => $idx,
                'db_id' => (int)($row['db_id'] ?? 0) ?: null,
            ];
        }

        // Update or create
        $keptIds = [];
        foreach ($clean as $row) {
            $dbId = $row['db_id'];
            unset($row['db_id']);

            if ($dbId) {
                // Update existing
                Education::where('id', $dbId)
                    ->where('user_id', $user->id)
                    ->update($row);
                $keptIds[] = $dbId;
            } else {
                // Create new
                $edu = Education::create(array_merge($row, ['user_id' => $user->id]));
                $keptIds[] = $edu->id;
            }
        }

        // Delete removed
        Education::where('user_id', $user->id)
            ->whereNotIn('id', $keptIds)
            ->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Education updated successfully']);
        }

        return back()->with('success', 'Education updated successfully');
    }



























    public function updateSkills(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->validate([
            // from hidden input (JSON string) for technical skills
            'skills' => ['required', 'string'],
            // from checkboxes for soft skills (values = slugs)
            'soft_skills' => ['array'],
            'soft_skills.*' => ['string', 'max:120'],
        ]);

        // ---------- TECH SKILLS (unchanged from your working version) ----------
        $skillsPayload = json_decode($data['skills'], true);
        if (!is_array($skillsPayload)) {
            return back()->withErrors(['skills' => 'Invalid skills payload'])->withInput();
        }

        $normTech = [];
        foreach ($skillsPayload as $i => $row) {
            $name = trim((string)($row['name'] ?? ''));
            if ($name === '') continue;

            $level = (int)($row['level'] ?? 2);
            $level = max(1, min(3, $level));

            $normTech[] = [
                'name'     => mb_substr($name, 0, 120),
                'level'    => $level,
                'position' => (int)($row['position'] ?? $i),
            ];
        }
        if (count($normTech) < 3) {
            return back()->withErrors(['skills' => 'Please add at least 3 skills.'])->withInput();
        }

        // ---------- SOFT SKILLS (new tables with updateOrCreate) ----------
        // Limit to 6 and keep original order for positions
        $softSlugs = array_slice(array_values(array_unique($request->input('soft_skills', []))), 0, 6);

        // Optional: map slugs -> icons (use the same icons you use in Blade)
        $iconMap = [
            'communication' => 'comments',
            'leadership' => 'users',
            'teamwork' => 'handshake',
            'problem-solving' => 'lightbulb',
            'creativity' => 'palette',
            'time-management' => 'clock',
            'adaptability' => 'sync',
            'critical-thinking' => 'brain',
            'attention-to-detail' => 'search',
            'organization' => 'list',
            'collaboration' => 'users-cog',
            'emotional-intelligence' => 'heart',
            'decision-making' => 'balance-scale',
            'conflict-resolution' => 'handshake-angle',
            'negotiation' => 'handshake-simple',
            'presentation' => 'chalkboard-user',
            'public-speaking' => 'microphone',
            'active-listening' => 'ear-listen',
            'empathy' => 'hands-holding-heart',
            'self-motivation' => 'rocket',
            'work-ethic' => 'briefcase',
            'flexibility' => 'arrows-spin',
            'resilience' => 'shield-heart',
            'initiative' => 'flag',
            'strategic-thinking' => 'chess',
            'analytical-skills' => 'chart-line',
            'customer-service' => 'headset',
            'project-management' => 'tasks',
            'multitasking' => 'layer-group',
            'mentoring' => 'user-graduate',
        ];

        DB::transaction(function () use ($user, $normTech, $softSlugs, $iconMap) {
            // ----- Save TECH skills (sync pivot with extra cols) -----
            $attach = [];
            foreach ($normTech as $row) {
                $existing = Skill::whereRaw('LOWER(name) = ?', [mb_strtolower($row['name'])])->first();
                if (!$existing) {
                    $existing = Skill::create([
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);
                }
                $attach[$existing->id] = [
                    'level'    => $row['level'],
                    'position' => $row['position'],
                ];
            }
            $user->skills()->sync($attach);

            // ----- Save SOFT skills with updateOrCreate() -----
            $keptSoftIds = [];
            foreach ($softSlugs as $i => $slug) {
                $name = Str::headline(str_replace('-', ' ', $slug)); // "critical-thinking" => "Critical Thinking"
                $soft = SoftSkill::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'icon' => $iconMap[$slug] ?? null]
                );

                // create/update the user pivot row
                $pivot = UserSoftSkill::updateOrCreate(
                    ['user_id' => $user->id, 'soft_skill_id' => $soft->id],
                    ['level' => 2, 'position' => $i]
                );

                $keptSoftIds[] = $soft->id;
            }

            // Remove any unchecked soft skills
            UserSoftSkill::where('user_id', $user->id)
                ->when(!empty($keptSoftIds), fn($q) => $q->whereNotIn('soft_skill_id', $keptSoftIds))
                ->when(empty($keptSoftIds), fn($q) => $q) // if none selected, delete all
                ->delete();
        });

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Skills updated successfully.');
    }

















    // Add this method to your ProfileController

    public function updateExperience(Request $request)
    {
        // Validate the request
        $data = $request->validate([
            'experiences' => ['required', 'string'], // JSON string
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        // Parse experiences from JSON
        $experiencesData = json_decode($data['experiences'], true);
        if (!is_array($experiencesData)) {
            return back()->withErrors(['experiences' => 'Invalid experiences data'])->withInput();
        }

        // Start transaction
        DB::beginTransaction();

        try {
            // Track which experience IDs we want to keep
            $keptIds = [];

            foreach ($experiencesData as $index => $expRow) {
                // Skip if missing required fields
                $company = trim($expRow['company'] ?? '');
                $title = trim($expRow['title'] ?? '');

                if (empty($company) || empty($title)) {
                    continue;
                }

                // Prepare experience data
                $expData = [
                    'user_id' => $user->id,
                    'company' => $company,
                    'company_id' => $expRow['company_id'] ?? null,
                    'title' => $title,
                    'start_month' => !empty($expRow['startMonth']) ? (int) $expRow['startMonth'] : null,
                    'start_year' => !empty($expRow['startYear']) ? (int) $expRow['startYear'] : null,
                    'end_month' => ($expRow['current'] ?? false) ? null : (!empty($expRow['endMonth']) ? (int) $expRow['endMonth'] : null),
                    'end_year' => ($expRow['current'] ?? false) ? null : (!empty($expRow['endYear']) ? (int) $expRow['endYear'] : null),
                    'is_current' => (bool) ($expRow['current'] ?? false),
                    'location_city' => trim($expRow['locationCity'] ?? ''),
                    'location_country' => trim($expRow['locationCountry'] ?? ''),
                    'description' => trim($expRow['description'] ?? ''),
                    'position' => $index,
                ];

                $dbId = !empty($expRow['db_id']) ? (int) $expRow['db_id'] : null;

                // Update or Create experience
                if ($dbId) {
                    // Update existing
                    $experience = Experience::where('id', $dbId)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($experience) {
                        $experience->update($expData);
                        $keptIds[] = $experience->id;
                    } else {
                        // If not found, create new
                        $experience = Experience::create($expData);
                        $keptIds[] = $experience->id;
                    }
                } else {
                    // Create new
                    $experience = Experience::create($expData);
                    $keptIds[] = $experience->id;
                }

                // Handle skills for this experience
                // Delete old skills
                ExperienceSkill::where('experience_id', $experience->id)->delete();

                // Add new skills
                $skillsData = $expRow['skills'] ?? [];
                if (is_array($skillsData) && !empty($skillsData)) {
                    foreach ($skillsData as $skillIndex => $skill) {
                        if (!empty($skill['name'])) {
                            ExperienceSkill::create([
                                'experience_id' => $experience->id,
                                'name' => trim($skill['name']),
                                'level' => (int) ($skill['level'] ?? 2),
                                'position' => $skillIndex,
                            ]);
                        }
                    }
                }
            }

            // Delete experiences that were removed
            if (!empty($keptIds)) {
                Experience::where('user_id', $user->id)
                    ->whereNotIn('id', $keptIds)
                    ->each(function ($exp) {
                        // Delete skills first
                        ExperienceSkill::where('experience_id', $exp->id)->delete();
                        // Delete experience
                        $exp->delete();
                    });
            } else {
                // If no experiences kept, delete all
                Experience::where('user_id', $user->id)
                    ->each(function ($exp) {
                        ExperienceSkill::where('experience_id', $exp->id)->delete();
                        $exp->delete();
                    });
            }

            // Commit transaction
            DB::commit();

            return back()->with('success', 'Experiences updated successfully');
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();

            return back()->with('error', 'Failed to update experiences: ' . $e->getMessage());
        }
    }

























    public function updateServices(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'services' => ['nullable', 'string'], // JSON array of strings OR objects with "title"
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        abort_unless($user, 403);

        $payload = json_decode($request->input('services', '[]'), true) ?: [];

        // Normalize to unique list of non-empty strings (keep original order)
        $normalized = [];
        foreach ($payload as $i => $row) {
            $val = is_array($row) ? ($row['title'] ?? $row['text'] ?? '') : $row;
            $val = Str::of((string)$val)->squish()->limit(100, '')->toString();
            if ($val !== '') $normalized[] = $val;
        }
        $normalized = array_values(array_unique($normalized));
        // Optional minimum: if you want at least 1 service, enforce here.

        DB::transaction(function () use ($user, $normalized) {
            // Upsert by (user_id, title), maintain positions
            $keep = [];
            foreach ($normalized as $pos => $title) {
                $svc = UserService::updateOrCreate(
                    ['user_id' => $user->id, 'title' => $title],
                    ['position' => $pos]
                );
                $keep[] = $svc->id;
            }

            // Delete rows not kept
            $q = UserService::where('user_id', $user->id);
            if (!empty($keep)) {
                $q->whereNotIn('id', $keep);
            }
            $q->delete();
        });

        return $request->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Services updated successfully.');
    }

    public function updateWhyChoose(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'reasons' => ['nullable', 'string'], // JSON array of strings OR objects with "text"
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        abort_unless($user, 403);

        $payload = json_decode($request->input('reasons', '[]'), true) ?: [];

        // Normalize to unique list of trimmed strings (<= 160)
        $normalized = [];
        foreach ($payload as $i => $row) {
            $val = is_array($row) ? ($row['text'] ?? $row['title'] ?? '') : $row;
            $val = Str::of((string)$val)->squish()->limit(160, '')->toString();
            if ($val !== '') $normalized[] = $val;
        }
        $normalized = array_values(array_unique($normalized));

        DB::transaction(function () use ($user, $normalized) {
            $keep = [];
            foreach ($normalized as $pos => $text) {
                $reason = UserReason::updateOrCreate(
                    ['user_id' => $user->id, 'text' => $text],
                    ['position' => $pos]
                );
                $keep[] = $reason->id;
            }

            $q = UserReason::where('user_id', $user->id);
            if (!empty($keep)) {
                $q->whereNotIn('id', $keep);
            }
            $q->delete();
        });

        return $request->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Why Choose Me updated successfully.');
    }





























    public function updateReviews(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        // ✅ VALIDATE AS ARRAY (not string)
        $data = $request->validate([
            'reviews'                      => ['required', 'array'],
            'reviews.*.db_id'              => ['nullable', 'integer'],
            'reviews.*.client_name'        => ['required', 'string', 'max:255'],
            'reviews.*.title'              => ['nullable', 'string', 'max:255'],
            'reviews.*.location'           => ['nullable', 'string', 'max:255'],
            'reviews.*.content'            => ['required', 'string'],
            'reviews.*.image'              => ['nullable'],  // base64 or URL or relative path
            'reviews.*.image_path'         => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $keptIds = [];

            foreach ($data['reviews'] as $index => $row) {
                $dbId     = isset($row['db_id']) ? (int) $row['db_id'] : null;
                $existing = $dbId
                    ? Review::where('id', $dbId)->where('user_id', $user->id)->first()
                    : null;

                // Resolve image (keep existing if none provided)
                $imagePath = $existing?->image_path;
                $imageDisk = $existing?->image_disk ?? 'public';

                if (!empty($row['image'])) {
                    $val = $row['image'];

                    // data:image/...;base64,....
                    if (str_starts_with($val, 'data:image')) {
                        if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $val, $m) === 1) {
                            $bin = base64_decode($m[2]);
                            $ext = strtolower($m[1]);

                            $filename = 'review_' . $user->id . '_' . uniqid('', true) . '.' . $ext;
                            $path     = "reviews/{$user->id}/{$filename}";

                            Storage::disk('public')->put($path, $bin);

                            if ($existing?->image_path) {
                                Storage::disk($existing->image_disk)->delete($existing->image_path);
                            }

                            $imagePath = $path;
                            $imageDisk = 'public';
                        }
                    } else {
                        // URL or relative path or provided image_path
                        if (filter_var($val, FILTER_VALIDATE_URL)) {
                            $parsed    = parse_url($val);
                            $imagePath = isset($parsed['path'])
                                ? ltrim(str_replace('/storage/', '', $parsed['path']), '/')
                                : $imagePath;
                        } else {
                            $imagePath = ltrim($val, '/') ?: ($row['image_path'] ?? $imagePath);
                        }
                    }
                } elseif (!empty($row['image_path'])) {
                    // explicit existing path from form
                    $imagePath = $row['image_path'];
                }

                $payload = [
                    'user_id'     => $user->id,
                    'client_name' => trim($row['client_name']),
                    'title'       => trim($row['title']    ?? ''),
                    'location'    => trim($row['location'] ?? ''),
                    'content'     => trim($row['content']),
                    'image_path'  => $imagePath,
                    'image_disk'  => $imageDisk,
                    'position'    => (int) $index,
                ];

                if ($existing) {
                    $existing->update($payload);
                    $keptIds[] = $existing->id;
                } else {
                    $keptIds[] = Review::create($payload)->id;
                }
            }

            // ✅ Delete reviews not sent back
            $toDeleteQuery = Review::where('user_id', $user->id);
            if (!empty($keptIds)) {
                $toDeleteQuery->whereNotIn('id', $keptIds);
            }

            // IMPORTANT: call each() on the **collection**, not the builder
            $toDeleteQuery->get()->each(function (Review $review) {
                if ($review->image_path) {
                    Storage::disk($review->image_disk)->delete($review->image_path);
                }
                $review->delete();
            });

            DB::commit();
            return back()->with('success', 'Reviews updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Review update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update reviews: ' . $e->getMessage());
        }
    }
















































































































































































































































































































































































































































































































































































































    public function times(Request $request, string $username)
    {
        // Find user by username
        $user = User::where('username', $username)
            ->with(['profile', 'devices' => function ($query) {
                $query->orderByDesc('last_seen_at')->limit(5);
            }])
            ->firstOrFail();

        // Get viewer's timezone
        $viewerTimezone = TimezoneService::getViewerTimezone();

        // Check if viewer is the profile owner
        $isOwner = Auth::check() && Auth::id() === $user->id;

        // Prepare all time data
        $timeData = [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'avatar_url' => $user->avatar_url,
                'timezone' => $user->timezone,
                'timezone_offset' => TimezoneService::getTimezoneOffset($user->timezone),
            ],

            'viewer' => [
                'timezone' => $viewerTimezone,
                'timezone_offset' => TimezoneService::getTimezoneOffset($viewerTimezone),
                'is_owner' => $isOwner,
            ],

            'timestamps' => [
                'created_at' => [
                    'utc' => $user->created_at?->toIso8601String(),
                    'utc_formatted' => $user->created_at?->format('Y-m-d H:i:s') . ' UTC',
                    'user_timezone' => TimezoneService::formatForDisplay(
                        $user->created_at,
                        $user->timezone,
                        'M d, Y g:i A'
                    ),
                    'viewer_timezone' => TimezoneService::formatForDisplay(
                        $user->created_at,
                        $viewerTimezone,
                        'M d, Y g:i A'
                    ),
                    'human' => TimezoneService::humanTime($user->created_at, $viewerTimezone),
                    'label' => 'Account Created',
                ],

                'updated_at' => [
                    'utc' => $user->updated_at?->toIso8601String(),
                    'utc_formatted' => $user->updated_at?->format('Y-m-d H:i:s') . ' UTC',
                    'user_timezone' => TimezoneService::formatForDisplay(
                        $user->updated_at,
                        $user->timezone,
                        'M d, Y g:i A'
                    ),
                    'viewer_timezone' => TimezoneService::formatForDisplay(
                        $user->updated_at,
                        $viewerTimezone,
                        'M d, Y g:i A'
                    ),
                    'human' => TimezoneService::humanTime($user->updated_at, $viewerTimezone),
                    'label' => 'Profile Updated',
                ],

                'last_login_at' => [
                    'utc' => $user->last_login_at?->toIso8601String(),
                    'utc_formatted' => $user->last_login_at?->format('Y-m-d H:i:s') . ' UTC',
                    'user_timezone' => TimezoneService::formatForDisplay(
                        $user->last_login_at,
                        $user->timezone,
                        'M d, Y g:i A'
                    ),
                    'viewer_timezone' => TimezoneService::formatForDisplay(
                        $user->last_login_at,
                        $viewerTimezone,
                        'M d, Y g:i A'
                    ),
                    'human' => TimezoneService::humanTime($user->last_login_at, $viewerTimezone),
                    'label' => 'Last Login',
                ],

                'last_seen_at' => [
                    'utc' => $user->last_seen_at?->toIso8601String(),
                    'utc_formatted' => $user->last_seen_at?->format('Y-m-d H:i:s') . ' UTC',
                    'user_timezone' => TimezoneService::formatForDisplay(
                        $user->last_seen_at,
                        $user->timezone,
                        'M d, Y g:i A'
                    ),
                    'viewer_timezone' => TimezoneService::formatForDisplay(
                        $user->last_seen_at,
                        $viewerTimezone,
                        'M d, Y g:i A'
                    ),
                    'human' => TimezoneService::humanTime($user->last_seen_at, $viewerTimezone),
                    'status_text' => TimezoneService::getOnlineStatusText($user->last_seen_at, $viewerTimezone),
                    'label' => 'Last Seen',
                ],
            ],

            'online_status' => [
                'is_online' => $this->onlineStatus->isOnline($user),
                'status' => $this->onlineStatus->getStatus($user),
                'status_text' => $this->onlineStatus->getLastSeenText($user, $viewerTimezone),
            ],

            'activity' => [
                'login_count' => $user->login_count ?? 0,
                'account_age_days' => $user->created_at?->diffInDays(now()) ?? 0,
                'is_active' => $user->is_active === 'active',
                'account_status' => $user->account_status,
                'profile_complete' => $user->is_profile_complete,
            ],

            'devices' => $user->devices->map(function ($device) use ($viewerTimezone) {
                return [
                    'id' => $device->id,
                    'device_name' => $device->device_name,
                    'device_type' => $device->device_type,
                    'browser' => $device->browser,
                    'platform' => $device->platform,
                    'last_seen_at' => [
                        'utc' => $device->last_seen_at?->toIso8601String(),
                        'formatted' => TimezoneService::formatForDisplay(
                            $device->last_seen_at,
                            $viewerTimezone,
                            'M d, Y g:i A'
                        ),
                        'human' => TimezoneService::humanTime($device->last_seen_at, $viewerTimezone),
                    ],
                    'last_activity_at' => [
                        'utc' => $device->last_activity_at?->toIso8601String(),
                        'formatted' => TimezoneService::formatForDisplay(
                            $device->last_activity_at,
                            $viewerTimezone,
                            'M d, Y g:i A'
                        ),
                        'human' => TimezoneService::humanTime($device->last_activity_at, $viewerTimezone),
                    ],
                    'is_current' => $device->is_current ?? false,
                ];
            })->toArray(),

            'location' => [
                'country' => $user->country,
                'state' => $user->state,
                'city' => $user->city,
                'location_string' => $user->location,
            ],
        ];

        return view('tenant.profile.times', compact('timeData'));
    }
}
