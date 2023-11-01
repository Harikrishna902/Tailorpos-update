<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{__('main.print')}}</title>
        <link rel="stylesheet" href="{{ asset('assets/css/thermal-print-style.css') }}">
    </head>
    <body onload="">
        @php
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            $branch = \App\Models\User::find($invoice->created_by);
        @endphp
        <div class="page-wrapper">
            <div class="invoice-card padding-top">
                <div class="invoice-head text-center">
                    <h4>{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}
                    </h4>
                    @if(Auth::user()->user_type != 2)
                        <p class="my-0">{{ $branch->address ?? '' }} </p>
                        @if ($branch->phone)
                            <p class="my-0">{{ getCountryCode() }} {{ $branch->phone ?? '' }}</p><br>
                        @endif
                    @else
                        @if (isset($site['company_mobile']) && !empty($site['company_mobile']))
                            <p class="my-0">{{ getCountryCode() }} {{ isset($site['company_mobile']) ? $site['company_mobile'] : ''}}</p><br>
                        @endif
                    @endif
                </div>
                <div class="invoice-details b-t-0">
                    <div class="invoice-list">
                        <div class="invoice-title-center">
                            <h4 class="heading">{{__('print.thermal_title')}}</h4>
                        </div>
                        <div class="row-data b-none mb-1-px">
                            <div class="item-info b-none mt-10" >
                                <h5 class="item-title"><b>{{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} No</b> :</h5>
                            </div>
                            <h5 class="">{{ $site['company_tax_registration'] ?? 'No Tax' }}</h5>
                        </div>
                        <div class="row-data mt-2 b-none" >
                            <div class="item-info b-none mt-10" >
                                <h5 class="item-title"><b>{{__('main.invoice_no')}}</b>: </h5>
                            </div>
                            <h5 class="5">{{ $invoice->order_number }}</h5>
                        </div>
                        <div class="row-data mt-2 b-none " >
                            <div class="item-info b-none mt-10" >
                                <h5 class="item-title"><b>{{__('main.preferred_delivery_date')}}</b>: </h5>
                            </div>
                            <h5 class="my-1">{{ \Carbon\Carbon::parse($invoice->preferred_delivery_time)->format('d/m/Y g:i A') }}</h5>
                        </div>  
                        <div class="row-data b-none">
                            <div class="item-info b-none mt-10" >
                                <h5 class="item-title"><b>{{__('main.date')}}</b> : </h5>
                            </div>
                            <h5 class="">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y g:i A') }}</h5>
                        </div>
                        <div class="invoice-title text-align-end">
                            <h6 class="heading1">{{__('print.item')}}</h6>
                            <h6 class="heading1">{{__('main.rate')}}</h6>
                            <h6 class="heading1 heading-child">{{__('main.qty')}}</h6>
                            <h6 class="heading1 heading-child">{{__('main.total')}}</h6>
                        </div>
                        @php
                            $qty = 0;
                        @endphp
                        @foreach ($invoice->details as $row)
                            <div class="row-data product-detail-container">
                                <div class="product-detail-item-name">
                                    <h5 class="my-5"><b>{{ $row->item_name }} </b></h5>
                                </div>
                                <div class="w-50-px">
                                    <h5 class="my-5"><b>{{ getFormattedCurrency($row->rate) }} </b></h5>
                                </div>
                                <div class="w-50-px">
                                    <h5 class="my-5"><b>{{ $row->quantity }} @if ($row->type == 2)
                                                {{ getUnitType($row->unit_type ?? '') }}
                                            @endif
                                        </b></h5>
                                </div>
                                <div class="w-58-px">
                                    <h5 class="my-5"><b>{{ getFormattedCurrency($row->total, 2) }}</b></h5>
                                </div>
                                @php
                                    $qty = $qty + $row->product_quantity;
                                @endphp
                            </div>
                        @endforeach
                    </div>
                    <div class="invoice-footer mb-15 ">
                        <div class="row-data">
                            <div class="item-info">
                                <h5 class="item-title"> <b>{{__('main.sub_total')}}</b> : </h5>
                            </div>
                            <h5 class="my-5">
                                {{ getFormattedCurrency($invoice->sub_total) }}
                            </h5>
                        </div>
                       
                        <div class="row-data mb-5">
                            <div class="item-info">
                                <h5 class="item-title"> <b>{{__('main.taxable_amount')}}</b> : </h5>
                            </div>
                            <h5 class="my-5">
                                {{ getFormattedCurrency($invoice->taxable_amount) }}
                            </h5>
                        </div>
                        <div class="row-data">
                            <div class="item-info">
                                <h5 class="item-title"> <b>{{__('main.total')}} {{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} ({{ $invoice->tax_percentage }}%)</b> :</h5>
                            </div>
                            <h5 class="my-5">{{ getFormattedCurrency($invoice->tax_amount) }}</h5>
                        </div>
                        <div class="row-data">
                            <div class="item-info">
                                <h5 class="item-title"> <b>{{__('main.total')}} </b> : </h5>
                            </div>
                            <h5 class="my-5">{{ getFormattedCurrency($invoice->total) }}</h5>
                        </div>
                      
                    </div>
                    <div class="invoice_address">
                        <div class="text-center">
                        </div>
                        <div class="text-center">
                            <p class="mt-10">
                                {{ isset($site['default_thanks_message']) && !empty($site['default_thanks_message']) ? $site['default_thanks_message'] : '' }}
                            </p>
                            <p class="b_top">{{__('main.powered_by')}} <b>{{ getApplicationName() }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
    </body>
    </html>
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