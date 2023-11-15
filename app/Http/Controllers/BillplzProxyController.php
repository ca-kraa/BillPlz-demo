<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BillplzProxyController extends Controller
{
    public function proxyRequest($billId)
    {
        $billplzUrl = "https://www.billplz-sandbox.com/bills/$billId";

        $response = Http::get($billplzUrl);

        return response($response->body())
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
