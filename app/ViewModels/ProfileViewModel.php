<?php



// app/ViewModels/ProfileViewModel.php
namespace App\ViewModels;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class ProfileViewModel
{

    public function __construct(private User $owner) {}

    public function userCard(): object
    {
        $o = $this->owner;
        $pref = $o->preference; // ✅ singular

        $fullName = trim(($o->first_name ?? '').' '.($o->last_name ?? '')) ?: ($o->name ?? $o->username);
        $headline = $o->headline ?? $o->bio ?? null;
        $about    = $o->about ?? $o->summary ?? null;
        $loc      = trim(collect([$o->city ?? $o->location_city, $o->country ?? $o->location_country])->filter()->join(', '));

        $skills = $o->skills?->sortBy('position') ?? collect();

        return (object) [
            'name'         => $fullName,
            'facebook'     => $o->facebook ?? $pref?->facebook,
            'instagram'    => $o->instagram ?? $pref?->instagram,
            'twitter'      => $o->twitter ?? $pref?->twitter,
            'linkedin'     => $o->linkedin ?? $pref?->linkedin,
            'bio'          => $headline ?: '—',
            'location'     => $loc ?: '—',
            'avatar'       => $o->avatar_url ?? $o->photo_url ?? null,
            'banner'       => $o->banner_url ?? null,
            'open_to_work' => (bool) ($o->open_to_work ?? $pref?->open_to_work ?? false),
            'about'        => $about ?: '',
            'skills'       => $skills->pluck('name')->values()->all(),
            'topSkills'    => $this->topSkills($skills),
            'softSkills'   => $this->softSkills($skills),
            'languages'    => $this->languages(),
            'education'    => $this->education(),
            'whyChooseMe'  => $this->whyChooseMe($headline, $about, $skills),
            'services'     => $this->services(),
        ];
    }


    








/** @return array<int, array{name:string,percentage:int,level:int}> */
public function skillsData(): array
{
    // Eager-loaded: $owner->skills (belongsToMany) with withPivot(level, position)
    $skills = $this->owner->skills ?? collect();

    // Sort by pivot position if present; keep original order otherwise
    $skills = $skills->sortBy(function ($s) {
        return $s->pivot->position ?? PHP_INT_MAX;
    });

    $levelToPct = static function (int $level): int {
        return match ($level) {
            3 => 95,   // Expert
            2 => 80,   // Proficient
            default => 60, // Beginner or unknown
        };
    };

    return $skills->map(function ($s) use ($levelToPct) {
        // Pull level from pivot first, then from column fallback if you ever store it there
        $level = (int)($s->pivot->level ?? $s->level ?? 1);

        return [
            'name'       => (string) $s->name,
            'percentage' => $levelToPct($level),
            'level'      => $level,
        ];
    })->values()->all();
}

/** @return array<int, string> */
private function topSkills(Collection $skills): array
{
    // Use pivot->level/position for ranking
    return $skills
        ->sortBy([
            fn ($s) => -1 * (int)($s->pivot->level ?? $s->level ?? 1),   // highest level first
            fn ($s) => (int)($s->pivot->position ?? PHP_INT_MAX),
        ])
        ->pluck('name')
        ->take(3)
        ->values()
        ->all();
}

/** @return array<int, array{name:string,icon:string}> */
private function softSkills(Collection $skills): array
{
    // unchanged logic; still works
    return $skills
        ->filter(function ($s) {
            $n = strtolower($s->name);
            return str_contains($n, 'communication')
                || str_contains($n, 'time')
                || str_contains($n, 'leadership')
                || str_contains($n, 'problem');
        })
        ->take(3)
        ->values()
        ->map(function ($s) {
            $icon = 'sparkles';
            $n = strtolower($s->name);
            if (str_contains($n, 'problem'))     $icon = 'lightbulb';
            elseif (str_contains($n, 'commun'))  $icon = 'mobile-screen';
            elseif (str_contains($n, 'time'))    $icon = 'clock';
            elseif (str_contains($n, 'leader'))  $icon = 'award';
            return ['name' => $s->name, 'icon' => $icon];
        })
        ->all();
}












    /** @return array<int, array{name:string,level:string}> */
    private function languages(): array
    {
        if (! method_exists($this->owner, 'languages')) return [];
        return $this->owner->languages->map(fn ($l) => [
            'name'  => $l->name,
            'level' => $l->pivot->level ?? $l->level ?? '',
        ])->values()->all();
    }

    /** @return array<int, array{title:string,institution:string,period:string,location:string,recent:bool}> */
    private function education(): array
    {
        return collect($this->owner->educations ?? [])->map(function ($e) {
            $period = '';
            if ($e->start_year || $e->end_year || $e->is_current) {
                $from = $e->start_year ?: '—';
                $to   = $e->is_current ? 'Present' : ($e->end_year ?: '—');
                $period = "$from - $to";
            }
            return [
                'title'       => trim(collect([$e->degree, $e->field])->filter()->join(' · ')) ?: 'Education',
                'institution' => $e->school ?: '—',
                'period'      => $period,
                'location'    => trim(collect([$e->city ?? null, $e->country ?? null])->filter()->join(', ')),
                'recent'      => (bool) $e->is_current,
            ];
        })->values()->all();
    }

    /** @return array<int, string> */
    private function whyChooseMe(?string $headline, ?string $about, Collection $skills): array
    {
        if (property_exists($this->owner, 'highlights') && is_array($this->owner->highlights)) {
            return array_values($this->owner->highlights);
        }
        $reasons = [];
        if ($headline) $reasons[] = $headline;
        if ($about)    $reasons[] = 'Client-focused, reliable communication';
        if ($skills->isNotEmpty()) $reasons[] = 'Proven with: ' . $skills->pluck('name')->take(4)->implode(', ');
        return $reasons;
    }

    /** @return array<int, string> */
    private function services(): array
    {
        $prefs = $this->owner->preferences;
        if (! $prefs || empty($prefs->services)) return [];
        return is_array($prefs->services) ? $prefs->services : (array) json_decode($prefs->services, true);
    }

    /** @return array<int, array{title:string,description:?string,link:?string,image:?string,tags:array,category:?string}> */
 // app/ViewModels/ProfileViewModel.php  (only the portfolio bits)
 public function portfolios(): array
 {
     return collect($this->owner->portfolios ?? [])
         ->map(function ($p) {
             $tags = [];
             $cat  = null;
             if (is_array($p->meta)) {
                 $tags = $p->meta['tags'] ?? [];
                 $cat  = $p->meta['category'] ?? null;
             }
             return [
                 'title'       => (string) $p->title,
                 'description' => (string) $p->description,
                 'link'        => $p->link_url ?: null,
                 'image'       => $p->image_url,   // accessor on model
                 'tags'        => is_array($tags) ? array_values($tags) : [],
                 'category'    => $cat,
             ];
         })
         ->values()
         ->all();
 }

 /** @return array<int, string> */
 public function portfolioCategories(): array
 {
     return array_values(array_unique(array_filter([
         'All',
         ...collect($this->portfolios())->pluck('category')->filter()->all(),
     ])));
 }


   


    /** @return array<int, array{company:string,title:string,description:?string,location:string,date:string,skills:array}> */
  // e.g. in App\ViewModels\ProfileViewModel::experiences()
public function experiences(): array
{
    return collect($this->user->experiences ?? [])
        ->map(function ($e) {
            $period = '';
            if ($e->start_month && $e->start_year) {
                $from = \Carbon\Carbon::createFromDate($e->start_year, $e->start_month, 1)->format('M Y');
                $to   = $e->is_current
                    ? 'Present'
                    : (($e->end_month && $e->end_year)
                        ? \Carbon\Carbon::createFromDate($e->end_year, $e->end_month, 1)->format('M Y')
                        : '');
                $period = $to ? "$from — $to" : $from;
            }

            return [
                'company'     => (string) $e->company,
                'title'       => (string) $e->title,
                'description' => (string) ($e->description ?? ''),
                'location'    => trim(collect([$e->location_city, $e->location_country])->filter()->join(', ')),
                'period'      => $period,                 // ✅ match Blade
                'current'     => (bool) $e->is_current,   // ✅ Blade uses this too
                'skills'      => collect($e->skills ?? [])
                                    ->sortBy('position')
                                    ->map(fn ($s) => [
                                        'name'  => (string) $s->name,
                                        'level' => (int) $s->level,
                                    ])->values()->all(),
            ];
        })
        ->values()
        ->all();
}

    /** @return array<int, array{author:string,rating:int,comment:string,date:?string}> */
    public function reviews(): array
    {
        if (! method_exists($this->owner, 'reviews')) return [];
        return $this->owner->reviews()->latest()->take(10)->get()->map(fn ($r) => [
            'author'  => $r->author_name ?? 'Client',
            'rating'  => (int) ($r->rating ?? 5),
            'comment' => $r->comment ?? '',
            'date'    => optional($r->created_at)->toFormattedDateString(),
        ])->all();
    }
}
