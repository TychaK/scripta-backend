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

class SyncGuardianArticles extends Command
{
    use ArticlesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:guardian_api {category}';

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

        Log::info("Starting to get guardian articles ");

        $client = Client::where('slug', 'guardian-api')->first();

        if (!$client) {
            Log::info("Unable to find such a client ... aborting ...");
            return "Processing aborted ...";
        }

        $category = (object)$this->argument('category');

        Log::info("Received category" . json_encode($category));

        // get the latest news for this category, note the last fetch time

        // we will ignore pagination for now but the service can be

        // improved to include pagination of records ...

        $baseUrl = $client->base_url;

        $apiKey = $client->api_key;

        $params = [
            'api-key' => $apiKey,
            'section' => $category->name,
            'show-fields' => implode(',', [
                'body',
                'thumbnail',
                'headline'
            ]),
            'show-tags' => implode(',', [
                'contributor'
            ])
        ];

        $url = $baseUrl . "?" . http_build_query($params);

        Log::info("Generated url with params as " . $url);

        $response = (object)$this->fetch($url);

        Log::info("Got articles for client " . $client->name . ' as ' . json_encode($response));

        // map through articles and save in a model that is universal

        $data = ($response->response->results);

        $articles = collect($data)->map(function ($article) use ($category) {

            $contributors = collect($article->tags)->map(function ($tag) {
                return $tag->webTitle;
            })->all();

            Log::info("Contributors" . json_encode($contributors));

            Log::info("Article now " . json_encode($article));

            $authorName = $article->tags->author ?? 'Unknown';

            $author = Author::updateOrCreate([
                'name' => $authorName
            ]);

            return [
                'category_id' => $category->id,
                'author_id' => $author->id,
                'contributors' => implode(',', $contributors),
                'title' => $article->webTitle,
                'description' => $article->fields->body ?? $article->fields->headline,
                'url' => $article->webUrl,
                'image_url' => $article->fields->thumbnail,
                'published_at' => Carbon::parse($article->webPublicationDate)
            ];
        })->all();

        Log::info("Mapped" . json_encode($articles));

        Article::insertOrIgnore($articles);

        Log::info("Finished fetching news api ... ");

    }

}
