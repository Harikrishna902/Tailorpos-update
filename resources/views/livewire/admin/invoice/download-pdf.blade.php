<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        #main {
            border-collapse: collapse;
            line-height: 1rem;
            text-align: center;
        }

        th {
            background-color: rgb(101, 104, 101);
            Color: white;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            padding: 10px;
        }

        td {
            text-align: center;
           
            font-size: 0.75rem;
            line-height: 1rem;
        }

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
            line-height: 0.5rem;
        }

        .text-center {
            text-align: center;
        }

        .border-none {
            border: none;
            text-align: left;
            padding-left: 2px;
            line-height: 1.5rem;
            font-weight: bold;

        }

        .border-none1 {
            border: none;
            text-align: right;
            padding-right: 2px;
            line-height: 1.5rem;
            font-weight: bold;
        }

        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }
        .border-dashed {    
            border-bottom: 0.5px  gray dashed;
            line-height: 1.5rem;
        }
    </style>
</head>

<body onload="" class="center">
    @php
    $settings = new App\Models\MasterSetting();
    $site = $settings->siteData();
    $invoice = \App\Models\Invoice::find($id);
    $branch = \App\Models\User::find($invoice->created_by);
    @endphp


    <div class="page-wrapper">
        <div class="invoice-card padding-top">
            <div class="invoice-head text-center">
                <h4 class="text-center">{{ isset($site['company_name']) && !empty($site['company_name']) ? $site['company_name'] : '' }}
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
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr class="table-dark">
                            <th class="text-primary w-table-10" scope="col" colspan="2">SIMPLIFIED TAX INVOICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> {{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} No</b> : </td>
                            <td class="w-table-15 border-none1">{{ $site['company_tax_registration'] ?? 'No Tax' }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> Invoice No.</b> : </td>
                            <td class="w-table-15 border-none1">{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none">Date: </td>
                            <td class="w-table-15 border-none1">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y g:i A') }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> File No</b> : </td>
                            <td class="w-table-15 border-none1">{{ $invoice->customer_file_number }}</td>
                        </tr>
                    </tbody>
                </table>
                <br/>
                <table id="main" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr class="table-dark">
                            <th class="text-primary w-table-10" scope="col">item</th>
                            <th class="text-primary w-table-15" scope="col">rate</th>
                            <th class="text-primary w-table-20" scope="col">qty</th>
                            <th class="text-primary w-table-10" scope="col">total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $qty = 0;
                        @endphp
                        @foreach ($invoice->invoiceProductDetails as $row)

                        <tr class="tag-text">
                            <td class="w-table-10  border-dashed"> {{ $row->item_name }} </td>
                            <td class="w-table-15 border-dashed">{{ getFormattedCurrency($row->rate) }}</td>
                            <td class="w-table-20 border-dashed">
                                {{ $row->quantity }} @if ($row->type == 2)
                                {{ getUnitType($row->unit_type ?? '') }}
                                @endif
                            </td>
                            @endphp
                            <td class="w-table-10 border-dashed">
                                {{ getFormattedCurrency($row->total, 2) }}
                            </td>
                            @php
                            $qty = $qty + $row->product_quantity;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>
    <br/>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none "> <b> Sub Total</b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->sub_total) }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b>Discount</b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->discount) }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b>Taxable Amount</b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->taxable_amount) }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b> Total {{isset($site['default_tax_name']) ? $site['default_tax_name'] : 'GST'}} ({{ $invoice->tax_percentage }}%)</b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->tax_amount) }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b>Total </b>  </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->total) }}</td>
                            @php
                        $paid = \App\Models\InvoicePayment::where('invoice_id', $invoice->id)->sum('paid_amount');
                        @endphp
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b>Advance</b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($paid) }}</td>
                        </tr>
                        <tr class="tag-text">
                            <td class="w-table-10 border-none"> <b>Balance </b> </td>
                            <td class="w-table-15 border-none1">{{ getFormattedCurrency($invoice->total - $paid) }}</td>
                        </tr>
                    </tbody>
                </table>
                <br />
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