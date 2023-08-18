<div class="form group mb-3">
    <label for="email" class="col-form-label required">{{ __('view.email_address') }}</label>
    <input type="email" name="email" placeholder="{{ __('view.email_address') }}" value="" class="form-control form-control-sm" id="email">
</div>
<div class="form group mb-3">
    <label for="password" class="col-form-label required">{{ __('view.password') }}</label>
    <input type="password" name="password" placeholder="{{ __('view.password') }}" value="" class="form-control form-control-sm" id="password">
</div>
<div class="form-group mb-3">
    <label for="role" class="col-form-label required">{{ __('view.role') }}</label>
    <select name="role" id="role" class="form-control form-control-sm">
        <option value="" disabled selected>-- {{ __('view.choose') }} --</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
        @endforeach
    </select>
</div>