@php
$hasActiveLicense = true;

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $CURRENT_LOCALE_DIRECTION }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ (isset($title) and $title) ? $title : __tr('Welcome') }} - {{ getAppSettings('name') }}</title>
    <!-- Favicon -->
    <link href="{{getAppSettings('favicon_image_url') }}" rel="icon">
    <!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    @stack('head')
    {!! __yesset(
    [
    // Icons
    'static-assets/packages/fontawesome/css/all.css',
    'dist/css/common-vendorlibs.css',
    'dist/css/vendorlibs.css',
    'argon/css/argon.min.css',
    'dist/css/app.css',
    ]) !!}
    {{-- custom app css --}}
</head>
<body class="@if(isLoggedIn()) lw-authenticated-page @else lw-guest-page @endif {{ $class ?? '' }}" x-cloak x-data="{disableSoundForMessageNotification:{{ getVendorSettings('is_disabled_message_sound_notification') ? 1 : 0 }},unreadMessagesCount:null}">
    @auth()
    @include('layouts.navbars.sidebar')
    @endauth

    <div class="main-content">
        @include('layouts.navbars.navbar')
            @if ($hasActiveLicense)
            @if(hasVendorAccess())
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-5 mb--7 pt-5 text-center">
                        @php
                        $vendorPlanDetails = vendorPlanDetails(null, null, getVendorId());
                        @endphp
                        @if(!$vendorPlanDetails->hasActivePlan())
                            <div class="alert alert-danger">
                                {{  $vendorPlanDetails->message }}
                            </div>
                        @elseif($vendorPlanDetails->is_expiring)
                            <div class="alert alert-warning">
                                {{  __tr('Your subscription plan is expiring on __endAt__', [
                                    '__endAt__' => formatDate($vendorPlanDetails->ends_at)
                                ]) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
            @yield('content')
            @else
            <div class="container">
                <div class="row">
                    <div class="col-12 my-5 py-5 text-center">
                       <div class="card my-5 p-5">
                        <i class="fas fa-exclamation-triangle fa-6x mb-4 text-warning"></i>
                        <div class="alert alert-danger my-5">
                            {{ __tr('Product has not been verified yet, please contact via profile or product page.') }}
                        </div>
                       </div>
                    </div>
                </div>
            </div>
            @endif
    </div>
    @guest()
    @include('layouts.footers.guest')
    @endguest
    <?= __yesset(['dist/js/common-vendorlibs.js','dist/js/vendorlibs.js', 'argon/bootstrap/dist/js/bootstrap.bundle.min.js', 'argon/js/argon.js'], true) ?>
    @stack('js')
    @if (hasVendorAccess() or hasVendorUserAccess())
    {{-- QR CODE model --}}
    <x-lw.modal id="lwScanMeDialog" :header="__tr('Scan QR Code to Start Chat')">
        @if (getVendorSettings('current_phone_number_number'))
        <div class="alert alert-dark text-center text-success">
            {{  __tr('You can use following QR Code to invite people to get connect with you on this platform.') }}
        </div>
        <div class="text-center">
            <img src="{{ route('vendor.whatsapp_qr', [
            'vendorUid' => getVendorUid(),
            'phoneNumber' => getVendorSettings('current_phone_number_number'),
        ]) }}">
        <div class="form-group">
            <h3 class="text-muted">{{  __tr('Phone Number') }}</h3>
            <h3 class="text-success">{{ getVendorSettings('current_phone_number_number') }}</h3>
            <label for="lwWhatsAppQRImage">{{ __tr('URL for QR Image') }}</label>
            <div class="input-group">
                <input type="text" class="form-control" readonly id="lwWhatsAppQRImage" value="{{ route('vendor.whatsapp_qr', [
                    'vendorUid' => getVendorUid(),
                    'phoneNumber' => getVendorSettings('current_phone_number_number'),
                ]) }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-light" type="button"
                        onclick="lwCopyToClipboard('lwWhatsAppQRImage')">
                        <?= __tr('Copy') ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <h3 class="text-muted">{{  __tr('WhatsApp URL') }}</h3>
            <div class="input-group">
                <input type="text" class="form-control" readonly id="lwWhatsAppUrl" value="https://wa.me/{{ getVendorSettings('current_phone_number_number') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-light" type="button"
                        onclick="lwCopyToClipboard('lwWhatsAppUrl')">
                        <?= __tr('Copy') ?>
                    </button>
                </div>
            </div>
        </div>
        </div>
        @else
        <div class="text-danger">
            {{  __tr('Phone number does not configured yet.') }}
        </div>
        @endif
    </x-lw.modal>
    {{-- /QR CODE model --}}
    <template x-if="!disableSoundForMessageNotification">
        <audio id="lwMessageAlertTone">
            <source src="<?= asset('/static-assets/audio/whatsapp-notification-tone.mp3'); ?>" type="audio/mpeg">
        </audio>
     </template>
    @endif
    <script>
        (function($) {
            'use strict';
            window.appConfig = {
                debug: "{{ config('app.debug') }}",
                csrf_token: "{{ csrf_token() }}",
                locale : '{{ app()->getLocale() }}',
                vendorUid : '{{ getVendorUid() }}',
                pusher : {
                    key : "{{ config('broadcasting.connections.pusher.key') }}",
                    cluster : "{{ config('broadcasting.connections.pusher.options.cluster') }}"
                },
            }
        })(jQuery);
    </script>
    <?= __yesset(
        [
            'dist/js/jsware.js',
            'dist/js/app.js',
            // keep it last
            'dist/js/alpinejs.min.js',
        ],
        true,
    ) ?>
    @if(hasVendorAccess() or hasVendorUserAccess())
    {!! __yesset('dist/js/bootstrap.js', true) !!}
    @endif
    @stack('vendorLibs')
    <script src="{{ route('vendor.load_server_compiled_js') }}"></script>
    @stack('footer')
    @stack('appScripts')
    <script>
    (function($) {
        'use strict';
        @if (session('alertMessage'))
            showAlert("{{ session('alertMessage') }}", "{{ session('alertMessageType') ?? 'info' }}");
            @php
                session('alertMessage', null);
                session('alertMessageType', null);
            @endphp
        @endif
        @if(hasVendorAccess() or hasVendorUserAccess())
        var broadcastActionDebounce;
        window.Echo.private(`vendor-channel.${window, appConfig.vendorUid}`).listen('.VendorChannelBroadcast', function (data) {
            clearTimeout(broadcastActionDebounce);
            broadcastActionDebounce = setTimeout(function() {
                // generic model updates
                if(data.eventModelUpdate) {
                    __DataRequest.updateModels(data.eventModelUpdate);
                }
                @if(hasVendorAccess('messaging'))
                // is incoming message
                if(data.isNewIncomingMessage) {
                    __DataRequest.get("{{ route('vendor.chat_message.read.unread_count') }}",{}, function() {});
                };
                // contact list update
                if($('.lw-whatsapp-chat-window').length) {
                    __DataRequest.get(__Utils.apiURL("{{ route('vendor.contacts.data.read', ['contactUid']) }}", {'contactUid': $('#lwWhatsAppChatWindow').data('contact-uid')}),{}, function() {});
                }
                // chat updates
                if(data.contactUid && $('.lw-contact-window-' + data.contactUid).length) {
                        __DataRequest.get(__Utils.apiURL("{{ route('vendor.chat_message.data.read', ['contactUid']) }}", {'contactUid': data.contactUid}),{}, function() {
                            window.lwScrollTo('#lwEndOfChats', true);
                        });
                } else {
                    // play the sound for incoming message notifications
                    if(data.isNewIncomingMessage && $('#lwMessageAlertTone').length) {
                        $('#lwMessageAlertTone')[0].play();
                    }
                }
                // campaign data update
                if(data.campaignUid && $('.lw-campaign-window-' + data.campaignUid).length) {
                    __DataRequest.get(__Utils.apiURL("{{ route('vendor.campaign.status.data', ['campaignUid']) }}", {'campaignUid': data.campaignUid}));
                };
                @endif
            }, 300);
        });
        @if(hasVendorAccess('messaging'))
        // initially get the unread count on page loads
        __DataRequest.get("{{ route('vendor.chat_message.read.unread_count') }}",{}, function() {});
        @endif
    @endif
    })(jQuery);
    </script>
    {!! getAppSettings('page_footer_code_all') !!}
    @if(isLoggedIn())
    {!! getAppSettings('page_footer_code_logged_user_only') !!}
    @endif
</body>
</html>