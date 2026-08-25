<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports a DB-IP "country lite" CSV (ip_from,ip_to,country_code — no
 * header row, no country name) into ip_ranges and ipv6_ranges. Free, no
 * signup, no API key: https://db-ip.com/db/download/ip-to-country-lite —
 * pass either a local path or a direct URL to the .csv or .csv.gz file.
 *
 * Both address families live in the same source file; each row is routed
 * to whichever table its address actually fits.
 */
#[Signature('geoip:import {source : Local file path or URL to the DB-IP country-lite CSV/CSV.GZ}')]
#[Description('Import an IP-to-country dataset into ip_ranges and ipv6_ranges')]
class ImportGeoIp extends Command
{
    private const CHUNK_SIZE = 1000;

    public function handle(): int
    {
        $source = $this->argument('source');
        $path = $this->localize($source);

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
        DB::table('ipv6_ranges')->truncate();

        $v4Buffer = [];
        $v6Buffer = [];
        $v4Count = 0;
        $v6Count = 0;
        $skipped = 0;
        $read = str_ends_with($path, '.gz') ? 'gzgets' : 'fgets';

        while (($line = $read($handle)) !== false) {
            $row = str_getcsv(trim($line));

            if (count($row) < 3) {
                $skipped++;

                continue;
            }

            [$from, $to, $code] = $row;
            $name = $countries[$code] ?? $code;

            if (str_contains($from, ':')) {
                $fromBinary = @inet_pton($from);
                $toBinary = @inet_pton($to);

                if ($fromBinary === false || $toBinary === false) {
                    $skipped++;

                    continue;
                }

                $v6Buffer[] = ['ip_from' => $fromBinary, 'ip_to' => $toBinary, 'country_code' => $code, 'country_name' => $name];

                if (count($v6Buffer) >= self::CHUNK_SIZE) {
                    DB::table('ipv6_ranges')->insert($v6Buffer);
                    $v6Count += count($v6Buffer);
                    $v6Buffer = [];
                    $this->output->write('.');
                }

                continue;
            }

            $fromLong = ip2long($from);
            $toLong = ip2long($to);

            if ($fromLong === false || $toLong === false) {
                $skipped++;

                continue;
            }

            $v4Buffer[] = [
                'ip_from' => sprintf('%u', $fromLong),
                'ip_to' => sprintf('%u', $toLong),
                'country_code' => $code,
                'country_name' => $name,
            ];

            if (count($v4Buffer) >= self::CHUNK_SIZE) {
                DB::table('ip_ranges')->insert($v4Buffer);
                $v4Count += count($v4Buffer);
                $v4Buffer = [];
                $this->output->write('.');
            }
        }

        if ($v4Buffer) {
            DB::table('ip_ranges')->insert($v4Buffer);
            $v4Count += count($v4Buffer);
        }

        if ($v6Buffer) {
            DB::table('ipv6_ranges')->insert($v6Buffer);
            $v6Count += count($v6Buffer);
        }

        $read === 'gzgets' ? gzclose($handle) : fclose($handle);

        $this->newLine();
        $this->info("Imported {$v4Count} IPv4 and {$v6Count} IPv6 ranges (skipped {$skipped} invalid rows).");

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
