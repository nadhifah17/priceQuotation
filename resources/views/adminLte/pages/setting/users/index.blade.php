@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="openModal('create')">+ {{ __('view.create_user') }}</button>
            </div>

            <div class="table-responsive">
                <table class="table {{ dt_table_class() }}" id="table-users">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- begin::modal --}}
    <div class="modal modal-q fade" id="modalAddUser"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-users">
                    <div class="body">
                        @include('adminLte.pages.setting.users.form')
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
    {{-- end::modal --}}
@endsection

@push('scripts')
    <script>
        let dt_route = "{{ route('users.ajax') }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    width: '5%' 
                },
                {data: 'email', name: 'email', width: '75%'},
                {data: 'action', name: 'action', width: '20%'},
        ];
        let dt = setDataTable('table-users', columns, dt_route);

        function openModal(type, url = null) {
            let form = $('#form-users');
            let method;
            if (type == 'create') {
                $('#modalAddUser').modal('show');
                form.attr('url', "{{ route('users.store') }}")
                form.attr('method', 'POST');
            } else if (type == 'edit') {
                $('#modalAddUser').modal('show');
                form.attr('url', url)
                form.attr('method', 'PUT');
            }
        }
        
        function closeModal() {
            $('#modalAddUser').modal('hide');
            resetForm('form-users');
        }

        function editItem(id) {
            let url = "{{ route('users.show', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    console.log(res);
                    openModal('edit', res.url);
                    $('#email').val(res.data.email);
                    $('#role').val(res.data.role.id);
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to get data');
                }
            })
        }

        function save() {
            let form = $('#form-users');
            let url = form.attr('url');
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
                    closeModal();
                    resetForm('form-users');
                    dt.ajax.reload();
                    setNotif(false, res.message)
                },
                error: function(err) {
                    setLoading('btn-submit', false);
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to save permission');
                }
            })
        }

        function deleteItem(id) {
            let url = `{{ route('users.destroy', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('view.confirm_delete_text_user') }}`,
                `{{ __('view.confirm_cancel_text') }}`,
                `{{ __('view.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush