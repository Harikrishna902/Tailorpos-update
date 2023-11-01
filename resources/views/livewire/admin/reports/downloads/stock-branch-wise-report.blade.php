<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Branchwise Stock Report</title>
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
        <h3 class="fw-500 text-dark">Branch Stock Report</h3>
        @php
            $material = \App\Models\Material::latest();
            if ($search != '') {
                $material->where(function ($query2) use ($search) {
                    $query2->where('name', 'like', '%' . $search . '%');
                });
            }
            $materials = $material->get();
        @endphp
        @if($search != '') 
                Search Term : {{ $search }} <br/>
        @endif
        @if ($branch_id != '')
            @php
                $branchUser = \App\Models\User::where('id', $branch_id)->first();
            @endphp
            <h3>[{{ $branchUser->name }}]</h3>
        @else
            <h3>[{{ 'All Branches' }}]</h3>
        @endif
        @php
            $total_count = 0;
            $i = 1;
        @endphp
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th class="text-primary w-table-10" scope="col">Date</th>
                    <th class="text-primary w-table-10" scope="col">Material Name</th>
                    <th class="text-primary w-table-20" scope="col">Received</th>
                    <th class="text-primary w-table-15" scope="col">Sales</th>
                    <th class="text-primary w-table-10" scope="col">Sales Return</th>
                    <th class="text-primary w-table-10" scope="col">In Stock<< /th>
                </tr>
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
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">Total Items:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{ $total_count }}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>