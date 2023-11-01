<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Payment Report</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
        <style>
            thead {
                display: table-row-group;
            }
            * {
                color: black !important;
                font-weight: 1000 !important;
                  /* font-size: calc(100% + 1px); */
            }
        </style>
    </head>
    <body onload="">
        <div class="row align-items-center justify-content-between mb-4">
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['payment_report'] ?? 'Payment Report' }}</h5>
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
                            @if ($recvia != 'all')
                                {{ getPaymentMode($recvia) }}
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
                        <div class=" mt-0">
                            <table class="table table-bordered text-dark">
                                <thead class="text-xs">
                                    <th class="text-primary w-table-8"  scope="col">Voucher No</th>
                                    <th class="text-dark w-table-15"  scope="col">Date</th>
                                    <th class="text-dark w-table-25"  scope="col">Customer</th>
                                    <th class="text-dark w-table-20"  scope="col">Paid Amount</th>
                                    <th class="text-dark w-table-20"  scope="col">Payment Method</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ $lang->data['payment_type'] ?? 'Payment Type' }}</th>
                                    <th class="text-dark w-table-15"  scope="col">Branch</th>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $item)
                                        <tr class="tag-text">
                                            <td class="w-table-8">#{{ $item->voucher_no }}</td>
                                            <td class="w-table-15">{{ $item->date->format('d/m/Y') }}</td>
                                            <td class="w-table-25">
                                                <span class="me-1">[{{ $item->customer->file_number ?? '' }}]</span>
                                                <span>{{ $item->customer->first_name ?? '' }}</span>
                                            </td>
                                            <td class="w-table-20">{{ getFormattedCurrency($item->paid_amount) }}</td>
                                            <td class="w-table-20"><span
                                                    class="text-uppercase">{{ getPaymentMode($item->payment_mode) }}</span>
                                            </td>
                                            @if ($item->payment_type == 1)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ $lang->data['invoice'] ?? 'Invoice' }}</div>
                                                    <div class="mt-50 text-xs fw-bold">
                                                        #{{ $item->invoice->invoice_number ?? '' }}</div>
                                                </td>
                                            @elseif($item->payment_type == 2)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ $lang->data['opening_balance'] ?? 'Opening Balance' }}</div>
                                                </td>
                                            @elseif($item->payment_type == 3)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ $lang->data['cash_receipt'] ?? 'Cash Receipt' }}</div>
                                                </td>
                                            @endif
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
                                        <div class="fw-bold text-xs">Total Payments:</div>
                                        <div class="fw-bold text-xs">{{ $payments->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Amount Received:</div>
                                        <div class="fw-bold text-xs">
                                            {{ getFormattedCurrency($payments->sum('paid_amount')) }}</div>
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