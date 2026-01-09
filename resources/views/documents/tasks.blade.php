<x-app-layout>

    {{-- Section Heading --}}
    <div class="mb-6 text-center">
        <div class="flex justify-center items-center gap-3 mb-2">
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-4 rounded-full shadow-lg text-white w-12 h-12 flex items-center justify-center">
                <i class="fa fa-folder-open text-xl"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Tugas Saya</h2>
        <p class="text-sm text-gray-600">Berikut adalah semua dokumen yang telah kamu unggah dan status terkininya.</p>
    </div>

    {{-- Tombol Tambah --}}
    <div class="flex justify-between items-center bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 mb-6 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Tugas Dokumen</h2>
            <p class="text-sm text-gray-600">Tambahkan tugas dokumen harianmu di sini dan kelola dengan mudah.</p>
        </div>
        <button
            onclick="document.getElementById('createDocumentModal').classList.remove('hidden')"
            class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 hover:opacity-90 text-white font-semibold text-sm px-5 py-3 rounded-lg shadow-md transition duration-200">
            + Tambah Dokumen
        </button>
    </div>

    {{-- Modal Create --}}
    <div id="createDocumentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Tambah Dokumen Baru</h2>
                <button onclick="document.getElementById('createDocumentModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="text-black block font-semibold mb-1">Nama Dokumen</label>
                    <input type="text" name="nama_dokumen" class="w-full text-black border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-black font-semibold mb-1">Jenis Dokumen</label>
                    <input type="text" name="jenis_dokumen" class="text-black w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-black font-semibold mb-1">Upload Dokumen</label>
                    <input type="file" name="upload_dokumen" class="text-black w-full border border-gray-300 rounded-lg px-4 py-2 bg-white" required>
                </div>

                {{-- Multiple PIC --}}
                <div>
                    <label class="block font-semibold mb-1">PIC</label>
                    <div id="pic-container">
                        <div class="flex gap-4 mb-2">
                            <input type="text" name="nama_pic[]" placeholder="Nama PIC" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                            <input type="text" name="nomor_pic[]" placeholder="Nomor PIC" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                            <button type="button" onclick="removePic(this)" class="text-red-500 font-bold">✕</button>
                        </div>
                    </div>
                    <button type="button" onclick="addPic('pic-container')" class="mt-2 text-blue-600 font-semibold">+ Tambah PIC</button>
                </div>

                <div>
                    <label class="block text-black font-semibold mb-1">Tanggal Expired</label>
                    <input type="date" name="tanggal_expired" class=" text-black w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="text-black block font-semibold mb-1">Diperingatkan H-berapa</label>
                    <select name="diperingatkan_h" class="text-black w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                        <option value="">Pilih Waktu Peringatan</option>
                        <option value="3">H-3 Hari</option>
                        <option value="7">H-1 Minggu</option>
                        <option value="14">H-2 Minggu</option>
                        <option value="30">H-1 Bulan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-black font-semibold mb-1">Ingatkan Setelah Kadaluarsa?</label>
                    <select name="ingatkan_setelah_kadaluwarsa" class="w-full text-black border border-gray-300 rounded-lg px-4 py-2 bg-white">
                        <option value="ya">Ya, tetap ingatkan</option>
                        <option value="tidak" selected>Tidak perlu diingatkan lagi</option>
                    </select>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>




    <!-- Modal Edit Document -->
    <div id="editDocumentModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Dokumen</h2>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Dokumen --}}
                <div class="mb-4">
                    <label for="edit_nama_dokumen" class="block text-black font-semibold mb-1">Nama Dokumen</label>
                    <input type="text" id="edit_nama_dokumen" name="nama_dokumen"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-black focus:ring focus:ring-blue-200"
                        required>
                </div>

                {{-- Jenis Dokumen --}}
                <div class="mb-4">
                    <label for="edit_jenis_dokumen" class="block text-black font-semibold mb-1">Jenis Dokumen</label>
                    <input type="text" id="edit_jenis_dokumen" name="jenis_dokumen"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-black focus:ring focus:ring-blue-200"
                        required>
                </div>

                {{-- Tanggal Expired --}}
                <div class="mb-4">
                    <label for="edit_tanggal_expired" class="block text-black font-semibold mb-1">Tanggal Expired</label>
                    <input type="date" id="edit_tanggal_expired" name="tanggal_expired"
                        class="w-full text-black border border-gray-300 rounded-lg px-4 py-2" required>
                </div>

                {{-- PIC --}}
                <div class="mb-4">
                    <label class="block text-black font-semibold mb-1">PIC</label>
                    <div id="edit-pic-container"></div>
                    <button type="button" onclick="addPic('edit-pic-container')"
                        class="mt-2 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                        + Tambah PIC
                    </button>
                </div>

                {{-- Diperingatkan H-berapa --}}
                <div class="mb-4">
                    <label for="edit_diperingatkan_h" class="text-black block font-semibold mb-1">Diperingatkan H-berapa</label>
                    <select id="edit_diperingatkan_h" name="diperingatkan_h"
                        class="text-black w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                        <option value="">Pilih Waktu Peringatan</option>
                        <option value="3">H-3 Hari</option>
                        <option value="7">H-1 Minggu</option>
                        <option value="14">H-2 Minggu</option>
                        <option value="30">H-1 Bulan</option>
                    </select>
                </div>

                {{-- Ingatkan Setelah Kadaluarsa --}}
                <div class="mb-4">
                    <label for="edit_ingatkan" class="block text-black font-semibold mb-1">Ingatkan Setelah Kadaluarsa?</label>
                    <select id="edit_ingatkan" name="ingatkan_setelah_kadaluwarsa"
                        class="w-full text-black border border-gray-300 rounded-lg px-4 py-2 bg-white">
                        <option value="ya">Ya, tetap ingatkan</option>
                        <option value="tidak" selected>Tidak perlu diingatkan lagi</option>
                    </select>
                </div>

                {{-- Status + Tombol --}}
                <div class="flex justify-between items-center mt-6 gap-4">
                    {{-- Status Toggle di kiri --}}
                    <div>
        
    

                    </div>

                    {{-- Tombol aksi di kanan --}}
                    <div class="flex gap-4">
                        <button type="button"
                            onclick="document.getElementById('editDocumentModal').classList.add('hidden')"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            Update Document
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    {{-- Table --}}
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
                    <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($userDocuments as $i => $doc)
                <tr class="hover:bg-purple-50 transition">
                    <td class="px-4 py-3 text-center">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-black">{{ $doc->nama_dokumen }}</td>
                    <td class="px-4 py-3 text-black">{{ $doc->jenis_dokumen }}</td>
                    <td class="px-4 py-3 text-red-600 font-semibold">
                        {{ \Carbon\Carbon::parse($doc->tanggal_expired)->format('Y-m-d') }}
                    </td>
                    <td class="px-4 py-3 text-black">
                        {{ $doc->nama_pic }}
                        <div class="text-xs text-gray-500">{{ $doc->jabatan }}</div>
                    </td>
                    <!-- <td class="px-4 py-3">
                        @if ($doc->status === 'aktif')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Aktif</span>
                        @elseif ($doc->status === 'tidak aktif')
                        <span class="bg-orange-100 text-orange-800 text-xs font-medium px-3 py-1 rounded-full">Tidak Aktif</span>
                        @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full">{{ ucfirst($doc->status) }}</span>
                        @endif
                    </td> -->
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
                   <td class="  text-center">
    <div class="flex justify-center items-center gap-2">
        <!-- Tombol Edit -->
        <button
            onclick='openEditModal(
                {{ $doc->id }},
                @json($doc->nama_dokumen),
                @json($doc->jenis_dokumen),
                @json($doc->tanggal_expired),
                @json(explode(", ", $doc->nama_pic)),
                @json(explode(", ", $doc->nomor_pic)),
                @json($doc->diperingatkan_h),
                @json($doc->ingatkan_setelah_kadaluwarsa),
                @json($doc->status)
            )'
            class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-full font-medium transition">
            ✏️ <span>Edit</span>
        </button>

        <!-- Tombol Hapus -->
        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-full font-medium transition">
                🗑 <span>Hapus</span>
            </button>
        </form>
    </div>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-600 text-center py-4">Belum ada dokumen tugas yang diunggah.</p>
        @endif
    </div>

    {{-- Scripts --}}
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

        // Tambah & hapus PIC
        function addPic(containerId) {
            const container = document.getElementById(containerId);

            const div = document.createElement('div');
            div.classList.add('flex', 'gap-4', 'mb-2');

            div.innerHTML = `
                <input type="text" name="nama_pic[]" placeholder="Nama PIC" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                <input type="text" name="nomor_pic[]" placeholder="Nomor PIC" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                <button type="button" onclick="removePic(this)" class="text-red-500 font-bold">✕</button>
            `;

            container.appendChild(div);
        }

        function removePic(button) {
            button.parentElement.remove();
        }

        // Modal Edit
        function openEditModal(id, nama_dokumen, jenis_dokumen, tanggal_expired, nama_pics, nomor_pics, diperingatkan_h, ingatkan, status) {
            document.getElementById('editDocumentModal').classList.remove('hidden');
            document.getElementById('editForm').action = `/documents/${id}`;
            document.getElementById('edit_nama_dokumen').value = nama_dokumen;
            document.getElementById('edit_jenis_dokumen').value = jenis_dokumen;
            document.getElementById('edit_tanggal_expired').value = tanggal_expired;
            document.getElementById('edit_diperingatkan_h').value = diperingatkan_h;
            document.getElementById('edit_ingatkan').value = ingatkan;
            // document.getElementById('edit_status').checked = status == 1;
            // document.getElementById('edit_status').checked = (status === "aktif");
            // toggleStatusLabel();
            // DEBUGGING: cek apa yang masuk        
            console.log("nama_pics:", nama_pics);
            console.log("nomor_pics:", nomor_pics);
            
            // isi PIC container
            const picContainer = document.getElementById('edit-pic-container');
            picContainer.innerHTML = "";

            // pastikan ini array
            if (Array.isArray(nama_pics)) {
                nama_pics.forEach((nama, index) => {
                    const nomor = Array.isArray(nomor_pics) ? (nomor_pics[index] ?? "") : "";
                    picContainer.innerHTML += `
                <div class="flex gap-4 mb-2">
                    <input type="text" name="nama_pic[]" value="${nama}" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                    <input type="text" name="nomor_pic[]" value="${nomor}" class="text-black flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                    <button type="button" onclick="removePic(this)" class="text-red-500 font-bold">✕</button>
                </div>
            `;
                });
            }
        }
    </script>
    </>
    @endpush

</x-app-layout>