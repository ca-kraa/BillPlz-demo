<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\history;
use App\Models\payment;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use App\Models\Callback;

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

    // public function createBill(Request $request)
    // {
    //     $apiKey = env('BILLPLZ_API_KEY');
    //     $collection = env('BILLPLZ_COLLECTION');

    //     $collectionId = $request->input('collection_id', $collection);
    //     $description = $request->input('description');
    //     $email = $request->input('email', 'tes@tes.com');
    //     $name = $request->input('name');
    //     $amount = $request->input('amount');
    //     $callbackUrl = $request->input('callback_url', '0');

    //     $response = Http::withBasicAuth($apiKey, '')
    //         ->post('https://www.billplz-sandbox.com/api/v3/bills', [
    //             'collection_id' => $collectionId,
    //             'description' => $description,
    //             'email' => $email,
    //             'name' => $name,
    //             'amount' => $amount,
    //             'callback_url' => $callbackUrl
    //         ]);

    //     $billData = $response->json();

    //     $callbackUrl = $billData['url'];

    //     $billData['id_pembayaran'] = $billData['id'];
    //     unset($billData['id']);

    //     Payment::create($billData);

    //     $urlFromResponse = $billData['url'];

    //     return redirect($urlFromResponse);
    // }

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

    public function createBill(Request $request)
    {
        $apiKey = env('BILLPLZ_API_KEY');
        $collectionId = env('BILLPLZ_COLLECTION');

        try {
            $response = Http::withBasicAuth($apiKey, '')
                ->post('https://www.billplz-sandbox.com/api/v3/bills', [
                    'collection_id' => $collectionId,
                    'description' => 'Pembayaran yang mudah, keselesaan yang berpanjangan.',
                    'email' => 'sanks@gmail.com',
                    'name' => 'Sanks',
                    'amount' => 300,
                    'callback_url' => url('handleBillplzCallback'),
                ]);
            $billData = $response->json();

            $bill = new payment();
            $bill->bill_id = $billData['id'];
            $bill->collection_id = $billData['collection_id'];
            $bill->paid = $billData['paid'];
            $bill->state = $billData['state'];
            $bill->amount = $billData['amount'];
            $bill->paid_amount = $billData['paid_amount'];
            $bill->due_at = $billData['due_at'];
            $bill->email = $billData['email'];
            $bill->mobile = $billData['mobile'];
            $bill->name = $billData['name'];
            $bill->url = $billData['url'];
            $bill->reference_1_label = $billData['reference_1_label'];
            $bill->reference_1 = $billData['reference_1'];
            $bill->reference_2_label = $billData['reference_2_label'];
            $bill->reference_2 = $billData['reference_2'];
            $bill->redirect_url = $billData['redirect_url'];
            $bill->callback_url = $billData['callback_url'];
            $bill->description = $billData['description'];
            $bill->paid_at = $billData['paid_at'];

            $this->handleBillplzCallback($request->merge($billData));

            $bill->save();
            Log::info("Tagihan berhasil dibuat. Bill ID: {$billData['id']}, URL: {$billData['url']}");
            return redirect($billData['url']);
        } catch (\Exception $e) {
            Log::error("Terjadi kesalahan saat membuat tagihan: {$e->getMessage()}");

            return response()->json(['error' => 'Terjadi kesalahan internal.'], 500);
        }
    }

    public function handleBillplzCallback(Request $request)
    {
        Log::info('Callback received from Billplz', $request->all());


        if ($request->input('paid') === 'true') {
            $billId = $request->input('id');
            $amount = $request->input('amount');
            $paidAt = $request->input('paid_at');
            $paidAmount = $request->input('paid_amount');
            $state = $request->input('state');

            $bill = payment::where('bill_id', $billId)->first();

            if ($bill) {
                $bill->paid_amount = $paidAmount;
                $bill->paid = true;
                $bill->state = $state;
                $bill->save();

                $pembayaran = new Callback();
                $pembayaran->bill_id = $billId;
                $pembayaran->amount = $amount;
                $pembayaran->paid_at = $paidAt;
                $pembayaran->save();

                Log::info("Pembayaran berhasil. Bill ID: $billId, Amount: $amount, Paid At: $paidAt");
            } else {
                Log::warning("Tagihan tidak ditemukan. Bill ID: $billId");
            }

            Log::info("Pembayaran berhasil. Bill ID: $billId, Amount: $amount, Paid At: $paidAt");
        } else {
            $billId = $request->input('id');


            Log::warning("Pembayaran tidak berhasil. Bill ID: $billId");
        }

        return response('OK');
    }
}
