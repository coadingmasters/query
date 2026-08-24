<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports a DB-IP "country lite" CSV (ip_from,ip_to,country_code — no
 * header row, no country name) into ip_ranges. Free, no signup, no API key:
 * https://db-ip.com/db/download/ip-to-country-lite — pass either a local
 * path or a direct URL to the .csv or .csv.gz file.
 *
 * IPv6 rows are skipped: ip_ranges stores plain unsigned integers, which
 * only fit an IPv4 address. A visitor on IPv6 simply gets no country rather
 * than a wrong one.
 */
#[Signature('geoip:import {source : Local file path or URL to the DB-IP country-lite CSV/CSV.GZ}')]
#[Description('Import an IP-to-country dataset into ip_ranges')]
class ImportGeoIp extends Command
{
    private const CHUNK_SIZE = 1000;

    public function handle(): int
    {
        $path = $this->localize($this->argument('source'));

        if (! $path) {
            $this->error("Could not read {$source}");

            return self::FAILURE;
        }

        $countries = config('countries');
        $handle = str_ends_with($path, '.gz') ? gzopen($path, 'r') : fopen($path, 'r');

        if (! $handle) {
            $this->error("Could not open {$path}");

            return self::FAILURE;
        }

        DB::table('ip_ranges')->truncate();

        $buffer = [];
        $imported = 0;
        $skipped = 0;
        $read = str_ends_with($path, '.gz') ? 'gzgets' : 'fgets';

        while (($line = $read($handle)) !== false) {
            $row = str_getcsv(trim($line));

            if (count($row) < 3 || str_contains($row[0], ':')) {
                $skipped++;

                continue;
            }

            [$from, $to, $code] = $row;
            $fromLong = ip2long($from);
            $toLong = ip2long($to);

            if ($fromLong === false || $toLong === false) {
                $skipped++;

                continue;
            }

            $buffer[] = [
                'ip_from' => sprintf('%u', $fromLong),
                'ip_to' => sprintf('%u', $toLong),
                'country_code' => $code,
                'country_name' => $countries[$code] ?? $code,
            ];

            if (count($buffer) >= self::CHUNK_SIZE) {
                DB::table('ip_ranges')->insert($buffer);
                $imported += count($buffer);
                $buffer = [];
                $this->output->write('.');
            }
        }

        if ($buffer) {
            DB::table('ip_ranges')->insert($buffer);
            $imported += count($buffer);
        }

        $read === 'gzgets' ? gzclose($handle) : fclose($handle);

        $this->newLine();
        $this->info("Imported {$imported} IPv4 ranges (skipped {$skipped} IPv6/invalid rows).");

        return self::SUCCESS;
    }

    /** Downloads a remote source to a temp file; returns a local path either way, or null on failure. */
    private function localize(string $source): ?string
    {
        if (! str_starts_with($source, 'http://') && ! str_starts_with($source, 'https://')) {
            return is_readable($source) ? $source : null;
        }

        $this->info("Downloading {$source}...");
        $contents = @file_get_contents($source);

        if ($contents === false) {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'geoip').(str_ends_with($source, '.gz') ? '.gz' : '.csv');
        file_put_contents($tmp, $contents);

        return $tmp;
    }
}
