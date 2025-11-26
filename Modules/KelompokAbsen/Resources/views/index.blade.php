@section('style')
    <link rel="stylesheet" href="{{ url('/css/select2.css') }}">
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
    <script>
        function cekData() {
            var selected = $('#select2-asn').val();
            if (selected.length == 0) {
                $('#info').removeClass('hidden').text('Pegawai belum dipilih.');
            } else {
                $('#info').addClass('hidden').text('');
                $('#btn-cek').attr('disabled', true);
                $('#spinner').removeClass('hidden');
                $('#td-spinner').removeClass('hidden');
                $('#btn-cek-text').html('&nbsp;Memeriksa');
                $('#td-text').html('&nbsp;Memeriksa');
                $('#td-text').removeClass('text-red-600');
                var data = {
                    'id': selected,
                    '_token': '{{ csrf_token() }}'
                };
                $.ajax({
                    url: '{{ route('kelompokabsen.cekasn') }}',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        $('#spinner').addClass('hidden');
                        $('#td-spinner').addClass('hidden');
                        $('#btn-cek-text').text('Cek');
                        $('#btn-cek').removeAttr('disabled');
                        $('#td-text').html('&nbsp;Memeriksa');
                        $('#tbody').html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#spinner').addClass('hidden');
                        $('#td-spinner').addClass('hidden');
                        $('#btn-cek-text').text('Cek');
                        $('#btn-cek').removeAttr('disabled');
                        if (textStatus == 'timeout') {
                            $('#td-text').addClass('text-red-600');
                            $('#td-text').html('Waktu koneksi habis. Silakan coba kembali.');
                        } else {
                            $('#td-text').addClass('text-red-600');
                            $('#td-text').html(errorThrown);
                        }
                    },
                    timeout: 60000
                });
            }
        }

        function updateKelompok() {
            $('#spinner-2').removeClass('hidden');
            $('#btn-simpan-text').html('&nbsp;Menyimpan');
            $('#btn-simpan').attr('disabled', true);
            var unor_id = $('input[name="unor_id[]"]').map(function() {
                return $(this).val();
            }).get();
            var unor_induk_id = $('select[name="unor_induk_id[]"]').map(function() {
                return $(this).val();
            }).get();
            var data = {
                'unor_id': unor_id,
                'unor_induk_id': unor_induk_id,
                '_token': '{{ csrf_token() }}'
            };
            $.ajax({
                url: '{{ route('kelompokabsen.update') }}',
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#btn-simpan-text').html('&nbsp;Memeriksa');
                    cekData();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btn-simpan-text').html('&nbsp;SIMPAN');
                    $('#spinner-2').addClass('hidden');
                    $('#btn-simpan').removeAttr('disabled');
                },
                timeout: 60000
            });
        }

        $('#btn-cek').on('click', function() {
            cekData();
        });
    </script>
@endsection
@section('breadcrumb')
    <li>Kelompok Absen</li>
@endsection
<x-app>
    <div class="shadow-drop-center bg-white">
        <div class="py-4 text-gray-700 text-center text-xl tracking-wider">
            Pilih ASN untuk memperbarui Kelompok Absen
        </div>
        <form class="bg-white border rounded px-8 pt-6 pb-8 mb-4" method="POST" autocomplete="on" novalidate>

            <div class="mb-4">
                <label class="block text-gray-700  text-md font-bold mb-2" for="pair">
                    Pegawai:
                </label>
                <select id="select2-asn" class="js-example-basic-multiple" style="width: 100%"
                    data-placeholder="Pilih satu atau lebih pegawai..." data-allow-clear="false" multiple="multiple"
                    title="Pilih Pegawai...">
                    @foreach ($data as $x)
                        <option value="{{ $x->id }}">{{ $x->nama.' ('.$x->nip_baru.')' }}</option>
                    @endforeach
                </select>
                <div class="text-red-600 italic hidden" id="info">

                </div>
            </div>
            <div class="flex items-center justify-end mr-8">
                <button id="btn-cek"
                    class="flex bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:pointer-events-none"
                    type="button">
                    <svg id="spinner" class="animate-spin hidden" width="24" height="24" fill="white"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
                            opacity=".25" />
                        <path
                            d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z"
                            class="spinner_ajPY" />
                    </svg>
                    <div id="btn-cek-text">
                        &nbsp;Cek
                    </div>
                </button>
            </div>
        </form>
        <div class="py-0 text-gray-700 text-center text-xl tracking-wider mb-10">
            <div class="relative overflow-x-auto m-8">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 w-2/6">
                                Unit Organisasi
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Kelompok Absen
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Terakhir Diperbarui
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-center" colspan="4">
                                <div id="td-info" class="flex justify-center items-center">
                                    <svg id="td-spinner" class="animate-spin hidden" width="24" height="24"
                                        fill="black" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
                                            opacity=".25" />
                                        <path
                                            d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z"
                                            class="spinner_ajPY" />
                                    </svg>
                                    <div id="td-text">
                                        Tidak ada data dipilih.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded px-8 pt-6 pb-8 mb-4">
            <strong>Catatan</strong>: absen diatur per Unit Organisasi, jika sebuah Unit Organisasi berada pada Kelompok
            Absen tertentu
            maka
            semua ASN pada unit organisasi tersebut akan berada pada kelompok absen tersebut. Tabel diatas menampilkan
            data
            kelompok absen sesuai pada data yang dipilih pada kolom isian pegawai.
        </div>
    </div>
</x-app>
