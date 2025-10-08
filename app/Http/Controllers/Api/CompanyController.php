<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // GET /api/companies/search?q=micros&limit=10
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $limit = max(1, min(20, (int) $request->query('limit', 10)));

        if ($q === '') return response()->json(['data' => []]);

        $likeStart = $q . '%';
        $likeAny   = '%' . $q . '%';

        $rows = Company::query()
            ->select(['id','name','country','city','logo_url','website','domains'])
            ->where(function($w) use ($likeStart, $likeAny) {
                $w->where('name', 'LIKE', $likeStart)
                  ->orWhere('name', 'LIKE', $likeAny);
            })
            ->limit($limit)
            ->get()
            ->map(function($c){
                return [
                    'id'      => $c->id,
                    'name'    => $c->name,
                    'country' => $c->country,
                    'city'    => $c->city,
                    'logo'    => $c->logo_url,
                    'website' => $c->website,
                    'domain'  => is_array($c->domains) && count($c->domains) ? $c->domains[0] : null,
                ];
            });

        return response()->json(['data' => $rows]);
    }

    // GET /api/companies?page=1
    public function index(Request $request)
    {
        $perPage = max(5, min(100, (int) $request->query('per_page', 25)));
        $paginator = Company::query()
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
