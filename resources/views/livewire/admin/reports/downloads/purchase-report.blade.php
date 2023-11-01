<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['print_sales_report'] ?? 'Print Sales Report'}}</title>
        <style>
            #main {
                border-collapse: collapse;
                line-height: 1rem;
                text-align: center;
            }
            th {
                background-color: #e6edef;
                font-size: 0.75rem;
                line-height: 1rem;
                font-weight: bold;
                text-align: left;
                padding: 10px;
            }
            td {
                text-align: left;
                border: 1px solid;
                font-size: 0.75rem;
                line-height: 1rem;
                padding: 10px;
            }
            .col {
                border: none;
                text-align: left;
                padding: 10px;
                font-size: 0.75rem;
                line-height: 1rem;
            }
        </style>
    </head>
    <body onload="">
        @php
             $purchases = \App\Models\Purchase::whereDate('purchase_date','>=',$start_date)->whereDate('purchase_date','<=',$end_date)->where('purchase_type',2)->latest()->get();
               
        @endphp
        <h3 class="fw-500 text-dark">Purchase Report</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>Start Date: </label>
                    {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                </td>
                <td class="col">
                    <label>End Date: </label>
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th class="text-primary w-table-10" scope="col">Date</th>
                    <th class="text-primary w-table-10" scope="col">Purchase#</th>
                    <th class="text-primary w-table-20" scope="col">Supplier</th>
                    <th class="text-primary w-table-15" scope="col">Taxable Amount</th>
                    <th class="text-primary w-table-10" scope="col">Discount</th>
                    <th class="text-primary w-table-10" scope="col">Tax Amount</th>
                    <th class="text-primary w-table-15" scope="col">Gross Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $item)
                <tr class="tag-text">
                    <td class="w-table-10">{{Carbon\Carbon::parse($item->purchase_date)->format('d/m/Y')}}</td>
                    <td class="w-table-10">{{$item->purchase_number}}</td>
                    <td class="w-table-20">
                        <span>{{$item->supplier->name??""}}</span>
                    </td>
                    <td class="w-table-15">{{getFormattedCurrency($item->sub_total)}}</td>
                    <td class="w-table-10">{{getFormattedCurrency($item->discount)}}</td>
                    <td class="w-table-10">{{getFormattedCurrency($item->tax_amount)}}</td>
                    <td class="w-table-15">{{getFormattedCurrency($item->total)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Purchases:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{$purchases->count()}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Amount:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($purchases->sum('total'))}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Discount:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($purchases->sum('discount'))}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Tax:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($purchases->sum('tax_amount'))}}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>