<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.stock_report') }}</h5>
                                </div>
                                <div class="col-12">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.search_product') }}
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input class="form-control" type="text"
                                                placeholder="{{ __('main.search_here') }}"
                                                wire:model="search" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-5" scope="col">#</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.material_name') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.opening_stock') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.purchase') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.transferred') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.adjusted') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.in_stock') }} </th>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 report-scroll">
                            @php
                                $total = 0;
                                $i = 1;
                                $totalquantity =0;
                            @endphp
                            <table class="table table-bordered">
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
                                                    $totalquantity += $increments-$decrements;
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
                            @if ($hasMorePages)
                                <div x-data="{
                                    init() {
                                        let observer = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                if (entry.isIntersecting) {
                                                    @this.call('loadMaterials')
                                                    console.log('loading...')
                                                }
                                            })
                                        }, {
                                            root: null
                                        });
                                        observer.observe(this.$el);
                                    }
                                }"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                    <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                        Loading...
                                        <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_items') }}:</div>
                                        <div class="fw-bold">{{ $total }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_quantity') }}:</div>
                                        <div class="fw-bold">{{ $totalquantity}}</div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary me-2"
                                        type="button" wire:click="downloadFile  ">{{ __('main.download_report') }}</button>
                                        <a href="{{url('admin/reports/print/stock-report/'.$search)}}" target="_blank">
                                            <button class="btn btn-primary"
                                            type="button">{{ __('main.print_report') }}</button>
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>