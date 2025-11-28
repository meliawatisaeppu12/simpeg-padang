@section('style')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bulma.min.css">
<style>
    .select {
        background-color: white;
    }

    input[type=search] {
        background-color: white;
        border: 1px solid #0000005c;
    }

    div[class=select] {
        background-color: white;
    }
</style>
@endsection
@section('script')
<script src="{{ url('/js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bulma.min.js"></script>
<script>
    $(document).ready(function() {

        // const fileInput = document.querySelector('#input-file input[type=file]');
        // fileInput.onchange = () => {
        //     if (fileInput.files.length > 0) {
        //         const fileName = document.querySelector('#input-file .file-name');
        //         fileName.textContent = fileInput.files[0].name;
        //     }
        // }

        @if(Auth::user()->username == "198801312010011006")
        var url = '{{route("data-pegawai-data")}}';
        @else
        var url = '{{route("data-pegawai-datatables")}}';
        @endif

        var datatable_pegawai = $('#datatable-pegawai').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 15,
            lengthMenu: [
                [15, 30, 60, -1],
                [15, 30, 60, 'Semua'],
            ],
            ajax: url,
            columns: [{
                    data: 'NIP_BARU',
                    className: 'bg-white'

                },
                {
                    data: 'NAMA',
                    className: 'bg-white'

                },
                {
                    data: 'UNOR_NAMA',
                    className: 'bg-white'

                },
                {
                    data: 'UNOR_INDUK_NAMA',
                    className: 'bg-white'

                },
                {
                    data: 'KELOMPOK_ABSEN',
                    className: 'bg-white'

                }
            ],
            initComplete: function() {

                featherReplace();

                function save() {
                    console.log('save');
                }
            },
            language: {
                "decimal": "",
                "emptyTable": "No data available in table",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                "infoEmpty": "menampilkan 0 hingga 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ total data.)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Menampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "",
                "search": "Cari:",
                "zeroRecords": "Data tidak ditemukan.",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            }
        });

        feather.replace({
            width: 18,
            height: 18
        });

        function featherReplace() {
            feather.replace({
                width: 18,
                height: 18
            });
        }

        $('input[name="file-data-pegawai"]').on('change', function() {
            var fileLength = $(this)[0].files.length;
            if (fileLength > 0) {
                $('#btn-upload').removeAttr('disabled');
            } else {
                $('#btn-upload').attr('disabled', 'disabled');
            }
        });

        // $('#btn-upload').on("click", function() {

        //     $('#label-processing').removeClass("hidden");
        //     $('#btn-upload').attr('disabled', 'disabled');
        //     $('#label-messages').addClass("hidden");
        //     $('#label-messages').removeClass("text-red-500");

        //     var formData = new FormData();
        //     formData.append('file-data-pegawai', $('input[name="file-data-pegawai"]')[0].files[0]);
        //     formData.append('_token', $('input[name="_token"]').val());

        //     $.ajax({
        //         url: '{{ route('data-pegawai-upload') }}',
        //         type: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(data) {
        //             $('#label-processing').addClass("hidden");
        //             $('#label-messages').removeClass("hidden");
        //             $('#btn-upload').attr('disabled', 'disabled');
        //             $('#label-messages').html(
        //                 '<i data-feather="check"></i>&nbsp;Proses unggah berhasil.');
        //             featherReplace();
        //             console.log(data);
        //             $('input[name="file-data-pegawai"]').val(null);
        //             setTimeout(() => {
        //                 $('#label-messages').html(
        //                     '<i data-feather="refresh-cw" class="animate-spin"></i>&nbsp;Memperbarui data...'
        //                 );
        //                 $('#label-messages').addClass("animate-pulse");
        //                 featherReplace();
        //                 datatable_pegawai.ajax.reload(function() {
        //                     $('#label-messages').removeClass(
        //                         "animate-pulse");
        //                     $('#label-messages').addClass("hidden");
        //                     location.replace('#');
        //                     const fileName = document.querySelector(
        //                         '#input-file .file-name');
        //                     fileName.textContent =
        //                     'Tidak ada file dipilih.';
        //                 });
        //             }, 1500);

        //         },
        //         error: function(xhr, ajaxOptions, thrownError) {

        //             var errorMessages;
        //             switch (xhr.status) {
        //                 case 500:
        //                     errorMessages = 'Terjadi kesalahan pada server.';
        //                     break;
        //                 case 400:
        //                     errorMessages = 'Permintaan tidak dapat diproses.';
        //                     break;
        //                 case 401:
        //                     errorMessages = 'Sesi telah berakhir.';
        //                     break;
        //                 case 419:
        //                     errorMessages = 'Sesi telah berakhir.';
        //                     break;
        //                 case 403:
        //                     errorMessages = 'Akses tidak diizinkan.';
        //                     break;
        //                 case 413:
        //                     errorMessages = 'Ukuran berkas terlalu besar.';
        //                     break;
        //                 case 422:
        //                     errorMessages = 'Format file tidak didukung.';
        //                     break;
        //                 default:
        //                     errorMessages = thrownError;
        //                     break;
        //             }

        //             $('#label-processing').addClass("hidden");
        //             $('#btn-upload').removeAttr('disabled', 'disabled');
        //             $('#label-messages').removeClass("hidden");
        //             $('#label-messages').addClass("text-red-500");
        //             $('#label-messages').html(
        //                 '<i data-feather="frown"></i>&nbsp;Berkas gagal diunggah.');
        //             featherReplace();
        //             setTimeout(() => {
        //                 $('#label-messages').html(
        //                     '<i data-feather="alert-circle"></i>&nbsp;' +
        //                     errorMessages);
        //                 featherReplace();
        //                 if (xhr.status == 419 || xhr.status == 401) {
        //                     console.log('error 401 atau 419');
        //                     setTimeout(() => {
        //                         location.replace("{{ route('login') }}");
        //                     },2000);
        //                 }
        //             }, 2000);
        //         }
        //     });
        // })
    });
</script>
@endsection
@section('breadcrumb')
<li>Data Pegawai</li>
@endsection
<x-app>
    <div class="p-5 bg-white min-h-screen">
        <div class="flex overflow-x-auto relative sm:rounded-lg" style="width: 93%;">
            <table class="text-sm text-left text-gray-500 table is-striped" id="datatable-pegawai" style="width: 100%;">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-0 whitespace-nowrap">
                            NIP
                        </th>
                        <th scope="col" class="px-6 py-3 w-0 whitespace-nowrap">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Unit Organisasi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Unit Organisasi Induk
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kelompok Absen
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</x-app>