<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Sales Report</title>
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
                <h5 class="fw-500 text-center text-dark">Purchase Report</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        <p class="text-xs">Start Date:
                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class=" text-xs ">End Date:
                            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="text-xs">
                                    <th class="text-primary w-table-10" scope="col">Date</th>
                                    <th class="text-primary w-table-10" scope="col">Purchase #</th>
                                    <th class="text-primary w-table-20" scope="col">Supplier</th>
                                    <th class="text-primary w-table-15" scope="col">Taxable Amount</th>
                                    <th class="text-primary w-table-10" scope="col">Discount</th>
                                    <th class="text-primary w-table-10" scope="col">Tax Amount</th>
                                    <th class="text-primary w-table-15" scope="col">Gross Total</th>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $item)
                                        <tr class="tag-text">
                                            <td>{{ Carbon\Carbon::parse($item->purchase_date)->format('d/m/Y') }}</td>
                                            <td>#{{ $item->purchase_number }}</td>
                                            <td>
                                                <span>{{ $item->supplier->name ?? '' }}</span>
                                            </td>
                                            <td>{{ getFormattedCurrency($item->sub_total) }}</td>
                                            <td>{{ getFormattedCurrency($item->discount) }}</td>
                                            <td>{{ getFormattedCurrency($item->tax_amount) }}</td>
                                            <td>{{ getFormattedCurrency($item->total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Purchases:</div>
                                        <div class="fw-bold text-xs">{{ $purchases->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Amount:</div>
                                        <div class="fw-bold text-xs">
                                            {{ getFormattedCurrency($purchases->sum('total')) }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Discount:</div>
                                        <div class="fw-bold text-xs">
                                            {{ getFormattedCurrency($purchases->sum('discount')) }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Tax:</div>
                                        <div class="fw-bold text-xs">
                                            {{ getFormattedCurrency($purchases->sum('tax_amount')) }}</div>
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