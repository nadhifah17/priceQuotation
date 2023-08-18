@extends('adminLte.auth.layout')

@section('pageTitle')
    {{ __('view.sign_in') }}
@endsection

@section('content')
<div class="login-box">
  <div class="card card-outline card-primary card-login">
    <div class="card-body">
      <p class="login-title">{{ __('view.login_title') }}</p>

      <form method="POST" action="{{ route('login.post') }}" novalidate="novalidate" id="kt_sign_in_form">
        @csrf
        <div class="form-group">
          <div class="input-group mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="{{ __('view.email_address') }}" autocomplete="off" value="{{ old('email') }}" required autofocus tabindex="1">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
          </div>
        </div>

        <div class="form-group">
            <div class="input-group mb-5">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="{{ __('view.password') }}" autocomplete="off" required tabindex="2">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

          <!-- /.col -->
          <div class="mb-5">
            <button type="submit" class="btn btn-primary btn-block btn-login" tabindex="3">{{-- __('view.login') --}} Login</button>
          </div>
          <!-- /.col -->
        <!-- </div> -->
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

<style type="text/css" media="all">
    body {
        background: #FFF !important;
    }

    div.login-box {
        width: 500px;
    }

    .btn-login {
        background: #2F5CCF !important;
    }

    .logo {
        width: 80px;
        height: 80px;
    }

    .login-title {
        font-style: normal;
        font-weight: 700;
        font-size: 26px;
        color: #8F7E7E;
        text-transform: uppercase;
    }

    div.login-box div.card {
        box-shadow: 0 .1rem 1rem .25rem rgba(0,0,0,.05)!important;
        border-radius: 0.475rem!important;
        border: 0 none !important;
    }

    div.login-box div.card div.card-body {
        padding: 18px 46px;
    }

    div.login-box div.card-header {
        padding: 0 !important;
        border: 0 none !important;
        margin: 2rem 0 1rem !important;
        /* margin-bottom: 24px !important; */
    }

    button.big-btn {
        height: 3.75rem;
        border-radius: 3.75rem;
        font-size: 1.05rem;
        font-weight: bold;
    }

    p.register {
        text-align: center;
        margin-top: 2rem;
        font-weight: 600 !important;
    }

    p.tracking-now {
        text-align: center;
        background-color: #E9ECEF;
        padding: 1.25rem 0;
        margin: 0 !important;
        border-radius: 0 0 0.475rem 0.475rem !important;
        font-weight: 600 !important;
    }

    .widget-title {
        padding: 0 !important;
        margin: 0 auto 24px !important;
        text-align: center !important;
        position: relative !important;
        display: block !important;
        font-size: 22px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .form-control {
        height: calc(50px + 2px) !important;
        border-radius: 5px !important;
    }

    .input-group:not(.has-validation) > .form-control:not(:last-child), .input-group:not(.has-validation) > .custom-select:not(:last-child), .input-group:not(.has-validation) > .custom-file:not(:last-child) .custom-file-label::after
    {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    @media (max-width: 767px)
    {
        html, body {
        margin: 0 !important;
        padding: 0 !important;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        overflow-x: hidden !important;
        width: unset !important;
        height: unset !important;
        }
        body { min-height: unset !important; }
        div.login-box {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        }
        div.login-box div.card {
        padding: 40px 24px !important;
        background: none transparent !important;
        box-shadow: none !important;

        }
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    function autoFill(){
        $('#email').val('admin@admin.com');
        $('#password').val('123456');
    }


    @if(env('DEMO_MODE') == 'On')
      // Class Initialization
      $(document).ready(function() {
        autoFill();

        $('body').on('click','#login_admin', function(e){
          $('#email').val('admin@admin.com');
          $('#password').val('123456');
          $('#signin_submit').trigger('click');
        });
        $('body').on('click','#login_employee', function(e){
          $('#email').val('employee@cargo.com');
          $('#password').val('123456');
          $('#signin_submit').trigger('click');
        });
        $('body').on('click','#login_driver', function(e){
          $('#email').val('driver@cargo.com');
          $('#password').val('123456');
          $('#signin_submit').trigger('click');
        });
        $('body').on('click','#login_branch', function(e){
          $('#email').val('branch@cargo.com');
          $('#password').val('123456');
          $('#signin_submit').trigger('click');
        });
        $('body').on('click','#login_client', function(e){
          $('#email').val('client@cargo.com');
          $('#password').val('123456');
          $('#signin_submit').trigger('click');
        });

      });
    @endif
</script>
@endsection