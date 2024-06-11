@extends('layouts.app', ['title' => __tr('Campaign Status')])
@section('content')
@include('users.partials.header', [
'title' => __tr('Campaign Report'),
'description' => '',
'class' => 'col-lg-7'
])
@php
$campaignData = $campaign->__data;
$selectedGroups = Arr::get($campaignData, 'selected_groups', []);
$isRestrictByTemplateContactLanguage = Arr::get($campaignData, 'is_for_template_language_only');
$isAllContacts = Arr::get($campaignData, 'is_all_contacts');
$messageLog = $campaign->messageLog;
$queueMessages = $campaign->queueMessages;
@endphp
<div class="container-fluid mt-lg--6 lw-campaign-window-{{ $campaign->_uid }}" x-cloak x-data="initialRequiredData">
    <div class="row">
        <!-- button -->
        <div class="col-12 mb-3">
            <div class="float-right">
                <a class="lw-btn btn btn-secondary" href="{{ route('vendor.campaign.read.list_view') }}">{{ __tr('Back to Campaigns') }}</a>
                <a class="lw-btn btn btn-primary" href="{{ route('vendor.campaign.new.view') }}">{{ __tr('Create New Campaign') }}</a>
            </div>
        </div>
        <!--/ button -->
        <div class="col-12 mb-4 ">
            <div class="card card-stats mb-4 mb-xl-0 ">
                <div class="card-body ">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __tr('Campaign Name') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $campaign->title }}</span>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <h3 class="text-success mr-2">{{ __tr('Execution Schedule') }}</h3>
                                <div class="text-warning">{{ formatDiffForHumans($campaign->scheduled_at, 3) }}</div>
                                @if ($campaign->timezone and getVendorSettings('timezone') != $campaign->timezone)
                                <div class="">{!! __tr('__scheduledAt__ as per your account timezone which is __selectedTimezone__', [
                                    '__scheduledAt__' => formatDateTime($campaign->scheduled_at),
                                    '__selectedTimezone__' => '<strong>'. getVendorSettings('timezone') .'</strong>'
                                ]) !!} </div>
                                <div class=" text-muted">{!! __tr('Campaign scheduled on __scheduledAt__ as per the __selectedTimezone__ timezone', [
                                    '__scheduledAt__' => formatDateTime($campaign->scheduled_at_by_timezone, null, null, $campaign->timezone),
                                    '__selectedTimezone__' => '<strong>'. $campaign->timezone .'</strong>'
                                ]) !!}</div>
                                @else
                                <span class="text-nowrap">{{ formatDateTime($campaign->scheduled_at) }}</span>
                                @endif

                            </p>
                            <div class="my-3">
                                <h5 class="card-title text-uppercase text-muted mb-2">{{ __tr('template Name') }}</h5>
                                <span class="h3 font-weight-bold mb-2">{{ $campaign->template_name }}</span>
                                <h5 class="card-title text-uppercase text-muted mb-2 mt-3">{{ __tr('template language') }}
                                </h5>
                                <span class="h3 font-weight-bold mb-2">{{ $campaign->template_language }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row mb-4">
                {{-- total contacts --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __tr('Total Contacts') }}</h5>
                                    <span class="h2 font-weight-bold mb-0" x-text="totalContacts"></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if ($isAllContacts)
                                {{ __tr('All contacts ') }}
                               @else
                                {{ __tr('All contacts from: ') }}
                                @foreach ($selectedGroups as $selectedGroup)
                                <strong class="text-nowrap text-warning">{{ $selectedGroup['title'] }}</strong>
                                @endforeach
                                {{ __tr(' groups.') }}
                                @endif
                                @if ($isRestrictByTemplateContactLanguage)
                                <span class="">{!! __tr('Excluding those contacts which don\'t have __languageCode__ language', [
                                    '__languageCode__' => "<span class='text-warning'>". e($campaign->template_language) ."</span>"
                                ]) !!}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                {{-- /total contacts --}}
                {{-- delivered to --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __tr('Total Delivered') }}
                                    </h5>
                                    <span class="h2 font-weight-bold mb-0" x-text="totalDeliveredInPercent"></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-nowrap" x-text="totalDelivered"></span>
                                <span class="text-nowrap">{{ __tr('Contacts') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                {{-- /delivered to --}}
                {{-- read by --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __tr('Total Read') }}</h5>
                                    <span class="h2 font-weight-bold mb-0" x-text="totalReadInPercent"></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-nowrap" x-text="totalRead"></span>
                                <span class="text-nowrap">{{ __tr('Contacts') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                {{-- /read by --}}
                {{-- failed --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __tr('Total Failed') }}
                                    </h5>
                                    <span class="h2 font-weight-bold mb-0" x-text="totalFailedInPercent"></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-nowrap" x-text="totalFailed"></span>
                                <span class="text-nowrap">{{ __tr('Contacts') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                {{-- /failed --}}
            </div>
            {{-- message log --}}
            <div class="card">
                <h2 class="card-header">
                    {{ __tr('Executed Log') }}
                </h2>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">{{ __tr('Name') }}</th>
                                <th scope="col">{{ __tr('Phone Number') }}</th>
                                <th scope="col">{{ __tr('Status') }}</th>
                                <th scope="col">{{ __tr('Message Delivered at') }}</th>
                                <th scope="col">{{ __tr('Last Status Updated at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="campaignMessageLogItem in messageLog">
                                <tr>
                                    <td>
                                        <template
                                            x-if="campaignMessageLogItem.__data?.contact_data?.is_template_test_contact"><i
                                                title="{{ __tr('Initial Test Message') }}"
                                                class="fa fa-flask text-warning"></i></template>
                                                <span x-text="campaignMessageLogItem.__data?.contact_data?.first_name + ' ' + campaignMessageLogItem.__data?.contact_data?.last_name"></span>
                                    </td>
                                    <td x-text="campaignMessageLogItem.contact_wa_id"></td>
                                    <td>
                                        <div x-text="campaignMessageLogItem.status"></div>
                                        <small class="text-danger" x-text="campaignMessageLogItem.whatsapp_message_error"></small>
                                    </td>
                                    <td
                                        x-text="campaignMessageLogItem.messaged_at ? campaignMessageLogItem.formatted_message_time : '{{ __tr('N/A') }}'">
                                    </td>
                                    <td x-text="campaignMessageLogItem.formatted_updated_time"></td>
                                </tr>
                            </template>
                            <template x-if="!messageLog.length">
                                <tr>
                                    <td colspan="5" class="text-muted text-center">{{ __tr('There are no items to display') }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">{{  __tr('This is the list of processed messages for contact.') }}</span>
                </div>
            </div>
            {{-- /message log --}}
            {{-- message error log --}}
            <div class="card mt-4">
                <h2 class="card-header">
                    {{ __tr('Queue Log') }}
                </h2>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">{{ __tr('Name') }}</th>
                                <th scope="col">{{ __tr('Phone Number') }}</th>
                                <th scope="col">{{ __tr('Last Status Updated at') }}</th>
                                <th scope="col">{{ __tr('Messages') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="queueMessage in queueMessages">
                                <tr>
                                    <td x-text="queueMessage.__data?.contact_data?.first_name + ' ' + queueMessage.__data?.contact_data?.last_name"></td>
                                    <td x-text="queueMessage.phone_with_country_code"></td>
                                    <td x-text="queueMessage.formatted_updated_time"></td>
                                    <template x-if="queueMessage.status == 2">
                                        <td class="text-danger" x-text="queueMessage.whatsapp_message_error"></td>
                                    </template>
                                    <template x-if="queueMessage.status == 3">
                                        <td class="text-success">{{ __tr('processing ..') }}</td>
                                    </template>
                                    <template x-if="(queueMessage.status != 2) && (queueMessage.status != 3)">
                                        <td class="text-muted">{{ __tr('waiting ..') }}</td>
                                    </template>
                                </tr>
                            </template>
                            <template x-if="!queueMessages.length">
                                <tr>
                                    <td colspan="4" class="text-muted text-center">{{ __tr('There are no items to display') }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">{{  __tr('This is the list of waiting/failed messages for contacts.') }}</span>
                </div>
            </div>
            {{-- /message error log --}}
        </div>
    </div>
</div>
@php
$totalContacts = (int) Arr::get($campaignData, 'total_contacts');
$totalRead = $messageLog->where('status', 'read')->count();
$totalReadInPercent = round($totalRead / $totalContacts * 100, 2) . '%';
$totalDelivered = $messageLog->where('status', 'delivered')->count();
$totalDeliveredInPercent = round(($totalDelivered + $totalRead) / $totalContacts * 100, 2) . '%';
$totalFailed = $queueMessages->where('status', 2)->count() + $messageLog->where('status', 'failed')->count();
$totalFailedInPercent = round($totalFailed / $totalContacts * 100, 2) . '%';
@endphp
<script>
    (function() {
        'use strict';
        document.addEventListener('alpine:init', () => {
            Alpine.data('initialRequiredData', () => ({
                messageLog: @json($messageLog),
                queueMessages: @json($queueMessages),
                totalContacts:'{{ __tr($totalContacts)  }}',
                totalDeliveredInPercent:'{{ __tr($totalDeliveredInPercent) }}',
                totalDelivered:'{{ __tr($totalDelivered + $totalRead) }}',
                totalRead:'{{ __tr($totalRead) }}',
                totalReadInPercent:'{{ __tr($totalReadInPercent) }}',
                totalFailed:'{{ __tr($totalFailed) }}',
                totalFailedInPercent:'{{ __tr($totalFailedInPercent) }}',
            }));
    });
    })();
</script>
@endsection()