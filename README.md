# Laravel CSV Response Macro

A lightweight Laravel package that adds a response macro for streaming CSV data directly to the browser, optimized for memory efficiency and designed to handle large datasets with ease.

## Installation

You can install the package via Composer. Run the following command in your terminal:

```bash
composer require kfoobar/laravel-csv-response
```

After installing the package, publish the configuration file using the Artisan command:

```bash
php artisan vendor:publish --tag=csv-response-config
```

This will create a `csv-response.php` file in your `config` directory, where you can customize the package settings.

## How to Use

```php
return response()->csv(
    rows: [], 
    options: [],
    inline: true // false = force download
);
```

### Display CSV Inline
To render the CSV directly in the browser:

```php
$rows = Product::all()
    ->map(function (Product $product) {
        return [
            $product->sku,
            $product->name,
            $product->price,
        ];
    })
    ->toArray();

return response()->csv($rows, inline: true);
```

### Download the CSV
To force the CSV file to download:

```php
$rows = Product::all()
    ->map(function (Product $product) {
        return [
            $product->sku,
            $product->name,
            $product->price,
        ];
    })
    ->toArray();

return response()->csv($rows, inline: false);
```

### Using Options

You may customize the CSV output using the options parameter:

```php
$rows = Product::all()
    ->map(function (Product $product) {
        return [
            $product->sku,
            $product->name,
            $product->price,
        ];
    })
    ->toArray();

$options = [
    'delimiter' => ',',
    'filename'  => 'catalog.csv',
    'headers'   => ['sku', 'name', 'price'],
];

return response()->csv($rows, $options, inline: false);
```

*You can also include the headers directly as the first row in the `$rows` array if you prefer not to define them in the `headers` option.*

## Contribution

Contributions are welcome! If you'd like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Make your changes and ensure tests pass.
4. Submit a pull request with a detailed description of your changes.

## License

This package is open-source and released under the MIT License. See [LICENSE](LICENSE) for more information.
