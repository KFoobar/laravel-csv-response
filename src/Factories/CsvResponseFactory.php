<?php

namespace KFoobar\CsvResponse\Factories;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvResponseFactory
{
    /**
     * Creates a streamed CSV response for inline display.
     *
     * @param array $rows
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function inline(array $rows, array $options = []): StreamedResponse
    {
        $options = self::options($options);

        return new StreamedResponse(
            fn() => self::stream($rows, $options),
            200,
            [
                'Content-Type' => 'text/plain; charset=' . ($options['encoding'] ?? 'UTF-8'),
                'Content-Disposition' => 'inline',
            ]
        );
    }

    /**
     * Creates a streamed CSV response for file download.
     *
     * @param array $rows
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function download(array $rows, array $options = []): StreamedResponse
    {
        $options = self::options($options);
        $filename = self::filename($options['filename'] ?? null);

        return new StreamedResponse(
            fn() => self::stream($rows, $options),
            200,
            [
                'Content-Type' => 'text/csv; charset=' . ($options['encoding'] ?? 'UTF-8'),
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    /**
     * Outputs CSV-formatted data to php://output.
     *
     * @param array $rows
     * @param array $options
     */
    public static function stream(array $rows, array $options = []): void
    {
        $handle = fopen('php://output', 'w');
        $delimiter = $options['delimiter'] ?? ';';

        if (!empty($options['headers'])) {
            fputcsv($handle, (array) $options['headers'], $delimiter);
        }

        foreach ($rows as $row) {
            fputcsv($handle, (array) $row, $delimiter);
        }

        fclose($handle);
    }

    /**
     * Merges provided options with the default configuration.
     *
     * @param array $options
     *
     * @return array
     */
    public static function options(array $options = []): array
    {
        return array_replace(config('csv-response'), $options);
    }

    /**
     * Generates a filename for CSV downloads.
     *
     * @param null|string $filename
     *
     * @return string
     */
    public static function filename(?string $filename): string
    {
        return $filename ?? 'export-' . Str::uuid() . '.csv';
    }
}
