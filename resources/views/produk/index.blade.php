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
            <tbody id="barangTableBody">
                <tr>
                    <td id="loadingMessage" colspan="5" class="text-center">Loading...</td>
                </tr>
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
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="deskripsi_barang"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi
                                Barang</label>
                            <textarea name="deskripsi_barang" id="deskripsi_barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required></textarea>
                        </div>
                        <div>
                            <label for="harga_barang"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Barang</label>
                            <input type="number" name="harga_barang" id="harga_barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
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

    <script>
        $(document).ready(function() {
            let loadingMessage = $('#loadingMessage');
            let loadingPembayaran = $('#loadingPembayaran');

            loadingMessage.text('Loading...');
            loadingPembayaran.hide();

            function fetchAndDisplayData() {
                $.ajax({
                    url: '/api/show-produk',
                    method: 'GET',
                    success: function(response) {
                        handleProductData(response.data);
                    },
                    error: function(error) {
                        console.error('Failed to fetch data:', error);
                        loadingMessage.text('');
                    }
                });
            }

            function handleProductData(data) {
                var counter = 1;
                var barangTableBody = $('#barangTableBody');
                barangTableBody.empty();

                if (data.length > 0) {
                    data.forEach(function(barang) {
                        var row = `<tr>
                    <td class="py-2 px-4 border-b text-center">${counter}</td>
                    <td class="py-2 px-4 border-b text-center">${barang.nama_barang}</td>
                    <td class="py-2 px-4 border-b text-center">${barang.deskripsi_barang}</td>
                    <td class="py-2 px-4 border-b text-center">${barang.harga_barang}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <button class="btn-bayar" data-nama="${barang.nama_barang}" data-deskripsi="${barang.deskripsi_barang}" data-harga="${barang.harga_barang}">Bayar</button>
                    </td>
                </tr>`;

                        barangTableBody.append(row);

                        counter++;
                    });

                    loadingMessage.text('');

                    $('.btn-bayar').click(function() {
                        loadingPembayaran.show();
                        var nama_barang = $(this).data('nama');
                        var deskripsi_barang = $(this).data('deskripsi');
                        var harga_barang = $(this).data('harga');

                        createBill(nama_barang, deskripsi_barang, harga_barang);
                    });
                } else {
                    var noDataMessage = `<tr>
                <td colspan="5" class="py-2 px-4 border-b text-center">
                    <i class="fa-regular fa-face-sad-cry"></i> Sila tambahkan produk terlebih dahulu.
                </td>
            </tr>`;
                    barangTableBody.append(noDataMessage);

                    loadingMessage.text('');
                }
            }

            function createBill(nama_barang, deskripsi_barang, harga_barang) {
                $.ajax({
                    url: '/api/createBill-produk',
                    method: 'POST',
                    data: {
                        name: nama_barang,
                        description: deskripsi_barang,
                        amount: harga_barang,
                    },
                    success: function(billResponse) {
                        console.log('Bill created successfully:', billResponse);
                        loadingPembayaran.hide();
                        var paymentUrl = billResponse.url;

                        openPaymentWindow(paymentUrl);
                    },
                    error: function(billError) {
                        console.error('Failed to create bill:', billError);
                        loadingPembayaran.hide();
                    }
                });
            }

            function openPaymentWindow(paymentUrl) {
                var paymentWindow = window.open(paymentUrl, '_blank', 'width=400,height=557');
            }

            $('#createBarangButton').click(function() {
                var nama_barang = $('#nama_barang').val();
                var deskripsi_barang = $('#deskripsi_barang').val();
                var harga_barang = $('#harga_barang').val();

                $.ajax({
                    url: '/api/create-produk',
                    method: 'POST',
                    data: {
                        nama_barang: nama_barang,
                        deskripsi_barang: deskripsi_barang,
                        harga_barang: harga_barang,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Data berhasil disimpan!');
                        fetchAndDisplayData();
                    },
                    error: function(error) {
                        console.error('Gagal menambahkan barang:', error);
                    }
                });
            });

            var timer = setInterval(function() {
                location.reload();
            }, 10000);

            $('.btn-bayar').click(function() {
                clearInterval(timer);
            });

            fetchAndDisplayData();
        });
    </script>

@endsection
