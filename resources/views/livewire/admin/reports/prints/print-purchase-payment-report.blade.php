<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Purchase Payment Report</title>
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
                <h5 class="fw-500 text-center text-dark">
                    {{ $lang->data['purchase_payment_report'] ?? 'Purchase Payment Report' }}</h5>
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
                            @if ($recvia != '')
                                {{ getPaymentMode($recvia) }}
                            @else
                                All
                            @endif
                        </p>
                    </div>
                </div>
                <div class="card mb-4 shadow-none border-0">
                    <div class="card-body p-0">
                        <div class=" mt-4">
                            <table class="table table-bordered text-dark">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ $lang->data['date'] ?? 'Date' }}</th>
                                    <th class="text-primary w-table-30" scope="col">
                                        {{ $lang->data['supplier'] ?? 'Supplier' }}</th>
                                    <th class="text-primary w-table-25" scope="col">
                                        {{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</th>
                                    <th class="text-primary w-table-25" scope="col">
                                        {{ $lang->data['payment_method'] ?? 'Payment Method' }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $item)
                                        <tr class="tag-text">
                                            <td class="w-table-20">
                                                {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                            <td class="w-table-30">
                                                <span>{{ $item->supplier_name }}</span>
                                            </td>
                                            <td class="w-table-25">{{ getFormattedCurrency($item->paid_amount) }}</td>
                                            <td class="w-table-25"><span
                                                    class="text-uppercase">{{ getPaymentMode($item->payment_mode) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3 ">
                            <div class="row mt-4 g-3 align-items-center justify-content-between ">
                                <div class="col-4">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold text-xs">
                                            {{ $lang->data['total_payments'] ?? 'Total Payments' }}:</div>
                                        <div class="fw-bold text-xs">{{ $payments->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold text-xs">
                                            {{ $lang->data['total_amount_paid'] ?? 'Total Amount Paid' }}:</div>
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