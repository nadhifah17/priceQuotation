@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <form action="{{ route('setting.roles.update', $data->id) }}" method="PUT" id="form-roles">
                <div class="form-group row mb-3">
                    <label for="name" class="col-form-label col-md-3">{{ __('view.role_name') }}</label>
                    <div class="col-md-6">
                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" id="name">
                    </div>
                </div>
    
                <div class="border-bottom mt-3 mb-3"></div>
    
                {{-- begin::permission-list --}}
                <label for="" class="col-form-label">{{ __('view.permission_list') }}</label>
                <div class="row">
                        @foreach ($all_permissions as $key => $permission)
                            <div class="col-md-4">
                                <div class="form-check ml-3">
                                    <input class="form-check-input" {{ $permission->active ? 'checked' : '' }} type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="defaultCheck{{ $key }}">
                                    <label class="form-check-label" for="defaultCheck{{ $key }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="d-flex align-items-center flex-wrap"> --}}
                        {{-- </div> --}}
                </div>
                {{-- end::permission-list --}}
    
                {{-- begin::action --}}
                <div class="form-group mt-3">
                    <div class="d-flex align-items-center justify-content-end pr-5">
                        <button class="btn btn-sm bg-primary-blue" type="button" id="btn-submit" onclick="save()">{{ __('view.submit') }}</button>
                        <a class="btn btn-sm bg-primary-warning ml-2" href="{{ route('setting.roles') }}">{{ __('view.cancel') }}</a>
                    </div>
                </div>
                {{-- end::action --}}
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function save() {
            let form = $('#form-roles');

            let permissions = [];
            $('input[type="checkbox"]:checked').each(function() {
                permissions.push($(this).val());
            });
            let data = {
                name: $('#name').val(),
                permissions: permissions
            };

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: data,
                beforeSend: function() {
                    setLoading('btn-submit', true);
                },
                success: function(res) {
                    setLoading('btn-submit', false);
                    window.location.href = res.url;
                    setNotif(false, res.message);
                },
                error: function(err) {
                    setLoading('btn-submit', false);
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to update data');
                }
            })
        }
    </script>
@endpush