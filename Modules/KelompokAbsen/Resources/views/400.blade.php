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
        <div class="py-4 text-gray-700 text-center text-xl tracking-wider"
            style="display: flex;flex-direction: column;height: 80vh;align-content: center;align-items: center;justify-content: center;">
            <video autoplay loop muted>
                <source src="https://simpeg.padang.go.id/vi/1709717899959.mp4" type="video/mp4">
            </video>
            <br>
            <div style="margin-left:150px;margin-right:150px;text-align:justify">
                Bpk/Ibu Yth, untuk saat ini fitur update Kelompok Absen tidak tersedia, silakan Bpk/Ibu cek data yang ada pada
                menu Data Pegawai, jika jumlah pegawai yang tampil belum sesuai dengan jumlah pegawai yang ada pada Perangkat Daerah/Unit
                Organisasi Bpk/Ibu, silakan buat laporan pada grup WhatsApp dengan melampirkan data Kekurangan/Kelebihan ASN pada perangkat 
                Daerah/Unit Organisasi Bpk/Ibu.
            </div>
        </div>
    </div>
</x-app>
