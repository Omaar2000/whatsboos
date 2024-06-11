@extends('layouts.app', ['class' => 'main-content-has-bg'])
@section('content')
@include('layouts.headers.guest')
<div class="container lw-guest-page-container-block pb-2">
    <div class="row justify-content-around align-items-center">
                        <div class=" col-4">
                            <!-- Masthead device mockup feature-->
                            <div class="d-none d-sm-block" >
                            <img style="max-width:100%; max-height:100%; min-width:20rem; min-height:20rem; margin-left: -2rem;" class="hero-svg" src="http://localhost/whatsboos/public/imgs/outer-home/login-svg.svg" alt="...">
                            </div>
                        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card lw-form-card-box shadow border-0">
                @if (isDemo())
                    <div class="card-header text-center">
                        <button onclick="document.getElementById('lwLoginEmail').value='demosuperadmin';document.getElementById('lwLoginPassword').value='demopass12';" class="btn btn-sm btn-danger">{{  __tr('Demo Super Admin Login') }}</button>
                        <button onclick="document.getElementById('lwLoginEmail').value='testcompany';document.getElementById('lwLoginPassword').value='demopass12';" class="btn btn-sm btn-danger">{{  __tr('Demo Company Login') }}</button>
                    </div>
                @endif
                <div class="card-body p-4 py-lg-5 m-2 m-sm-5 m-md-0 m-lg-1">
                    <x-lw.form id="lwLoginForm" data-secured="true" :action="route('auth.login.process')">
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} ">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                </div>
                                <input id="lwLoginEmail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __tr('Email or Username') }}" type="text" name="email" value="" required autofocus autocomplete="email">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input id="lwLoginPassword" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __tr('Password') }}" type="password" value="" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="custom-control custom-control-alternative custom-checkbox">
                            <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customCheckLogin">
                                <span class="text-bold">{{ __tr('Remember me') }}</span>
                            </label>
                            @if (Route::has('auth.password.request'))
                            <a href="{{ route('auth.password.request') }}" class="text-bold float-right">
                                <small>{{ __tr('Forgot password?') }}</small>
                            </a>
                            @endif
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success my-4 btn-lg btn-block ">{{ __tr('Login') }}</button>
                            <!-- social login links -->
                            @if(getAppSettings('allow_google_login'))
                            <a href="<?= route('login.google') ?>" class="btn btn-google btn-user btn-block">
                                <i class="fab fa-google fa-fw"></i> <?= __tr('Continue with Google')  ?>
                            </a>
                            @endif
                            @if(getAppSettings('allow_facebook_login'))
                            <a href="<?= route('login.facebook') ?>" class="btn btn-facebook btn-user btn-block">
                                <i class="fab fa-facebook-f fa-fw"></i> <?= __tr('Continue with Facebook')  ?>
                            </a>
                            @endif
                            @if(getAppSettings('enable_vendor_registration'))
                            <!-- social login links -->
                            <div class="mb-3 mt-3 free">
                                {{  __tr('If you don\'t have an Account yet? Create One! Its Free!!') }}
                            </div>
                            <a href="{{ route('auth.register') }}" class="btn btn-lg btn-warning">
                                <small>{{ __tr('Create New Account') }}</small>
                            </a>
                            @elseif(getAppSettings('message_for_disabled_registration'))
                            <div class="mb-3 mt-5">
                                {{  __tr('Want to create New Account?') }}
                            </div>
                            <a href="{{ route('auth.register') }}" class="btn btn-lg btn-warning">
                                <small>{{ __tr('More Info') }}</small>
                            </a>
                            @endif
                        </div>
                    </x-lw.form>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection