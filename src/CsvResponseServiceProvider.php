<?php

namespace KFoobar\CsvResponse;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use KFoobar\CsvResponse\Factories\CsvResponseFactory;

class CsvResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/csv-response.php',
            'csv-response'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/csv-response.php' => config_path('csv-response.php'),
        ], 'csv-response-config');

        Response::macro('csv', function (array $rows, ?array $options = [], ?bool $inline = true) {
            return ($inline === true)
                ? CsvResponseFactory::inline($rows, $options)
                : CsvResponseFactory::download($rows, $options);
        });
    }
}
