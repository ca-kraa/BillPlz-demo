@extends('layout.main')
@section('title', 'Cek Pembayaran')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

        <div class="flex items-center justify-center mb-4">
            <label for="billId" class="mr-2">Cari ID Pembayaran:</label>
            <input type="text" id="billId" class="border p-2">
            <button id="searchBtn" class="bg-blue-500 text-white px-4 py-2 ml-2"><i class="fa-solid fa-magnifying-glass"></i>
                Cari</button>
        </div>

        <div id="resultTable" class="flex items-center justify-center">
            <p class="text-gray-600">Tolong masukkan ID pembayaran Anda.</p>
        </div>
    </div>

    <script>
        document.getElementById('searchBtn').addEventListener('click', function() {
            var billId = document.getElementById('billId').value;
            if (billId.trim() !== '') {
                fetchBillData(billId);
            }
        });

        function fetchBillData(billId) {
            fetch(`/api/billplz/billing/${billId}`)
                .then(response => response.json())
                .then(data => displayBillData(data))
                .catch(error => console.error('Error fetching data:', error));
        }

        function displayBillData(data) {
            var resultTable = document.getElementById('resultTable');

            resultTable.innerHTML = `
            <table class="table-auto border-collapse border w-full mt-4">
  <thead>
    <tr>
      <th class="border p-2">Status</th>
      <th class="border p-2">Jumlah</th>
      <th class="border p-2">Jumlah yang Dibayar</th>
      <th class="border p-2">Jatuh Tempo</th>
      <th class="border p-2">Email</th>
      <th class="border p-2">Nomor Telepon</th>
      <th class="border p-2">Nama</th>
      <th class="border p-2">URL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="border p-2 ${data.state === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
        ${data.state === 'paid' ? 'Sudah dibayar' : 'Belum dibayar'}
        <i class="fas ${data.state === 'paid' ? 'fa-check text-green-500' : 'fa-times text-red-500'} ml-2"></i>
      </td>
      <td class="border p-2">RM ${data.amount}</td>
      <td class="border p-2">RM ${data.paid_amount}</td>
      <td class="border p-2">${data.due_at}</td>
      <td class="border p-2">${data.email}</td>
      <td class="border p-2">${data.mobile || 0}</td>
      <td class="border p-2">${data.name}</td>
      <td class="border p-2"><a href="${data.url}" target="_blank" class="text-blue-500 hover:text-blue-700">Lihat</a></td>
    </tr>
  </tbody>
</table>
    `;
        }
    </script>
@endsection
