<div class="row">
    <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
        <div class="col-md-6 col-sm-6 col-12">
            <a class="btn btn-info btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="mb-4 position-relative col-md-6 col-sm-6 col-12">
            <input id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="Cari Pegawai...">
            <div wire:loading wire:target="search" class="position-absolute"
                style="top: 50%; right: 10px; transform: translateY(-50%);">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
        </div>
    </div>
    <table class="table .table-responsive">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">NIP</th>
                <th scope="col">Nama</th>
                <th scope="col">Jabatan</th>
                <th scope="col">Unit Organisasi Induk</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="text-center">{{ ($current_page - 1) * $perPage + $loop->iteration }}</td>
                    <td>{{ $item->nip_baru }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jabatan_nama }}</td>
                    <td>{{ $item->unor_induk_nama }}</td>
                    <td><button wire:click="goTo({{ '"' . Crypt::encrypt($item->nip_baru) . '"' }})"
                            class="btn btn-info btn-sm" type="button"><i class="fa fa-arrow-right"></i></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <nav class="w-100 d-flex justify-content-between">
        <ul class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12">
            @if (!$data->onFirstPage())
                <button type="button" wire:click="previousPage" class="btn btn-info btn-sm">Previous</button>
                <button type="button" class="btn btn-info btn-sm pointer-none">...</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Previous</button>
            @endif

            @foreach ($page_links as $page)
                <button wire:click="gotoPage({{ $page }})"
                    class="btn btn-info btn-sm {{ $page == $data->currentPage() ? 'disabled' : '' }}"
                    {{ $page == $data->currentPage() ? 'disabled' : '' }}>{{ $page }}</button>
            @endforeach

            @if ($data->hasMorePages())
                <button type="button" class="btn btn-info btn-sm pointer-none">...</button>
                <button type="button" wire:click="nextPage" class="btn btn-info btn-sm">Next</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Next</button>
            @endif
        </ul>
        <div class="mr-4">
            @if ($filtered_data != $total_data && $filtered_data != 0)
                Total: {{ $filtered_data }} dari total {{ $total_data }} data.
            @else
                Total: {{ $total_data }}.
            @endif
        </div>
    </nav>
</div>
