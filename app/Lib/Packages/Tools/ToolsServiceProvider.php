<?php namespace Lib\Packages\Tools;

use Guzzle\Http\Client;
use Illuminate\Support\ServiceProvider;
use Lib\Packages\Tools\OpenGraph\OpenGraph;

class ToolsServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton("Tools/OpenGraph", function() {
            return new OpenGraph((new Client()));
        });
    }

    /**
     * @return OpenGraph
     * @deprecated Now using the Facebook SDK for reliably fetching OpenGraph data
     */
    public static function makeOpenGraph() : OpenGraph
    {
        return \App::make("Tools/OpenGraph");
    }
}
