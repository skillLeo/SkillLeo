<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    // GET /api/institutions/search?q=oxford&limit=10
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min(20, $limit));

        if ($q === '') {
            return response()->json(['data' => []]);
        }

        // Prioritize starts-with, then contains; simple + fast
        $likeStart = $q.'%';
        $likeAny   = '%'.$q.'%';

        $query = Institution::query()
            ->select(['id','name','country','country_code','city','logo_url','website','domains'])
            ->where(function($w) use ($likeStart, $likeAny) {
                $w->where('name', 'LIKE', $likeStart)
                  ->orWhere('name', 'LIKE', $likeAny);
            })
            ->limit($limit);

        $rows = $query->get()->map(function ($i) {
            return [
                'id'      => $i->id,
                'name'    => $i->name,
                'country' => $i->country,
                'city'    => $i->city,
                'logo'    => $i->logo_url,
                'website' => $i->website,
                'domain'  => is_array($i->domains) && count($i->domains) ? $i->domains[0] : null,
            ];
        });

        return response()->json(['data' => $rows]);
    }

    // GET /api/institutions?page=1
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(5, min(100, $perPage));

        $paginator = Institution::query()
            ->orderBy('country')
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}
