


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
                text-transform: uppercase;
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
            h3{
                text-align: center;
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
            $payments = \App\Models\SupplierPayment::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->latest();
                if($payment_mode != '')
                {
                    $payments->where('payment_mode',$payment_mode);
                }
                $payments = $payments->get();
        @endphp
        <h3 class="fw-500 text-dark">Purchase Payment Report</h3>
       
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col" style="padding: 0"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 0" class="col" style="text-align: end;">
                    <p style="text-align: end; padding: 0;">{{$lang->data['end_date'] ?? 'End Date'}}: 
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
                </td>
            </tr>
            <tr>
                <td class="col" style="padding: 0">
                    <p style="text-align: end; padding: 0;">Received Via: 
                        @if($payment_mode != '')
                        {{getPaymentMode($payment_mode)}}
                        @else
                        All
                        @endif</p>
                </td>
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="bg-light text-xs">
                <th class="text-primary w-table-20" scope="col">{{$lang->data['date'] ?? 'Date'}}</th>
                <th class="text-primary w-table-30" scope="col">{{$lang->data['supplier'] ?? 'Supplier'}}</th>
                <th class="text-primary w-table-25" scope="col">{{$lang->data['paid_amount'] ?? 'Paid Amount'}}</th>
                <th class="text-primary w-table-25" scope="col">{{$lang->data['payment_method'] ?? 'Payment Method'}}</th>
            </thead>
            <tbody>
                @foreach ($payments as $item)
                <tr class="tag-text">
                    <td class="w-table-20">{{\Carbon\Carbon::parse($item->date)->format('d/m/Y')}}</td>
                    <td class="w-table-30">
                        <span>{{$item->supplier_name}}</span>
                    </td>
                    <td class="w-table-25">{{getFormattedCurrency($item->paid_amount)}}</td>
                    <td class="w-table-25"><span class="text-uppercase">{{getPaymentMode($item->payment_mode)}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Payments:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{$payments->count()}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Amount Received:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($payments->sum('paid_amount'))}}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>