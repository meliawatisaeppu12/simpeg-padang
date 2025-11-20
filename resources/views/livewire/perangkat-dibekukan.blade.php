@section('breadcrumb')
    Akun Dibekukan
@endsection
@section('styles')
    {{-- styles --}}
@endsection
@section('scripts')
@endsection
<div>
    <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
        <div class="mb-4 position-relative col-md-12 col-sm-12 col-12">
            <input autofocus id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="Cari ...">
            <div wire:loading wire:target="search" class="position-absolute"
                style="top: 50%; right: 10px; transform: translateY(-50%);">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
        </div>
    </div>
    <div>
        Info:
        <i>Informasi percobaan akses yang diblokir bisa dilihat pada menu Perangkat.</i>
    </div>
    <table class="table .table-responsive mt-4">
        <thead>
            <tr style="background: #eaeef2">
                <th scope="col">No</th>
                <th scope="col">NIP</th>
                <th scope="col">Nama</th>
                <th scope="col">Status</th>
                <th scope="col">Waktu Deteksi</th>
                <th scope="col">Waktu Pembaruan</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $x)
                <tr>
                    <td style="text-align: center; vertical-align:top">
                        {{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                    <td style="vertical-align: top">{{ $x->nip }}</td>
                    <td>
                        <strong>{{ $x->nama }}</strong><br>
                        <i>{{ $x->unor_nama }}</i><br>
                        {{ $x->unor_induk_nama }}
                    </td>
                    <td class="@if ($x->counted == 0) text-warning @else text-danger @endif">
                        {{ $x->counted == 0 ? 'Akses Dibatasi' : 'Akun Dibekukan' }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $x->created_at, 'Asia/Jakarta')->translatedFormat('H:i:s d F Y') }}
                    </td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $x->updated_at, 'Asia/Jakarta')->translatedFormat('H:i:s d F Y') }}
                    </td>
                    <td>
                        @if ($x->counted > 0)
                            <button wire:loading.attr="disabled" class="btn btn-info btn-sm d-flex align-items-center"
                                wire:click="update({{$x->id}})"><i wire:loading
                                    wire:target="update({{$x->id}})" class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Unblock&nbsp;&nbsp;</button>
                        @endif
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <nav class="w-100 d-flex align-items-center justify-content-between">
        <ul class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12">
            @if (!$data->onFirstPage())
                <button type="button" wire:click="previousPage" class="btn btn-info btn-sm">Previous</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Previous</button>
            @endif

            @foreach ($page_links as $page)
                <button wire:click="gotoPage({{ $page }})"
                    class="btn btn-info btn-sm {{ $page == $data->currentPage() ? 'disabled' : '' }}"
                    {{ $page == $data->currentPage() ? 'disabled' : '' }}>{{ $page }}</button>
            @endforeach

            @if ($data->hasMorePages())
                <button type="button" wire:click="nextPage" class="btn btn-info btn-sm">Next</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Next</button>
            @endif
        </ul>
        Total: {{$jumlahData}} data.
    </nav>
</div>
