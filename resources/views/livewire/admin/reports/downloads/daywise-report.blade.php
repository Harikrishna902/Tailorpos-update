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
             $mycollection = [];
        $start_date = \Carbon\Carbon::parse($start_date);
        $end_date = \Carbon\Carbon::parse($end_date);
        $period = \Carbon\CarbonPeriod::create($start_date,$end_date);
        $gross_expense = 0;
        $gross_payments = 0;
        $gross_sales = 0;
        $gross_invoices = 0;
        foreach($period as $row)
        {
            $no_of_invoices = \App\Models\Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = \App\Models\Invoice::whereDate('date',$row->toDateString())->latest();
            $payment_received = \App\Models\InvoicePayment::whereDate('date',$row->toDateString())->latest();
            $total_expense = \App\Models\Expense::whereDate('date',$row->toDateString())->latest();
            if($branch != '')
            {
                $no_of_invoices->where('created_by',$branch);
                $total_sales->where('created_by',$branch);
                $payment_received->where('created_by',$branch);
                $total_expense->where('created_by',$branch);
            }
            $no_of_invoices = $no_of_invoices->count();
            $total_sales = $total_sales->sum('total');
            $payment_received = $payment_received->sum('paid_amount');
            $total_expense = $total_expense->sum('amount');
            $gross_expense += $total_expense;
            $gross_payments += $payment_received;
            $gross_sales += $total_sales;
            $gross_invoices += $no_of_invoices;
            if($no_of_invoices!= 0 || $total_sales != 0 || $payment_received != 0 || $total_expense != 0 )
            {
                $items = [
                    'invoices'  => $no_of_invoices,
                    'sales' => getFormattedCurrency($total_sales),
                    'payments'  => getFormattedCurrency($payment_received),
                    'expense'   => getFormattedCurrency($total_expense)
                ];
                $mycollection[$row->format('d/m/Y')] = $items;
            }
        }
        @endphp
        <h3 class="fw-500 text-dark">Day-Wise Report</h3>
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
                    <th class="text-primary w-table-15" scope="col">Date</th>
                    <th class="text-primary w-table-20" scope="col">Invoices</th>
                    <th class="text-primary w-table-25" scope="col">Sales Total</th>
                    <th class="text-primary w-table-20" scope="col">Payments Received</th>
                    <th class="text-primary w-table-20" scope="col">Expense Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mycollection as $key => $item)
                                    
                <tr class="tag-text">
                    <td class="w-table-15">{{$key}}</td>
                    <td class="w-table-20">{{$item['invoices']}}</td>
                    <td class="w-table-25">{{$item['sales']}}</td>
                    <td class="w-table-20">{{$item['payments']}}</td>
                    <td class="w-table-20">{{$item['expense']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Invoices:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{$gross_invoices}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Sales:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($gross_sales)}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Payments:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($gross_payments)}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Expenses:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($gross_expense)}}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>