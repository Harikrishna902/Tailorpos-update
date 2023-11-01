<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Expense Report</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
        <style>
            thead {
                display: table-row-group;
            }
            * {
                color: black !important;
                font-weight: 1000 !important;
                font-size: calc(100%);
            }
        </style>
    </head>

    <body onload="">
        <div class="row align-items-center justify-content-between mb-4">
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['expense_report'] ?? 'Expense Report' }}</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        <p class="text-xs">{{ $lang->data['start_date'] ?? 'Start Date' }}:
                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        </p>

                        @if ($branch != '')
                            <p class="text-xs">{{ $lang->data['branch'] ?? 'Branch' }}:
                                {{ \App\Models\User::find($branch)->name ?? '' }}
                            </p>
                        @else
                            <p class="text-xs">{{ $lang->data['branch'] ?? 'Branch' }}:
                                All Branches
                            </p>
                        @endif
                    </div>
                    <div class="col-4">
                        <p class="text-xs ">{{ $lang->data['end_date'] ?? 'End Date' }}:
                            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                        </p>
                        <p class=" text-xs">{{ $lang->data['recevied_via'] ?? 'Received Via' }}:
                            @if ($payment_mode != '')
                                {{ getPaymentMode($payment_mode) }}
                            @else
                                All
                            @endif
                        </p>
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0">
                        <div class="table mt-0">
                            <table class="table table-bordered">
                                <thead class="text-dark  text-xs">
                                    <th class="text-dark w-table-10"  scope="col">Date</th>
                                    <th class="text-dark w-table-15"  scope="col">Expense Amount</th>
                                    <th class="text-dark w-table-20"  scope="col">Towards/Category</th>
                                    <th class="text-dark w-table-10"  scope="col">Tax %</th>
                                    <th class="text-dark w-table-15"  scope="col">Tax Amount</th>
                                    <th class="text-dark w-table-15"  scope="col">Payment Method</th>
                                    <th class="text-dark w-table-15"  scope="col">Branch</th>
                                </thead>
                                <tbody>
                                    @php
                                        $totaltax = 0;
                                    @endphp
                                    @foreach ($expenses as $item)
                                        <tr class="tag-text">
                                            <td class="w-table-10">{{ $item->date->format('d/m/Y') }}</td>
                                            <td class="w-table-15">{{ getFormattedCurrency($item->amount) }}</td>
                                            <td class="w-table-20">
                                                {{ $item->head->name }}
                                            </td>
                                            @php
                                                $unitprice = $item->amount * (100 / (100 + $item->tax_percentage ?? 15));
                                                $taxamount = $item->amount - $unitprice;
                                                if ($item->tax_included == 0) {
                                                    $taxamount = 0;
                                                }
                                                $totaltax += $taxamount;
                                            @endphp
                                            <td class="w-table-10">
                                                @if ($item->tax_included != 0)
                                                    {{ $item->tax_percentage }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="w-table-15">{{ getFormattedCurrency($taxamount) }}</td>
                                            <td class="w-table-15">
                                                <span
                                                    class="text-uppercase">{{ getPaymentMode($item->payment_mode) }}</span>
                                            </td>
                                            <td class="w-table-15">
                                                <span
                                                    class="badge bg-dark text-white text-uppercase">{{ $item->createdBy->name ?? '' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Expense:</div>
                                        <div class="fw-bold text-xs">
                                            {{ getFormattedCurrency($expenses->sum('amount')) }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Tax:</div>
                                        <div class="fw-bold text-xs">{{ getFormattedCurrency($totaltax) }}</div>
                                    </div>
                                </div>
                            </div>
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