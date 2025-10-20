<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\ProfilePage;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Skill;
use App\Models\State;
use App\Models\Review;
use App\Models\Country;
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
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\Tenant\UpdateProfileRequest;





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

        $p = $owner->profile; // alias

        $social   = is_array($p?->social_links) ? $p->social_links : [];
        $titleCase = static fn($s) => $s !== '' ? \Illuminate\Support\Str::of($s)->squish()->title()->toString() : '';
        $first = $owner->name ?? '';
        $last  = $owner->last_name ?? '';
        $full  = trim($titleCase($first) . ' ' . $titleCase($last)) ?: ($owner->username ?? 'User');

        $p = $owner->profile; // short alias to profile
        $social   = is_array($p?->social_links) ? $p->social_links : [];
        $avatar   = $owner->avatar_url ?: ('https://ui-avatars.com/api/?name=' . urlencode($full) . '&size=200&background=random');
        $location = collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ');

        $user = (object) [
            'name'     => $titleCase($first),
            'last_name'      => $titleCase($last),
            'full_name'           => $full,
            'email'          => $owner->email,
            'headline'       => (string) ($p?->headline ?? ''),
            'about'            => (string) ($p?->about ?? ''),
            'about'          => (string) ($p?->about ?? ''),
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
        $user->profile = $owner->profile;

        $user->services    = $owner->services->sortBy('position')->pluck('title')->values()->all();
        $user->whyChooseMe = $owner->reasons->sortBy('position')->pluck('text')->values()->all();

        // ===== Skills summary =====
        $skillsData = $owner->skills
            ->sortBy('pivot.position')
            ->map(fn($s) => [
                'name'       => (string) $s->name,
                'percentage' => (int) match ((int)($s->pivot->level ?? 1)) {
                    3 => 95,
                    2 => 82,
                    default => 68
                },
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
    public function updatePersonal(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Authorization
        abort_unless(Auth::check() && Auth::id() === $user->id, 403);

        // ============ VALIDATION ============
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'headline' => 'nullable|string|max:120',
            'about' => 'nullable|string|max:2000',
            'phone' => 'nullable|string|max:40',

            // Location fields
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',

            // Social links
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',

            // Avatar
            'avatar' => 'nullable|image|max:5120', // 5MB
        ]);

        DB::beginTransaction();

        try {
            // ============ UPDATE USER BASIC INFO ============
            $user->name = $validated['name'];
            $user->last_name = $validated['last_name'] ?? null;
            $user->email = $validated['email'];

            // ============ HANDLE AVATAR UPLOAD ============
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists and is local
                if ($user->avatar_url && !filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
                    $oldPath = str_replace('/storage/', '', parse_url($user->avatar_url, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPath);
                }

                // Upload new avatar
                $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');
                $user->avatar_url = Storage::disk('public')->url($path);
            }

            $user->save();

            // ============ NORMALIZE LOCATION DATA ============
            $city = $validated['city'] ? Str::of($validated['city'])->trim()->title()->toString() : null;
            $state = $validated['state'] ? Str::of($validated['state'])->trim()->title()->toString() : null;
            $country = $validated['country'] ? Str::of($validated['country'])->trim()->title()->toString() : null;

            // ============ PREPARE SOCIAL LINKS ============
            $socialLinks = [];
            foreach (['linkedin', 'twitter', 'github', 'website', 'facebook', 'instagram'] as $platform) {
                if (!empty($validated[$platform])) {
                    $socialLinks[$platform] = $validated[$platform];
                }
            }

            // ============ UPDATE OR CREATE PROFILE ============
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $validated['phone'] ?? null,
                    'headline' => $validated['headline'] ?? null,
                    'about' => $validated['about'] ?? null,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'social_links' => $socialLinks,
                ]
            );

            // ============ LOG SUCCESS ============
            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'location' => $city && $state && $country ? "{$city}, {$state}, {$country}" : 'Not set',
            ]);

            DB::commit();

            // ============ RETURN RESPONSE ============
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'user' => [
                        'name' => $user->full_name,
                        'avatar_url' => $user->avatar_url,
                        'location' => $user->location,
                    ],
                ]);
            }

            return back()
                ->with('status', 'Profile updated successfully! 🎉');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile. Please try again.',
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update profile. Please try again.']);
        }
    }

    /**
     * Verify location exists in database and get IDs
     * 
     * @param string|null $countryName
     * @param string|null $stateName
     * @param string|null $cityName
     * @return array
     */
    private function verifyLocation(?string $countryName, ?string $stateName, ?string $cityName): array
    {
        $data = [
            'country_id' => null,
            'state_id' => null,
            'city_id' => null,
            'verified' => false,
        ];

        if (!$countryName || !$stateName || !$cityName) {
            return $data;
        }

        try {
            // Find country (case-insensitive)
            $country = Country::whereRaw('LOWER(name) = ?', [Str::lower($countryName)])->first();

            if (!$country) {
                return $data;
            }

            $data['country_id'] = $country->id;

            // Find state
            $state = State::where('country_id', $country->id)
                ->whereRaw('LOWER(name) = ?', [Str::lower($stateName)])
                ->first();

            if (!$state) {
                return $data;
            }

            $data['state_id'] = $state->id;

            // Find city
            $city = City::where('state_id', $state->id)
                ->whereRaw('LOWER(name) = ?', [Str::lower($cityName)])
                ->first();

            if (!$city) {
                return $data;
            }

            $data['city_id'] = $city->id;
            $data['verified'] = true;

            return $data;
        } catch (\Exception $e) {
            Log::error('Location verification failed', [
                'error' => $e->getMessage(),
                'location' => "{$cityName}, {$stateName}, {$countryName}"
            ]);

            return $data;
        }
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
            'skills'         => ['nullable'],             // JSON string (optional)
            'soft_skills'    => ['sometimes', 'array'],   // optional
            'soft_skills.*'  => ['nullable', 'string'],
            'soft_payload'   => ['sometimes'],            // JSON string (optional)
            'mode'           => ['nullable', 'in:skills,soft,both'],
        ]);
    
        // Small helper to safely decode JSON (string|Stringable|null -> array|null)
        $safeDecode = function ($val): ?array {
            if ($val instanceof \Illuminate\Support\Stringable) {
                $val = (string) $val;
            }
            if (!is_string($val)) return null;
            $val = trim($val);
            if ($val === '' || $val === 'null') return null;
            $decoded = json_decode($val, true);
            return is_array($decoded) ? $decoded : null;
        };
    
        // =========================
        // TECHNICAL SKILLS (OPTIONAL)
        // =========================
        $attachTech = null;
    
        // Use input() or cast to string (NOT ->string())
        $rawSkills = $request->input('skills'); // string|null
        if (is_string($rawSkills) && trim($rawSkills) !== '') {
            $skillsPayload = $safeDecode($rawSkills);
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
    
            // Enforce minimum only if tech block was actually submitted
            if (count($normTech) < 3) {
                return back()->withErrors(['skills' => 'Please add at least 3 skills.'])->withInput();
            }
    
            $attachTech = [];
            foreach ($normTech as $row) {
                $existing = Skill::whereRaw('LOWER(name) = ?', [mb_strtolower($row['name'])])->first();
                if (!$existing) {
                    $existing = Skill::create([
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);
                }
                $attachTech[$existing->id] = [
                    'level'    => $row['level'],
                    'position' => $row['position'],
                ];
            }
        }
    
        // =========================
        // SOFT SKILLS (OPTIONAL, DYNAMIC)
        // =========================
        $softItems = [];
    
        // Prefer fully-dynamic JSON payload if present
        $rawSoftPayload = $request->input('soft_payload'); // string|null
        $decodedSoft    = $safeDecode($rawSoftPayload);
    
        if (is_array($decodedSoft)) {
            // Expect array of objects: { id?, slug?, name?, icon?, level? }
            $softItems = $decodedSoft;
        } elseif ($request->has('soft_skills')) {
            // Fallback: simple array (ids/slugs/names)
            $softItems = array_map(
                fn($v) => ['value' => (string)$v],
                array_values($request->input('soft_skills', []))
            );
        }
    
        // Normalize & cap to 6
        $MAX_SOFT = 6;
        $normalizedSoft = [];
        $seen = [];
    
        foreach ($softItems as $i => $item) {
            $id    = isset($item['id']) ? (int)$item['id'] : null;
            $slug  = isset($item['slug']) ? trim((string)$item['slug']) : null;
            $name  = isset($item['name']) ? trim((string)$item['name']) : null;
            $icon  = isset($item['icon']) ? trim((string)$item['icon']) : null;
            $level = isset($item['level']) ? max(1, min(3, (int)$item['level'])) : 2;
    
            if (!$id && !$slug && !$name && isset($item['value'])) {
                $val = trim((string)$item['value']);
                if ($val !== '') {
                    if (ctype_digit($val)) {
                        $id = (int)$val;
                    } else {
                        if (str_contains($val, '-')) {
                            $slug = Str::slug($val);
                            $name = $name ?: Str::headline(str_replace('-', ' ', $slug));
                        } else {
                            $name = $val;
                            $slug = Str::slug($val);
                        }
                    }
                }
            }
    
            if ($name && !$slug) $slug = Str::slug($name);
            if ($slug && !$name) $name = Str::headline(str_replace('-', ' ', $slug));
    
            $key = $id ? "id:$id" : ($slug ? "slug:$slug" : ($name ? "name:".mb_strtolower($name) : null));
            if (!$key || isset($seen[$key])) continue;
            $seen[$key] = true;
    
            $normalizedSoft[] = compact('id','slug','name','icon','level');
            if (count($normalizedSoft) >= $MAX_SOFT) break;
        }
    
        DB::transaction(function () use ($user, $attachTech, $normalizedSoft) {
            // TECH
            if (is_array($attachTech)) {
                $user->skills()->sync($attachTech);
            }
    
            // SOFT
            if (!empty($normalizedSoft)) {
                $keptSoftIds = [];
    
                foreach ($normalizedSoft as $position => $row) {
                    if (!empty($row['id'])) {
                        $soft = SoftSkill::find($row['id']);
                        if (!$soft) {
                            $slug = $row['slug'] ?? ($row['name'] ? Str::slug($row['name']) : null);
                            $name = $row['name'] ?? ($slug ? Str::headline(str_replace('-', ' ', $slug)) : null);
                            $soft = SoftSkill::firstOrCreate(
                                ['slug' => $slug ?? Str::slug($name ?? 'untitled')],
                                ['name' => $name ?? 'Untitled', 'icon' => $row['icon'] ?? null]
                            );
                        }
                    } else {
                        $slug = $row['slug'] ?? ($row['name'] ? Str::slug($row['name']) : null);
                        $name = $row['name'] ?? ($slug ? Str::headline(str_replace('-', ' ', $slug)) : 'Untitled');
    
                        $soft = SoftSkill::firstOrCreate(
                            ['slug' => $slug ?? Str::slug($name)],
                            ['name' => $name, 'icon' => $row['icon'] ?? null]
                        );
    
                        if (!empty($row['icon']) && $soft->icon !== $row['icon']) {
                            $soft->icon = $row['icon'];
                            $soft->save();
                        }
                    }
    
                    UserSoftSkill::updateOrCreate(
                        ['user_id' => $user->id, 'soft_skill_id' => $soft->id],
                        ['level' => $row['level'] ?? 2, 'position' => $position]
                    );
    
                    $keptSoftIds[] = $soft->id;
                }
    
                UserSoftSkill::where('user_id', $user->id)
                    ->whereNotIn('soft_skill_id', $keptSoftIds)
                    ->delete();
            }
        });
    
        return $request->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Skills updated successfully.');
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
}
