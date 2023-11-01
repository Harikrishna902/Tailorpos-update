<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['print_stock_report'] ?? 'Print Stock Report'}}</title>
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
            $branch = 'All Branches';
            if($search != '')
        {
            $search = $search;
            $materials =\App\Models\Material::where('name','like','%'.$search.'%')->whereIsActive(1)->get();
        }
        else{
            $materials =App\Models\Material::whereIsActive(1)->get();
        }
        @endphp
        <h3 class="fw-500 text-dark " style="text-align: center">Stock Report</h3>
       {{$branch}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['report_date'] ?? 'Report Date'}}: </label>
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
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
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                $i = 1;
            @endphp
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
                            $increments = $row->opening_stock  + $row->purchase->sum('purchase_quantity') + $row->adjustment->where('type', 2)->sum('quantity');
                            $decrements = $row->transfer->sum('quantity') + $row->adjustment->where('type', 1)->sum('quantity'); 
                        @endphp
                        {{ $increments - $decrements }} {{ getUnitType($row->unit) }}
                    </td>
                </tr>
                @endforeach
                @php
                    $total = $total + 1;
                @endphp
            </tbody>
        </table>
        <br> <br>
    </body>
    </html>
</div>