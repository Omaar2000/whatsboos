<div class="row">
    <div class="col-md-8"
    x-data="{ enableStep2: {{ getVendorSettings('facebook_app_id') ? 1 : 0 }}, enableStep3: {{ getVendorSettings('whatsapp_access_token') ? 1 : 0 }} }"
    x-cloak>
    <!-- Page Heading -->
    <h1>
        <?= __tr('API Access') ?>
    </h1>
    <fieldset>
        <legend>{{  __tr('Your Account Access API') }}</legend>
        <p>{{  __tr('To access the available APIs you need access token') }}</p>
    </fieldset>
    <fieldset class="lw-fieldset mb-3" >
            <legend>{!! __tr('API Access Token') !!}</legend>
            <div>
                @php
                $vendorId = getVendorId();
                // check the feature limit
                $vendorPlanDetails = vendorPlanDetails('api_access', 0, $vendorId);
                @endphp
                @if ($vendorPlanDetails['is_limit_available'])
                <div class="col-12">
                    @if(getVendorSettings('vendor_api_access_token'))
                    <div class="input-group">
                        <input type="text" class="form-control" readonly id="lwAccessToken" value='{{ getVendorSettings('vendor_api_access_token') }}'>
                        <div class="input-group-append">
                            <button class="btn btn-outline-light" type="button" onclick="lwCopyToClipboard('lwAccessToken')">
                                <?= __tr('Copy') ?>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-light">
                        {{  __tr('No token generated yet.') }}
                    </div>
                    @endif
                </div>
                <script type="text/template" id="lwRegenerateTokenAlert">
                    <h3>{{  __tr('Generate New Token?') }}</h3>
                    <p>{{  __tr('Your existing tokens will be void immediately') }}</p>
                </script>
                <form class="lw-ajax-form lw-form" @if(getVendorSettings('vendor_api_access_token')) data-confirm="#lwRegenerateTokenAlert" @endif method="post" action="<?= route('vendor.settings.write.update', ['pageType' => 'internals']) ?>" >
                    <div class="my-4">
                        <input type="hidden" name="vendor_api_access_token" value="{{ Str::random(64) }}">
                        {{-- submit button --}}
                        <button type="submit" href class="ml-3 btn btn-primary btn-user lw-btn-block-mobile">
                            <i class="fa fa-key"></i> {{ __tr('Generate New Token') }}
                        </button>
                    </div>
                    </form>
                    <div>
                        <hr>
                        <h3>{{  __tr('API Endpoint Information') }}</h3>
                        <div class="form-group">
                            <label for="lwApiBaseUrl">{{  __tr('API Base URL') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="lwApiBaseUrl" value='{{ route('api.base_url') }}'>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-light" type="button" onclick="lwCopyToClipboard('lwApiBaseUrl')">
                                        <?= __tr('Copy') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lwVendorUid">{{  __tr('Your Vendor UID') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="lwVendorUid" value='{{ getVendorUid() }}'>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-light" type="button" onclick="lwCopyToClipboard('lwVendorUid')">
                                        <?= __tr('Copy') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lwExampleEndpoint">{{  __tr('Example Endpoint for Send Message it will consist of API base url and vendor uid') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="lwExampleEndpoint" value='{{ route('api.vendor.chat_message.send.process', [
                                    'vendorUid' => getVendorUid()
                                ]) }}'>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-light" type="button" onclick="lwCopyToClipboard('lwExampleEndpoint')">
                                        <?= __tr('Copy') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        {{  __tr('API Access is not available in your plan, please upgrade your subscription plan.') }}
                    </div>
                @endif
            </div>
        </fieldset>
        <fieldset>
            <legend>{{  __tr('API Documentation') }}</legend>
            <fieldset>
                <legend>{{  __tr('Variables and Parameters') }}</legend>
                <h4>{{  __tr('Contact Related Dynamic Parameters') }}</h4>
                <div class="help-text my-3 border p-3">{{  __tr('You are free to use following dynamic variables for parameters excluding phone_number, template_name, template_language, which will get replaced with contact\'s concerned field value.') }} <div><code>{{ implode(' ', $dynamicFields) }}</code></div></div>
                <h3>{{  __tr('Example Parameters') }}</h3>
<pre>
<code>
{
    "phone_number": "phone number with country code without prefixing + or 0",
    "template_name" : "your_template_name",
    "template_language" : "en",
    "header_image" : "https://cdn.pixabay.com/photo/2015/01/07/15/51/woman-591576_1280.jpg",
    "header_video" : "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
    "header_document" : "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
    "header_document_name" : "{full_name}",
    "header_field_1" : "{full_name}",
    "location_latitude" : "22.22",
    "location_longitude" : "22.22",
    "location_name" : "Example Name",
    "location_address" : "Example address",
    "field_1" : "{first_name}",
    "field_2" : "{last_name}",
    "field_3" : "{email}",
    "field_4" : "{country}",
    "field_5" : "{language_code}",
    "button_0" : "{full_name}",
    "button_1" : "{phone_number}",
    "copy_code" : "EXAMPLE_CODE"
}
</code>
</pre>
            </fieldset>
            <div class="my-4">
                <h3>{{  __tr('Click on the button below for API information') }}</h3>
            <a target="_blank" href="{{ getAppSettings('api_documentation_url') }}" class="btn btn-info"> <i class="fa fa-book"></i> {{  __tr('API Documentation') }} - {{ getAppSettings('api_documentation_url') }}</a>
            </div>
        </fieldset>
</div>
</div>