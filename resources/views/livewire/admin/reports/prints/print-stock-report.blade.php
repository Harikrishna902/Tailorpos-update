<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Stock Report</title>
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
                <h5 class="fw-500 text-center text-dark">{{ $lang->data['stock_report'] ?? 'Stock Report' }}</h5>
                <p class="mb-0 text-xxs text-center">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
            </div>
            <div class="col-12">
                <div class="row d-flex justify-content-between mx-4 mt-3">
                    <div class="col-4">
                        <p class="text-xs">{{ $lang->data['search_term'] ?? 'Search Term' }}:
                            {{ $search ?? '-' }}
                        </p>
                        <p class="text-xs">{{ $lang->data['branch'] ?? 'Branch' }}:
                            All Branches
                        </p>
                    </div>
                </div>
                <div class="card mb-4 shadow-none">
                    <div class="card-body p-0">
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-5" scope="col">#</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ $lang->data['material_name'] ?? 'Material Name' }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ $lang->data['opening_stock'] ?? 'Opening Stock' }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ $lang->data['purchase'] ?? 'Purchase' }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ $lang->data['transferred'] ?? 'Transferred' }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ $lang->data['adjusted'] ?? 'Adjusted' }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ $lang->data['in_stock'] ?? 'In Stock' }} </th>
                                </thead>
                                @php
                                    $total = 0;
                                    $i = 1;
                                @endphp
                                <tbody>
                                    @foreach ($materials as $row)
                                        <tr class="tag-text">
                                            <td class="w-table-5">{{ $i++ }}</td>
                                            <td class="w-table-20">{{ $row->name }}</td>
                                            <td class="w-table-15">{{ $row->opening_stock }}
                                                {{ getUnitType($row->unit) }}</td>
                                            <td class="w-table-15">
                                                @php
                                                    $purchase_list = \App\Models\Purchase::where('purchase_type', 2)->get();
                                                    $purchase_sum = 0;
                                                    foreach ($purchase_list as $purchase_row) {
                                                        $purchase_row_list = \App\Models\PurchaseDetail::where('purchase_id', $purchase_row->id)->get();
                                                        foreach ($purchase_row_list as $detail_row) {
                                                            if ($detail_row->material_id == $row->id) {
                                                                $purchase_sum = $purchase_sum + $detail_row->purchase_quantity;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                {{ $purchase_sum }} {{ getUnitType($row->unit) }}
                                            </td>
                                            <td class="w-table-10">{{ $row->transfer->sum('quantity') }}
                                                {{ getUnitType($row->unit) }}
                                            </td>
                                            <td class="w-table-15"><span class="text-success">+</span>
                                                {{ $row->adjustment->where('type', 2)->sum('quantity') }}
                                                {{ getUnitType($row->unit) }} / <span class="text-danger">-</span>
                                                {{ $row->adjustment->where('type', 1)->sum('quantity') }}
                                                {{ getUnitType($row->unit) }}</td>
                                            <td class="w-table-15">
                                                @php
                                                    $increments = $row->opening_stock + $row->purchase->sum('purchase_quantity') + $row->adjustment->where('type', 2)->sum('quantity');
                                                    $decrements = $row->transfer->sum('quantity') + $row->adjustment->where('type', 1)->sum('quantity');
                                                @endphp
                                                {{ $increments - $decrements }} {{ getUnitType($row->unit) }}
                                            </td>
                                        </tr>
                                        @php
                                            $total = $total + 1;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
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