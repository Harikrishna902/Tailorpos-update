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
             $expenses = \App\Models\Expense::whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->latest();
                if($branch != '')
                {
                    $expenses->where('created_by',$branch);
                }
                if($payment_mode != '')
                {
                    $expenses->where('payment_mode',$payment_mode);
                }
        $expenses = $expenses->get();
        @endphp
        <h3 class="fw-500 text-dark">Expense Report</h3>
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
                    <th class="text-primary w-table-15" scope="col">Expense Amount</th>
                    <th class="text-primary w-table-20" scope="col">Towards/Category</th>
                    <th class="text-primary w-table-10" scope="col">Tax %</th>
                    <th class="text-primary w-table-15" scope="col">Tax Amount</th>
                    <th class="text-primary w-table-15" scope="col">Payment Method</th>
                    <th class="text-primary w-table-15" scope="col">Branch</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totaltax = 0;
                @endphp
                @foreach ($expenses as $item)
                                    
                <tr class="tag-text">
                    <td class="w-table-10">{{$item->date->format('d/m/Y')}}</td>
                    <td class="w-table-15">{{getFormattedCurrency($item->amount)}}</td>
                    <td class="w-table-20">
                        {{$item->head->name}}
                    </td>
                    @php
                    $unitprice = $item->amount * (100 / (100 + $item->tax_percentage ?? 15));
                    $taxamount  = $item->amount - $unitprice;
                    if($item->tax_included == 0)
                    {
                        $taxamount = 0;
                    }
                    $totaltax += $taxamount;
                    @endphp
                    <td class="w-table-10">
                        @if($item->tax_included != 0)
                            {{$item->tax_percentage}}%
                        @else
                        -
                        @endif
                    </td>
                    <td class="w-table-15">{{getFormattedCurrency($taxamount)}}</td>
                    <td class="w-table-15">
                        <span class="text-uppercase">{{getPaymentMode($item->payment_mode)}}</span>
                    </td>
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
                    <span class="text-sm mb-0 fw-500">Total Expense:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{$expenses->sum('amount')}}</span>
                </td>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Tax:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getFormattedCurrency($totaltax)}}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>