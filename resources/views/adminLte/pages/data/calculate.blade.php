@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <h2>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</h2>
    <h5>Berikut keterangan nilai untuk kriteria Material Spec:</h5>        

   <!-- resources/views/data/calculate.blade.php -->

    {{-- begin::table --}}
    <style>
    .table-container {
        display: inline-block;
        vertical-align: top;
    }

    .table-small {
        width: 32%;
    }

    .table-medium {
        width: 35%;
    }

    .table-large {
        width: 32%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid black;
    }
    </style>


    <div class="table-container table-small">
        <table>
            <thead >
                    <tr>
                        <th>No.</th>
                        <th>Material Spec</th>
                        <th>Nilai</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Material-01</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Material-02</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Material-03</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Material-04</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Material-05</td>
                        <td>5</td>
                    </tr>
                </thead>
        </table>
    </div>
    <p></p>

    {{-- end::table --}}


    {{-- begin::table --}}
    <style>
    .table-container {
        display: inline-block;
        vertical-align: top;
    }

    .table-spk {
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        padding: 8px;
        text-align: left;
        border: 1px solid black;
    }
    </style>

    <div class="table-responsive">
        <table class="table {{ dt_table_class() }}" id="table-alt">
            <thead class="table-dark">
                <tr>
                <h6>Di bawah ini merupakan perangkingan Sistem Pendukung Keputusan: </h6>
                <tr>
                    <th>Alternatif</th>
                    <th>Overhead</th>
                    <th>Material Spec</th>
                    <th>Cycle Time</th>
                    <th>Total</th>
                </tr>
                @foreach($alternatives as $alternative)
                <tr>
                    <td>{{ $alternative->name }}</td>
                    <td>{{ $alternative->price }}</td>
                    <td>{{ $alternative->quality }}</td>
                    <td>{{ $alternative->service }}</td>
                    <td>{{ $alternative->total }}</td>
                </tr>
                @endforeach
            </thead>
        </table>
        <p>Data teratas menunjukkan alternatif terbaik! </p>
    </div>
    {{-- end::table --}}
@endsection



