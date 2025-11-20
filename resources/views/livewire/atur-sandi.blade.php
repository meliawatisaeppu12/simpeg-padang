@section('breadcrumb')
    Atur Ulang Kata Sandi
@endsection
@section('styles')
    {{-- styles --}}
@endsection
@section('scripts')
@endsection

<div>
    <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12" role="group"
            aria-label="First group">
        </div>
        <div class="mb-4 position-relative col-md-12 col-sm-12 col-12">
            <label for="search">
                Gunakan kolom dibawah untuk melakukan pencarian data.
            </label>
            <input autofocus id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="Cari NIP atau Nama ...">
            <div wire:loading wire:target="search" class="position-absolute"
                style="top: 50%; right: 10px; transform: translateY(-50%);">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
        </div>
    </div>
    <table class="table .table-responsive">
        <thead>
            <tr>
                <th style="text-align: center;" colspan="6">Pegawai</th>
            </tr>
            <tr style="background: #eaeef2">
                <th scope="col" style="text-align: center">Nama</th>
                <th scope="col" style="text-align: center">NIP</th>
                <th scope="col" style="text-align: center">Jabatan</th>
                <th scope="col" style="text-align: center">Unit Organisasi</th>
                <th scope="col" style="text-align: center">Unit Organisasi Induk</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $x)
                <tr>
                    <td>{{$x->nama}}</td>
                    <td>{{$x->username}}</td>
                    <td>{{$x->jabatan_nama}}</td>
                    <td>{{$x->unor_nama}}</td>
                    <td>{{$x->unor_induk_nama}}</td>
                    <td>
                        <button wire:loading.attr="disabled" class="btn btn-info btn-sm d-flex align-items-center"
                            wire:click="resetSandi({{$x->username}})">
                            <i wire:loading wire:target="resetSandi({{$x->username}})" class="fa fa-spinner fa-spin"></i>
                            &nbsp;&nbsp;Reset&nbsp;&nbsp;
                        </button>
                    </td>
                </tr>
            @empty

            @endforelse
            @if($showInfo)
                @if($status == 1)
                    <tr>
                        <td colspan="6" class="text-light bg-success text-center">
                            Kata Sandi telah diatur ulang.
                        </td>
                    </tr>
                @else 
                    <tr>
                        <td colspan="6" class="text-light bg-danger text-center">
                            Permintaan gagal diproses.
                        </td>
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="6" class="text-dark bg-light text-center"><i>Gunakan tombol reset untuk mengatur ulang kata sandi.</i></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
