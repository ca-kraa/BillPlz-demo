@extends('layout.main')
@section('title', 'Product')

@section('content')

    <div class="container mx-auto mt-8 text-center">
        <button data-modal-target="tambahdata-modal" data-modal-toggle="tambahdata-modal"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Tambahkan Produk
        </button>
    </div>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-md shadow-md">
        <h1 class="text-2xl font-bold mb-6">List Barang</h1>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-center">#</th>
                    <th class="py-2 px-4 border-b text-center">Nama Item</th>
                    <th class="py-2 px-4 border-b text-center">Penerangan Item</th>
                    <th class="py-2 px-4 border-b text-center">Harga Item</th>
                    <th class="py-2 px-4 border-b text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($barangs as $barang)
                    <tr>
                        <td class="text-center align-middle">{{ $no++ }}</td>
                        <td class="text-center align-middle">{{ $barang->nama_barang }}</td>
                        <td class="text-center align-middle">{{ $barang->deskripsi_barang }}</td>
                        <td class="text-center align-middle">{{ $barang->harga_barang }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('create.bill', ['barangId' => $barang->id]) }}" class="btn-bayar">Bayar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div id="tambahdata-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambahkan Produk
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover-bg-gray-600 dark:hover:text-white"
                        data-modal-hide="tambahdata-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form id="createBarangForm" class="space-y-4">
                        <div>
                            <label for="nama_barang"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Item</label>
                            <input type="text" name="nama_barang" id="nama_barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="deskripsi_barang"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penerangan Item</label>
                            <textarea name="deskripsi_barang" id="deskripsi_barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required></textarea>
                        </div>
                        <div>
                            <label for="harga_barang"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Item</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-900 dark:text-white">
                                    RM
                                </span>
                                <input type="number" name="harga_barang" id="harga_barang" step="0.01"
                                    class="pl-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    required>
                            </div>
                        </div>

                        <button type="button" id="createBarangButton"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus-ring-blue-800">Tambah
                            Barang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingPembayaran" class="hidden fixed inset-0 z-10 items-center justify-center bg-white bg-opacity-70">
        <div class="p-5 rounded-lg bg-white shadow-lg flex flex-col items-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-r-4 my-4"></div>
            <p class="text-gray-700">Bayaran anda sedang berjalan</p>
        </div>
    </div>

@endsection
