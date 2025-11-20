<div class="row w-100">
    <div class="row w-100">
        <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
            <div class="col-md-6 col-sm-6 col-12">
                <a class="btn btn-info btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="mb-4 position-relative col-md-6 col-sm-6 col-12">
                <input id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                    placeholder="Cari Usulan..." disabled>
                <div wire:loading wire:target="search" class="position-absolute"
                    style="top: 50%; right: 10px; transform: translateY(-50%);">
                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row w-100 mb-5">
        <div class="row col-md-12 col-sm-12">
            <label class="col-md-12 col-sm-12 weight-600">Status Usulan</label>
            @forelse($ref_status_usulan as $item)
            <div class="custom-control custom-radio mb-5 col-md-4 col-sm-6">
                <input wire:model="statusUsulan" type="radio" id="statusUsulan{{$item->id}}" name="statusUsulan" class="custom-control-input" value="{{$item->id}}">
                <label class="custom-control-label" for="statusUsulan{{$item->id}}">{{$item->nama}}</label>
            </div>
            @empty
            @endforelse
        </div>
    </div>
    <div class="row w-100">
        <table class="table .table-responsive w-100">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIP Pengusul</th>
                    <th scope="col">Nama Pengusul</th>
                    <th scope="col">Jenis Riwayat</th>
                    <th scope="col">Tanggal Usulan</th>
                    <th scope="col">Status Usulan</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usulan_riwayat_jabatan as $item)
                    <tr>
                        <td class="text-center">{{ ($current_page - 1) * $perPage + $loop->iteration }}</td>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nipPengusul }}</td>
                        <td>{{ $item->namaPengusul }}</td>
                        <td>{{ $item->jenisRiwayat }}</td>
                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->tanggalUsulan, 'Asia/Jakarta')->format('j F Y') }}
                        </td>
                        <td>{{ $item->statusUsulan }}</td>
                        <td><button wire:click="goTo({{ '"' . Crypt::encrypt($item->id) . '"' }})"
                                class="btn btn-info btn-sm" type="button"><i class="fa fa-arrow-right"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <nav class="w-100 d-flex justify-content-between">
            <ul class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12">
                @if (!$usulan_riwayat_jabatan->onFirstPage())
                    <button type="button" wire:click="previousPage" class="btn btn-info btn-sm">Previous</button>
                    <button type="button" class="btn btn-info btn-sm pointer-none">...</button>
                @else
                    <button class="btn btn-info btn-sm disabled" disabled>Previous</button>
                @endif

                @foreach ($page_links as $page)
                    <button wire:click="gotoPage({{ $page }})"
                        class="btn btn-info btn-sm {{ $page == $usulan_riwayat_jabatan->currentPage() ? 'disabled' : '' }}"
                        {{ $page == $usulan_riwayat_jabatan->currentPage() ? 'disabled' : '' }}>{{ $page }}</button>
                @endforeach

                @if ($usulan_riwayat_jabatan->hasMorePages())
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
</div>
