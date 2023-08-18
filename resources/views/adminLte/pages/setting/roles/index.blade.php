@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="openModal('create')">+ {{ __('view.create_role') }}</button>
            </div>

            <div class="table-responsive">
                <table class="table {{ dt_table_class() }}" id="table-roles">
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
    <div class="modal modal-q fade" id="modalAddRoles"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddRolesLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-permission">
                    <div class="body">
                        @include('adminLte.pages.setting.roles.form')
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
        let dt_route = "{{ route('setting.roles.ajax') }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    width: '5%' 
                },
                {data: 'name', name: 'name', width: '75%'},
                {data: 'action', name: 'action', width: '20%'},
        ];
        let dt = setDataTable('table-roles', columns, dt_route);

        function openModal(type, url = null) {
            let form = $('#form-permission');
            let method;
            if (type == 'create') {
                $('#modalAddRoles').modal('show');
                form.attr('url', "{{ route('setting.roles.store') }}")
                form.attr('method', 'POST');
            } else if (type == 'edit') {
                $('#modalAddRoles').modal('show');
                form.attr('url', url)
                form.attr('method', 'PUT');
            }
        }
        
        function closeModal() {
            $('#modalAddRoles').modal('hide');
            resetForm('form-permission');
        }

        function save() {
            let form = $('#form-permission');
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
                    resetForm('form-permission');
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
            let url = `{{ route('setting.roles.destroy', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('view.confirm_delete_text_role') }}`,
                `{{ __('view.confirm_cancel_text') }}`,
                `{{ __('view.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush