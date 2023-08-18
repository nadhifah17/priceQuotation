<div class="form-group mb-3">
    <label for="spec" class="col-form-label required">{{ __('view.spec_material', ['name' => $type]) }}</label>
    <input type="text" name="spec" class="form-control form-control-sm" id="spec" placeholder="{{ __('view.spec_material', ['name' => $type]) }}">
</div>
<div class="form-group mb-3">
    <label for="period" class="col-form-label required">{{ __('view.period') }}</label>
    <input type="text" name="period" class="form-control form-control-sm" id="period" placeholder="{{ __('view.period') }}">
</div>
<div class="form-group mb-3">
    <label for="rate" class="col-form-label required">{{ __('view.material_rate') }}</label>
    <input type="number" name="rate" class="form-control form-control-sm" id="rate" placeholder="{{ __('view.material_rate') }}">
</div>

<script>
    $( function() {
        $("#spec").autocomplete({
            autoFocus: true,
            source: function( request, response ) {
                $.ajax( {
                    type: 'POST',
                    url: "{{ route('material.search-spec') }}",
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