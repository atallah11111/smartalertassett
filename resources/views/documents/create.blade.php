<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-md">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📝 Tambah Dokumen</h2>

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="text-black block font-semibold mb-1">Nama Dokumen</label>
                <input type="text" name="nama_dokumen" class="w-full text-black border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-black font-semibold mb-1">Jenis Dokumen</label>
                <input type="text" name="jenis_dokumen" class="text-black w-full border border-gray-300 rounded-lg px-4 py-2" required>
            </div>

            <div>
                <label class="block text-black font-semibold mb-1">Upload Dokumen</label>
                <input type="file" name="upload_dokumen" class=" text-black w-full border text-black border-gray-300 rounded-lg px-4 py-2 bg-white" required>
            </div>

            <div>
    <label class="block font-semibold mb-1">PIC</label>

    <div id="pic-container">
    <div class="flex gap-4 mb-2">
        <input type="text" name="nama_pic[]" placeholder="Nama PIC" class="text-black  flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
        <input type="text" name="nomor_pic[]" placeholder="Nomor PIC" class="text-black  flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
        <button type="button" onclick="removePic(this)" class="text-red-500 font-bold">✕</button>
    </div>
</div>
    <button type="button" onclick="addPic()" class="mt-2 text-blue-600 font-semibold">+ Tambah PIC</button>
</div>

            <div>
                <label class="block text-black font-semibold mb-1">Jabatan</label>
                <input type="text" name="jabatan" class="w-full border text-black border-gray-300 rounded-lg px-4 py-2" required>
            </div>



            <div>
                <label class="block text-black font-semibold mb-1">Tanggal Expired</label>
                <input type="date" name="tanggal_expired" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
            </div>

            <div>
                <label class="text-black block font-semibold mb-1">Diperingatkan H-berapa</label>
                <select name="diperingatkan_h" class="text-black w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    <option value="" class="text-black">Pilih Waktu Peringatan</option>
                    <option value="3" class="text-black">H-3 Hari</option>
                    <option value="7" class="text-black">H-1 Minggu</option>
                    <option value="14"class="text-black">H-2 Minggu</option>
                    <option value="30" class="text-black">H-1 Bulan</option>
                </select>
            </div>
            <div>
                <label class="block text-black" font-semibold mb-1">Ingatkan Setelah Kadaluarsa?</label>
                <select name="ingatkan_setelah_kadaluwarsa" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    <option value="ya"class="text-black">Ya, tetap ingatkan</option>
                    <option value="tidak" class="text-black" selected>Tidak perlu diingatkan lagi</option>
                </select>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Simpan Dokumen
                </button>
            </div>
        </form>
    </div>
      <x-slot:script>
        <script>
            // Tambah baris input nama_pic & nomor_pic
            function addPic() {
                const container = document.getElementById('pic-container');
                const newRow = document.createElement('div');
                newRow.classList.add('flex', 'gap-4', 'mb-2');
                newRow.innerHTML = `
    <input type="text" name="nama_pic[]" placeholder="Nama PIC" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
    <input type="text" name="nomor_pic[]" placeholder="Nomor PIC" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
    <button type="button" onclick="removePic(this)" class="text-red-500 font-bold">✕</button>
`;
                container.appendChild(newRow);
            }

            function removePic(button) {
                button.parentElement.remove();
            }
        </script>
    </x-slot:script>
</x-app-layout>