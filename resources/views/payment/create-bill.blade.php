@extends('layout.main')

@section('title', 'Membuat Pembayaran')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-4">Membuat Pembayaran</h1>

        <div class="flex items-center justify-center">
            <form class="w-full max-w-md" method="POST" action="/api/billplz/createBill">
                @csrf
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi
                        Pembayaran</label>
                    <input type="text" id="description" name="description" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Nama</label>
                    <input type="text" id="name" name="name" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-gray-700 text-sm font-medium mb-2">Jumlah (RM)</label>
                    <input type="number" id="amount" name="amount" class="border p-2 w-full">
                </div>

                <div class="flex items-center justify-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hoverbg-blue-700">
                        <i class="fa-solid fa-check-circle"></i> Buat Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
