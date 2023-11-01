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
            h2{
                color: #246961;
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
            .col {
                border: none;
                text-align: left;
                padding: 10px;
                font-size: 0.75rem;
                line-height: 1rem;
            }
            .row {
                display: flex;
                width: 100%;
                align-items: center;
                justify-content: center;
            }
            p{
                padding: 0px;
                margin: 0px;
            }
        </style>
    </head>
    <body onload="">
        @php
                $no_of_invoices = \App\Models\Invoice::whereDate('date',$date)->latest();
                $total_sales = \App\Models\Invoice::whereDate('date',$date)->latest();
                $payment_received = \App\Models\InvoicePayment::whereDate('date',$date)->latest();
                $total_expense = \App\Models\Expense::whereDate('date',$date)->latest();
                $invoices = \App\Models\Invoice::whereDate('date',$date)->latest();
                if($branch != '')
                {
                    $no_of_invoices->where('created_by',$branch);
                    $total_sales->where('created_by',$branch);
                    $payment_received->where('created_by',$branch);
                    $total_expense->where('created_by',$branch);
                    $invoices->where('created_by',$branch);
                }   
                $invoices = $invoices->get();
                $no_of_invoices = $no_of_invoices->count();
                $total_sales = $total_sales->sum('total');
                $payment_received = $payment_received->sum('paid_amount');
                $total_expense = $total_expense->sum('amount');
        @endphp
        <h2 class="fw-500 text-dark text-center" style="text-align: center">Daily Sales Report</h2>
        <div class="">
            <p style="font-size: .7rem !important;"> <label>Date: </label>
          {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                
        </div>
        <div class="">
            <p style="font-size: .7rem !important;"> 
            @if($branch != '')
                {{$lang->data['branch'] ?? 'Branch'}}: 
                {{ \App\Models\User::find($branch)->name ?? '' }}
            
            @else

                {{$lang->data['branch'] ?? 'Branch'}}: 
                    All Branches
           
            @endif
            </p>
                
        </div>
        <h4>Daily Sales</h4>

        <div class="table-responsive mt-0 mb-3 ">
            <table class="table table-bordered" width="100%" cellpadding="0">
                <thead class="bg-light">
                    <th class="text-primary text-xs" scope="">Invoice No</th>
                    <th class="text-primary text-xs" scope="">File No.</th>
                    <th class="text-primary text-xs" scope="">Qty</th>
                    <th class="text-primary text-xs" scope="">Amount</th>
                    <th class="text-primary text-xs" scope="">Payment</th>
                    <th class="text-primary text-xs" scope="">Balance</th>
                </thead>
                <tbody>
                    @php
                        $total_balance = 0;
                        $total_payment = 0;
                    @endphp
                    @foreach ($invoices as $item)
                    <tr>
                        <td>
                            <div class="mb-0 fw-bold text-xs">#{{$item->invoice_number}}</div>

                        </td>
                        <td>
                            <div class="mb-0 text-xs">{{$item->customer_file_number}}</div>
                        </td>
                        <td >
                            @php
                            $localquantity = \App\Models\InvoiceDetail::where('invoice_id',$item->id)->sum('quantity');
                        @endphp
                        <div class="mb-0 ">{{$localquantity}}</div>
                            
                        </td>
                        <td >
                            <div class="mb-0 text-xs">{{getFormattedCurrency($item->total)}}</div>
                        </td>
                        <td >
                            @php
                                $payment = \App\Models\InvoicePayment::where('invoice_id',$item->id)->sum('paid_amount');
                                $total_balance+= $item->total - $payment;
                                $total_payment+= $payment;
                            @endphp
                            <div class="mb-0 text-xs">{{getFormattedCurrency($payment)}}</div>
                            
                        </td>
                        <td >
                            <div class="mb-0 text-xs">{{getFormattedCurrency($item->total - $payment)}}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row g-3 align-items-center justify-content-between px-4 mb-2">
            <table style="width: 100%; margin-top: 1rem; margin-bottom : 1rem;">
                <tbody>
                    <td>
                        <div class="col-2 ">
                            <div class="">
                                <div class="fw-bold text-xs">Total Amount:</div>
                                <div class="fw-bold text-xs">{{getFormattedCurrency($invoices->sum('total'))}}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="col-2">
                            <div class="">
                                <div class="fw-bold text-xs">Total Payment : </div>
                                <div class="fw-bold text-xs">{{getFormattedCurrency($total_payment)}}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="col-2">
                            <div class="">
                                <div class="fw-bold text-xs">Total Balance:</div>
                                <div class="fw-bold text-xs">{{getFormattedCurrency($total_balance)}}</div>
                            </div>
                        </div>
                    </td>
                </tbody>
            </table>
        </div>
        <hr>
        <h4>Other Daily Data</h4>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th class="text-primary" scope="col">Particulars</th>
                    <th class="text-primary" scope="col">Value</th>
                    <th class="text-primary" scope="col">...</th>
                    <th class="text-primary" scope="col">...</th>
                </tr>
            </thead>
            <tbody>
                @php
                if($branch== '')
                {
                    $opening_balance = \App\Models\InvoicePayment::whereDate('date',$date)->where('payment_type',2)->sum('paid_amount');
                    $all_payment = \App\Models\InvoicePayment::whereDate('date',$date)->where('payment_type',1)->sum('paid_amount');
                    $max_payment = \App\Models\InvoicePayment::whereDate('date',$date)->sum('paid_amount');
                }
                else{
                    $opening_balance = \App\Models\InvoicePayment::whereCreatedBy($branch)->whereDate('date',$date)->where('payment_type',2)->sum('paid_amount');
                    $all_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)->whereDate('date',$date)->where('payment_type',1)->sum('paid_amount');
                    $max_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)->whereDate('date',$date)->sum('paid_amount');
                }
            @endphp
            <tr>
                <td class="text-xs" scope="row">Number of Invoices </td>
                <td class="">
                    <div class="mb-0 fw-bold text-xs">{{$no_of_invoices}}</div>
                </td>
                <td class="text-xs">Opening Balance Received</yd>
                <td class="">
                    <div class=" fw-bold text-xs">{{getFormattedCurrency($opening_balance)}}</div>
                </td>
            </tr>
            <tr>
                <td class="text-xs" scope="row">Total Sales </td>
                <td class="">
                    <div class="mb-0 fw-bold text-xs">{{getFormattedCurrency($total_sales)}}</div>
                </td>
                <td class="text-xs" scope="row">Old Invoice Balance Received</td>
                <td class="">
                    <div class="mb-0 fw-bold text-xs">{{getFormattedCurrency($all_payment - $total_payment)}}</div>
                </td>
            </tr>
            <tr>
                <td class="text-xs" scope="row">Total Expense </td>
                <td class="">
                    <div class="mb-0 fw-bold text-xs">{{getFormattedCurrency($total_expense)}}</div>
                </td>
                <td class="text-xs" scope="row">Total Payment Received</td>
                <td class="">
                    <div class="mb-0 fw-bold text-xs">{{getFormattedCurrency($max_payment)}}</div>
                </td>
            </tr>
            </tbody>
        </table>
        <br> <br>
    </body>
    </html>
</div>