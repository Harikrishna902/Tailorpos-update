<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Daily Report</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
        <style>
            body {
                padding: 15px;
            }

            thead {
                display: table-row-group;
            }

            * {
                color: black !important;
                font-weight: 1000 !important;
                /*   font-size: calc(100% + 1px); */
            }
        </style>
    </head>
    <body onload="">
        <div class="row align-items-center justify-content-between mb-4">
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['daily_sales_report'] ?? 'Daily Sales Report' }}
                </h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between  mt-3">
                    <div class="col-4">
                        <p class="text-sm p-0 m-0">{{ $lang->data['date'] ?? 'Date' }}:
                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        </p>
                        @if ($branch != '')
                            <p class="text p-0 text-sm">{{ $lang->data['branch'] ?? 'Branch' }}:
                                {{ \App\Models\User::find($branch)->name ?? '' }}
                            </p>
                        @else
                            <p class="text text-sm p-0">{{ $lang->data['branch'] ?? 'Branch' }}:
                                All Branches
                            </p>
                        @endif
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-body p-0">
                        <div class="row px-4 mt-3 align-items-center mb-2">
                            <div class="col">
                                <h7>Daily Sales</h7>
                            </div>
                        </div>
                        <div class="table-responsive mt-0 mb-3">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <th class="text-primary text-xs" scope="col">Invoice No</th>
                                    <th class="text-primary text-xs" scope="col">File No.</th>
                                    <th class="text-primary text-xs" scope="col">Qty</th>
                                    <th class="text-primary text-xs" scope="col">Amount</th>
                                    <th class="text-primary text-xs" scope="col">Payment</th>
                                    <th class="text-primary text-xs" scope="col">Balance</th>
                                </thead>
                                <tbody>
                                    @php
                                        $total_balance = 0;
                                        $total_payment = 0;
                                    @endphp
                                    @foreach ($invoices as $item)
                                        <tr>
                                            <td>
                                                <div class="mb-0   text-xs">#{{ $item->invoice_number }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 text-xs">{{ $item->customer_file_number }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $localquantity = \App\Models\InvoiceDetail::where('invoice_id', $item->id)->sum('quantity');
                                                @endphp
                                                <div class="mb-0 text-xs">{{ $localquantity }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 text-xs">{{ getFormattedCurrency($item->total) }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $payment = \App\Models\InvoicePayment::where('invoice_id', $item->id)->sum('paid_amount');
                                                    $total_balance += $item->total - $payment;
                                                    $total_payment += $payment;
                                                @endphp
                                                <div class="mb-0 text-xs">{{ getFormattedCurrency($payment) }}</div>

                                            </td>
                                            <td>
                                                <div class="mb-0 text-xs">
                                                    {{ getFormattedCurrency($item->total - $payment) }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row g-3 align-items-center justify-content-between px-4 mb-2">
                            <div class="col-2 ">
                                <div class="">
                                    <div class="  text-xs">Total Amount:</div>
                                    <div class="  text-xs">{{ getFormattedCurrency($invoices->sum('total')) }}</div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="">
                                    <div class="  text-xs">Total Payment : </div>
                                    <div class="  text-xs">{{ getFormattedCurrency($total_payment) }}</div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="">
                                    <div class="  text-xs">Total Balance:</div>
                                    <div class="  text-xs">{{ getFormattedCurrency($total_balance) }}</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4 mt-4 align-items-center mb-2">
                            <div class="col">
                                <h7>Other Data</h7>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <th class="text-primary text-xs" scope="col">Particulars</th>
                                    <th class="text-primary text-xs" scope="col">Value</th>
                                    <th class="text-primary text-xs" scope="">...&nbsp;</th>
                                    <th class="text-primary text-xs" scope=" ">...</th>
                                </thead>
                                <tbody>
                                    @php
                                        if ($branch == '') {
                                            $opening_balance = \App\Models\InvoicePayment::whereDate('date', $date)
                                                ->where('payment_type', 2)
                                                ->sum('paid_amount');
                                            $all_payment = \App\Models\InvoicePayment::whereDate('date', $date)
                                                ->where('payment_type', 1)
                                                ->sum('paid_amount');
                                            $max_payment = \App\Models\InvoicePayment::whereDate('date', $date)->sum('paid_amount');
                                        } else {
                                            $opening_balance = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->where('payment_type', 2)
                                                ->sum('paid_amount');
                                            $all_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->where('payment_type', 1)
                                                ->sum('paid_amount');
                                            $max_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->sum('paid_amount');
                                        }
                                    @endphp
                                    <tr>
                                        <th class="text-xs" scope="row">Number of Invoices </th>
                                        <td class="">
                                            <div class="mb-0   text-xs">{{ $no_of_invoices }}</div>
                                        </td>
                                        <th class="text-xs">Opening Balance Received</th>
                                        <td class="">
                                            <div class="   text-xs">{{ getFormattedCurrency($opening_balance) }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-xs" scope="row">Total Sales </th>
                                        <td class="">
                                            <div class="mb-0   text-xs">{{ getFormattedCurrency($total_sales) }}</div>
                                        </td>
                                        <th class="text-xs" scope="row">Old Invoice Balance Received</th>
                                        <td class="">
                                            <div class="mb-0   text-xs">
                                                {{ getFormattedCurrency($all_payment - $total_payment) }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-xs" scope="row">Total Expense </th>
                                        <td class="">
                                            <div class="mb-0   text-xs">{{ getFormattedCurrency($total_expense) }}
                                            </div>
                                        </td>
                                        <th class="text-xs" scope="row">Total Payment Received</th>
                                        <td class="">
                                            <div class="mb-0   text-xs">{{ getFormattedCurrency($max_payment) }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
    </body>
    </html>
</div>