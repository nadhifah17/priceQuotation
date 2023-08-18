@foreach ($import as $key => $val)
    <div class="text-center">
        <h5>{{ $key }}</h5>

        <div class="border-bottom mb-3"></div>

        <div class="table-responsive mt-3 mb-3">
            <table class="table {{ dt_table_class() }}">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Specification</th>
                        <th>Period</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $a = 1;
                    @endphp
                    @foreach ($val as $item)
                        <tr>
                            <td>{{ $a }}</td>
                            <td>
                                <p class="m-0 p-0 text-overview" id="text-spec-overview-{{ $a }}">{{ $item['spec'] }}</p>
                                <input type="text" name="material[{{$a}}][spec]" value="{{ $item['spec'] }}" class="form-control form-control-sm d-none input-overview" id="input-spec-overview-{{ $a }}">
                            </td>
                            <td>
                                <p class="m-0 p-0" id="text-overview-{{ $a }}">{{ date('M y', strtotime($item['period'])) }}</p>
                            </td>
                            <td>
                                <p class="m-0 p-0 text-overview" id="text-rate-overview-{{ $a }}">{{ number_format($item['rate']) }}</p>
                                <input type="text" name="material[{{$a}}][rate]" value="{{ $item['rate'] }}" class="form-control form-control-sm d-none input-overview" id="input-rate-overview-{{ $a }}">
                            </td>
                            </td>
                        </tr>
                        @php
                            $a++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach