<x-app-layout>
<div class="bg-white rounded-2xl p-6 text-gray-800 shadow">

    {{-- Header --}}
    <div class="mb-6 text-center">
        <div class="flex justify-center items-center gap-3 mb-2">
            <div class="w-12 h-12 flex items-center justify-center 
                        bg-gradient-to-br from-indigo-500 to-purple-600 
                        rounded-full shadow-lg text-white">
                <i class="fa fa-history text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold">Log Aktivitas Dokumen</h2>
        </div>
        <p class="text-sm text-gray-600">
            Riwayat semua aktivitas user terhadap dokumen.
        </p>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
        <table id="logTable" class="min-w-full text-sm bg-white">
            <thead>
                <tr class="text-white text-sm">
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-center">No</th>
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600">User</th>
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600">Dokumen</th>
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600">Aktivitas</th>
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600">Keterangan</th>
                    <th class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600">Waktu</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

            {{-- ===== JIKA TIDAK ADA DATA ===== --}}
            @if($logs->isEmpty())
                <tr>
                    <td class="px-4 py-3 text-center">-</td>
                    <td class="px-4 py-3 text-center">-</td>
                    <td class="px-4 py-3 text-center">-</td>
                    <td class="px-4 py-3 text-center">-</td>
                    <td class="px-4 py-3 text-center text-gray-500">
                        Belum ada aktivitas tercatat.
                    </td>
                    <td class="px-4 py-3 text-center">-</td>
                </tr>

            {{-- ===== JIKA ADA DATA ===== --}}
            @else
                @foreach ($logs as $i => $log)
                <tr class="hover:bg-indigo-50 transition">

                    {{-- No --}}
                    <td class="px-4 py-3 text-center">
                        {{ $i + 1 }}
                    </td>

                    {{-- User --}}
                    <td class="px-4 py-3 font-medium">
                        {{ $log->user->name ?? 'System' }}
                    </td>

                    {{-- Dokumen --}}
                    <td class="px-4 py-3">
                        {{ $log->document->nama_dokumen ?? '-' }}
                    </td>

                    {{-- Aktivitas --}}
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 
                                    text-xs font-semibold px-3 py-1 rounded-full">
                            {{ ucwords(str_replace('_',' ', $log->event)) }}
                        </span>
                    </td>

                    {{-- Keterangan --}}
                    <td class="px-4 py-3 text-gray-700">
                        {{ $log->keterangan ?? '-' }}
                    </td>

                    {{-- Waktu --}}
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>

                </tr>
                @endforeach
            @endif

            </tbody>
        </table>
    </div>

    {{-- DataTables --}}
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#logTable').DataTable({
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
