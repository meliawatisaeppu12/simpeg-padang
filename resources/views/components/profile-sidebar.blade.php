@if ($kind == 1)
    <div class="pd-20 card-box height-100-p">
        <div class="profile-photo">
            <img style="width: 100px; height: 100px; object-fit: cover;"
                src="{{ route('photo-pegawai', json_decode($user)->nip_baru) }}" alt="" class="avatar-photo">
        </div>
        <div class="text-center">
            {{ json_decode($user)->nama . ', ' . json_decode($user)->gelar_belakang }}
        </div>
        <p class="text-center text-muted font-14">
            {{ json_decode($user)->nip_baru }}</p>
        <div class="profile-info" style="border-bottom: 2px dashed #ecf0f4;">
            <div class="text-primary text-center">
                {{ json_decode($user)->jabatan_nama }}</div>
            <div class="text-primary text-center font-weight-bolder">
                {{ json_decode($user)->unor_induk_nama }}</div>
        </div>
        @if (str_contains(url()->current(), '/pegawai/'))
            <div class="w-100">
                <a class="btn btn-info btn-sm w-100" href="{{ route('data-pegawai') }}"><i class="fa fa-arrow-left"></i>
                    Data Pegawai</a>
            </div>
        @endif
    </div>
@else
    <div class="w-100 text-center">
        <a class="btn btn-info btn-sm w-100" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i>
            Kembali</a>
    </div>
@endif
