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
            $invoices = \App\Models\Invoice::whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)
                ->latest();
            if ($staff != '') {
                $invoices->where('salesman_id', $staff);
            } else {
                $staff_ids = \App\Models\User::whereIn('user_type', [4])
                    ->whereBranchId(Auth::user()->id)
                    ->pluck('id');
                $invoices->whereIn('salesman_id', $staff_ids);
            }
            $invoices = $invoices->get();
        @endphp
        <h3 class="fw-500 text-dark">Staff Report</h3>

        <h3>[{{ Auth::user()->name }}]</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{ $lang->data['start_date'] ?? 'Start Date' }}: </label>
                    {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                </td>
                <td class="col">
                    <label>{{ $lang->data['end_date'] ?? 'End Date' }}: </label>
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </td>
                @if ($staff != '')
                    @php
                        $staffUser = \App\Models\User::where('id', $staff)->first();
                    @endphp
                    <td class="col">
                        <label>{{ $lang->data['staff'] ?? 'Staff' }}: </label>
                        {{ $staffUser->name }}
                    </td>
                @else
                    <td class="col">
                        <label>{{ $lang->data['staff'] ?? 'Staff' }}: </label>
                        All Staff
                    </td>
                @endif
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th class="text-primary w-table-10" scope="col">Date</th>
                    <th class="text-primary w-table-10" scope="col">Invoice #</th>
                    <th class="text-primary w-table-20" scope="col">Customer</th>
                    <th class="text-primary w-table-15" scope="col">Taxable Amount</th>
                    <th class="text-primary w-table-10" scope="col">Discount</th>
                    <th class="text-primary w-table-10" scope="col">Tax Amount</th>
                    <th class="text-primary w-table-15" scope="col">Gross Total</th>
                    @if (!$staff)
                        <th class="text-primary w-table-10" scope="col">Staff</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $item)
                    <tr class="tag-text">
                        <td class="w-table-10">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td class="w-table-10">#{{ $item->invoice_number }}</td>
                        <td class="w-table-20">
                            <span class="me-1">[{{ $item->customer_file_number ?? '' }}]</span>
                            <span>{{ $item->customer_name ?? '' }}</span>
                        </td>
                        <td class="w-table-15">{{ getFormattedCurrency($item->taxable_amount) }}</td>
                        <td class="w-table-10">{{ getFormattedCurrency($item->discount) }}</td>
                        <td class="w-table-10">{{ getFormattedCurrency($item->tax_amount) }}</td>
                        <td class="w-table-15">{{ getFormattedCurrency($item->total) }}</td>
                        @if (!$staff)
                            <td class="w-table-10">
                                <span
                                    class="badge bg-secondary text-uppercase">{{ $item->salesman->name ?? '' }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Invoices:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{ $invoices->count() }}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Sales:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getFormattedCurrency($invoices->sum('total') ?? 0) }}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Discount:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getFormattedCurrency($invoices->sum('discount')) }}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">Total Tax:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getFormattedCurrency($invoices->sum('tax_amount')) }}</span>
                </td>
            </tr>
        </table>
    </body>

    </html>
</div>
