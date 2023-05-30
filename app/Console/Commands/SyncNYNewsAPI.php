<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Client;
use App\Traits\ArticlesTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncNYNewsAPI extends Command
{
    use ArticlesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ny_api {category}';

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
        Log::info("Starting to get ny news api articles ");

        $categories = Category::all();

        $client = Client::where('slug', 'ny-api')->first();

        if (!$client) {
            Log::info("Unable to find such a client ... aborting ...");
            return;
        }

        $category = (object)$this->argument('category');

        // get the latest news for this category, note the last fetch time

        // we will ignore pagination for now but the service can be

        // improved to include pagination of records ...

        $baseUrl = $client->base_url;

        $apiKey = $client->api_key;

        $params = [
            'api-key' => $apiKey,
            'q' => $category->name
        ];

        $url = $baseUrl . "?" . http_build_query($params);

        Log::info("Generated url with params as " . $url);

        $response = (object)$this->fetch($url);

        if (count(get_object_vars($response)) == 0) {
            return;
        }

        Log::info("Got articles for client " . $client->name . ' as ' . json_encode($response));

        // map through articles and save in a model that is universal

        $data = ($response->response->docs);

        Log::info("Articles now raw " . json_encode($data));

        $articles = collect($data)->map(function ($article) use ($category) {

            Log::info("Article now " . json_encode($article));

            $authorName = $article->byline->original ?? 'Unknown';

            $author = Author::updateOrCreate([
                'name' => $authorName
            ]);

            $imageUrl = null;

            $images = $article->multimedia;

            if (sizeof($images) > 0) {

                $initialImage = $images[0];

                $imageUrl = "https://www.nytimes.com/" . $initialImage->url;
            }

            // todo; add article images table for multiple media ...

            return [
                'category_id' => $category->id,
                'author_id' => $author->id,
                'title' => $article->headline->main,
                'description' => $article->abstract,
                'url' => $article->web_url,
                'image_url' => $imageUrl,
                'published_at' => Carbon::parse($article->pub_date)
            ];
        })->all();

        Log::info("Mapped" . json_encode($articles));

        Article::insertOrIgnore($articles);

        Log::info("Finished fetching ny api ... ");

    }
}
