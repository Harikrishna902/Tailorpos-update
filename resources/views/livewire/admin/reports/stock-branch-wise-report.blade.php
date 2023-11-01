<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">
                                        {{ __('main.branch_stock_report') }}</h5>
                                </div>
                                <div class="col-9">
                                    <div class="mb-0">
                                        <label
                                            class="form-label">{{ __('main.search_product') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input class="form-control" type="text"
                                                placeholder="{{ __('main.search_here') }}"
                                                wire:model="search" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    @if (Auth::user()->user_type == 2)
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.branch') }}</label>
                                            <select required class="form-select" wire:model="branch_id">
                                                <option value="">
                                                    {{ __('main.all_branches') }}
                                                </option>
                                                @foreach ($branches as $row_branch)
                                                    <option value="{{ $row_branch->id }}">{{ $row_branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-10" scope="col">#</th>
                                    <th class="text-primary w-table-25" scope="col">
                                        {{ __('main.material_name') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.received') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.sales') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.sales_return') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.in_stock') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 report-scroll">
                            @php
                                $total_count = 0;
                                $i = 1;
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($materials as $row)
                                        @php
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
                                        <div class="fw-bold">{{ $total_count }}</div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                        <button class="btn btn-secondary me-2" type="button" wire:click="downloadFile()">{{ __('main.download_report') }}</button>
                                        @php
                                        $temp_branch_id = 'all';
                                        if($branch_id!='')  {
                                            $temp_branch_id = $branch_id;

                                        } else {
                                            $temp_branch_id = 'all';
                                        }
                                        @endphp
                                        <a href="{{url('admin/reports/print/stock-branch-wise/'.$temp_branch_id.'/'.$search)}}" target="_blank">  <button class="btn btn-primary" type="button">{{ __('main.print_report') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>