@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
--}}
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="btn_login">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $( document ).ready(function() {
        let before=document.referrer;
        console.log(window.location.host);
        $('#btn_login').click(function(){
           let get_email=$('#email').val();
           let get_password=$('#password').val();
           if(get_email !="" && get_password!="")
           {
               let user={
                    email:get_email,
                    password:get_password
               }
                // url: 'https://'+window.location.hostname+'/api/login',
                //http://erp.com/api/public/library
                 $.ajax({
                   type: 'POST',
                   url: 'https://'+window.location.host+'/api/login',
                   data: user,   
                      success: function(data)
                      {
                        // let expire_days = 1; // 過期日期(天)
                        // var d = new Date();
                        // d.setTime(d.getTime() + (expire_days * 24 * 60 * 60 * 1000));
                        // var expires = "expires=" + d.toGMTString();
                        // document.cookie = "sso="+data.data.access_token + "; " + expires + ';domain=.erp.com; path=/';
                        location.href = before+'?sso=' + data.data.access_token;
                        // location.href = before+'?sso=123';
                      },
                      error: function(e)
                      {
                        alert('帳密錯誤');
                      }
                  });
           }
        });

    });
</script>
@stop
