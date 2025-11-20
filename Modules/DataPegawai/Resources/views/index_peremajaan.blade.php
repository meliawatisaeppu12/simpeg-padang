@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bulma.min.css">
<style>
    .select {
        background-color: white;
    }

    input[type=text] {
        background-color: bg-gray-200;
        /* border: 1px solid #0000005c; */
        margin-top: 18pt
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
            feather.replace({
                width: 18,
                height: 18
            });
    });
</script>
@endsection
@section('breadcrumb')
<li>Peremajaan</li>
@endsection
<x-app>
    
</x-app>