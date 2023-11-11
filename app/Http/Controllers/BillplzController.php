<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\history;

class BillplzController extends Controller
{
    public function getCollection($id)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $response = Http::withBasicAuth($apiKey, '')
            ->get("https://www.billplz-sandbox.com/api/v3/collections/{$id}");

        $collection = $response->json();

        return response()->json($collection);
    }

    public function getBill($id)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $response = Http::withBasicAuth($apiKey, '')
            ->get("https://www.billplz-sandbox.com/api/v3/bills/{$id}");

        $bill = $response->json();

        return response()->json($bill);
    }

    public function createBill(Request $request)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $collectionId = $request->input('collection_id');
        $description = $request->input('description');
        $email = $request->input('email');
        $name = $request->input('name');
        $amount = $request->input('amount');
        $callbackUrl = $request->input('callback_url');

        $response = Http::withBasicAuth($apiKey, '')
            ->post('https://www.billplz-sandbox.com/api/v3/bills', [
                'collection_id' => $collectionId,
                'description' => $description,
                'email' => $email,
                'name' => $name,
                'amount' => $amount,
                'callback_url' => $callbackUrl
            ]);

        $bill = $response->json();

        return response()->json($bill);
    }

    public function getPayment(Request $request)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $response = Http::withBasicAuth($apiKey, '')
            ->get('https://www.billplz-sandbox.com/api/v4/payment_gateways');

        $payment = $response->json();

        return response()->json($payment);
    }


    public function getTransactions($BILL_ID)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $response = Http::withBasicAuth($apiKey, '')
            ->get("https://www.billplz-sandbox.com/api/v3/bills/{$BILL_ID}/transactions");

        return response()->json($response->json());
    }

    public function saveToHistory(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $existingRecord = History::where('kode_billing', $data['kode_billing'])->first();

        if ($existingRecord) {
            $existingRecord->update([
                'status' => $data['state'] === 'paid' ? 'Sudah dibayar' : 'Belum dibayar',
                'amount' => $data['amount'],
                'paid_amount' => $data['paid_amount'],
                'due_at' => $data['due_at'],
                'email' => $data['email'],
                'mobile' => $data['mobile'] ?? 0,
                'name' => $data['name'],
                'url' => $data['url'],
            ]);
        } else {
            History::create([
                'kode_billing' => $data['kode_billing'],
                'status' => $data['state'] === 'paid' ? 'Sudah dibayar' : 'Belum dibayar',
                'amount' => $data['amount'],
                'paid_amount' => $data['paid_amount'],
                'due_at' => $data['due_at'],
                'email' => $data['email'],
                'mobile' => $data['mobile'] ?? 0,
                'name' => $data['name'],
                'url' => $data['url'],
            ]);
        }

        return response()->json(['message' => 'You Caught Me :(']);
    }
}
