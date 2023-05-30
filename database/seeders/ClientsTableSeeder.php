<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $clients = [
            [
                'name' => 'News ORG',
                'slug' => 'news-api',
                'base_url' => 'https://newsapi.org/v2/top-headlines',
                'api_key' => '7530f61520e54e7ea29eb5e705910e5c'
            ],
            [
                'name' => 'Guardian News',
                'slug' => 'guardian-api',
                'base_url' => 'https://content.guardianapis.com/search',
                'api_key' => '7e380c45-8604-472a-90a4-c62eec6cb37d'
            ],
            [
                'name' => 'New York Times',
                'slug' => 'ny-api',
                'base_url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
                'api_key' => 'Dfs6AQFCMxa30xGfUZxHEQml8vHNtbzA'
            ]
        ];

        collect($clients)->each(function ($client) {
            Client::updateOrCreate($client);
        });
    }
}
