<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportCompanies extends Command
{
    protected $signature = 'companies:import
        {--countryQid= : Optional Wikidata country QID (e.g., Q843 for Pakistan)}
        {--limit=500   : Rows per page (keep lower for WDQS)}
        {--max=10000   : Max total rows to import}
        {--sleep=2     : Seconds to sleep between pages}
        {--truncate    : Truncate companies table before import}
        {--debug       : Show detailed debug info}';

    protected $description = 'Import companies from Wikidata - Optimized & Fast';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            DB::table('companies')->truncate();
            $this->info('✓ Companies table truncated.');
        }

        $countryQid = $this->option('countryQid') ?: null;
        $limit      = max(100, min(500, (int) $this->option('limit')));  // cap at 500
        $max        = max(1, (int) $this->option('max'));
        $sleep      = max(1, (int) $this->option('sleep'));
        $debug      = (bool) $this->option('debug');

        $imported = 0;
        $offset   = 0;

        $this->info("🚀 Starting Wikidata company import...");
        $this->info("📊 Settings: limit/page={$limit}, max={$max}, country=" . ($countryQid ?: 'ALL'));
        $this->newLine();

        $bar = $this->output->createProgressBar($max);
        $bar->start();

        while ($imported < $max) {
            $chunk = $this->fetchChunk($countryQid, $limit, $offset, $debug);
            $count = count($chunk);
            
            if ($count === 0) {
                if ($debug) $this->warn("\nNo more results from Wikidata");
                break;
            }

            foreach ($chunk as $row) {
                $name    = trim($row['name'] ?? '');
                $qid     = $row['qid'] ?? null;
                $website = $row['website'] ?? null;

                if ($name === '' || !$qid) continue;

                // Extract domain
                $domain = $this->extractDomain($website);
                
                // Get logo (prefer Wikidata, fallback to Google favicon)
                $logo = $row['logo_url'] ?? null;
                if (!$logo && $domain) {
                    $logo = "https://logo.clearbit.com/{$domain}";
                }

                try {
                    Company::updateOrCreate(
                        ['wikidata_qid' => $qid],
                        [
                            'name'         => mb_substr($name, 0, 255),
                            'country'      => $row['country'] ?? null,
                            'country_code' => $row['country_code'] ?? null,
                            'city'         => $row['city'] ?? null,
                            'website'      => $website,
                            'domains'      => $domain ? [$domain] : [],
                            'logo_url'     => $logo,
                            'aliases'      => $row['aliases'] ?? [],
                        ]
                    );
                    $imported++;
                    $bar->advance();
                } catch (\Exception $e) {
                    if ($debug) {
                        $this->warn("\nFailed to save: {$name} - " . $e->getMessage());
                    }
                }

                if ($imported >= $max) break;
            }

            $offset += $limit;
            
            if ($count < $limit) {
                if ($debug) $this->info("\nReached end of results (got {$count} < {$limit})");
                break;
            }
            
            if ($imported >= $max) break;
            
            // Be nice to Wikidata servers
            sleep($sleep);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Done! Total imported: {$imported} companies");
        
        return self::SUCCESS;
    }

    protected function extractDomain(?string $url): ?string
    {
        if (!$url) return null;
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) return null;
        return strtolower(preg_replace('/^www\./i', '', $host));
    }

    /**
     * Simplified SPARQL query - much faster!
     */
    protected function fetchChunk(?string $countryQid, int $limit, int $offset, bool $debug): array
    {
        // Simple query: just get organizations (Q43229) with websites
        // This is MUCH faster than complex class hierarchies
        
        $countryFilter = $countryQid 
            ? "?org wdt:P17 wd:{$countryQid} ."
            : "OPTIONAL { ?org wdt:P17 ?country . }";

        $sparql = <<<SPARQL
SELECT DISTINCT ?org ?orgLabel ?website ?logo ?countryLabel ?countryCode ?cityLabel
WHERE {
  # Instance of: organization (Q43229) OR business (Q4830453)
  VALUES ?type { wd:Q43229 wd:Q4830453 }
  ?org wdt:P31 ?type .
  
  # Must have official website
  ?org wdt:P856 ?website .
  
  # Country (optional or required based on filter)
  {$countryFilter}
  
  # Optional: logo
  OPTIONAL { ?org wdt:P154 ?logo . }
  
  # Optional: headquarters location
  OPTIONAL { 
    ?org wdt:P159 ?hq .
    ?hq rdfs:label ?cityLabel .
    FILTER(LANG(?cityLabel) = 'en')
  }
  
  # Optional: country ISO code
  OPTIONAL { ?country wdt:P297 ?countryCode . }
  
  # Get labels in English
  SERVICE wikibase:label { 
    bd:serviceParam wikibase:language "en" . 
  }
}
LIMIT {$limit}
OFFSET {$offset}
SPARQL;

        try {
            $response = Http::timeout(90)
                ->retry(3, 2000)
                ->withHeaders([
                    'Accept'     => 'application/sparql-results+json',
                    'User-Agent' => 'ProMatch-CompanyImporter/2.0 (Educational; +https://github.com/skillleo)'
                ])
                ->get('https://query.wikidata.org/sparql', [
                    'query'  => $sparql,
                    'format' => 'json',
                ]);

            if (!$response->successful()) {
                if ($debug) {
                    $this->error("\n❌ WDQS returned HTTP {$response->status()}");
                    $this->line("Response: " . substr($response->body(), 0, 500));
                }
                return [];
            }

            $json = $response->json();
            $bindings = Arr::get($json, 'results.bindings', []);
            
            if ($debug && $offset === 0) {
                $this->info("📦 First batch received: " . count($bindings) . " results");
            }

            $results = [];
            foreach ($bindings as $b) {
                $uri = Arr::get($b, 'org.value');
                $qid = $uri ? basename($uri) : null;
                
                if (!$qid) continue;

                $name    = Arr::get($b, 'orgLabel.value');
                $website = Arr::get($b, 'website.value');
                $country = Arr::get($b, 'countryLabel.value');
                $countryCode = Arr::get($b, 'countryCode.value');
                $city    = Arr::get($b, 'cityLabel.value');
                $logo    = Arr::get($b, 'logo.value');

                // Convert Wikimedia Commons file to URL
                $logoUrl = null;
                if ($logo) {
                    if (preg_match('~^https?://~i', $logo)) {
                        $logoUrl = $logo;
                    } else {
                        // It's a filename, convert to Wikimedia URL
                        $encoded = rawurlencode($logo);
                        $logoUrl = "https://commons.wikimedia.org/wiki/Special:FilePath/{$encoded}?width=200";
                    }
                }

                $results[] = [
                    'qid'          => $qid,
                    'name'         => $name,
                    'country'      => $country,
                    'country_code' => $countryCode ? strtoupper($countryCode) : null,
                    'city'         => $city,
                    'website'      => $website,
                    'logo_url'     => $logoUrl,
                    'aliases'      => [],
                ];
            }

            return $results;

        } catch (\Throwable $e) {
            if ($debug) {
                $this->error("\n❌ Query failed: " . $e->getMessage());
            }
            return [];
        }
    }
}