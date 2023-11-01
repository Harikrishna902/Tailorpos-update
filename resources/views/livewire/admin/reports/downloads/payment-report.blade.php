


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
                border: 1px solid;
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
        </style>
    </head>
    <body onload="">
        @php
            $payments = \App\Models\InvoicePayment::whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->latest();
                if($branch != '')
                {
                    $payments->where('created_by',$branch);
                }
                if($payment_mode != '')
                {
                    $payments->where('payment_mode',$payment_mode);
                }
                $payments = $payments->orderBy('voucher_no','asc')->get();
        @endphp
        <h3 class="fw-500 text-dark">Customer Payment Report</h3>
        @if($branch != '')
           @php
           $branchUser = \App\Models\User::where('id',$branch)->first();
           @endphp
           <h3>[{{
            $branchUser->name;
           }}]</h3>
        @endif
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                </td>
                <td class="col">
                    <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th class="text-primary w-table-8"  scope="col">Voucher No</th>
                    <th class="text-primary w-table-15" scope="col">Date</th>
                    <th class="text-primary w-table-25" scope="col">Customer</th>
                    <th class="text-primary w-table-20" scope="col">Paid Amount</th>
                    <th class="text-primary w-table-20" scope="col">Payment Method</th>
                    <th class="text-primary w-table-15" scope="col">{{$lang->data['payment_type']??'Payment Type'}}</th>

                    <th class="text-primary w-table-15" scope="col">Branch</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $item)
                <tr class="tag-text">
                    <td style="width: 8%">#{{$item->voucher_no}}</td>
                    <td class="w-table-15">{{$item->date->format('d/m/Y')}}</td>
                    <td class="w-table-25">
                        <span class="me-1">[{{$item->customer->file_number ?? ''}}]</span>
                        <span>{{$item->customer->first_name ?? ''}}</span>
                    </td>
                    <td class="w-table-20">{{getFormattedCurrency($item->paid_amount)}}</td>
                    <td class="w-table-20"><span class="text-uppercase">{{getPaymentMode($item->payment_mode)}}</span></td>
                    @if($item->payment_type == 1)
                    <td class="w-table-15">
                        <div class="mb-0 text-uppercase">{{$lang->data['invoice']??'Invoice'}}</div>
                        <div class="mt-50 text-xs fw-bold">#{{$item->invoice->invoice_number ?? ''}}</div>
                    </td>
                    @elseif($item->payment_type == 2)
                    <td class="w-table-15">
                        <div class="mb-0 text-uppercase">{{$lang->data['opening_balance']??'Opening Balance'}}</div>
                    </td>
                    @elseif($item->payment_type == 3)
                    <td class="w-table-15">
                        <div class="mb-0 text-uppercase">{{$lang->data['cash_receipt']??'Cash Receipt'}}</div>
                    </td>
                    @endif
                    <td class="w-table-15">
                        <span class="badge bg-secondary text-uppercase">{{$item->createdBy->name ?? ''}}</span>
                    </td>
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