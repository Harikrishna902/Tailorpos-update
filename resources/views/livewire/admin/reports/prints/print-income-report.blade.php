<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Income Report</title>
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
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['income_report'] ?? 'Income Report' }}</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0">
                        <div class="">
                            <div class="row px-4 align-items-center mb-2">
                                <div class="col">
                                    <h6>Net Sales</h6>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-0">
                                        <span>Duration:</span>
                                        <span>{{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}</span>
                                        <span class="px-1">To</span>
                                        <span>{{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <thead class=" text-xs">
                                        <th class="text-dark w-table-25"  scope="col">Date</th>
                                        <th class="text-dark w-table-25"  scope="col">No. of Invoices</th>
                                        <th class="text-dark w-table-50"  scope="col">Invoice Total</th>
                                    </thead>
                                    <tbody>
                                        @if (count($mycollection) > 0)
                                            @foreach ($mycollection as $key => $item)
                                                <tr class="tag-text">
                                                    <td class="w-table-25">{{ $key }}</td>
                                                    <td class="w-table-25">{{ $item['invoices'] }}</td>
                                                    <td class="w-table-50">{{ $item['sales'] }}</td>
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
                            </div>
                            <div class="row px-4 align-items-center mt-4 mb-2">
                                <div class="col">
                                    <h6>Net Expense</h6>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-0">
                                        <span>Duration:</span>
                                        <span>{{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}</span>
                                        <span class="px-1">To</span>
                                        <span>{{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <thead class=" text-xs">
                                        <th class="text-dark w-table-25"  scope="col">Date</th>
                                        <th class="text-dark w-table-25"  scope="col">No. of Expense</th>
                                        <th class="text-dark w-table-50" scope="col">Expense Total</th>
                                    </thead>
                                    <tbody>
                                        @if (count($expensecollect) > 0)
                                            @foreach ($expensecollect as $key => $item)
                                                <tr class="tag-text">
                                                    <td class="w-table-25">{{ $key }}</td>
                                                    <td class="w-table-25">{{ $item['no'] }}</td>
                                                    <td class="w-table-50">{{ $item['expense'] }}</td>
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
                            </div>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row d-flex g-3 align-items-center justify-content-between">
                                <div class="col-2 ">
                                    <p class="">
                                    <div class="fw-bold text-xs">Total Sales:</div>
                                    <div class="fw-bold text-xs">{{ getFormattedCurrency($gross_sales) }}</div>
                                    </p>
                                </div>
                                <div class="col-2 ">
                                    <p class="">
                                    <div class="fw-bold text-xs">Total Expense:</div>
                                    <div class="fw-bold text-xs">{{ getFormattedCurrency($gross_expense) }}</div>
                                    </p>
                                </div>
                                <div class="col-2">
                                    <p class="">
                                    <div class="fw-bold text-xs">Net Income:</div>
                                    <div class="fw-bold text-xs">{{ getFormattedCurrency($net_income) }}</div>
                                    </p>
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
