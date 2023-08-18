@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="openModal('create', 'manual')">+ {{ __('view.insert_data') }}</button>
        <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center ml-3" type="button" onclick="openModal('create', 'import')">+ {{ __('view.import_data') }}</button>
    </div>

    {{-- begin::table --}}
    <div class="table-responsive">
        <table class="table {{ dt_table_class() }}" id="table-process">
            <thead class="{{ dt_head_class() }}">
                <tr>
                    <th>No.</th>
                    <th>Group</th>
                    <th>Process Code</th>
                    <th>Process Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    {{-- end::table --}}

    {{-- begin::modal --}}
    <div class="modal modal-q fade" id="modalAddProcess"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddProcessLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-process">
                    <div class="body">
                        @include('adminLte.pages.process.form')
                    </div>
                    <div class="footer d-flex align-items-end justify-content-end">
                        <button class="btn btn-sm bg-primary-success" type="button" onclick="save()" id="btn-submit">{{ __('view.submit') }}</button>
                        <button class="btn btn-sm bg-primary-danger ml-2" type="button" onclick="closeModal('create')">{{ __('view.close') }}</button>
                    </div>
                </form>
                {{-- end::form --}}
                {{-- <button type="button" class="btn btn-primary">{{ __('view.submit') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('view.close') }}</button> --}}
            </div>
        </div>
        </div>
    </div>

    <div class="modal modal-q fade" id="modalImportProcess"  data-keyboard="false" tabindex="-1" aria-labelledby="modalImportProcess" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Import Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-import-process" action="{{ route('process.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="body">
                        <div class="form-group mb-3">
                            <label for="file" class="col-form-label">File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xlsb" id="file">
                        </div>
                        <div class="form-group mb-3">
                            <p class="m-0">{{ __("view.don't_have_template") }} {{ __('view.download') }} <a href="{{ route('process.download-template') }}">here</a></p>
                        </div>
                    </div>
                    <div class="footer d-flex align-items-end justify-content-end">
                        {{-- <button class="btn btn-sm bg-primary-success" type="submit" id="btn-overview-import">{{ __('view.submit') }}</button> --}}
                        <button class="btn btn-sm bg-primary-success" type="button" id="btn-overview-import" onclick="overviewImport()">{{ __('view.submit') }}</button>
                        <button class="btn btn-sm bg-primary-danger ml-2" type="button" onclick="closeModal('import')">{{ __('view.close') }}</button>
                    </div>
                </form>
                {{-- end::form --}}
            </div>
        </div>
        </div>
    </div>

    <div class="modal modal-q fade" id="modalOverview"  data-backdrop="static"data-keyboard="false" tabindex="-1" aria-labelledby="modalOverview" aria-hidden="true">
        <form action="{{ route('process.submit-import') }}" method="post">
            @csrf
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content p-0 bg-transparent border-none">
                    <div class="modal-header modal-custom-header">
                        <p class="title">Overview Data</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0 m-0">
                        <div class="body" id="targetOverview">
                        </div>
                        <input type="text" name="current_file" class="d-none" id="current_file">
                        {{-- end::form --}}
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-sm bg-primary-success" type="submit" id="btn-submit-import">{{ __('view.submit') }}</button> --}}
                        <button class="btn btn-sm bg-primary-success" type="button" id="btn-submit-import" onclick="submitImport()">{{ __('view.submit') }}</button>
                        <button class="btn btn-sm bg-primary-danger ml-2" type="button" onclick="closeModal('overview')">{{ __('view.close') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- end::modal --}}
@endsection

@push('scripts')
    <script>
        let dt_route = "{{ route('process.ajax') }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'process_id', name: 'process_id'},
                {data: 'process_code_id', name: 'process_code_id'},
                {data: 'rate', name: 'rate'},
                {data: 'action', name: 'action'},
        ];
        let dt = setDataTable('table-process', columns, dt_route, 1);

        // begin:: action when modal has been closed
        $('#modalImportProcess').on('hidden.bs.modal', function (event) {
            document.getElementById('form-import-process').reset();
        });
        $('#modalOverview').on('hidden.bs.modal', function (event) {
            $('#current_file').val('');
        });
        $('#modalAddProcess').on('hidden.bs.modal', function (event) {
            resetForm('form-process');
        });
        // end:: action when modal has been closed

        function openModal(action, typeModal) {
            let url = '';
            if (typeModal == 'manual') {
                $('#modalAddProcess').modal('show');
                if (action == 'create') {
                    url = "{{ route('process.store') }}";
                    $('#form-process').attr('action', url);
                    $('#form-process').attr('method', 'POST');
                }
            } else if (typeModal == 'import') {
                $('#modalImportProcess').modal('show');
            }
        }

        function submitImport() {
            let data = $('#current_file').val();
            $.ajax({
                type: 'POST',
                url: "{{ route('process.submit-import') }}",
                data: {
                    current_file: data
                },
                beforeSend: function() {
                    let loading = `<div class="spinner-border" style="width: 1em; height: 1em" role="status">
                        <span class="visually-hidden"></span>
                        </div>`;
                    $('#btn-submit-import').html(loading);
                },
                success: function(res) {
                    $('#btn-submit-import').html("{{ __('view.submit') }}");
                    setNotif(false, res.message);
                    $('#current_file').val('');
                    $('#modalOverview').modal('hide');
                    dt.ajax.reload();
                },
                error: function(err) {
                    $('#btn-submit-import').html("{{ __('view.submit') }}");
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed');
                }
            })
        }

        function overviewImport() {
            let form = $('#form-import-process');
            let url = form.attr('action');
            let method = form.attr('method');
            let formWithData = $('#form-import-process')[0];
            let data = new FormData(formWithData);

            $.ajax({
                type: "POST",
                url: url,
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                beforeSend: function() {
                    let loading = `<div class="spinner-border" style="width: 1em; height: 1em" role="status">
                        <span class="visually-hidden"></span>
                        </div>`;
                    $('#btn-overview-import').html(loading);
                },
                success: function(res) {
                    $('#btn-overview-import').html("{{ __('view.submit') }}");
                    $('#targetOverview').html(res.view);
                    $('#modalOverview').modal('show');
                    $('#modalImportProcess').modal('hide');
                    $('#current_file').val(res.filename);
                    document.getElementById('form-import-process').reset();
                },
                error: function(err) {
                    $('#btn-overview-import').html("{{ __('view.submit') }}");
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed');
                }
            })
        }

        function closeModal(action) {
            if (action == 'create') {
                $('#modalAddProcess').modal('hide');
            } else if (action == 'overview') {
                $('#modalOverview').modal('hide');
            } else if (action == 'import') {
                $('#modalImportProcess').modal('hide');
            }
        }

        function editItem(id) {
            let elem = $('#btn-edit-process-' + id);
            let url = elem.data('url');
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    console.log(res);
                    openModal('edit', 'manual');
                    fillValue(res.data);
                    // manipulate attribute
                    $('#form-process').attr('action', res.url);
                    $('#form-process').attr('method', 'PUT');
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function fillValue(res) {
            $('#process_group').val(res.process_id);
            $('#process_code').val(res.code.name);
            $('#rate').val(res.rate);
        }

        function save() {
            let form = $('#form-process');
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
            let url = `{{ route('process.destroy', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('view.confirm_delete_text_process') }}`,
                `{{ __('view.confirm_cancel_text') }}`,
                `{{ __('view.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush