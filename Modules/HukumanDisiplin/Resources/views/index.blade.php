<x-admin>
    @section("breadcrumb")
        Hukuman Disiplin
    @endsection
    
    @section('styles')
        <link rel="stylesheet" href="{{url('vendor/alertify/css/alertify.min.css')}}">
        <link rel="stylesheet" href="{{url('vendor/alertify/css/themes/default.min.css')}}">
    @endsection
    @section('scripts')
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <script src="{{url('vendor/alertify/alertify.min.js')}}"></script>
        <script>
            $('#select2').select2({
                ajax: {
                    url: '{{route("search-pegawai")}}',
                    dataType: 'json',
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 10) < data.count_filtered
                            },
                        };
                    },
                }
            });
        </script>
    @endsection
</x-admin>