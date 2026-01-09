<x-app-layout>

    {{-- Header Judul --}}
    <!-- Header Ikon dan Judul -->
<div class="bg-white rounded-xl p-6 text-center mb-6">
    <!-- Icon -->
    <div class="flex justify-center mb-4">
        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-4 rounded-full text-white shadow-lg w-16 h-16 flex items-center justify-center">
            <i class="fa fa-history text-2xl"></i>
        </div>
    </div>

    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800">Log Aktivitas Dokumen</h1>
    <p class="text-sm text-gray-600">Riwayat perubahan dan aksi terakhir terhadap dokumen sistem</p>

    {{-- Tabel Log Dokumen --}}
    <div class="overflow-x-auto rounded-xl shadow border border-gray-200 bg-white text-gray-800 p-5 mt-4">
    <table id="myTable" class="min-w-full text-sm border-collapse">   
        <thead>
            <tr class="text-white text-sm">
                <th class="px-4 py-3  text-center bg-gradient-to-r from-blue-600 to-purple-600 rounded-tl-xl">No</th>
                <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Dokumen</th>
                <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Event</th>
                <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Keterangan</th>
                <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tr-xl">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($recentDocumentLogs as $i => $log)
                <tr class="hover:bg-purple-50 transition">
                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                    <td class="px-4 text-left  py-3 font-medium">{{ $log->document->nama_dokumen ?? '-' }}</td>
                    <td class="px-4 text-left  py-3 capitalize text-blue-600 font-medium">{{ $log->event }}</td>
                    <td class="px-4 text-left  py-3 text-sm text-gray-600">{{ $log->keterangan ?? '-' }}</td>
                    <td class="px-4 text-left  py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
            }
        });
    });
</script>
@endpush

</div>


    
</x-app-layout>
