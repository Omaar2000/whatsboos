@extends('layouts.app', ['class' => 'main-content-has-bg'])

@section('content')
@include('layouts.headers.guest')
<div class="container lw-guest-page-container-block pb-2" id="pageTop">
    <!-- Table -->
    <div class="row justify-content-around align-items-center">
    <div class=" col-7 col-md-6">
                            <!-- Masthead device mockup feature-->
                            <div class="" >
                            <img style="max-width:100%; max-height:100%; min-width:20rem; min-height:20rem; margin-left: -2rem;" class="hero-svg" src="http://localhost/whatsboos/public/imgs/outer-home/register-svg.svg" alt="...">
                            </div>
                        </div>
        <div class="col-8 col-md-6">
            @if(getAppSettings('enable_vendor_registration'))
            <div class="card lw-form-card-box shadow border-0">
                
                <div class="card-body">
                    @php
                    $formSignUpRoute = route('auth.register.process');
                    if (getAppSettings('activation_required_for_new_user')) {
                    $formSignUpRoute = route('activation_required.auth.register.process');
                    }
                    @endphp
                    <x-lw.form :action="$formSignUpRoute" data-secured="true">
                        <!-- Vendor Name -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Vendor/Company Name') }}" type="text"
                                    name="vendor_title" value="{{ old('vendor_title') }}" required autofocus>
                            </div>
                        </div>
                        <div class="text-center text-muted my-2">
                            {{ __tr('Admin User Details') }}
                        </div>
                        <!-- Username -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Username') }}" type="text"
                                    name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Name -->
                                <div class="form-group">
                                    <div class="input-group input-group-alternative mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="{{ __tr('First Name') }}" type="text"
                                        name="first_name" value="{{ old('first_name') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <!-- Last Name -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Last Name') }}" type="text"
                                    name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>
                            </div>
                        </div>
                        <!-- Email address -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-at"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Email') }}" type="email" name="email"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Password') }}" type="password"
                                    name="password" required>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i> <sup><i
                                                class="fa fa-check"></i></sup></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Confirm Password') }}" type="password"
                                    name="password_confirmation" required>
                            </div>
                        </div>
                        <!-- privacy policy -->
                        @if (getAppSettings('user_terms') or getAppSettings('vendor_terms') or getAppSettings('privacy_policy'))
                        <div class="row my-4">
                            <div class="col-12">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" name="terms_and_conditions" id="itemsAccept"
                                        type="checkbox">
                                    <label class="custom-control-label" for="itemsAccept">
                                        <span class="text-white">{{ __tr('I agree with the') }}
                                            @if (getAppSettings('user_terms'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'user_terms'
                                            ]) }}">{{ __tr('User Terms And Conditions') }}</a>,
                                            @endif
                                            @if (getAppSettings('vendor_terms'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'vendor_terms'
                                            ]) }}">{{ __tr('Vendor Terms And Conditions') }}</a>,
                                            @endif
                                            @if (getAppSettings('privacy_policy'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'privacy_policy'
                                            ]) }}">{{
                                                __tr('Privacy Policy')
                                                }}</a>
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- create account action -->
                        <div class="text-center">
                            <button  type="submit" class="btn btn-success btn-lg btn-block mt-3">{{ __tr('Create Account') }}</button>
                        </div>
                    </x-lw.form>
                    <div class="card-footer text-center">
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
                        <!-- social login links -->
                        <div class="mb-3 mt-2">
                            {{ __tr('Already have an Account?') }}
                        </div>
                        <a href="{{ route('auth.login') }}" class="btn btn-lg btn-warning">
                            <small>{{ __tr('Click here to login') }}</small>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card lw-form-card-box shadow border-0">
                <div class="card-header text-center">
                    @if (getAppSettings('message_for_disabled_registration'))
                    {!! getAppSettings('message_for_disabled_registration') !!}
                @else
                {{ __tr('Vendor Registrations are closed now.') }}
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection