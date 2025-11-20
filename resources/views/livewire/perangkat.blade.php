@section('breadcrumb')
    Perangkat
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
                <th scope="col" style="text-align: center" colspan="2">Unit Organisasi Induk</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($search))
                @forelse ($mapped_data as $item)
                    <tr>
                        <td style="text-align: center">{{ $item['nama'] }}</td>
                        <td style="text-align: center">{{ $item['nip_baru'] }}</td>
                        <td style="text-align: center">{{ $item['jabatan_nama'] }}</td>
                        <td style="text-align: center">{{ $item['unor_nama'] }}</td>
                        <td style="text-align: center" colspan="2">{{ $item['unor_induk_nama'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col" colspan="2"></th>
                    </tr>
                    <tr style="">
                        <th style="text-align: center;" colspan="6">Devices</th>
                    </tr>
                    <tr style="background: #eaeef2">
                        <th scope="col">Brand</th>
                        <th scope="col">Seri</th>
                        <th scope="col">Device</th>
                        <th scope="col">Identifier</th>
                        <th scope="col" style="white-space: nowrap;">Waktu Terdaftar</th>
                        <th scope="col">Tanggal Terdaftar</th>
                    </tr>
                    @forelse($item['device'] as $device)
                        <tr>
                            <td style="vertical-align: top">{{ strtoupper($device[0]->device_brand) }}</td>
                            <td style="vertical-align: top">{{ $device[0]->device_name }}</td>
                            <td style="vertical-align: top">{{ $device[0]->device }}</td>
                            <td style="vertical-align: top">
                                @forelse($device as $d)
                                    - {{ $d->uuid }}<br>
                                @empty
                                @endforelse
                            </td>
                            <td style="vertical-align: top;">
                                @forelse($device as $d)
                                    -
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->translatedFormat('H:i:s') }}<br>
                                @empty
                                @endforelse
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                @forelse($device as $d)
                                    -
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                                @empty
                                @endforelse
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col" colspan="2"></th>
                    </tr>
                    <tr style="">
                        <th style="text-align: center;" colspan="6">Akses Diblokir</th>
                    </tr>
                    <tr style="background: #eaeef2">
                        <th scope="col">Brand</th>
                        <th scope="col">Seri</th>
                        <th scope="col">Device</th>
                        <th scope="col">Identifier</th>
                        <th scope="col" style="white-space: nowrap;">Waktu Akses</th>
                        <th scope="col">Tanggal Akses</th>
                    </tr>
                    @forelse($item['unknown_device'] as $device)
                        <tr>
                            <td style="vertical-align: top">{{ strtoupper($device[0]->device_brand) }}</td>
                            <td style="vertical-align: top">{{ $device[0]->device_name }}</td>
                            <td style="vertical-align: top">{{ $device[0]->device }}</td>
                            <td style="vertical-align: top">
                                @forelse($device as $d)
                                    - {{ $d->uuid }}<br>
                                @empty
                                @endforelse
                            </td>
                            <td style="vertical-align: top;">
                                @forelse($device as $d)
                                    -
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->translatedFormat('H:i:s') }}<br>
                                @empty
                                @endforelse
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                @forelse($device as $d)
                                    -
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                                @empty
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center">Tidak ada akses diblokir</td>
                        </tr>
                    @endforelse
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            @else
                <tr>
                    <td scope="col" colspan="6" style="text-align: center">Ketik NIP atau Nama pada kolom
                        pencarian.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
