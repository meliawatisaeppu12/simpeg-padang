@section('style')

@endsection

@section('script')

    <script src="{{ url('/js/orgchart.js') }}"></script>
    <!-- choose one -->
    <script>
        var chart = new OrgChart(document.getElementById("tree"), {
            nodeBinding: {
                field_0: "name"
            },
            tags: {
            //     "assistant": {
            //         template: "ula"
            //     },
                "management": {
                    template: "olivia"
                }
            },
            nodes: [{
                    id: 1,
                    name: "Kepala Dinas",
                    tags: ["management"]
                },
                {
                    id: 2,
                    pid: 1,
                    name: "Sekretaris",
                    tags: ["assistant"]
                },
                {
                    id: 3,
                    pid: 1,
                    name: "Bidang e-Government"
                },
                {
                    id: 4,
                    pid: 1,
                    name: "Bidang Infrastuktur"
                }
            ]
        });
    </script>
@endsection

@section('breadcrumb')
    <li>Struktur Organisasi</li>
@endsection

<x-app>
    <div class="bg-gray-400 min-h-screen">
       
        <div class="bg-white border border-solid p-5">
            <div>
                <label class="label">
                    <span class="label-text">Pilih Perangkat Daerah</span>
                </label>
                <select class="select select-bordered w-full max-w-xs">
                    <option disabled selected>- Perangkat Daerah</option>
                    @forelse ($organisasi->sortBy("nama_organisasi") as $item)
                        <option value="{{ $item->id_organisasi }}">{{ $item->nama_organisasi }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div>
                <div style="width:100%; height:700px;" id="tree" />
            </div>
        </div>
    </div>
</x-app>
