@extends('layout.main')
@section('title', 'Cek Pembayaran')

@section('content')
    <div class="container mx-auto mt-8 text-center">
        <h1 class="text-2xl font-bold mb-4">Cek Pembayaran</h1>
        <div class="flex items-center justify-center mb-4">
            <label for="billId" class="mr-2">Masukkan ID pembayaran anda</label>
        </div>
        <div class="flex items-center justify-center mb-4">
            <input type="text" id="billId" class="border p-2">
            <button id="searchBtn" class="bg-blue-500 text-white px-4 py-2 ml-2">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
        </div>

        <div id="resultTable" class="flex items-center justify-center">
            <p class="text-gray-600">Tolong masukkan ID pembayaran Anda.</p>
        </div>
    </div>

    <div id="loadingIndicator" class="hidden fixed inset-0 z-10 flex items-center justify-center bg-white bg-opacity-70">
        <div class="p-5 rounded-lg bg-white shadow-lg flex flex-col items-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-r-4 my-4">
            </div>
            <p class="text-gray-700">Tunggu sebentar...</p>
        </div>
    </div>

    <div id="resultTable" class="flex items-center justify-center">
    </div>


    <script>
        document.getElementById('billId').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                var billId = document.getElementById('billId').value;
                if (billId.trim() !== '') {
                    document.getElementById('loadingIndicator').classList.remove('hidden');
                    fetchBillData(billId);
                }
            }
        });

        document.getElementById('searchBtn').addEventListener('click', function() {
            var billId = document.getElementById('billId').value;
            if (billId.trim() !== '') {
                document.getElementById('loadingIndicator').classList.remove('hidden');
                fetchBillData(billId);
            }
        });

        function fetchBillData(billId) {
            fetch(`/api/billplz/billing/${billId}`)
                .then(response => response.json())
                .then(data => {
                    displayBillData(data);
                    saveBillDataToHistory(data);
                    document.getElementById('loadingIndicator').classList.add(
                        'hidden');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.getElementById('loadingIndicator').classList.add(
                        'hidden');
                });
        }

        function displayBillData(data) {
            var resultTable = document.getElementById('resultTable');

            resultTable.innerHTML = `
              <div class="overflow-x-auto">
                  <table class="table-auto border-collapse border w-full mt-4">
                      <thead>
                          <tr>
                              <th class="border p-2 text-center">ID Billing</th>
                              <th class="border p-2 text-center">Status</th>
                              <th class="border p-2 text-center">Jumlah</th>
                              <th class="border p-2 text-center">Jumlah yang Dibayar</th>
                              <th class="border p-2 text-center">Jatuh Tempo</th>
                              <th class="border p-2 text-center">Email</th>
                              <th class="border p-2 text-center">Nomor Telepon</th>
                              <th class="border p-2 text-center">Nama</th>
                              <th class="border p-2 text-center">URL</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td class="border p-2 text-center uppercase">${data.id}</td>
                              <td class="border p-2 text-center ${data.state === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                  ${data.state === 'paid' ? 'Sudah dibayar' : 'Belum dibayar'}
                                  <i class="fas ${data.state === 'paid' ? 'fa-check text-green-500' : 'fa-times text-red-500'} ml-2"></i>
                              </td>
                              <td class="border p-2 text-center">RM ${data.amount}</td>
                              <td class="border p-2 text-center">RM ${data.paid_amount}</td>
                              <td class="border p-2 text-center">${data.due_at}</td>
                              <td class="border p-2 text-center">${data.email}</td>
                              <td class="border p-2 text-center">${data.mobile || 0}</td>
                              <td class="border p-2 text-center">${data.name}</td>
                              <td class="border p-2 text-center">
                              <a href="${data.url}" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-700">
                                  Lihat
                                  <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                              </a>
                          </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          `;
        }

        function saveBillDataToHistory(data) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var kodeBilling = data.id;

            data.kode_billing = kodeBilling;
            delete data.id;

            fetch('/api/history/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .catch(error => console.error('Error saving data:', error));
        }
    </script>

@endsection
