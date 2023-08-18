@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
<html>
    <head>
        <title>SAW Calculation</title>
    </head>
    <body>
    <!-- resources/views/data/create.blade.php -->
<h1>Create New Data</h1>

 {{-- begin::table --}}
    <div class="table-responsive">
        <table class="table {{ dt_table_class() }}" id="table-cost">
            <thead class="{{ dt_head_class() }}">
                <tr>
                    <th>No.</th>
                    <th>Part No.</th>
                    <th>Part Name</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    {{-- end::table --}}
<form id="form-calculate" method="POST" action="{{ route('data.store') }}">
    @csrf

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        @error('name')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="price">Price:</label>
        <input type="number" name="service" id="service" value="{{ old('service') }}" required>
        @error('price')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="quality">Quality:</label>
        <input type="number" name="service" id="service" value="{{ old('service') }}" required>
        @error('quality')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="service">Service:</label>
        <input type="number" name="service" id="service" value="{{ old('service') }}" required>
        @error('service')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">Create</button>
</form>

    </body>
</html>
@endsection
