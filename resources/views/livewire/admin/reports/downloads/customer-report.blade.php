<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ $lang->data['print_sales_report'] ?? 'Print Sales Report' }}</title>
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
            $period = \Carbon\CarbonPeriod::create($start_date, $end_date);
            $selected_customer = \App\Models\Customer::where('is_active', 1)
                ->where('id', $id)
                ->first();
            if ($selected_customer) {
                foreach ($period as $row) {
                    $invoice = \App\Models\Invoice::whereDate('date', $row->toDateString())
                        ->where('customer_id', $selected_customer->id)
                        ->latest()
                        ->get();
                    $payment = \App\Models\InvoicePayment::whereDate('date', $row->toDateString())
                        ->where('customer_id', $selected_customer->id)
                        ->latest()
                        ->get();
                    $discount = \App\Models\CustomerPaymentDiscount::whereDate('date', $row->toDateString())
                        ->where('customer_id', $selected_customer->id)
                        ->latest()
                        ->get();
                    if (count($invoice) > 0 || count($payment) > 0 || count($discount) > 0) {
                        $items = [
                            'invoice' => $invoice,
                            'payment' => $payment,
                            'discount' => $discount,
                        ];
                        $mycollection[$row->format('d/m/Y')] = $items;
                    }
                }
                $mycollection = array_reverse($mycollection);
            }
        @endphp
        <h3 class="fw-500 text-dark">Customer Report</h3>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{ $lang->data['start_date'] ?? 'Start Date' }}: </label>
                    {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                </td>
                <td class="col">
                    <label>{{ $lang->data['end_date'] ?? 'End Date' }}: </label>
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
        <br /> <br />
        @if ($selected_customer)
            <div class="card-body pb-1">
                <div class="row g-3">
                    <table width="100%">
                        <thead>
                            <th>Customer File Number</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Customer Adrress</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $selected_customer->file_number }}</td>
                                <td> {{ getCountryCode() }} {{ $selected_customer->phone_number_1 }} @if ($selected_customer->phone_number_2)
                                        , {{ getCountryCode() }} {{ $selected_customer->phone_number_2 }}
                                    @endif
                                </td>
                                <td>{{ $selected_customer->email ?? '-' }}</td>
                                <td>{{ $selected_customer->address ?? '-' }}</td>
                            </tr>

                        </tbody>
                    </table>
                    <br><br><br>
                    @php
                        $total = \App\Models\Invoice::where('customer_id', $selected_customer->id)->sum('total');
                        $paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)->sum('paid_amount');
                        $invoice_paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                            ->where('payment_type', 1)
                            ->sum('paid_amount');
                        $opening_received = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                            ->where('payment_type', 2)
                            ->sum('paid_amount');
                        $opening_balance = $selected_customer->opening_balance != '' ? $selected_customer->opening_balance : 0;
                        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id', $selected_customer->id)->sum('amount');
                    @endphp
                    <table>
                        <thead>
                            <th>Opening Balance</th>
                            <th>Total Invoices</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Payment Discount</th>
                            <th>Balance Amount</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ getFormattedCurrency($opening_balance) }}</td>
                                <td>{{ \App\Models\Invoice::where('customer_id', $selected_customer->id)->count() }}
                                </td>
                                <td>{{ getFormattedCurrency($total) }}</td>
                                <td>{{ getFormattedCurrency($paid) }}</td>
                                <td>{{ getFormattedCurrency($discount) }}</td>
                                <td>{{ getFormattedCurrency($total + $selected_customer->opening_balance - ($paid + $discount)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br><br><br>
            @php
                $total_invoices = 0;
                $total_sales = 0;
                $total_payments = 0;
                $total_discounts = 0;
            @endphp
            <table class="table table-bordered w-100">
                <thead class="bg-light text-xs">
                    <th class="text-primary" scope="col">Date</th>
                    <th class="text-primary"scope="col">Particulars</th>
                    <th class="text-primary" scope="col">Debit</th>
                    <th class="text-primary" scope="col">Credit</th>
                </thead>
                <tbody>
                    @foreach ($mycollection as $key => $value)
                        @foreach ($value['invoice'] as $item)
                            @php
                                $total_invoices++;
                                $total_sales += $item->total;
                            @endphp
                            <tr class="tag-text">
                                <td>{{ $key }}</td>
                                <td>
                                    <span class="me-1">Sales</span>
                                    <span>- #{{ $item->invoice_number }}</span>
                                </td>
                                <td>{{ getFormattedCurrency($item->total) }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        @foreach ($value['payment'] as $item)
                            @php
                                $total_payments += $item->paid_amount;
                            @endphp
                            <tr class="tag-text">
                                <td>{{ $key }}</td>
                                <td>
                                    <span class="me-1">Payment - #{{ $item->voucher_no }}</span>
                                </td>
                                <td></td>
                                <td>{{ getFormattedCurrency($item->paid_amount) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($value['discount'] as $item)
                            @php
                                $total_discounts += $item->amount;
                            @endphp
                            <tr class="tag-text">
                                <td>{{ $key }}</td>
                                <td>
                                    <span class="me-1">Payment Discount</span>
                                </td>
                                <td></td>
                                <td>{{ getFormattedCurrency($item->amount) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <br><br><br>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Total Invoices:</td>
                    <td>{{ $total_invoices }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Total Sales:</td>
                    <td class="fw-bold">{{ getFormattedCurrency($total_sales) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Total Payments:</td>
                    <td class="fw-bold">{{ getFormattedCurrency($total_payments) }}</td>
                </tr>
                <tr>

                    <td class="fw-bold">Payment Discounts:</td>
                    <td class="fw-bold">{{ getFormattedCurrency($total_discounts) }}</td>

                </tr>
            </table>
        @endif
        <br> <br>
    </body>

    </html>
</div>
