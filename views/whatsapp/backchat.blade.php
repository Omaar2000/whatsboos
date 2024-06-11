@extends('layouts.app', ['title' => __tr('WhatsApp Chat')])
@section('content')
@include('users.partials.header', [
'title' => __tr('WhatsApp Chat'),
'description' => '',
// 'class' => 'col-lg-7'
])
@push('head')
{!! __yesset('dist/css/whatsapp-chat.css', true) !!}
@endpush
@if ($contact)
<div class="container-fluid" x-data="{myAssignedUnreadMessagesCount:null}">
    <div class="">
        <div class="alert alert-dark">
            {{  __tr('Once you get the response by the contact, they will be come in the contact list of this chat window, alternatively you can click on chat button of the contact list to chat with the contact.') }}
        </div>
        @if (isDemo() and isDemoVendorAccount())
        <div class="alert alert-warning">
            <h4 class="text-white">{{  __tr('Demo Account - Your Phone Number Uses Info') }}</h4>
            {{  __tr('This demo account uses WhatsApp Test Number so you won\'t be able to receive a message on your phone, however you can send a message from your phone to receive it here.') }}
        </div>
        @endif
        <div class="card lw-whatsapp-chat-block-container">
            <div class="card-header">
            @if (getVendorSettings('current_phone_number_number'))
               <a class="text-success h1 mr-4" target="_blank" href="https://api.whatsapp.com/send?phone={{ getVendorSettings('current_phone_number_number') }}"><i class="fab fa-whatsapp"></i>  {{ __tr('WhatsApp Now __currentPhoneNumber__', [
                '__currentPhoneNumber__' => getVendorSettings('current_phone_number_number')
            ]) }}</a> <button type="button" class="lw-btn btn btn-white float-right" data-toggle="modal" data-target="#lwScanMeDialog"><i class="fa fa-qrcode"></i> {{  __tr('QR to Share') }}</button>
            @else
            <div class="text-danger">
                {{  __tr('Phone number does not configured yet.') }}
            </div>
            @endif
            </div>
            <div id="lwWhatsAppChatWindow"
                class="card-body lw-whatsapp-chat-window lw-contact-window-{{ $contact->_uid }}"
                data-contact-uid="{{ $contact->_uid }}">
                <div class="row" x-cloak x-data="{isContactListOpened:false}">
                    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-4 lw-contact-list-block" x-show="isContactListOpened">
                        <h2 class="lw-contacts-header">{{ __tr('Conversations') }} <span class="btn btn-light btn-sm float-right d-md-none" @click.prevent="isContactListOpened = false"><i class="fa fa-arrow-left"></i></span></h2>
                        
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <a class="nav-link {{ ($assigned ?? null) ? '' : 'active' }}" href="{{ route('vendor.chat_message.contact.view') }}" id="lw-all-contacts-tab"  data-target="#lwAllContactsTab" type="button" role="tab" aria-controls="lwAllContactsTab" aria-selected="true">{{  __tr('All') }} <span x-cloak x-show="unreadMessagesCount" class="badge bg-yellow text-dark badge-white rounded-pill ml-2" x-text="unreadMessagesCount"></span></a>
                              <a href="{{ route('vendor.chat_message.contact.view', [
                                'assigned' => 'to-me',
                              ]) }}" class="nav-link {{ ($assigned ?? null) ? 'active' : '' }}" id="lw-to-me-tab"  data-target="#lwAssignedToMeTab" type="button" role="tab" aria-controls="lwAssignedToMeTab" aria-selected="false">{{  __tr('Mine') }} <span x-cloak x-show="myAssignedUnreadMessagesCount" class="badge bg-yellow text-dark badge-white rounded-pill ml-2" x-text="myAssignedUnreadMessagesCount"></span></a>
                            </div>
                          </nav>
                          <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="lwAllContactsTab" role="tabpanel" aria-labelledby="lw-all-contacts-tab">
                            <div class="list-group lw-contact-list shadow-lg list-group-flush" x-cloak
                            x-data="initialContactListData">
                            <template x-for="contactItem in contacts">
                                @if (($assigned ?? null))
                                <template x-if="contactItem.assigned_users__id == '{{ getUserId() }}'">
                                @endif
                                <a :data-messaged-at="contactItem.last_message?.messaged_at"
                                    :class="['lw-contact-' + contactItem._uid, ('{{ $contact->_uid }}' == contactItem._uid) ? 'list-group-item-light' : '']"
                                    :href="('{{ $contact->_uid }}' != contactItem._uid) ? __Utils.apiURL('{{ route('vendor.chat_message.contact.view', ['contactUid', 'assigned' => (($assigned ?? null) ? 'to-me' : '')]) }}',{'contactUid': contactItem._uid}) : '#'"
                                    class="list-group-item list-group-item-action lw-contact">
                                    {{-- d-flex align-items-start --}}
                                    <div class="ms-2 me-auto w-100 mt-1">
                                        <div class="float-left">
                                            <img class="lw-contact-avatar" :src="contactItem.gravatar"
                                                :alt="contactItem.full_name">
                                        </div>
                                        <div class="mt-2">
                                            <h3>
                                                <span x-show="contactItem.full_name" x-text="contactItem.full_name"></span>
                                                <span x-show="contactItem.full_name"> - </span>
                                                <span x-text="contactItem.wa_id"></span>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-right w-100 mt-3">
                                        <small class="text-muted lw-last-message-at"
                                            x-text="contactItem.last_message?.formatted_message_ago_time"></small>
                                        <span x-show="contactItem.unread_messages_count"
                                            class="badge bg-success rounded-pill"
                                            x-text="contactItem.unread_messages_count"></span>
                                    </div>
                                </a>
                                @if (($assigned ?? null))
                                </template>
                                @endif
                            </template>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="page col-sm-12 col-md-6 col-lg-8 col-xl-9 mb-4">
                        <h2>{{ __tr('Chat') }}</h2>
                       <div class="col-md-12 div col-lg-6 col-xl-6 p-0">
                        <x-lw.form id="lwAssignSystemUserForm" lwSubmitOnChange :action="route('vendor.chat.assign_user.process')" >
                            <input type="hidden" name="contactIdOrUid" value="{{ $contact->_uid }}">
                            <x-lw.input-field type="selectize" data-form-group-class="" name="assigned_users_uid"
                            :label="__tr('Select messaging permitted team member to assign this contact chat')"
                            data-selected="{{ $contact->assignedUser?->_uid }}">
                            <x-slot name="selectOptions">
                                <option value="">{{  __tr('Select User') }}</option>
                                <option value="no_one">{{  __tr('Select User') }}</option>
                                @foreach ($vendorMessagingUsers as $vendorMessagingUser)
                                <option value="{{ $vendorMessagingUser->_uid }}">{{ $vendorMessagingUser->first_name . ' ' . $vendorMessagingUser->last_name }} @if($vendorMessagingUser->_uid == getUserUID()) ({{  __tr('You') }}) @endif @if($vendorMessagingUser->vendors__id == getVendorId()) ({{  __tr('Account Owner') }}) @endif </option>
                                @endforeach
                            </x-slot>
                        </x-lw.input-field>
                        </x-lw.form>
                       </div>
                        <div class="marvel-device nexus5">
                            <div class="screen">
                                <div class="screen-container">
                                    <div class="chat">
                                        <div class="chat-container" x-cloak x-data="initialMessageData">
                                            <div class="user-bar">
                                                <div class="back d-md-none" @click.prevent="isContactListOpened = true">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                                <div class="avatar">
                                                    <img src="{{ $contact->gravatar }}" alt="{{ $contact->full_name }}">
                                                </div>
                                                <div class="name">
                                                    <span>{{ $contact->full_name }} <small>(<a target="_blank" href="https://api.whatsapp.com/send?phone={{ $contact->wa_id }}">{{ $contact->wa_id }}</a>)</small></span>
                                                    @if (isDemo() and isDemoVendorAccount() and (getVendorSettings('test_recipient_contact') != $contact->_uid))
                                                        <span class="status text-pink">{{  __tr('This demo account uses WhatsApp Test Number so you won\'t be able to send messages from here.') }}</span>
                                                   @else
                                                    @if ($isDirectMessageDeliveryWindowOpened)
                                                    <abbr class="status text-success" title="{{ __tr('As you received message response in last 24 hours, you can send direct messages to this  contact for next __windowClosedTime__.', [
                                                        '__windowClosedTime__' => $directMessageDeliveryWindowOpenedTillMessage
                                                        ]) }}">{{  __tr('You can reply for __windowClosedTime__', [
                                                            '__windowClosedTime__' => $directMessageDeliveryWindowOpenedTillMessage
                                                        ]) }}</abbr>
                                                        @else
                                                        <abbr class="status text-danger" title="{{ __tr("As you may not received any response in last 24 hours, your direct message may not get delivered. However you can send template messages.") }}">{{  __tr('You can\'t reply, they needs to reply back to start conversion.') }}</abbr>
                                                        @endif
                                                        @endif
                                                </div>
                                                <div class="actions more lw-user-new-actions" x-cloak x-data="{isAiChatBotEnabled: {{ !$contact->disable_ai_bot ? 1 : 0 }}}">
                                                    @if(getVendorSettings('enable_flowise_ai_bot') and getVendorSettings('flowise_url'))
                                                    <a :title="isAiChatBotEnabled ? '{{ __tr('Disable AI Bot') }}' : '{{ __tr('Enable AI Bot') }}'" href="{{ route('vendor.contact.write.toggle_ai_bot', [
                                                        'contactIdOrUid' => $contact->_uid
                                                    ]) }}" :class="isAiChatBotEnabled ? 'text-yellow' : 'text-white'" class="lw-whatsapp-bar-icon-btn mr-3 lw-ajax-link-action" data-method="post">
                                                       <i class="fa fa-robot"></i>
                                                    </a>
                                                    @endif
                                                    <a href="#" class="lw-whatsapp-bar-icon-btn" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v text-white"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('vendor.template_message.contact.view', ['contactUid' => $contact->_uid]) }}"
                                                        class="dropdown-item"><i class="fas fa-paper-plane"></i> {{ __tr('Send Template Message') }}</a>
                                                    <a x-cloak
                                                        :class="whatsappMessageLogs.length <= 0 ? 'disabled' : ''"
                                                        data-method="post" data-confirm="#lwClearChatHistoryWarning"
                                                        href="{{ route('vendor.chat_message.delete.process', ['contactUid' => $contact->_uid]) }}"
                                                        class="dropdown-item text-danger lw-ajax-link-action"><i class="fas fa-eraser"></i> {{ __tr('Clear Chat History') }}</a>
                                                    <script type="text/template" id="lwClearChatHistoryWarning">
                                                        <h3>{{  __tr('Are you sure you want to clear chat history for this contact?') }}</h3>
                                                            <p>{{  __tr('All the history will be deleted permanently. It will also affect various statistic and logs like messages processed, campaign stats and logs etc') }}</p>
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation">
                                                <div class="conversation-container" id="lwConversionChatContainer">
                                                        <template x-for="whatsappMessageLogItem in whatsappMessageLogs">
                                                            <div class="lw-chat-message-item"
                                                                :id="whatsappMessageLogItem._uid">
                                                                <template
                                                                    x-if="whatsappMessageLogItem.is_incoming_message">
                                                                    <div class="message received">
                                                                        <template
                                                                            x-if="whatsappMessageLogItem.replied_to_whatsapp_message_logs__uid">
                                                                            <a href="#"
                                                                                @click.prevent="lwScrollTo('#'+whatsappMessageLogItem.replied_to_whatsapp_message_logs__uid)"
                                                                                class="badge d-flex text-muted justify-content-end"><i
                                                                                    class="fa fa-link"></i> {{
                                                                                __tr('Replied to') }}</a>
                                                                        </template>
                                                                        <template
                                                                            x-if="whatsappMessageLogItem.template_message">
                                                                            <div class="lw-template-message"
                                                                                x-show="whatsappMessageLogItem.template_message"
                                                                                x-html="whatsappMessageLogItem.template_message">
                                                                            </div>
                                                                        </template>
                                                                        <div x-show="whatsappMessageLogItem.message && !whatsappMessageLogItem.__data?.interaction_message_data"><span class="lw-plain-message-text" x-html="whatsappMessageLogItem.message"></span></div>
                                                                        <span class="metadata"><span class="time"
                                                                                x-text="whatsappMessageLogItem.formatted_message_time"></span></span>
                                                                    </div>
                                                                </template>
                                                                <template
                                                                    x-if="!whatsappMessageLogItem.is_incoming_message">
                                                                    <div class="message sent">
                                                                        <template
                                                                            x-if="whatsappMessageLogItem.__data?.options?.bot_reply">
                                                                            <span class="badge d-flex text-muted justify-content-end"
                                                                                :title="whatsappMessageLogItem.__data?.options?.ai_bot_reply ? '{{ __tr('AI Bot Reply') }}' : '{{ __tr('Bot Reply') }}'">
                                                                                <template x-if="whatsappMessageLogItem.__data?.options?.ai_bot_reply">
                                                                                    <span class="mr-1 text-warning">AI</span>
                                                                                </template>
                                                                                <i class="fas fa-robot text-muted"></i>
                                                                            </span>
                                                                        </template>
                                                                        <template
                                                                            x-if="whatsappMessageLogItem.template_message">
                                                                            <div class="lw-template-message"
                                                                                x-show="whatsappMessageLogItem.template_message"
                                                                                x-html="whatsappMessageLogItem.template_message">
                                                                            </div>
                                                                        </template>
                                                                        <template x-if="whatsappMessageLogItem.message && !whatsappMessageLogItem.__data?.interaction_message_data">
                                                                            <div class="lw-template-message"
                                                                                x-show="whatsappMessageLogItem.message"
                                                                                ><span class="lw-plain-message-text" x-html="whatsappMessageLogItem.message"></span>
                                                                            </div>
                                                                        </template>
                                                                        <template
                                                                            x-if="whatsappMessageLogItem.status == 'failed'">
                                                                            <div class="p-1 mt-2">
                                                                                <small class="text-danger"> <i
                                                                                        class="fas fa-exclamation-circle text-danger text-shadow"></i>
                                                                                    <em
                                                                                        x-text="whatsappMessageLogItem.whatsapp_message_error"></em></small>
                                                                            </div>
                                                                        </template>
                                                                        <span class="metadata">
                                                                            <span class="time"
                                                                                x-text="whatsappMessageLogItem.formatted_message_time"></span>
                                                                            <span class="tick">
                                                                                <template
                                                                                    x-if="whatsappMessageLogItem.status == 'read'">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="16" height="15"
                                                                                        id="msg-dblcheck-ack" x="2063"
                                                                                        y="2076">
                                                                                        <path
                                                                                            d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z"
                                                                                            fill="#4fc3f7" />
                                                                                    </svg>
                                                                                </template>
                                                                                <template
                                                                                    x-if="whatsappMessageLogItem.status == 'delivered'">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="16" height="15"
                                                                                        id="msg-dblcheck" x="2047"
                                                                                        y="2061">
                                                                                        <path
                                                                                            d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z"
                                                                                            fill="#92a58c" />
                                                                                    </svg>
                                                                                </template>
                                                                                <template
                                                                                    x-if="whatsappMessageLogItem.status == 'sent'">
                                                                                    <svg width="16" height="16"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M4 12.6111L8.92308 17.5L20 6.5"
                                                                                            stroke="#92a58c"
                                                                                            stroke-width="2"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>
                                                                                </template>
                                                                                <template
                                                                                    x-if="whatsappMessageLogItem.status == 'failed'">
                                                                                    <i
                                                                                        class="fas fa-exclamation-circle text-danger"></i>
                                                                                </template>
                                                                                <template
                                                                                    x-if="(whatsappMessageLogItem.status == 'accepted')">
                                                                                    <i
                                                                                        class="far fa-clock text-muted"></i>
                                                                                </template>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    <div class="w-100" id="lwEndOfChats">&shy;</div>
                                                </div>
                                                <x-lw.form data-event-stream-update="true" data-callback="appFuncs.resetForm" id="whatsAppMessengerForm"
                                                    class="conversation-compose" data-show-processing="false"
                                                    :action="route('vendor.chat_message.send.process')">
                                                    <input type="hidden" name="contact_uid"
                                                        value="{{ $contact->_uid }}">
                                                    {{-- emoji following blank tag as removing it may break input layout
                                                    --}}
                                                    <div class="emoji">
                                                    </div>
                                                    <textarea name="message_body" required class="input-msg lw-input-emoji"
                                                        name="input" placeholder="{{ __tr(' Type a message') }}" autocomplete="off" autofocus></textarea>
                                                        <div class="photo dropup">
                                                            <!-- Default dropup button -->
                                                            <a href="#" class="lw-whatsapp-bar-icon-btn" data-toggle="dropdown" aria-expanded="false">
                                                                <i class=" fa fa-paperclip text-muted"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a title="{{ __tr('Send Document') }}"
                                                            class="lw-ajax-link-action dropdown-item" data-toggle="modal"
                                                            data-response-template="#lwWhatsappAttachment"
                                                            data-target="#lwMediaUploadAndSend"
                                                            data-callback="appFuncs.prepareUpload" href="{{ route('vendor.chat_message_media.upload.prepare', [
                                                            'mediaType' => 'document'
                                                        ]) }}"><i class="fa fa-file text-muted"></i> {{ __tr('Send Document') }}</a>
                                                        <a title="{{ __tr('Send Image') }}" class="lw-ajax-link-action dropdown-item"
                                                        data-toggle="modal"
                                                        data-response-template="#lwWhatsappAttachment"
                                                        data-target="#lwMediaUploadAndSend"
                                                        data-callback="appFuncs.prepareUpload" href="{{ route('vendor.chat_message_media.upload.prepare', [
                                                        'mediaType' => 'image'
                                                    ]) }}"><i class="fa fa-image text-muted"></i> {{ __tr('Send Image') }}</a>
                                                    <a title="{{ __tr('Send Video') }}" class="lw-ajax-link-action dropdown-item"
                                                    data-toggle="modal"
                                                    data-response-template="#lwWhatsappAttachment"
                                                    data-target="#lwMediaUploadAndSend"
                                                    data-callback="appFuncs.prepareUpload" href="{{ route('vendor.chat_message_media.upload.prepare', [
                                                    'mediaType' => 'video'
                                                ]) }}"><i class="fa fa-video text-muted"></i> {{ __tr('Send Video') }}</a>
                                                <a title="{{ __tr('Send Audio') }}" class="lw-ajax-link-action dropdown-item"
                                                data-toggle="modal"
                                                data-response-template="#lwWhatsappAttachment"
                                                data-target="#lwMediaUploadAndSend"
                                                data-callback="appFuncs.prepareUpload" href="{{ route('vendor.chat_message_media.upload.prepare', [
                                                'mediaType' => 'audio'
                                            ]) }}"><i class="fa fa-headphones text-muted"></i> {{ __tr('Send Audio') }}</a>
                                                            </div>
                                                        </div>
                                                    <button class="send" type="submit">
                                                        <div class="circle pl-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em"
                                                                height="1.5em" viewBox="0 0 24 24">
                                                                <path fill="currentColor"
                                                                    d="M2.01 21L23 12L2.01 3L2 10l15 2l-15 2z" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </x-lw.form>
                                                {{-- error container --}}
                                                <div data-form-id="#whatsAppMessengerForm"
                                                    class="lw-error-container-message_body p-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-lw.modal id="lwMediaUploadAndSend" :header="__tr('Send Media')" :hasForm="true"
    data-pre-callback="clearModelContainer">
    <!--  document form -->
    <x-lw.form id="lwMediaUploadAndSendForm" :action="route('vendor.chat_message_media.send.process')"
        data-callback="appFuncs.modelSuccessCallback" :data-callback-params="['modalId' => '#lwMediaUploadAndSend']">
        <!-- form body -->
        <input type="hidden" name="contact_uid" value="{{ $contact->_uid }}">
        <div id="lwWhatsappAttachment" class="lw-form-modal-body"></div>
        <script type="text/template" id="lwWhatsappAttachment-template">
            <% if(__tData.mediaType == 'document') { %>
            <div class="form-group col-sm-12">
                <input id="lwDocumentMediaFilepond" type="file" data-allow-revert="true"
                    data-label-idle="{{ __tr('Select Document') }}" class="lw-file-uploader" data-instant-upload="true"
                    data-action="<?= route('media.upload_temp_media', 'whatsapp_document') ?>" id="lwDocumentField" data-file-input-element="#lwDocumentMedia" data-raw-upload-data-element="#lwRawDocumentMedia" data-allowed-media='<?= getMediaRestriction('whatsapp_document') ?>' />
                <input id="lwDocumentMedia" type="hidden" value="" name="uploaded_media_file_name" />
                <input type="hidden" value="document" name="media_type" />
            </div>
            <% } else if(__tData.mediaType == 'image') { %>
                <div class="form-group col-sm-12">
                    <input id="lwImageMediaFilepond" type="file" data-allow-revert="true"
                        data-label-idle="{{ __tr('Select Image') }}" class="lw-file-uploader" data-instant-upload="true"
                        data-action="<?= route('media.upload_temp_media', 'whatsapp_image') ?>" id="lwImageField" data-file-input-element="#lwImageMedia" data-raw-upload-data-element="#lwRawDocumentMedia" data-allowed-media='<?= getMediaRestriction('whatsapp_image') ?>' />
                    <input id="lwImageMedia" type="hidden" value="" name="uploaded_media_file_name" />
                    <input type="hidden" value="image" name="media_type" />
                </div>
                <% } else if(__tData.mediaType == 'video') { %>
                    <div class="form-group col-sm-12">
                        <input id="lwVideoMediaFilepond" type="file" data-allow-revert="true"
                            data-label-idle="{{ __tr('Select Video') }}" class="lw-file-uploader" data-instant-upload="true"
                            data-action="<?= route('media.upload_temp_media', 'whatsapp_video') ?>" id="lwVideoField" data-file-input-element="#lwVideoMedia" data-raw-upload-data-element="#lwRawDocumentMedia" data-allowed-media='<?= getMediaRestriction('whatsapp_video') ?>' />
                        <input id="lwVideoMedia" type="hidden" value="" name="uploaded_media_file_name" />
                        <input type="hidden" value="video" name="media_type" />
                    </div>
                <% } else if(__tData.mediaType == 'audio') { %>
                    <div class="form-group col-sm-12">
                        <input id="lwAudioMediaFilepond" type="file" data-allow-revert="true"
                            data-label-idle="{{ __tr('Select Audio') }}" class="lw-file-uploader" data-instant-upload="true"
                            data-action="<?= route('media.upload_temp_media', 'whatsapp_audio') ?>" id="lwAudioField" data-file-input-element="#lwAudioMedia" data-raw-upload-data-element="#lwRawDocumentMedia" data-allowed-media='<?= getMediaRestriction('whatsapp_audio') ?>' />
                        <input id="lwAudioMedia" type="hidden" value="" name="uploaded_media_file_name" />
                        <input type="hidden" value="audio" name="media_type" />
                    </div>
                <% } %>
                <input id="lwRawDocumentMedia" type="hidden" value="" name="raw_upload_data"/>
                <% if(__tData.mediaType != 'audio') { %>
                <div>
                    <label for="lwMediaCaptionText">{{  __tr('Caption/Text') }}</label>
                    <textarea name="caption" id="lwCaptionField" class="form-control" rows="2"></textarea>
                </div>
                <% } %>
        </script>
        <!-- form footer -->
        <div class="modal-footer">
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __tr('Cancel') }}</button>
        </div>
    </x-lw.form>
    <!--/  document form -->
</x-lw.modal>
<script>
     (function() {
        'use strict';
    document.addEventListener('alpine:init', () => {
        Alpine.data('initialMessageData', () => ({
            whatsappMessageLogs: @json($whatsappMessageLog)
        }));
        Alpine.data('initialContactListData', () => ({
            contacts: @json($contacts)
        }));
    });
})();
</script>
@push('head')
    {!! __yesset('dist/emojionearea/emojionearea.min.css', true) !!}
@endpush
@push('appScripts')
{!! __yesset('dist/emojionearea/emojionearea.min.js', true) !!}
<script>
(function($) {
    'use strict';
    window.lwScrollTo('#lwEndOfChats', true);
    window.lwMessengerEmojiArea = $(".lw-input-emoji").emojioneArea({
    useInternalCDN: true,
    pickerPosition: "top",
    searchPlaceholder: "{{ __tr('Search') }}",
    buttonTitle: "{{ __tr('Use the TAB key to insert emoji faster') }}",
    events: {
        'emojibtn.click': function (editor, event) {
            this.hidePicker();
        },
        keyUp: function (editor, event) {
            if (event && event.which == 13 && !event.shiftKey && $.trim(this.getText())) { // On Enter
                $('.lw-input-emoji').val(this.getText());
                $('#whatsAppMessengerForm').submit();
                this.hidePicker();
                appFuncs.resetForm();
            }
        }
    }
});
})(jQuery);
</script>
@endpush
@else
<div class="container-fluid">
    <div class="col-12">
        <div class="alert alert-danger">
            {{  __tr('There isn\'t any contact to chat with. Click on Chat button from contacts list') }}
        </div>
    </div>
</div>
@endif
@endsection()