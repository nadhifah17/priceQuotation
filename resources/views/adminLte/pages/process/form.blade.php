<div class="form-group mb-3">
    <label for="process_group" class="col-form-label required">{{ __('view.process_group') }}</label>
    <select name="process_group" id="process_group" class="form-control form-control-sm">
        <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
        @foreach ($process_group as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group mb-3">
    <label for="process_code" class="col-form-label required">{{ __('view.process') }}</label>
    <input type="text" name="process_code" class="form-control form-control-sm" id="process_code" placeholder="{{ __('view.process') }}">
</div>
<div class="form-group mb-3">
    <label for="rate" class="col-form-label required">{{ __('view.process_rate') }}</label>
    <input type="number" name="rate" class="form-control form-control-sm" id="rate" placeholder="{{ __('view.process_rate') }}">
</div>

<script>
    $( function() {
        $("#process_code").autocomplete({
            autoFocus: true,
            source: function( request, response ) {
                $.ajax( {
                    type: 'POST',
                    url: "{{ route('process.search-spec') }}",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 2,
            // select: function( event, ui ) {
            // }
        });
    } );
</script>