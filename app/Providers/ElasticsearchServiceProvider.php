<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(\Elastic\Elasticsearch\Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200')])
                ->setHttpClient(new \GuzzleHttp\Client())
                ->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
