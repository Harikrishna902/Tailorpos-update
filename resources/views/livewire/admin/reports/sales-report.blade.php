<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.sales_report') }}</h5>
                                </div>
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.start_date') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="date" wire:model="start_date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.end_date') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="date" wire:model="end_date" />
                                        </div>
                                    </div>
                                </div>
                                @if (Auth::user()->user_type == 2)
                                    <div class="col-3">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.branch') }}</label>
                                            <select required class="form-select" wire:model="branch">
                                                <option value="">
                                                    {{ __('main.all_branches') }}</option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.date') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.invoice') }} #</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.customer') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.taxable_amount') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.discount') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.tax_amount') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.gross_total') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.branch') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 report-scroll">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($invoices as $item)
                                        <tr class="tag-text">
                                            <td class="w-table-10">
                                                {{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                            <td class="w-table-10">#{{ $item->invoice_number }}</td>
                                            <td class="w-table-20">
                                                <span class="me-1">[{{ $item->customer_file_number ?? '' }}]</span>
                                                <span>{{ $item->customer_name ?? '' }}</span>
                                            </td>
                                            <td class="w-table-15">{{ getFormattedCurrency($item->taxable_amount) }}
                                            </td>
                                            <td class="w-table-10">{{ getFormattedCurrency($item->discount) }}</td>
                                            <td class="w-table-10">{{ getFormattedCurrency($item->tax_amount) }}</td>
                                            <td class="w-table-15">{{ getFormattedCurrency($item->total) }}</td>
                                            <td class="w-table-10">
                                                <span
                                                    class="badge bg-secondary text-uppercase">{{ $item->createdBy->name ?? '' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_invoices') }}:
                                        </div>
                                        <div class="fw-bold">{{ $invoices->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_sales') }}:</div>
                                        <div class="fw-bold">{{ getFormattedCurrency($invoices->sum('total') ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_discount') }}:
                                        </div>
                                        <div class="fw-bold">{{ getFormattedCurrency($invoices->sum('discount')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_tax') }}:</div>
                                        <div class="fw-bold">{{ getFormattedCurrency($invoices->sum('tax_amount')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary me-2" type="button"
                                        wire:click="downloadFile()">{{ __('main.download_report') }}</button>
                                    <a href="{{ url('admin/reports/print/sales/' . $start_date . '/' . $end_date . '/' . $branch) }}"
                                        target="_blank"> <button class="btn btn-primary"
                                            type="button">{{ __('main.print_report') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>