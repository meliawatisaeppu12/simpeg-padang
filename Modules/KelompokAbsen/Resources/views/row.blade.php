@foreach ($return as $x)
    <tr>
        <td rowspan="2" style="vertical-align: top; text-align:center">{{ $loop->iteration }}</td>
        <td>
            <strong>{{ $x['unor_nama'] }}</strong>
            <input type="hidden" name="unor_id[]" value="{{$x['unor_id']}}">
        </td>
        <td rowspan="2" style="vertical-align: top">
            <select name="unor_induk_id[]" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                @foreach($dropdown_data as $z)
                    <option value="{{$z->unor_id}}" @if($z->unor_id==$x['unor_induk_id']) selected @endif>{{$z->unor_nama}}</option>
                @endforeach
            </select>
        </td>
        <td rowspan="2" style="vertical-align: top; text-align:center">
            <strong>{{ Carbon\Carbon::createFromDate($x['updated_at'])->setTimezone('Asia/Jakarta')->translatedFormat('j F Y') }}</strong></td>
    </tr>
    <tr>
        <td>
            @foreach (collect($x['asn_unit']) as $y)
                - {{ $y->nama }}<br>
            @endforeach
        </td>
    </tr>
@endforeach
<tr>
    <td colspan="4">
        <div class="flex justify-end items-center mr-8">
            <button id="btn-simpan" onclick="updateKelompok()"
                class="flex items-center bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:pointer-events-none">
                <svg id="spinner-2" class="animate-spin hidden" width="24" height="24" fill="white"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
                            opacity=".25" />
                        <path
                            d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z"
                            class="spinner_ajPY" />
                    </svg>
                    <div id="btn-simpan-text">
                        &nbsp;SIMPAN
                    </div>
            </button>
        </div>
    </td>
</tr>
