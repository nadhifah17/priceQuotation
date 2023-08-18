@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')

@if (session()->has('success'))
            <div class="alert alert-danger mb-3" role="alert">
                {{ session()->get('success') }}
                {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
            </div>
    @endif


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

    {{-- begin::modal --}}
    <div class="modal modal-q fade" id="modalAddCost"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddCostLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-cost">
                    <div class="body">
                        @include('adminLte.pages.tmmin.form')
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


    </body>
@endsection

@push('scripts')<script>
        let dt_route = "{{ route('data.bajax') }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'number', name: 'number'},
                {data: 'name', name: 'name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action'},
        ];
        let dt = setDataTable('table-cost', columns, dt_route, 1);

        $('#modalAddCost').on('hidden.bs.modal', function (event) {
            resetForm('form-cost');
        });
        // end:: action when modal has been closed

        function openModal(action, typeModal) {
            let url = '';
            if (typeModal == 'manual') {
                $('#modalAddCost').modal('show');
                if (action == 'create') {
                    url = "{{ route('tmmin.store') }}";
                    $('#form-cost').attr('action', url);
                    $('#form-cost').attr('method', 'POST');
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

        function save() {
            let form = $('#form-cost');
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
                `hapus`,
                `gajadi`,
                `confirm`,
                url,
                dt
            );
        }


     </script>
    
@endpush
