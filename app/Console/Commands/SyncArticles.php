<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SyncArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categories = Category::all();

        while (true) {
            collect($categories)->each(function ($category) {
                Artisan::call('sync:guardian_api', ['category' => $category]);
                Artisan::call('sync:news_api', ['category' => $category]);
                Artisan::call('sync:ny_api', ['category' => $category]);
            });

            sleep(env('ARTICLES_SYNC_INTERVAL'));

        }

    }
}
