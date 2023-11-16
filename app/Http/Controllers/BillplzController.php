<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\history;
use App\Models\payment;
use App\Models\Barang;

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
        $collection = env('BILLPLZ_COLLECTION');

        $collectionId = $request->input('collection_id', $collection);
        $description = $request->input('description');
        $email = $request->input('email', 'tes@tes.com');
        $name = $request->input('name');
        $amount = $request->input('amount');
        $callbackUrl = $request->input('callback_url', '0');

        $response = Http::withBasicAuth($apiKey, '')
            ->post('https://www.billplz-sandbox.com/api/v3/bills', [
                'collection_id' => $collectionId,
                'description' => $description,
                'email' => $email,
                'name' => $name,
                'amount' => $amount,
                'callback_url' => $callbackUrl
            ]);

        $billData = $response->json();

        $callbackUrl = $billData['url'];

        $billData['id_pembayaran'] = $billData['id'];
        unset($billData['id']);

        Payment::create($billData);

        $urlFromResponse = $billData['url'];

        return redirect($urlFromResponse);
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

    public function deleteBill($BILL_ID)
    {
        $apiKey = env('BILLPLZ_API_KEY');

        $response = Http::withBasicAuth($apiKey, '')
            ->delete("https://www.billplz-sandbox.com/api/v3/bills/{$BILL_ID}/transactions");

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

    public function createForm()
    {
        return view('payment.create-bill');
    }

    public function createBarang(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string',
            'deskripsi_barang' => 'required|string',
            'harga_barang' => 'required|numeric',
        ]);

        $barang = Barang::create($validatedData);

        return response()->json([
            'message' => 'Data Barang berhasil dibuat',
            'data' => $barang,
        ], 201);
    }

    public function showBarang(Request $request)
    {
        $barang = Barang::all();

        return response()->json(['data' => $barang]);
    }

    public function createBillProduct(Request $request)
    {
        $apiKey = env('BILLPLZ_API_KEY');
        $collection = env('BILLPLZ_COLLECTION');

        $collectionId = $request->input('collection_id', $collection);
        $description = $request->input('description');
        $email = $request->input('email', 'tes@tes.com');
        $name = $request->input('name');
        $amount = $request->input('amount');
        $callbackUrl = $request->input('callback_url', '0');

        $produk = Barang::where('nama_barang', $name)->first();

        if ($produk) {
            $description = $produk->deskripsi_barang;

            $response = Http::withBasicAuth($apiKey, '')
                ->post('https://www.billplz-sandbox.com/api/v3/bills', [
                    'collection_id' => $collectionId,
                    'description' => $description,
                    'email' => $email,
                    'name' => $name,
                    'amount' => $amount,
                    'callback_url' => $callbackUrl
                ]);

            $billData = $response->json();

            $callbackUrl = $billData['url'];

            $billData['id_pembayaran'] = $billData['id'];
            unset($billData['id']);

            Payment::create($billData);

            $urlFromResponse = $billData['url'];

            return response()->json(['url' => $urlFromResponse]);
        } else {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }
    }
}
