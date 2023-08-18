@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <h2>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</h2>
    <h5>Berikut keterangan nilai untuk kriteria Material Spec:</h5>

    @if (session()->has('success'))
            <div class="alert alert-danger mb-3" role="alert">
                {{ session()->get('success') }}
                {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
            </div>
    @endif

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


    {{-- end::table --}}

    <h6>Range Nilai Setiap Kriteria:</h6>
    <ul>
        <li><h7>Overhead     : 0,05 ≤ Overhead ≤ 0,15</h7></li>
        <li><h7>Material Spec: 1 ≤ Material Spec ≤ 5</h7></li>
        <li><h7>Cycle Time   : 0 < Cycle Time ≤ 5</h7></li>
    </ul>


    @if (auth()->user()->can('manage-master-tmmin'))
    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="openModal('create', 'manual')">+ {{ __('view.insert_data') }}</button>
    </div>
    @endif

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

    

    {{-- begin::table --}}
    <div class="table-responsive">
        <table class="table {{ dt_table_class() }}" id="table-alt">
            <thead class="{{ dt_head_class() }}">
                    @if($data)
                    <!-- EDIT -->
                    <h5>Berikut beberapa Alternatif:</h5>
                    <!-- Tampilkan informasi lainnya sesuai struktur tabel summary_cost_tmmin -->
                    @else
                    <!-- ADD ALT -->
                    <p>Silahkan klik "+ Insert Data" untuk membuat beberapa Alternatif!</p>
                    @endif
                <tr>
                    <th>No.</th>
                    <th>Alternatif</th>
			        <th>Overhead</th>
			        <th>Material Spec</th>
			        <th>Cycle Time</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    {{-- end::table --}}

    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="window.location.href='{{ route('data.calculate', ['id' => $cost->id]) }}'">Calculate SPK</button>
    </div>
    <br>

    {{-- begin::modal --}}
    <div class="modal modal-q fade" id="modalAddCost"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddCostLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-column">
                    <div class="body">
                        @include('adminLte.pages.data.formcrt')
                    </div>
                    <div class="footer d-flex align-items-end justify-content-end">
                        <button class="btn btn-sm bg-primary-success" type="button" onclick="save()" id="btn-submit">{{ __('view.submit') }}</button>
                        <button class="btn btn-sm bg-primary-danger ml-2" type="button" onclick="closeModal('create')">{{ __('view.close') }}</button>
                    </div>
                </form>
                {{-- end::form --}}
            </div>
        </div>
        </div>
    </div>
    {{-- end::modal --}}

@endsection

@push('scripts')  
    <script>
        let dt_route = "{{ route('data.ajax', ['id' => $cost->id]) }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'quality', name: 'quality'},
                {data: 'service', name: 'service'},
                {data: 'action', name: 'action'},
             
        ];
        let dt = setDataTable('table-alt', columns, dt_route, 1);

        function openModal(action, typeModal) {
            let url = '';
            if (typeModal == 'manual') {
                $('#modalAddCost').modal('show');
                if (action == 'create') {
                    url = "{{ route('data.store', ['id' => $cost->id]) }}";
                    $('#form-column').attr('action', url);
                    $('#form-column').attr('method', 'POST');
                }
            } else if (typeModal == 'import') {
                $('#modalImportCost').modal('show');
            }
        }


        function closeModal(action) {
            if (action == 'create') {
                $('#modalAddCost').modal('hide');
            } else if (action == 'overview') {
                $('#modalOverview').modal('hide');
            } else if (action == 'import') {
                $('#modalImportCost').modal('hide');
            }
        }

        function editItem(id) {
            let elem = $('#btn-edit-attribute-' + id);
            let type = elem.data('type');
            let url = elem.data('url');
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    openModal('edit', 'manual');
                    fillValue(res.data);
                    // manipulate attribute
                    $('#form-column').attr('action', res.url);
                    $('#form-column').attr('method', 'PUT');
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function fillValue(res) {
            $('#name').val(res.name);
            $('#price').val(res.price);
            $('#quality').val(res.quality);
            $('#service').val(res.service);
        }

        function save() {
            let form = $('#form-column');
            let url = form.attr('action');
            let method = form.attr('method');
            let data = form.serialize();
            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-submit', true);
                },
                success: function(res) {
                    setLoading('btn-submit', false);
                    closeModal('create');
                    setNotif(false, res.message);
                    dt.ajax.reload();
                },
                error: function(err) {
                    setLoading('btn-submit', false);
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Something wrong');
                }
            })
        }

        function deleteItem(id) {
            let url = `{{ route('data.destroy', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                    `{{ __('view.confirm_delete_text_alternative') }}`,
                    `{{ __('view.confirm_cancel_text') }}`,
                    `{{ __('view.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush

