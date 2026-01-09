<x-app-layout>
    {{-- Tabel Dokumen --}}
    <div class="bg-white rounded-2xl text-gray-800">
        <div class="mb-6 text-center">
            <div class="flex justify-center items-center gap-3 mb-2">
                <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 rounded-full shadow-lg text-white">
                    <i class="fa fa-folder-open text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Dokumen Anda</h2>
            </div>
            <p class="text-sm text-gray-600">Berikut adalah semua dokumen yang telah kamu unggah dan status terkininya.</p>
        </div>

        <div class="overflow-x-auto rounded-xl shadow border border-gray-200 bg-white text-gray-800 p-5 mt-4">
            @if ($userDocuments->count())
            <table id="myTable" class="min-w-full text-sm">
                <thead>
                    <tr class="text-white text-sm">
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tl-xl text-center">No</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Nama Dokumen</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Jenis</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Expired</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">PIC</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Status</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tr-xl">Lihat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($userDocuments as $i => $doc)
                    <tr class="hover:bg-purple-50 transition">
                        <td class="px-4 py-3 text-center">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-black">{{ $doc->nama_dokumen }}</td>
                        <td class="px-4 py-3 text-black">{{ $doc->jenis_dokumen }}</td>
                        <td class="px-4 py-3 text-red-600 font-semibold">
                            {{ \Carbon\Carbon::parse($doc->tanggal_expired)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-black">
                            {{ $doc->nama_pic }}
                            <div class="text-xs text-gray-500">{{ $doc->jabatan }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($doc->status === 'aktif')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Aktif</span>
                            @elseif ($doc->status === 'tidak aktif')
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-3 py-1 rounded-full">Tidak Aktif</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full">{{ ucfirst($doc->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center" x-data="{ open: false }">
                            <!-- Lihat dokumen -->
                            <a href="{{ asset('storage/' . $doc->upload_dokumen) }}" target="_blank"
                                class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold transition">
                                📄 Lihat
                            </a>

                            <!-- Tombol Edit -->
                            <button @click="open = true"
                                class="inline-block bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold transition">
                                ✏️ Edit
                            </button>

                            <!-- Modal Edit Document -->
                            <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                                <div class="absolute inset-0 bg-black opacity-50" @click="open = false"></div>
                                
                                <div class="bg-white rounded-lg shadow-lg max-w-md w-full z-50 p-6">
                                    <h2 class="text-lg font-semibold mb-4">Edit Dokumen</h2>
                                    <form action="{{ route('user.documents.update', $doc->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Unggah Dokumen Baru</label>
                                            <input type="file" name="upload_dokumen" class="border border-gray-300 rounded px-2 py-1 w-full" required>
                                        </div>

                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="open = false"
                                                class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            @else
            <p class="text-gray-600 text-center py-4">Belum ada dokumen yang diunggah.</p>
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
                    }
                });
            });
        </script>
        @endpush
</x-app-layout>
