@extends('layouts.app', ['title' => __tr('Dashboard')])
@php
$vendorIdOrUid = $vendorIdOrUid ?? getVendorUid();
if(!isset($vendorViewBySuperAdmin)) {
    $vendorViewBySuperAdmin = null;
}
@endphp
@section('content')
@if(hasCentralAccess())
@include('users.partials.header', [
'title' => __tr('__vendorTitle__ Dashboard', [
    '__vendorTitle__' => $vendorInfo['title'] ?? getVendorSettings('title')
]),
'description' => '',
// 'class' => 'col-lg-7'
])
@else
@include('users.partials.header', [
'title' => __tr('Hi __userFullName__,', [
    '__userFullName__' => getUserAuthInfo('profile.first_name')
]),
'description' => '',
// 'class' => 'col-lg-7'
])
@endif
<div class="container-fluid">
    @if(hasCentralAccess())
    <div class="col-xl-12 p-0">
        <!-- breadcrumbs -->
        <nav aria-label="breadcrumb" class="lw-breadcrumb-container">
            <ol class="breadcrumb bg-transparent text-light p-0 m-0">
                <li class=" breadcrumb-item mb-3">
                    <a class="text-decoration-none" href="{{ route('central.vendors') }}">{{ __tr('Manage Vendors') }}</a>

                </li>
                <li class="text-light breadcrumb-item" aria-current="page">{{ __tr('Dashboard') }}</li>
            </ol>
        </nav>
        <!-- /breadcrumbs -->
    </div>
    <div class="col-xl-12 pl-1">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page-dashboard"
                    href="{{ route('vendor.dashboard',['vendorIdOrUid'=>$vendorIdOrUid])}}">{{ __tr('Dashboard') }}</a>
            </li>
        </ul>
    </div>
    @endif
</div>
@if(isDemo() and isDemoVendorAccount() and hasVendorAccess())
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="alert alert-dark">
                <h2 class="text-white">{{  __tr('Demo Account') }}</h2>
                <p>{{  __tr('This is the demo account while you can test all the functionalities, you can not receive messages to your mobile numbers.') }}</p>
                <p>{{  __tr('If you want to test system with your own numbers, you can simply create your own account and test it with. Facebook also provides Test Number which is very easy to setup and test. You can follow the steps given in Quick Start on dashboard to get started.') }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@include('layouts.headers.cards')
@if(hasVendorAccess() or $vendorViewBySuperAdmin )
<div class="container-fluid">
    @if (!isWhatsAppBusinessAccountReady($vendorIdOrUid))
    <div class="alert alert-danger">
        {{  __tr('You are not ready to send messages, WhatsApp Setup is Incomplete') }}
        <br>
        <a class="btn btn-sm btn-white my-2" href="{{ route('vendor.settings.read', ['pageType' => 'whatsapp-cloud-api-setup']) }}">{{ __tr('Complete your WhatsApp Cloud API setup') }}</a>
    </div>
    @endif
    @if (getVendorSettings('whatsapp_access_token_expired', null, null, $vendorIdOrUid))
    <div class="alert alert-danger">
        {{  __tr('Your WhatsApp token seems to be expired, Generate new token, prefer creating permanent token and save.') }}
        <br>
        <a class="btn btn-sm btn-white my-2" href="{{ route('vendor.settings.read', ['pageType' => 'whatsapp-cloud-api-setup']) }}">{{ __tr('Cloud API setup') }}</a>
    </div>
    @endif
    @if(!$vendorViewBySuperAdmin)
    <div class="row">
        <div class="col-12 mb-5">
            <fieldset>
                <legend>{{  __tr('Quick Start') }}</legend>
                <h3>
                    <ol>
                        <li>{{  __tr('Login to your Facebook Account') }}</li>
                        <li>{!! __tr('Complete Setup as Shown in __cloudApiSetupLink__', [
                            '__cloudApiSetupLink__' => '<a href="'. route('vendor.settings.read', ['pageType' => 'whatsapp-cloud-api-setup']) .'">'. __tr('Cloud API Setup').'</a>'
                        ]) !!}</li>
                         <li>{!! __tr('Manage and Sync WhatsApp templates at __manageContactsLink__',[
                            '__manageContactsLink__' => '<a href="'. route('vendor.whatsapp_service.templates.read.list_view') .'">'. __tr('Manage WhatsApp Templates').'</a>'
                        ]) !!}</li>
                        <li>{!! __tr('Create your contact groups using __manageGroupsLink__', [
                            '__manageGroupsLink__' => '<a href="'. route('vendor.contact.group.read.list_view') .'">'. __tr('Manage Groups').'</a>'
                        ]) !!}</li>
                        <li>{!! __tr('Create your Contacts or Upload excel file with predefined exportable template at __manageContactsLink__',[
                            '__manageContactsLink__' => '<a href="'. route('vendor.contact.read.list_view') .'">'. __tr('Manage Contacts').'</a>'
                        ]) !!}</li>
                        <li>{!! __tr('Create & Schedule your Campaigns at __manageCampaignsLink__',[
                            '__manageCampaignsLink__' => '<a href="'. route('vendor.campaign.read.list_view') .'">'. __tr('Manage Campaigns').'</a>'
                        ]) !!}</li>
                    </ol>
                </h3>
            </fieldset>
        </div>
    </div>
    @endif
</div>
@endif
@push('head')
<?= __yesset(['dist/css/dashboard.css'],true) ?>
@endpush
@push('js')
<?= __yesset(['dist/js/dashboard.js'],true)?>
@endpush
@endsection()