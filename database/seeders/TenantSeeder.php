<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Stancl\Tenancy\Database\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // id is your tenant key; data can store extra meta like domain
        Tenant::firstOrCreate(
            ['id' => 'acme'],
            ['data' => ['name' => 'Acme', 'domain' => 'acme.localhost']]
        );

        Tenant::firstOrCreate(
            ['id' => 'beta'],
            ['data' => ['name' => 'Beta', 'domain' => 'beta.localhost']]
        );
    }
}
