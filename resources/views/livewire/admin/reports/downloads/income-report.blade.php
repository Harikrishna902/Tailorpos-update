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
        $expensecollect = [];
        $start_date = \Carbon\Carbon::parse($start_date);
        $end_date = \Carbon\Carbon::parse($end_date);
        $period = \Carbon\CarbonPeriod::create($start_date,$end_date);
        $gross_expense = 0;
        $gross_sales = 0;
        $net_income = 0;
        foreach($period as $row)
        {
            $no_of_invoices = \App\Models\Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = \App\Models\Invoice::whereDate('date',$row->toDateString())->latest();
            $total_expense = \App\Models\Expense::whereDate('date',$row->toDateString())->latest();
            $no_of_expense = \App\Models\Expense::whereDate('date',$row->toDateString())->latest()->count();
            $no_of_invoices1 = $no_of_invoices->count();
            $total_sales1 = $total_sales->sum('total');
            $total_expense1 = $total_expense->sum('amount');
            $gross_expense += $total_expense1;
            $gross_sales += $total_sales1;
            if($no_of_expense!= 0 || $total_expense1 != 0  )
            {
                $items = [
                    'no'  => $no_of_expense,
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $expensecollect[$row->format('d/m/Y')] = $items;
            }
            if($no_of_invoices1!= 0 || $total_sales1 != 0  )
            {
                $items = [
                    'invoices'  => $no_of_invoices1,
                    'sales' => getFormattedCurrency($total_sales1),
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $mycollection[$row->format('d/m/Y')] = $items;
            }
            
        }
        $net_income = $gross_sales - $gross_expense;
        @endphp
        <h3 class="fw-500 text-dark">Income Report</h3>
        @if($branch != '')
           @php
           $branchUser = \App\Models\User::where('id',$branch)->first();
           @endphp
           <h3>[{{
            $branchUser->name;
           }}]</h3>
        @endif
        <h5>Net Sales</h5>
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
                    <th class="text-primary w-table-25" scope="col">Date</th>
                    <th class="text-primary w-table-25" scope="col">No. of Invoices</th>
                    <th class="text-primary w-table-50"  scope="col">Invoice Total</th>
                </tr>
            </thead>
            <tbody>
                @if(count($mycollection) > 0)
                @foreach ($mycollection as $key => $item)
                    
                <tr class="tag-text">
                    <td class="w-table-25">{{$key}}</td>
                    <td class="w-table-25">{{$item['invoices']}}</td>
                    <td class="w-table-50">{{$item['sales']}}</td>
                </tr>
                @endforeach
                @else
                <tr class="tag-text">
                    <td class="w-table-25">-</td>
                    <td class="w-table-25">-</td>
                    <td class="w-table-50">-</td>
                </tr>
                @endif
            </tbody>
        </table>
        <br> <br>
        <h5 class="fw-500 text-dark">Net Expense</h5>
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
                    <th class="text-primary w-table-25" scope="col">Date</th>
                    <th class="text-primary w-table-25" scope="col">No. of Expense</th>
                    <th class="text-primary w-table-50"  scope="col">Expense Total</th>
                </tr>
            </thead>
            <tbody>
                @if(count($expensecollect) > 0)
                @foreach ($expensecollect as $key => $item)
                <tr class="tag-text">
                    <td class="w-table-25">{{$key}}</td>
                    <td class="w-table-25">{{$item['no']}}</td>
                    <td class="w-table-50">{{$item['expense']}}</td>
                </tr>
                @endforeach
                @else
                <tr class="tag-text">
                    <td class="w-table-25">-</td>
                    <td class="w-table-25">-</td>
                    <td class="w-table-50">-</td>
                </tr>
                @endif
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Sales:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($gross_sales)}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Expense:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($gross_expense)}}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Net Income:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($net_income)}}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>