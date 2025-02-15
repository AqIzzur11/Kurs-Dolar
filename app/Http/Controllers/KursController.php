<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB; // ✅ Tambahkan ini!
use Carbon\Carbon;

class KursController extends Controller
{

    public function showKurs()
    {
        return view('kurs');
    }

    public function getKurs(Request $request)
    {
        $kursTerbaru = DB::table('exchange_rate')
        // ->where('updated_at', date('Y-m-d'))
        ->first(); // Ambil kurs terbaru

        // dd($kursTerbaru);
        $jual = $kursTerbaru->nominal ?? 0;
        $formattedDate = Carbon::parse($kursTerbaru->updated_at)->format('Y-m-d');
        $formattedclock = Carbon::parse($kursTerbaru->updated_at)->format('H:i:s');
        $tanggal = Carbon::parse($formattedDate)->format('d-m-Y');
        // $jual = 16235;
    return response()->json([
        'kursJual'  => $jual,
        'tanggal'   => $tanggal ?? date('d-m-Y'),
        'jam'       => $formattedclock ?? date('H:i:s'),

    ]);

    }      
        // dd($latestData);


        // $client = new Client();
    
        // // Ambil tanggal dari request atau default ke 5 hari terakhir
        // $startDate = $request->input('startdate', now()->subDays(5)->format('Y-m-d'));
        // $endDate = $request->input('enddate', now()->format('Y-m-d'));
    
        // $url = 'https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursLokal1';
    
        // try {
    
            // $client = new Client();
            // $response = $client->get('https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursJisdor1', [
            //     'headers' => [
            //         'Accept' => 'application/xml',
            //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                
            //     ],'verify' => false, // Nonaktifkan verifikasi SSL
            // ]);
            // // echo $response;
            // $xmlString = $response->getBody()->getContents();
            // // dd($xmlString);
            // // echo $xmlString;
            // $xml = simplexml_load_string($xmlString);
            // if ($xml === false) {
            //     die("Gagal memparsing XML.");
            // }
            
            // // Daftarkan namespace
            // $namespaces = $xml->getNamespaces(true);
            
            // // Akses <diffgr:diffgram>
            // $diffgram   = $xml->children($namespaces['diffgr'])->diffgram;
            // $newDataSet = $diffgram->children()->NewDataSet;
            // $table      = $newDataSet->children()->Table;
            // // Akses <NewDataSet>
            // // $newDataSet = $diffgram->NewDataSet;
            // dd($table);
            // $latestData = $table->sortByDesc('tgl_subkursasing')->first();
            
            // dd($latestData);
            // // Iterasi <Table>
            // foreach ( $table as $table) {
                // $table = collect([
                //     ['tgl_subkursasing' => '2025-02-14T00:00:00+07:00', 'beli_subkursasing' => 16285.00, 'jual_subkursasing' => 16285.00],
                //     ['tgl_subkursasing' => '2025-02-13T00:00:00+07:00', 'beli_subkursasing' => 16365.00, 'jual_subkursasing' => 16365.00],
                //     ['tgl_subkursasing' => '2025-02-12T00:00:00+07:00', 'beli_subkursasing' => 16364.00, 'jual_subkursasing' => 16364.00],
                // ]);
                
                // Ambil data dengan tanggal terbaru
                // echo "ID: " . $table->id_subkursasing . "<br>";
                // // echo "Nilai: " . $table->lnk_subkursasing . "<br>";
                // echo "Nilai: " . $table->nil_subkursasing . "<br>";
                // echo "Beli: " . $table->beli_subkursasing . "<br>";
                // echo "Jual: " . $table->jual_subkursasing . "<br>";
                // echo "Tanggal: " . $table->tgl_subkursasing . "<br>";
                // echo "MTS: " . $table->mts_subkursasing . "<br>";
                // echo "<hr>";
            // }
    
    
    
            // print_r($xml);
            // Mengurai data XML
            // $data = [];
            // foreach ($xml->NewDataSet->Table as $table) {
            //     dd($xml);
            //     $data[] = [
            //         'id_subkurslokal' => (int)$table->id_subkurslokal,
            //         'ink_subkurslokal' => (int)$table->ink_subkurslokal,
            //         'nil_subkurslokal' => (float)$table->nil_subkurslokal,
            //         'bell_subkurslokal' => (float)$table->bell_subkurslokal,
            //         'jual_subkurslokal' => (float)$table->jual_subkurslokal,
            //         'tgl_subkurslokal' => (string)$table->tgl_subkurslokal,
            //         'nttsubkurslokal' => (string)$table->nttsubkurslokal,
            //     ];
                // echo $data;
                // dd();
         
        

    }

