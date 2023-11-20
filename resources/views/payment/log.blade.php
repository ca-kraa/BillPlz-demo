@extends('layout.main')
@section('title', 'Log Pembayaran')

@section('content')

    <div class="mx-auto bg-white p-8 rounded-md shadow-md" style="max-width: 80%;">
        <h1 class="text-xxl font-semibold mb-4">Bill Log</h1>

        <div class="mb-4">
            <button onclick="filterTable('all')"
                class="bg-blue-500 text-white hover:bg-blue-700 active:bg-blue-800 py-1 px-2 rounded mr-2">All</button>
            <button onclick="filterTable('paid')"
                class="bg-green-500 text-white hover:bg-green-700 active:bg-green-800 py-1 px-2 rounded mr-2">Paid</button>
            <button onclick="filterTable('due')"
                class="bg-red-500 text-white hover:bg-red-700 active:bg-red-800 py-1 px-2 rounded">Not
                Paid</button>

        </div>

        <table class="w-full bg-white border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b text-center font-medium text-gray-600">Name</th>
                    <th class="py-2 px-4 border-b text-center font-medium text-gray-600">Paid Amount</th>
                    <th class="py-2 px-4 border-b text-center font-medium text-gray-600">Description</th>
                    <th class="py-2 px-4 border-b text-center font-medium text-gray-600">Status</th>
                    <th class="py-2 px-4 border-b text-center font-medium text-gray-600">URL</th>
                </tr>
            </thead>
            <tbody id="billTableBody" class="text-gray-700">
            </tbody>
        </table>
    </div>

    <script>
        let allPayments = [];
        let currentFilter = 'all'; // Keep track of the current filter

        // Fetch data immediately after page load
        fetchData();

        // Fetch data every 5 seconds
        setInterval(fetchData, 5000);

        function fetchData() {
            fetch('/api/showbill-log')
                .then(response => response.json())
                .then(data => {
                    allPayments = data.Data;
                    filterTable(currentFilter); // Apply the current filter after fetching data
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function filterTable(state) {
            currentFilter = state; // Update the current filter

            if (state === 'all') {
                displayPayments(allPayments);
            } else {
                const filteredPayments = allPayments.filter(payment => payment.state === state);
                displayPayments(filteredPayments);
            }
        }

        function displayPayments(payments) {
            const tableBody = document.getElementById('billTableBody');
            tableBody.innerHTML = '';

            if (payments.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML =
                    '<td colspan="5" class="text-center py-2 px-4 border-b text-center">No payments found. They may have already been paid or there may be none at this time. <br>ヾ(≧ ▽ ≦)ゝ</td>';
                tableBody.appendChild(row);
            } else {
                payments.forEach(payment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td class="py-2 px-4 border-b text-center">${payment.name}</td>
                <td class="py-2 px-4 border-b text-center">${payment.description}</td>
                <td class="py-2 px-4 border-b text-center">${payment.paid_amount}</td>
                <td class="py-2 px-4 border-b text-center">${payment.state}</td>
                <td class="py-2 px-4 border-b text-center">
                <button onclick="openMiniWindow('${payment.url}')" class="bg-blue-500 text-white hover:bg-blue-700 py-1 px-2 rounded">
                    <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i> Open
                </button>
            </td>
            `;
                    tableBody.appendChild(row);
                });
            }
        }

        function openMiniWindow(url) {
            const options = 'width=425,height=600';
            window.open(url, '_blank', options);
        }
    </script>

@endsection
