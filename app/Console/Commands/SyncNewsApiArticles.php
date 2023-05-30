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

class SyncNewsApiArticles extends Command
{
    use ArticlesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:news_api {category}';

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
        Log::info("Starting to get news api articles ");

        $categories = Category::all();

        $client = Client::where('slug', 'news-api')->first();

        if (!$client) {
            Log::info("Unable to find such a client ... aborting ...");
        }

        $category = (object)$this->argument('category');

        // get the latest news for this category, note the last fetch time

        // we will ignore pagination for now but the service can be

        // improved to include pagination of records ...

        $baseUrl = $client->base_url;

        $apiKey = $client->api_key;

        $params = [
            'apiKey' => $apiKey,
            'category' => $category->name
        ];

        $url = $baseUrl . "?" . http_build_query($params);

        Log::info("Generated url with params as " . $url);

        $response = (object)$this->fetch($url);

        Log::info("Got articles for client " . $client->name . ' as ' . json_encode($response));

        if (count(get_object_vars($response)) == 0) {
            Log::info("No Articles for category ");
            return;
        }

        // map through articles and save in a model that is universal

        $data = ($response->articles);

        $articles = collect($data)->map(function ($article) use ($category) {

            Log::info("Article now " . json_encode($article));

            $authorName = $article->author ?? 'Unknown';

            $author = Author::updateOrCreate([
                'name' => $authorName
            ]);

            return [
                'category_id' => $category->id,
                'author_id' => $author->id,
                'title' => $article->title,
                'description' => $article->content ?? $article->description,
                'url' => $article->url,
                'image_url' => $article->urlToImage,
                'published_at' => Carbon::parse($article->publishedAt)
            ];
        })->all();

        Log::info("Mapped" . json_encode($articles));

        Article::insertOrIgnore($articles);

        Log::info("Finished fetching guardian api ... ");

    }
}
