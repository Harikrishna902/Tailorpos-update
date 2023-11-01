<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Day-Wise Report</title>
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
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['day_ise_report'] ?? 'Day Wise Report' }}</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        <p class="">{{ $lang->data['start_date'] ?? 'Start Date' }}:
                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        </p>
                        @if ($branch != '')
                            <p class="">{{ $lang->data['branch'] ?? 'Branch' }}:
                                {{ \App\Models\User::find($branch)->name ?? '' }}
                            </p>
                        @else
                            <p class="">{{ $lang->data['branch'] ?? 'Branch' }}:
                                All Branches
                            </p>
                        @endif
                    </div>
                    <div class="col-4">
                        <p class=" ">{{ $lang->data['end_date'] ?? 'End Date' }}:
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
                                <thead class=" text-xs">
                                    <th class="text-dark w-table-15"  scope="col">Date</th>
                                    <th class="text-dark w-table-20"  scope="col">Invoices</th>
                                    <th class="text-dark w-table-25"  scope="col">Sales Total</th>
                                    <th class="text-dark w-table-20"  scope="col">Payments Received</th>
                                    <th class="text-dark w-table-20"  scope="col">Expense Total</th>
                                </thead>
                                <tbody>
                                    @foreach ($mycollection as $key => $item)
                                        <tr class="tag-text">
                                            <td class="w-table-15">{{ $key }}</td>
                                            <td class="w-table-20">{{ $item['invoices'] }}</td>
                                            <td class="w-table-25">{{ $item['sales'] }}</td>
                                            <td class="w-table-20">{{ $item['payments'] }}</td>
                                            <td class="w-table-20">{{ $item['expense'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Invoices:</div>
                                        <div class="fw-bold text-xs">{{ $gross_invoices }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Sales:</div>
                                        <div class="fw-bold text-xs">{{ getFormattedCurrency($gross_sales) }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Payments:</div>
                                        <div class="fw-bold text-xs">{{ getFormattedCurrency($gross_payments) }}</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Expenses:</div>
                                        <div class="fw-bold text-xs">{{ getFormattedCurrency($gross_expense) }}</div>
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