<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{__('main.customer_report')}}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
        <style>
            thead {
                display: table-row-group;
            }

            * {
                color: black !important;
                font-weight: 1000 !important;
                  /* font-size: calc(100% + 1px); */
            }
        </style>
    </head>
    <body onload="">
        <div class="row align-items-center justify-content-between mb-4">
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="fw-500 text-center text-dark">{{__('main.customer_report')}}</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <p class="text-center"> {{ $selected_customer->file_number }} -
                            {{ $selected_customer->first_name ?? '' }} </p>
                    </div>
                </div>
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        <p class="text-xs">{{ __('main.start_date') }}:
                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="text-xs ">{{ __('main.end_date') }}:
                            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0">
                        @if ($selected_customer)
                            <div class="card-body pb-1">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-start">

                                            <div class="ms-2">
                                                <div class="mb-1 fw-bold text-sm">
                                                    {{__('main.file_no')}} : {{ $selected_customer->file_number }}
                                                </div>
                                                <div class="mb-1 fw-bold"> {{__('main.name')}} : {{ $selected_customer->first_name }}
                                                </div>
                                                <div class="mb-1 text-sm fw-bold">
                                                    {{__('main.phone')}} : {{ getCountryCode() }}
                                                    {{ $selected_customer->phone_number_1 }} @if ($selected_customer->phone_number_2)
                                                        , {{ getCountryCode() }}
                                                        {{ $selected_customer->phone_number_2 }}
                                                    @endif
                                                </div>
                                                <div class="mb-1 text-sm fw-bold">
                                                    @if ($selected_customer->email)
                                                        {{__('main.email')}} : {{ $selected_customer->email ?? '' }}
                                                    @endif
                                                </div>
                                                <div class="mb-0 text-sm fw-bold">
                                                    @if ($selected_customer->address)
                                                        {{__('main.address')}} : {{ $selected_customer->address ?? '' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $total = \App\Models\Invoice::where('customer_id', $selected_customer->id)->sum('total');
                                        $paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)->sum('paid_amount');
                                        $invoice_paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                                            ->where('payment_type', 1)
                                            ->sum('paid_amount');
                                        $opening_received = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                                            ->where('payment_type', 2)
                                            ->sum('paid_amount');
                                        $opening_balance = $selected_customer->opening_balance != '' ? $selected_customer->opening_balance : 0;
                                        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id', $selected_customer->id)->sum('amount');
                                    @endphp
                                    <div class="col-6 ledger-text">
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{__('main.opening_balance')}}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($opening_balance) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{__('main.total_invoices')}}:</div>
                                            <div class="col-auto fw-bold">
                                                {{ \App\Models\Invoice::where('customer_id', $selected_customer->id)->count() }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{__('main.total_amount')}}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($total) }}</div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{__('main.paid_amount')}}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($paid) }}</div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{__('main.payment_discount')}}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($discount) }}</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{__('main.balance_amount')}}:</div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($total + $selected_customer->opening_balance - ($paid + $discount)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $total_invoices = 0;
                            $total_sales = 0;
                            $total_payments = 0;
                            $total_discounts = 0;
                        @endphp
                        <table class="table table-bordered mt-4">
                            <thead class="text-xs">
                                <th class="text-dark w-table-15"  scope="col">{{__('main.date')}}</th>
                                <th class="text-dark w-table-35" scope="col">{{__('main.particulars')}}</th>
                                <th class="text-dark w-table-25"  scope="col">{{__('main.debit')}}</th>
                                <th class="text-dark w-table-25"  scope="col">{{__('main.credit')}}</th>
                            </thead>
                            <tbody>
                                @foreach ($mycollection as $key => $value)
                                    @foreach ($value['invoice'] as $item)
                                        @php
                                            $total_invoices++;
                                            $total_sales += $item->total;
                                        @endphp
                                        <tr class="tag-text">
                                            <td class="w-table-15">{{ $key }}</td>
                                            <td class="w-table-35">
                                                <span class="me-1">{{__('main.sales')}}</span>
                                                <span>- #{{ $item->invoice_number }}</span>
                                            </td>
                                            <td class="w-table-25">{{ getFormattedCurrency($item->total) }}</td>
                                            <td class="w-table-25"></td>
                                        </tr>
                                    @endforeach
                                    @foreach ($value['payment'] as $item)
                                        @php
                                            $total_payments += $item->paid_amount;
                                        @endphp
                                        <tr class="tag-text">
                                            <td class="w-table-15">{{ $key }}</td>
                                            <td class="w-table-35">
                                                <span class="me-1">{{__('main.payment')}} - #{{ $item->voucher_no }}</span>
                                            </td>
                                            <td class="w-table-25"></td>
                                            <td class="w-table-25">{{ getFormattedCurrency($item->paid_amount) }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($value['discount'] as $item)
                                        @php
                                            $total_discounts += $item->amount;
                                        @endphp
                                        <tr class="tag-text">
                                            <td class="w-table-15">{{ $key }}</td>
                                            <td class="w-table-35">
                                                <span class="me-1">{{__('main.payment_discount')}}</span>
                                            </td>
                                            <td class="w-table-25"></td>
                                            <td class="w-table-25">{{ getFormattedCurrency($item->amount) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-3">
                        <div class="row d-flex g-3 align-items-center justify-content-between">
                            <div class="col-2 ">
                                <p class="">
                                <div class="fw-bold text-xs">{{__('main.total_invoices')}}:</div>
                                <div class="fw-bold text-xs">{{ $total_invoices }}</div>
                                </p>
                            </div>
                            <div class="col-2 ">
                                <p class="">
                                <div class="fw-bold text-xs">{{__('main.total_sales')}}:</div>
                                <div class="fw-bold text-xs">{{ getFormattedCurrency($total_sales) }}</div>
                                </p>
                            </div>
                            <div class="col-2 ">
                                <p class="">
                                <div class="fw-bold text-xs">{{__('main.total_payments')}}:</div>
                                <div class="fw-bold text-xs">{{ getFormattedCurrency($total_payments) }}</div>
                                </p>
                            </div>
                            <div class="col-2">
                                <p class="">
                                <div class="fw-bold text-xs">{{__('main.net_discounts')}}:</div>
                                <div class="fw-bold text-xs">{{ getFormattedCurrency($total_discounts) }}</div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            window.onload = function() {
            "use strict";
                window.print();
                setTimeout(function() {
            window.onfocus=function(){ window.close();}
        }, 200);
            }
        </script>
    </body>
    </html>
</div>