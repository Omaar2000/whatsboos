<div class="row">
    <div class="col-md-8"
    x-data="{ enableStep2: {{ getVendorSettings('facebook_app_id') ? 1 : 0 }}, enableStep3: {{ getVendorSettings('whatsapp_access_token') ? 1 : 0 }} }"
    x-cloak>
    <!-- Page Heading -->
    <h1>
        <?= __tr('WhatsApp Cloud API Setup') ?>
    </h1>
    <div class="accordion" id="whatsAppSetupSettingsBlock">
        <fieldset class="lw-fieldset mb-3"
            x-data="{openForUpdate:false,fbAppIdExists:{{ getVendorSettings('facebook_app_id') ? 1 : 0 }}}">
            <legend data-toggle="collapse" data-target="#lwFacebookAppSettings" aria-expanded="true"
                aria-controls="lwFacebookAppSettings">{!! __tr('Step 1 : Facebook Developer Account & Facebook App')
                !!} <small class="text-muted">{{ __tr('Click to expand/collapse') }}</small>
            </legend>
            <div class="collapse {{ getVendorSettings('facebook_app_id') ? '' : 'show' }}" id="lwFacebookAppSettings"
                data-parent="#whatsAppSetupSettingsBlock">
                <!-- whatsapp cloud api setup form -->
                <form id="lwWhatsAppFacebookAppForm" class="lw-ajax-form lw-form"
                    name="whatsapp_setup_facebook_app_form" method="post"
                    action="<?= route('vendor.settings.write.update') ?>">
                    <input type="hidden" name="pageType" value="whatsapp_cloud_api_setup">
                    <!-- set hidden input field with form type -->
                    <input type="hidden" name="form_type" value="whatsapp_setup_facebook_app_form" />
                    <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started#set-up-developer-assets" target="_blank" 
                        class="float-right">{!! __tr('Help & More Information') !!} <i
                            class="fas fa-external-link-alt"></i></a>
                    <p>
                        {!! __tr('To get started you should have __facebookApp__, you mostly need to select Business as
                        type of your app.', [
                        '__facebookApp__' => '<strong>' . __tr('Facebook App') . '</strong>',
                        ],
                        ) !!}
                    <div>
                        <a target="_blank" href="https://developers.facebook.com/apps/" class="btn btn-dark">{{
                            __tr('Create or Select Facebook App') }}
                            <i class="fas fa-external-link-alt"></i></a>
                    </div>
                    </p>
                    <p>
                        {{ __tr('Once you have the Facebook app, add your App ID below') }}
                    </p>
                    <div>
                        <div x-show="!fbAppIdExists">
                            <x-lw.input-field type="text" id="lwFacebookAppId"
                                data-form-group-class="col-md-12 col-lg-4" :label="__tr('Facebook App ID')"
                                name="facebook_app_id"
                                placeholder="{{ getVendorSettings('facebook_app_id') ? __tr('App ID exists, add new to update') : __tr('Your Facebook App ID') }}" />
                            <div class="form-group mt-3">
                                <!-- Update Button -->
                                <button type="submit" class="btn btn-primary btn-user lw-btn-block-mobile">
                                    <?= __tr('Save') ?>
                                </button>
                                <!-- /Update Button -->
                            </div>
                        </div>
                        <button x-show="fbAppIdExists" @click.prevent="fbAppIdExists = !fbAppIdExists,openForUpdate=true" type="button" class="btn btn-warning">{{ __tr('Click Here to Update') }}</button>
                    </div>
                </form>
                <!-- / whatsapp cloud api setup form -->
            </div>
            <div class="badge badge-success py-1 mt-2" x-show="fbAppIdExists"><i class="fa fa-2x fa-check-square"></i>
                <span class="lw-configured-badge">{{ __tr('Configured') }}</span></div>
            <div class="badge badge-danger py-1 mt-2" x-show="!fbAppIdExists && !openForUpdate"><i
                    class="fas fa-exclamation-circle fa-2x"></i> <span class="lw-configured-badge">{{ __tr('Not
                    Configured') }}</span></div>
        </fieldset>

        <fieldset class="lw-fieldset my-4"
            x-data="{openForUpdate:false,whatsAppSettings:{{ (getVendorSettings('whatsapp_access_token') and !getVendorSettings('whatsapp_access_token_expired')) ? 1 : 0 }}}">
            <legend data-toggle="collapse" data-target="#lwWhatsAppSettingsBlock" aria-expanded="false"
                aria-controls="lwWhatsAppSettingsBlock">{!! __tr('Step 2: WhatsApp Integration Setup') !!} <small
                    class="text-muted">{{ __tr('Click to expand/collapse') }}</small></legend>
            <div class="collapse" :class="(!enableStep2) ? 'lw-disabled-block-content' : ''"
                id="lwWhatsAppSettingsBlock" data-parent="#whatsAppSetupSettingsBlock">
                <p>{{ __tr('Once you created your app you now need to choose WhatsApp from list click on the setup as
                    shown in the below screenshot') }}
                <div class="col-12 col-md-3 col-xl-2">
                    <img class="img-fluid" src="https://i.imgur.com/aeDwghR.png"
                        alt="{{ __tr('WhatsApp Integration') }}">
                </div>
                </p>
                <p>
                    {{ __tr('You may need to select or setup Meta Business Account, once done go to API setup from
                    sidebar
                    under the WhatsApp menu item as shown in the below screenshot') }}
                <div class="col-12 col-md-3 col-xl-2">
                    <img class="img-fluid" src="https://i.imgur.com/G4fMiT9.png" alt="{{ __tr('WhatsApp API Setup') }}">
                </div>
                </p>
                <div>
                    <!-- whatsapp cloud api setup form -->
                    <form x-show="!whatsAppSettings" id="lwWhatsAppSetupBusinessForm" class="lw-ajax-form lw-form"
                        name="whatsapp_setup_business_form" method="post"
                        action="<?= route('vendor.settings.write.update') ?>">
                        <input type="hidden" name="pageType" value="whatsapp_cloud_api_setup">
                        <!-- set hidden input field with form type -->
                        <input type="hidden" name="form_type" value="whatsapp_setup_business_form" />
                        <div>
                            <div class="float-right">
                                <a target="_blank" href="https://developers.facebook.com/docs/whatsapp/business-management-api/get-started#1--acquire-an-access-token-using-a-system-user-or-facebook-login"
                                    class="">{!! __tr('Help & More Information') !!} <i
                                        class="fas fa-external-link-alt"></i></a> | <a target="_blank"
                                    href="https://www.cloudperitus.com/blog/whatsapp-cloud-api-integration-generating-permanent-access-token"
                                    class="">{!! __tr('External Help') !!} <i class="fas fa-external-link-alt"></i></a>
                            </div>
                            {{-- Access Token --}}
                            @if (getVendorSettings('whatsapp_access_token_expired'))
                            <div class="alert alert-white border-danger text-danger my-3">
                                {{  __tr('Your token seems to be expired, Generate new token, prefer creating permanent token') }}
                            </div>
                            @endif
                            <x-lw.input-field
                                placeholder="{{ getVendorSettings('whatsapp_access_token') ? __tr('Token exists, add new to update') : __tr('Your Access Token') }}"
                                type="text" id="lwAccessToken" data-form-group-class="col-md-12 col-lg-8"
                                :label="__tr('Access Token')" name="whatsapp_access_token" :helpText="__tr(
                            'You can either use Temporary access token or Permanent Access token, as the Temporary token expires in 24 hours its strongly recommended that you should create Permanent token.',
                        )" />
                            {{-- /Access Token ID --}}
                        </div>
                        <div class="alert alert-info mt-4 ">
                            {{ __tr('You can find following on API Setup page') }}
                        </div>
                        <div class="col-md-12 col-lg-4">
                            {{-- WhatsApp Business Account ID --}}
                            <x-lw.input-field
                                placeholder="{{ getVendorSettings('whatsapp_business_account_id') ? __tr('ID exists, add new to update') : __tr('Your Business Account ID') }}"
                                type="text" id="lwBusinessAccountId" data-form-group-class=""
                                :label="__tr('WhatsApp Business Account ID')" name="whatsapp_business_account_id" />
                            {{-- /WhatsApp Business Account ID --}}
                        </div>
                        <div class="col-md-12 col-lg-4">
                            {{-- From Phone Number --}}
                            <x-lw.input-field
                                placeholder="{{ getVendorSettings('current_phone_number_number') ? __tr('Number exists, add new to update') : __tr('Your WhatsApp From Phone Number') }}"
                                type="text" id="lwFromPhoneNumber" data-form-group-class=""
                                :label="__tr('WhatsApp From Phone Number')" name="current_phone_number_number" />
                            {{-- /From Phone Number --}}
                        </div>
                        <div class="col-md-12 col-lg-4">
                            {{-- From Phone Number ID --}}
                            <x-lw.input-field
                                placeholder="{{ getVendorSettings('current_phone_number_id') ? __tr('ID exists, add new to update') : __tr('Your WhatsApp From Phone Number') }}"
                                type="text" id="lwFromPhoneNumberId" data-form-group-class=""
                                :label="__tr('From Phone Number ID')" name="current_phone_number_id" />
                            {{-- /From Phone Number ID --}}
                        </div>
                        <div class="form-group mt-3">
                            <!-- Update Button -->
                            <button type="submit" class="btn btn-primary btn-user lw-btn-block-mobile">
                                <?= __tr('Save') ?>
                            </button>
                            <!-- /Update Button -->
                        </div>
                    </form>
                    <button x-show="whatsAppSettings"
                        @click.prevent="whatsAppSettings = !whatsAppSettings, openForUpdate = true" type="button"
                        class="btn btn-warning">{{ __tr('Click here to Update') }}</button>
                </div>
            </div>
            <div class="badge badge-success py-1 mt-2" x-show="whatsAppSettings"><i
                    class="fa fa-2x fa-check-square"></i> <span class="lw-configured-badge">{{ __tr('Configured') }}</span></div>
            <div class="badge badge-danger py-1 mt-2" x-show="!whatsAppSettings && !openForUpdate"><i
                    class="fas fa-exclamation-circle fa-2x"></i> <span class="lw-configured-badge">{{ __tr('Not
                    Configured') }}</span></div>
        </fieldset>
        <fieldset class="lw-fieldset mb-3"
            x-data="{isWebhookVerified: {{ getVendorSettings('webhook_verified_at') ? 1 : 0 }},isWebhookMessagesFieldVerified: {{ getVendorSettings('webhook_messages_field_verified_at') ? 1 : 0 }}}">
            <legend data-toggle="collapse" data-target="#lwWhatsAppWebhookSettings" aria-expanded="false"
                aria-controls="lwWhatsAppWebhookSettings">{!! __tr('Step 3 : Setup Webhook') !!} <small
                    class="text-muted">{{ __tr('Click to expand/collapse') }}</small></legend>
            <div class="collapse" :class="(!enableStep3) ? 'lw-disabled-block-content' : ''"
                id="lwWhatsAppWebhookSettings" data-parent="#whatsAppSetupSettingsBlock">
                <!-- whatsapp cloud api setup form -->
                <form id="lwWhatsAppWebhookSetup" class="lw-ajax-form lw-form" name="whatsapp_setup_webhook"
                    method="post" action="<?= route('vendor.settings.write.update') ?>">
                    <input type="hidden" name="pageType" value="whatsapp_cloud_api_setup">
                    <!-- set hidden input field with form type -->
                    <input type="hidden" name="form_type" value="whatsapp_setup_webhook" />
                    <a target="_blank"
                        href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started#set-up-developer-assets"
                        class="float-right">{!! __tr('Help & More Information') !!} <i
                            class="fas fa-external-link-alt"></i></a>
                    <p>
                        {!! __tr('Now its time to setup Webhook so the WhatsApp Cloud API can send notification of the
                        events') !!}
                    <div>
                        <a target="_blank"
                            href="https://developers.facebook.com/apps/{{ getVendorSettings('facebook_app_id') }}/whatsapp-business/wa-settings"
                            class="btn btn-dark">{{ __tr('Setup Webhook') }} <i
                                class="fas fa-external-link-alt"></i></a>
                        <div class="text-warning my-3"><strong>{{ __tr('Important!') }}</strong>
                            {{ __tr('You need to select/subscribe Messages from Webhook Fields') }}</div>
                    </div>
                    </p>
                    <div class="form-group col-md-12 col-lg-10">
                        @php
                            $webhookUrl = getViaSharedUrl(route('vendor.whatsapp_webhook', [
                                'vendorUid' => getVendorUid(),
                            ]));
                        @endphp
                        <label for="lwWhatsAppWebhook">{{ __tr('WhatsApp Webhook') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" readonly id="lwWhatsAppWebhook" value="{{ $webhookUrl }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button"
                                    onclick="lwCopyToClipboard('lwWhatsAppWebhook')">
                                    <?= __tr('Copy') ?>
                                </button>
                            </div>
                        </div>
                        @if (!Str::startsWith($webhookUrl, 'https://'))
                            <div class="alert alert-danger my-3">
                                {{  __tr('Non https url may not accepted by Facebook for webhook.') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-md-12 col-lg-10">
                        <label for="lwWhatsAppWebhookVerifyToken">{{ __tr('WhatsApp Webhook Verify Token') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" readonly id="lwWhatsAppWebhookVerifyToken"
                                value="{{ sha1(getVendorUid()) }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button"
                                    onclick="lwCopyToClipboard('lwWhatsAppWebhookVerifyToken')">
                                    <?= __tr('Copy') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- / whatsapp cloud api setup form -->
            </div>
            <div class="badge badge-success py-1 mt-2" x-show="isWebhookVerified"><i
                    class="fa fa-2x fa-check-square"></i> <span class="lw-configured-badge">{{ __tr('Webhook Verified')
                    }}</span></div>
            <div class="badge badge-danger py-1 mt-2" x-show="!isWebhookVerified"><i
                    class="fas fa-exclamation-circle fa-2x"></i> <span class="lw-configured-badge">{{ __tr('Webhook Not Verified') }}</span></div>
            <div class="badge badge-success py-1 mt-2" x-show="isWebhookMessagesFieldVerified"><i
                    class="fa fa-2x fa-check-square"></i> <span class="lw-configured-badge">{{ __tr('Webhook Messages Field Verified')
                    }}</span></div>
            <div class="badge badge-danger py-1 mt-2" x-show="!isWebhookMessagesFieldVerified"><i
                    class="fas fa-exclamation-circle fa-2x"></i> <span class="lw-configured-badge">{{ __tr('Webhook Messages Field Not Verified') }}</span></div>
        </fieldset>
        <fieldset class="lw-fieldset mb-3"
            x-data="{openForUpdate:false,testContactExists: {{ getVendorSettings('test_recipient_contact') ? 1 : 0 }}}">
            <legend data-toggle="collapse" data-target="#lwWhatsAppTestContactBlock" aria-expanded="false"
                aria-controls="lwWhatsAppTestContactBlock">{!! __tr('Step 4 : Test Contact for Campaign') !!} <small
                    class="text-muted">{{ __tr('Click to expand/collapse') }}</small></legend>
            <div class="collapse" :class="(!enableStep3) ? 'lw-disabled-block-content' : ''"
                id="lwWhatsAppTestContactBlock" data-parent="#whatsAppSetupSettingsBlock">
                <!-- whatsapp cloud api setup form -->
                <form id="lwWhatsAppTestContact" class="lw-ajax-form lw-form" name="whatsapp_setup_test_contact"
                    method="post" action="<?= route('vendor.settings.write.update') ?>">
                    <input type="hidden" name="pageType" value="whatsapp_cloud_api_setup">
                    <!-- set hidden input field with form type -->
                    <input type="hidden" name="form_type" value="whatsapp_setup_test_contact" />
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light">
                                {{ __tr('It will be used for campaign, if there is no contact add it from the contacts
                                list
                                then choose test contact here.') }}
                                <a href="{{ route('vendor.contact.read.list_view') }}"
                                    class="btn btn-sm btn-secondary">{{
                                    __tr('Go to Manage Contacts') }}</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <x-lw.input-field type="selectize" data-form-group-class="" name="test_recipient_contact"
                                :label="__tr('Choose Test Contact')"
                                data-selected="{{ getVendorSettings('test_recipient_contact') }}">
                                <x-slot name="selectOptions">
                                    @isset($configurationData['contactsListData'])
                                    @foreach ($configurationData['contactsListData'] as $contact)
                                    <option value="{{ $contact['_uid'] }}">{{ $contact['full_name'] }}</option>
                                    @endforeach
                                    @endisset
                                </x-slot>
                            </x-lw.input-field>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <!-- Update Button -->
                        <button type="submit" class="btn btn-primary btn-user lw-btn-block-mobile">
                            <?= __tr('Save') ?>
                        </button>
                        <!-- /Update Button -->
                    </div>
                </form>
            </div>
            <div class="badge badge-success py-1 mt-2" x-show="testContactExists"><i
                    class="fa fa-2x fa-check-square"></i> <span class="lw-configured-badge">{{ __tr('Configured')
                    }}</span></div>
            <div class="badge badge-danger py-1 mt-2" x-show="!testContactExists"><i
                    class="fas fa-exclamation-circle fa-2x"></i> <span class="lw-configured-badge">{{ __tr('Not Configured') }}</span></div>
        </fieldset>
        <fieldset class="lw-fieldset mb-3">
            <legend data-toggle="collapse" data-target="#lwManageTemplates" aria-expanded="false"
                aria-controls="lwManageTemplates">{!! __tr('Step 5 : Manage or Sync Templates') !!} <small
                    class="text-muted">{{ __tr('Click to expand/collapse') }}</small></legend>
            <div class="collapse" id="lwManageTemplates" data-parent="#whatsAppSetupSettingsBlock"
                :class="(!enableStep3) ? 'lw-disabled-block-content' : ''">
                {{ __tr('In order to send template message you should have created and approved templates for WhatsApp Business if you have already created the templates you can Sync it or click on Manage templates to create.')
                }}
                <div class="my-3">
                    <a class="lw-btn btn btn-primary lw-ajax-link-action" data-method="post"
                        href="{{ route('vendor.whatsapp_service.templates.write.sync') }}"> {{ __tr('Sync WhatsApp Templates') }}</a>
                    <a class="lw-btn btn btn-default" target="_blank"
                        href="https://business.facebook.com/wa/manage/message-templates/?waba_id={{ getVendorSettings('whatsapp_business_account_id') }}">
                        {{ __tr('Manage Templates') }} <i class="fa fa-link"></i></a>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<div class="col-md-4">
   <fieldset x-data="initializedWhatsAppData">
    <legend>{{ __tr('WhatsApp Business Health Status') }}</legend>
    @if (isDemo() and isDemoVendorAccount())
        <div class="alert alert-dark">
            {{  __tr('Information Hidden for Demo Account') }}
        </div>
    @else
   <dl>
    <dt>{{  __tr('WhatsApp Business ID') }}</dt>
    <dd x-text="healthStatusData?.whatsapp_business_account_id"></dd>
    <dt>{{  __tr('Status as at') }}</dt>
    <dd x-text="healthStatusData?.health_status_updated_at_formatted"></dd>
    <dt>{{  __tr('Overall Health') }}</dt>
    <dd x-text="healthStatusData?.health_data?.health_status.can_send_message"></dd>
        <template x-for="healthEntity in healthStatusData?.health_data?.health_status.entities">
            <fieldset>
                <legend> <span x-text="healthEntity.entity_type"></span> - <span x-text="healthEntity.id"></span></legend>
            <dl>
                <dt>{{  __tr('Can Send Message') }}</dt>
                <dd x-text="healthEntity.can_send_message"></dd>
                <template x-for="errorItem in healthEntity.errors">
                    <dl>
                    <dt>{{  __tr('Error Description') }}</dt>
                    <dd class="text-danger" x-text="errorItem.error_description"></dd>
                    <dt>{{  __tr('Possible Solution') }}</dt>
                    <dd class="text-success" x-text="errorItem.possible_solution"></dd>
                </dl>
                </template>
            </dl>
        </fieldset>
        </template>
   </dl>
   <a href="{{ route('vendor.whatsapp.health.status') }}" class="btn btn-primary lw-ajax-link-action {{ !getVendorSettings('whatsapp_access_token') ? 'disabled' : '' }}" data-method="post">{{  __tr('Refresh Status') }}</a>
   @endif
   </fieldset>
</div>
</div>
<script>
    (function() {
       'use strict';
       @if(getVendorSettings('whatsapp_business_account_id'))
        document.addEventListener('alpine:init', () => {
            Alpine.data('initializedWhatsAppData', () => ({
                healthStatusData: @json(getVendorSettings('whatsapp_health_status_data'))[{{ getVendorSettings('whatsapp_business_account_id') }}]
            }));
       @else
       document.addEventListener('alpine:init', () => {
        Alpine.data('initializedWhatsAppData', () => ({
            healthStatusData: []
        }));
       @endif
   });
})();
</script>