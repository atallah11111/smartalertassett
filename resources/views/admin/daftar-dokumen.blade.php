<x-app-layout>
    <div class="bg-white text-gray-800 rounded-xl p-6">
        <div class="text-center mb-6">
            <div class="flex justify-center mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full shadow flex items-center justify-center">
                    <i class="fa fa-file-alt text-white text-xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Dokumen Terbaru</h1>
            <p class="text-sm text-gray-600">Lihat daftar dokumen yang baru saja diunggah dan kelola statusnya.</p>
        </div>

        @if ($recentDocuments->count())
        <div class="overflow-x-auto rounded-xl shadow border p-5 border-gray-200">
            <table id="myTable" class="min-w-full text-sm">
                <thead>
                    <tr class="text-white">
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left rounded-tl-xl ">Nama Dokumen</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left">Jenis</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left">Expired</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left">PIC</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left">Uploader</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-left">Status</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-center">Lihat</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-center rounded-tr-xl">Hapus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($recentDocuments as $doc)
                    <tr class="hover:bg-gradient-to-r from-blue-50 to-purple-50 transition">
                        <td class="px-4 py-3">{{ $doc->nama_dokumen }}</td>
                        <td class="px-4 py-3">{{ $doc->jenis_dokumen }}</td>
                        <td class="px-4 py-3 text-red-600 font-medium">
                            {{ \Carbon\Carbon::parse($doc->tanggal_expired)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-800">{{ $doc->nama_pic }}</span><br>
                            <span class="text-xs text-gray-500">{{ $doc->jabatan }}</span><br>
                            <span class="text-xs text-blue-500">📞 {{ $doc->nomor_pic }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $doc->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                  <select name="status" onchange="this.form.submit()"
    class="appearance-none border border-gray-300 rounded-full pl-3 pr-6 py-1 text-sm  
           focus:outline-none focus:ring-2 focus:border-transparent
           font-medium flex items-center gap-2
           {{ $doc->status === 'aktif' ? 'bg-green-100 text-green-700 focus:ring-green-500' : 'bg-orange-100 text-orange-700 focus:ring-orange-500' }}">
    <option value="aktif" {{ $doc->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
    <option value="tidak aktif" {{ $doc->status === 'tidak aktif' ? 'selected' : '' }} class="bg-orange-100 text-orange-700 focus:ring-orange-500">Tidak Aktif</option>
</select>

                                </form>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ asset('storage/' . $doc->upload_dokumen) }}" target="_blank"
                                class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold transition">
                                📄 Lihat
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-full font-medium transition">
                                    🗑 Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-600 mt-4 text-center">Belum ada dokumen yang ditambahkan.</p>
        @endif
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                pageLength: 5,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                },
                columnDefs: [{
                    targets: [-1, -2],
                    className: 'dt-center'
                }]
            });
        });
    </script>
    @endpush
</x-app-layout>