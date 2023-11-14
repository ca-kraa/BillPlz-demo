<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BillplzProxyController extends Controller
{
    public function proxyRequest($billId)
    {
        // Ganti dengan URL BillPlz yang sesuai
        $billplzUrl = "https://www.billplz-sandbox.com/bills/$billId";

        // Lakukan permintaan ke BillPlz
        $response = Http::get($billplzUrl);

        // Tetapkan header respons dan kirimkan kembali ke browser
        return response($response->body())
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
