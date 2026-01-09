<x-app-layout>
    <div class="text-gray-800">

        {{-- Header Welcome --}}
        <div class="bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-2xl shadow-lg p-4 mb-4">
            <h1 class="text-3xl font-bold">👋 Hai, {{ Auth::user()->name }}!</h1>
            <p class="text-sm mt-1">Selamat datang kembali, berikut ringkasan aktivitas Anda.</p>
        </div>

        {{-- Ringkasan Statistik (khusus user) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition">
                <h3 class="text-sm font-medium">Dokumen Saya</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $myDocuments }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition">
                <h3 class="text-sm font-medium">Tugas Aktif</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $activeTasks }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border hover:shadow-lg transition">
                <h3 class="text-sm font-medium">Notifikasi</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $myNotifications }}</p>
            </div>
        </div>  

        {{-- Daftar Dokumen User --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border mb-4">
            <h2 class="text-xl font-bold mb-4">📄 Dokumen Terbaru Anda</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2">Nama Dokumen</th>
                        <th class="p-2">Tanggal Expired</th>
                        <th class="p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestDocuments as $doc)
                        <tr class="border-b">
                            <td class="p-2">{{ $doc->nama_dokumen }}</td>
                            <td class="p-2">{{ $doc->tanggal_expired->format('d M Y') }}</td>
                            <td class="p-2">
                                @if($doc->isExpired())
                                    <span class="text-red-500 font-semibold">Expired</span>
                                @elseif($doc->isExpiringSoon())
                                    <span class="text-yellow-500 font-semibold">Segera Expired</span>
                                @else
                                    <span class="text-green-500 font-semibold">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-2 text-gray-500 text-center">Belum ada dokumen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Notifikasi User --}}
        {{-- Chart Status Dokumen --}}
{{-- Chart Status Dokumen --}}
<div class="bg-white p-6 rounded-2xl shadow-md border">
    <h2 class="text-xl font-bold mb-6">📊 Status Dokumen</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6">
        {{-- Keterangan di kiri --}}
        <div class="space-y-3">
            <p class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-green-500"></span>
                <span class="text-gray-700">Aman: <b>{{ $safeCount }}</b></span>
            </p>
            <p class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-yellow-400"></span>
                <span class="text-gray-700">Segera Expired: <b>{{ $expiringSoonCount }}</b></span>
            </p>
            <p class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-red-500"></span>
                <span class="text-gray-700">Expired: <b>{{ $expiredCount }}</b></span>
            </p>
        </div>

        {{-- Grafik di kanan --}}
        <div class="flex justify-center">
    <canvas id="documentChart" class="w-72 h-72"></canvas>
</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('documentChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aman', 'Segera Expired', 'Expired'],
            datasets: [{
                data: [
                    {{ $safeCount }},
                    {{ $expiringSoonCount }},
                    {{ $expiredCount }}
                ],
                backgroundColor: ['#22c55e', '#facc15', '#ef4444'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>


    </div>
</x-app-layout>
