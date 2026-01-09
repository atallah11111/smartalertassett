<x-app-layout>
    <div class=" text-gray-800">

        {{-- Header Welcome --}}
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-2xl shadow-lg p-2 mb-2 ">
            <h1 class="text-3xl font-bold">👋 Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-sm mt-1">Selamat datang di dashboard sistem Anda.</p>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2">
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition text-black">
                <h3 class="text-sm font-medium text-black">JUMLAH USER</h3>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
               
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition text-black">
                <h3 class="text-sm font-medium text-black mb-1">JUMLAH ASET</h3>
                <p class="text-3xl font-bold">{{ $totalDocuments }}</p>
               
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition text-black">
                <h3 class="text-sm font-medium text-black mb-1">NOTIFIKASI TERKIRIM</h3>
                <p class="text-3xl font-bold">{{ $expiringDocuments }}</p>
                
            </div>
        </div>  

        {{-- Manajemen User & Notifikasi --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2">
            {{-- Grafik Dokumen --}}
            <div class="col-span-2 bg-white p-6 rounded-2xl shadow-md border text-black">
                <div class="flex justify-center mb-4">
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-4 rounded-full text-white shadow-lg w-16 h-16 flex items-center justify-center">
                        <i class="fa fa-file-alt text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-center">Grafik Dokumen</h2>
                <p class="text-sm text-center text-gray-600 mb-2">Jumlah dokumen dibuat per hari selama 7 hari terakhir.</p>

                <div class="bg-gray-100 h-64 rounded-lg flex items-center justify-center text-gray-400 border-dashed border-2 border-gray-300">
                    <canvas id="documentChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Notifikasi --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border text-black">
    <div class="flex justify-center mb-4">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-4 rounded-full text-white shadow-lg w-16 h-16 flex items-center justify-center">
            <i class="fa fa-bell text-2xl"></i>
        </div>
    </div>
    <h2 class="text-xl font-bold text-center">Notifikasi</h2>
    <p class="text-sm text-center text-gray-600 mb-6">Informasi terbaru & peringatan sistem</p>
    <ul class="space-y-4">
        <li class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
            <div>
                <p class="text-sm text-red-500 font-semibold">{{ $bahaya }} dokumen sudah lewat tanggal expired</p>
            </div>
        </li>
        <li class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
            <div>
                <p class="text-sm text-yellow-500 font-semibold">{{ $waspada }} dokumen akan expired dalam 14 hari</p>
            </div>
        </li>
        <li class="flex items-center gap-3">
            <i class="fas fa-check-circle text-blue-500 text-lg"></i>
            <div>
                <p class="text-sm text-blue-500 font-semibold">{{ $good }} dokumen masih aman (>30 hari)</p>
            </div>
        </li>
    </ul>
</div>


    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('documentChart').getContext('2d');
        const documentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($days), 
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: @json($documentCounts),
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</x-app-layout>
