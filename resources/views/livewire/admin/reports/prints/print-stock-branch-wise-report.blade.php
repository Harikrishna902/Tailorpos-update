<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Stock branchwise Report</title>
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
                    {{ $lang->data['branch_stock_report'] ?? 'Branch Stock Report' }}
                </h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        @if ($search != '')
                            <p class="text-xs"> Search Term : {{ $search }} </p>
                        @endif

                        @if ($branch_id != '')
                            <p class="text-xs">{{ $lang->data['branch'] ?? 'Branch' }}:
                                {{ \App\Models\User::find($branch_id)->name ?? '' }}
                            </p>
                        @else
                            <p class="text-xs">{{ $lang->data['branch'] ?? 'Branch' }}:
                                All Branches
                            </p>
                        @endif
                    </div>
                    <div class="col-4">
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                        @php
                            $total_count = 0;
                            $i = 1;
                        @endphp
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="text-xs">
                                    <th class="text-dark" scope="col">Date</th>
                                    <th class="text-dark" scope="col">Material Name</th>
                                    <th class="text-dark" scope="col">Received</th>
                                    <th class="text-dark" scope="col">Sales</th>
                                    <th class="text-dark" scope="col">Sales Return</th>
                                    <th class="text-dark" scope="col">In Stock</th>
                                </thead>
                                <tbody>
                                    @foreach ($materials as $row)
                                        @php
                                            /* sales */
                                            if ($branch_id != '') {
                                                $invoices = \App\Models\Invoice::where('branch_id', $branch_id)->get();
                                            } else {
                                                $invoices = \App\Models\Invoice::get();
                                            }
                                            $sales_count = 0;
                                            foreach ($invoices as $base_invoice_row) {
                                                $invoice_details = \App\Models\InvoiceDetail::where('invoice_id', $base_invoice_row->id)
                                                    ->where('type', 2)
                                                    ->get();
                                                foreach ($invoice_details as $invoice_row) {
                                                    if ($invoice_row->item_id == $row->id) {
                                                        $sales_count = $sales_count + $invoice_row->quantity;
                                                    }
                                                }
                                            }
                                            /* transfers */
                                            if ($branch_id != '') {
                                                $transfers = \App\Models\StockTransfer::where('warehouse_to', $branch_id)->get();
                                            } else {
                                                $transfers = \App\Models\StockTransfer::get();
                                            }
                                            $transfer_count = 0;
                                            foreach ($transfers as $base_transfer_row) {
                                                $transfer_details = \App\Models\StockTransferDetail::where('stock_transfer_id', $base_transfer_row->id)->get();
                                                foreach ($transfer_details as $transfer_row) {
                                                    if ($transfer_row->material_id == $row->id) {
                                                        $transfer_count = $transfer_count + $transfer_row->quantity;
                                                    }
                                                }
                                            }
                                            /* transfers */
                                            if ($branch_id != '') {
                                                $returns = \App\Models\SalesReturn::where('branch_id', $branch_id)->get();
                                            } else {
                                                $returns = \App\Models\SalesReturn::get();
                                            }
                                            $return_count = 0;
                                            foreach ($returns as $sales_return_row) {
                                                $return_details = \App\Models\SalesReturnDetail::where('sales_return_id', $sales_return_row->id)->get();
                                                foreach ($return_details as $return_row) {
                                                    if ($return_row->item_id == $row->id) {
                                                        if ($return_row->type == 2) {
                                                            $return_count = $return_count + $return_row->quantity;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($transfer_count != 0 || $sales_count != 0)
                                            @php
                                                $total_count = $total_count + 1;
                                            @endphp
                                            <tr class="tag-text">
                                                <td class="w-table-10">{{ $i++ }}</td>
                                                <td class="w-table-25">{{ $row->name }}</td>
                                                <td class="w-table-15">{{ $transfer_count }}
                                                    {{ getUnitType($row->unit) }}</td>
                                                <td class="w-table-15"> {{ $sales_count }}
                                                    {{ getUnitType($row->unit) }}</td>
                                                <td class="w-table-15"> {{ $return_count }}
                                                    {{ getUnitType($row->unit) }}</td>
                                                <td class="w-table-15">
                                                    {{ $transfer_count - $sales_count + $return_count }}
                                                    {{ getUnitType($row->unit) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-2">
                                    <div class="">
                                        <div class="fw-bold text-xs">Total Items:</div>
                                        <div class="fw-bold text-xs">{{ $total_count }}</div>
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