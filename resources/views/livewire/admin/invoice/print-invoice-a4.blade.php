<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="author" content="Xfortech (P) Ltd.">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="manifest" href="">
    <link rel="stylesheet" href="{{ asset('assets/css/a4-print-style.css') }}">
    <title>{{__('main.print')}}</title>
</head>

<body>
    @php
    $settings = new App\Models\MasterSetting();
    $site = $settings->siteData();
    $branch = \App\Models\User::find($invoice->created_by);
    @endphp
    <main class="">
        <div class="row mb-2 justify-content-between align-items-end">
            <div class="col-6">
                <img src="{{getCompanyLogo()}}" height="80px">
            </div>
            <div class="col-4">
                <div class="text-right">
                    <h2 class="text-uppercase m-0 font-weight-bolder">Tax Invoice</h2>
                </div>
            </div>
        </div>
        <div class="row mb-2 justify-content-between">
            <div class="col-6">
                <div class="">
                    <h5 class="text-uppercase">{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}</h5>
                    <p class="mb-25 text-uppercase">{{getCompanyTaxRegistration()}}</p>
                    <!--Tax Number-->
                    @if(Auth::user()->user_type != 2)
                    <p class="mb-25">
                        <span>{{ $branch->address ?? '' }}</span>,
                    </p>
                    @if ($branch->phone)
                    <p class="mb-0">
                        <span>{{ getCountryCode() }}</span>
                        <span>{{ $branch->phone ?? '' }}</span>
                    </p>
                    @endif
                    @else
                    @if (isset($site['company_mobile']) && !empty($site['company_mobile']))
                    <p class="mb-0">
                        <span>{{ getCountryCode() }}</span>
                        <span>{{ isset($site['company_mobile']) ? $site['company_mobile'] : ''}}</span>
                    </p>
                    @endif
                    @endif
                </div>
            </div>
            <div class="col-4">
                <div class="text-right">
                    <h5 class="text-uppercase text-purple">
                        <span>#</span>
                        {{ $invoice->invoice_number }}
                    </h5>
                    <p class="mb-25">
                        <span class="mr-50">Date :</span>
                        <span>{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y g:i A') }}</span>
                    </p>
                    <p class="mb-0">
                        <span class="mr-50">{{__('main.file_no')}} :</span>
                        <span>{{ $invoice->customer_file_number }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="row mb-2 justify-content-between align-items-start">
            <div class="col-6">
                <div class="">
                    <p class="mb-50 text-uppercase text-purple font-weight-bold">Invoice To</p>
                    <h5 class="text-uppercase">{{$invoice->customer_name}}</h5>
                    <!--Tax Number-->
                    <p class="mb-25">
                        <span>{{$invoice->customer_address}}</span>
                    </p>
                    <p class="mb-0">
                        <span>{{ getCountryCode() }}</span>
                        <span>{{$invoice->customer_phone}}</span>
                    </p>
                </div>
            </div>
            <div class="col-4">
                <div class="text-right">
                    
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="" style="border:.5px solid #94939c; border-collapse: collapse; width: 100%; margin-bottom: 1rem;">
                        <thead style="color: #fff; background-color: #2e5984; !important border:.5px solid #fff;">
                        <tr style="border:0px solid #94939c">
                                <th style="width: 5%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff;" scope="col">#</th>
                                <th style="width: 30%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff;" scope="col">{{__('print.item')}}</th>
                                <th style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff;" scope="col">{{__('main.rate')}}</th>
                                <th style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.qty')}}</th>
                                <th style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.tax')}}</th>
                                <th style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.tax_amount')}}</th>
                                <th style="width: 15%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .9rem; text-align: right;" scope="col">{{__('main.total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $qty = 0;
                        @endphp
                        @foreach ($invoice->invoiceProductDetails as $row)
                            <tr style="border:.5px solid #94939c">
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="">{{$loop->index+1}}</div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                {{ $row->item_name }}
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($row->rate) }} </span>
                                    </div>
                                </td>
                                <td style=" border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span>{{ $row->quantity }}</span>
                                        <span class="text-uppercase">@if ($row->type == 2)
                                                {{ getUnitType($row->unit_type ?? '') }}
                                            @endif</span>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class=""> {{$invoice->tax_percentage }} % </span>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($row->tax_amount) }} </span>
                                    </div>
                                </td>
                                <td style="text-align: right; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .95rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($row->total, 2) }}</span>
                                    </div>
                                </td>
                                @php
                                    $qty = $qty + $row->product_quantity;
                                @endphp
                            </tr>
                              @endforeach
                        </tbody>
                        <tfoot style="color: #000; background-color: #fff; font-weight: 500; border:.5px solid #94939c !important">
                            <tr>
                                <td style="width: 5%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c;" scope="col">
                                </td>
                                <td style="width: 30%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c; text-align: right;" scope="col">
                                    Total
                                </td>
                                <td style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c;" scope="col">
                                </td>
                                <td style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c;" scope="col">
                                </td>
                                <td style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c;" scope="col">
                                </td>
                                <td style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; border-right: 1px solid #94939c;" scope="col">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($invoice->tax_amount) }}</span>
                                    </div>
                                </td>
                                <td style="width: 15%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: 1rem; text-align: right;" scope="col">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($invoice->taxable_amount) }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row justify-content-between align-items-start mb-3">
            <div class="col-5">
                <div class="mb-3">
                    <p class="mb-50 text-capitalize font-weight-bold">{{ isset($site['default_thanks_message']) && !empty($site['default_thanks_message']) ? $site['default_thanks_message'] : 'Thank You for Doing business with us' }}  !</p>
                </div>
                <div class="mb-2">
                  
                </div>
                <div class="">
                    
                    <div class="row mb-50 align-items-center text-xs">
                    </div>
                    <div class="row mb-50 align-items-center text-xs">
                    </div>
                    <div class="row mb-50 align-items-center text-xs">
                    </div>
                    <div class="row mb-50 align-items-center text-xs">
                    </div>
                    <div class="row mb-0 align-items-center text-xs">
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="mb-2">
                    <div class="row mb-50 align-items-center">
                        <div class="col font-weight-bold">{{__('main.discount')}} :</div>
                        <div class="col-auto font-weight-bolder">
                            <span>{{ getFormattedCurrency($invoice->discount) }}</span>
                        </div>
                    </div>
                    <div class="row mb-50 align-items-center">
                        <div class="col font-weight-bold">{{__('main.taxable_amount')}} :</div>
                        <div class="col-auto font-weight-bolder">
                            <span> {{ getFormattedCurrency($invoice->taxable_amount) }}</span>
                        </div>
                    </div>
                    <div class="row mb-50 align-items-center">
                        <div class="col font-weight-bold">{{__('main.total')}} {{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} ({{ $invoice->tax_percentage }}%):</div>
                        <div class="col-auto font-weight-bolder">
                            <span>{{ getFormattedCurrency($invoice->tax_amount) }}</span>
                        </div>
                    </div>
                    <div class="row mb-50 align-items-center">
                        <div class="col font-weight-bold">{{__('main.total')}} :</div>
                        <div class="col-auto font-weight-bolder">
                            <span>{{ getFormattedCurrency($invoice->total) }}</span>
                        </div>
                        @php
                                $paid = \App\Models\InvoicePayment::where('invoice_id', $invoice->id)->sum('paid_amount');
                            @endphp
                    </div>
                    <div class="row mb-50 align-items-center">
                        <div class="col font-weight-bold">{{__('print.advance')}}:</div>
                        <div class="col-auto font-weight-bolder">
                            <span>{{ getFormattedCurrency($paid) }}</span>
                        </div>
                    </div>
                    <hr class="my-50" />
                    <div class="row align-items-center">
                        <div class="col font-weight-bold">{{__('main.balance')}} :</div>
                        <div class="col-auto font-weight-bolder">
                            <span>{{ getFormattedCurrency($invoice->total - $paid) }}</span>
                        </div>
                    </div>
                    <hr class="my-50" />
                </div>
                <div class="">
                    <p style="margin-bottom: 4.75rem;" class="text-uppercase text-purple font-weight-bold">For <span class="font-weight-bolder ms-50">{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}</span></p>
                    <p class="mb-0 text-capitalize text-xs">Authorized Signatory</p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center text-uppercase font-weight-bold">
            <div class="mr-1">Powered by</div>
            {{ getApplicationName() }}
        </div>
    </main>
</body>

</html>
<script type="text/javascript">
    window.onload = function() {
    "use strict";
        window.print();
        setTimeout(function() {
            window.onfocus=function(){ window.close();}
        }, 200);
    }
</script>