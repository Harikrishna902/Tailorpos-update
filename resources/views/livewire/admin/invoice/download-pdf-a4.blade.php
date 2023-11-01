<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="author" content="Xfortech (P) Ltd.">

    <link rel="apple-touch-icon" sizes="180x180" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="manifest" href="">

    <title>{{__('main.print')}}</title>
    <style>
        .align-items-center {
            align-items: center !important
        }

        .d-flex {
            display: flex !important
        }

        .mb-50,
        .my-50 {
            margin-bottom: .7rem !important
        }

        .justify-content-between {
            justify-content: space-between !important
        }

        .align-items-end {
            align-items: flex-end !important
        }

        .mb-2,
        .my-2 {
            margin-bottom: 1.7rem !important
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

        .mb-25,
        .my-25 {
            margin-bottom: .25rem !important
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px
        }
    </style>
    <style>
        .col {
            border: none;
            text-align: left;
            padding: 10px;
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .col1 {
            border: none;
            text-align: right;
            padding: 5px;
            font-size: 0.75rem;
            line-height: 0.7rem;
        }

        .text-center {
            text-align: center;
        }

        .border-none {
            border: none;
            text-align: left;
            padding-left: 2px;
            font-weight: bold;
            line-height: 0.7rem;

        }

        .border-none1 {
            border: none;
            text-align: right;
            padding-right: 2px;
            font-weight: bold;
            line-height: 0.7rem;
        }

        .border-none-left {
            border: none;
            text-align: right;
            padding-right: 2px;
            font-weight: bold;
            line-height: 0.7rem;
        }

        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }

        .border-dashed {
            border-bottom: 0.5px gray dashed;
            line-height: 1.7rem;
        }

        .text-purple {
            color: #b627c0 !important
        }

        .font-weight-bolder {
            font-weight: 600 !important
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

       .bordertr {
            margin: 1rem 0;
            color: inherit;
            border: 1;
            border-top: var(--bs-border-width) solid;
            opacity: 0.25;
        }
    </style>
</head>

<body>
    @php
    $settings = new App\Models\MasterSetting();
    $site = $settings->siteData();
    $invoice = App\Models\Invoice::where('id',$id)->first();
    $branch = \App\Models\User::find($invoice->created_by);
    if(Auth::user()->user_type==2) {
    $invoice = \App\Models\Invoice::where('id',$id)->first();
    }
    /* if the user is branch */
    if(Auth::user()->user_type==3) {
    $nvoice = \App\Models\Invoice::where('id',$id)->where('created_by',Auth::user()->id)->first();
    }
    if(!$invoice){
    abort(404);
    }
    @endphp
    <main class="">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="border-none">
                    <img src="{{getCompanyLogoPdf()}}" height="80px">
                </td>
                <td class="border-none1">
                    <p class="text-right">
                    <h2 class="text-uppercase m-0 font-weight-bolder">Tax Invoice</h2>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="border-none">
                    <h5 class="text-uppercase">{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}</h5>
                    <p class="mb-25 text-uppercase">{{getCompanyTaxRegistration()}}</p>
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
                </td>
                <td class="border-none1">
                    <h5 class="text-uppercase text-purple font-weight-bold">
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
                </td>
            </tr>
            <tr>
                <td class="border-none">
                    <p class="mb-50 text-uppercase text-purple font-weight-bold">Invoice To</p>
                    <h5 class="text-uppercase">{{$invoice->customer_name}}</h5>
                    <p class="mb-25">
                        <span>{{$invoice->customer_address}}</span>
                        <span>{{ getCountryCode() }}</span>
                        <span>{{$invoice->customer_phone}}</span>
                    </p>
                </td>
                <td class="border-none1">

                </td>
            </tr>
        </table>
        <br />
        <div class="row mb-2">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="" style="border:.5px solid #94939c; border-collapse: collapse; width: 100%; margin-bottom: 1rem;">
                        <thead style="color: #fff; background-color: #2e5984; !important border:.5px solid #fff;">
                            <tr style="border:0px solid #94939c">
                                <th style="width: 5%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff;" scope="col">#</th>
                                <th style="width: 30%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff;" scope="col">{{__('print.item')}}</th>
                                <th style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff;" scope="col">{{__('main.rate')}}</th>
                                <th style="width: 12%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.qty')}}</th>
                                <th style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.tax')}}</th>
                                <th style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; border-right: 0px solid #fff; text-align: left;" scope="col">{{__('main.tax_amount')}}</th>
                                <th style="width: 15%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .55rem; text-align: right;" scope="col">{{__('main.total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $qty = 0;
                            @endphp
                            @foreach ($invoice->invoiceProductDetails as $row)
                            <tr style="border:.5px solid #94939c">
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    <div class="">{{$loop->index+1}}</div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    {{ $row->item_name }}
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($row->rate) }} </span>
                                    </div>
                                </td>
                                <td style=" border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span>{{ $row->quantity }}</span>
                                        <span class="text-uppercase">@if ($row->type == 2)
                                            {{ getUnitType($row->unit_type ?? '') }}
                                            @endif</span>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class=""> {{$invoice->tax_percentage }} % </span>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #94939c; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($row->tax_amount) }} </span>
                                    </div>
                                </td>
                                <td style="text-align: right; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; font-size: .55rem; font-weight: 500;">
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
                                <td style="width: 13%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .8rem; border-right: 1px solid #94939c;" scope="col">
                                    <div class="m-0">
                                        <span class="">{{ getFormattedCurrency($invoice->tax_amount) }}</span>
                                    </div>
                                </td>
                                <td style="width: 15%; padding: .35rem .75rem .35rem .75rem; vertical-align: middle; text-transform: uppercase; font-size: .8rem; text-align: right;" scope="col">
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

        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
            <tr>
                <td width="50%">
                    <p class="text-capitalize font-weight-bold">{{ isset($site['default_thanks_message']) && !empty($site['default_thanks_message']) ? $site['default_thanks_message'] : 'Thank You for Doing business with us' }} !</p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                    <p></p>
                </td>
                <td width="50%">
                    <table cellpadding="0" cellspacing="0" border="0"  width="100%;">
                        <tbody>
                            <tr class="tag-text">
                                <td class="border-none-left col" style="width:70%;"> <b>Discount</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($invoice->discount) }}</td>
                            </tr>
                            <tr class="tag-text">
                                <td class="border-none-left col" style="width:70%;"> <b>Taxable Amount</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($invoice->taxable_amount) }}</td>
                            </tr>
                            <tr class="tag-text">
                                <td class="border-none-left col" style="width:70%;"> <b>Total {{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} ({{ $invoice->tax_percentage }}%)</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($invoice->tax_amount) }}</td>
                            </tr>
                            <tr class="tag-text">
                                @php
                                $paid = \App\Models\InvoicePayment::where('invoice_id', $invoice->id)->sum('paid_amount');
                                @endphp
                                <td class="border-none-left col" style="width:70%;"> <b>Total</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($invoice->total) }}</td>
                            </tr>
                            <tr class="tag-text">
                                <td class="border-none-left col" style="width:70%;"> <b>Advance</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($paid) }}</td>
                            </tr>
                            <tr class="tag-text">
                                <td class="border-none-left col" style="width:70%;"> <b>Balance</b> </td>
                                <td class="border-none1 col" style="width:30%;">{{ getFormattedCurrency($invoice->total - $paid) }}</td>
                            </tr>
                            <tr class="tag-text">
                                <td class="border-none-left col" colspan="2"> <b>
                                        <p class="text-uppercase text-purple font-weight-bold">For <span class="font-weight-bolder ms-50">{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}</span></p>
                                        <br /><br /><br />
                                        <p class="mb-0 text-capitalize text-xs" style=" font-size: 1rem;">Authorized Signatory</p>
                                    </b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br /><br /><br /><br /><br />
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
            <tr>
                <td style="width:50%" width="50%">
                    <p class="d-flex align-items-center text-uppercase font-weight-bold" style="text-align:center;">
                        Powered by {{ getApplicationName() }}
                    </p>
                </td>
            <tr>
        </table>
    </main>
</body>

</html>