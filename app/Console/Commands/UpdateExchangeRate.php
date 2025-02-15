<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;


class UpdateExchangeRate extends Command
{
    protected $signature = 'exchange:update';
    protected $description = 'Update kurs secara otomatis setiap hari';
    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get('https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursJisdor1', [
                'headers' => [
                    'Accept' => 'application/xml',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ],
                'verify' => false,
            ]);

            $xmlString = $response->getBody()->getContents();
            $xml = simplexml_load_string($xmlString);

            if ($xml === false) {
                DB::table('exchange_logs')->insert([
                    'status'    => 'error',
                    'message'   => 'Gagal memparsing XML dari API.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return;
            }

            $namespaces = $xml->getNamespaces(true);
            $diffgram = $xml->children($namespaces['diffgr']);
            $newDataSet = $diffgram->children()->NewDataSet;
            $tables = $newDataSet->children();

            $dataArray = [];
            foreach ($tables as $row) {
                $dataArray[] = [
                    'tgl_subkursasing' => (string) $row->tgl_subkursasing,
                    'nilai'            => (string) $row->beli_subkursasing,
                ];
            }

            usort($dataArray, function ($a, $b) {
                return strtotime($b['tgl_subkursasing']) - strtotime($a['tgl_subkursasing']);
            });

            $latestData = $dataArray[0] ?? null;

            if ($latestData) {
                $nominal = (int) $latestData['nilai'];

                // Cek apakah sudah ada data kurs untuk hari ini
                $existing = DB::table('exchange_rate')
                    ->whereDate('created_at', now()->toDateString())
                    ->first();

                if ($existing) {
                    DB::table('exchange_rate')
                        ->where('id_exchange_rate', $existing->id_exchange_rate)
                        ->update([
                            'nominal'    => $nominal,
                            'updated_at' => now(),
                        ]);
                    $message = "Kurs berhasil diperbarui: Nominal = {$nominal} Dari BI";
                } else {
                    DB::table('exchange_rate')->insert([
                        'nominal'    => $nominal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $message = "Kurs baru berhasil disimpan: Nominal = {$nominal} Dari BI";
                }

                // Simpan log sukses
                DB::table('exchange_logs')->insert([
                    'nominal'    => $nominal,
                    'status'     => 'success',
                    'message'    => $message,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Simpan log error jika terjadi kesalahan
            $error = $e->getMessage().'Dari BI';
            DB::table('exchange_logs')->insert([
                'status'     => 'error',
                'message'    => $error,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
