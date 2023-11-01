<div>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ __('main.print') }}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" />
    </head>
    <body>
        <div class="container mt-0">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $settings = new App\Models\MasterSetting();
                                $site = $settings->siteData();
                            @endphp
                            <div class="row">
                                <div class="col">
                                    <h4 class="font-weight-bold mb-1 mt-3 w-table-40">
                                        {{ $site['company_name'] ?? 'Tailor POS' }}</h4>
                                    <p class="mb-0"><small
                                            class="font-weight-bold text-gray">{{ __('main.voucher_no') }}:
                                            {{ $site['company_cr_number'] ?? 'No CR' }}</small></p>
                                    <p class="mb-0"><small
                                            class="font-weight-bold text-gray">{{ isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST' }}:
                                            {{ $site['company_tax_registration'] ?? 'No Tax' }}</small></p>
                                    <p><small class="font-weight-bold text-gray">{{ __('main.phone_number') }}:
                                            {{ Auth::user()->phone }}</small></p>
                                </div>
                                <div class="col-auto pt-80">
                                    <p class="mb-0"><strong>{{ __('print.no') }}</strong>: @if ($bill->voucher_no)
                                            #{{ $bill->voucher_no }}
                                        @endif
                                    </p>
                                    <p><strong>{{ __('main.date') }}</strong>:
                                        {{ $bill->date->format('d-M-Y h:i a') }}</p>
                                </div>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td colspan="8">{{ __('print.received_from') }}:
                                                <strong>{{ $bill->customer->first_name ?? '' }}<strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">{{ __('main.amount') }}:
                                                <strong>{{ getFormattedCurrency($bill->paid_amount) }} <strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">{{ __('print.amount_in_words') }}:
                                                <strong>{{ getIndianCurrency($bill->paid_amount) }}
                                                    {{ __('print.currency_words') }}
                                                    <strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">{{ __('print.being_for') }} <strong>
                                                    @if ($bill->payment_type == 1)
                                                        {{ ' Invoice #' }} {{ $bill->invoice->invoice_number }}
                                                    @endif

                                                    @if ($bill->payment_type == 2)
                                                        {{ ' Opening Balance' }}
                                                    @endif
                                                    <strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-100">
                                <div class="col">
                                    <strong>{{ __('print.receivers_sign') }}</strong>.........................................
                                </div>
                                <div class="col-auto">
                                    <strong>{{ __('print.signature') }}</strong>...................................................
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script>
            window.onload = function() {
            "use strict";
                window.print();
                setTimeout(function() {
            window.onfocus=function(){ window.close();}
        }, 200);
            }
        </script>
    </body>
</div>