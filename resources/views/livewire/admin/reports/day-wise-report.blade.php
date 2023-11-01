<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.day_wise') }}</h5>
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
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.date') }}</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.invoices') }}</th>
                                    <th class="text-primary w-table-25" scope="col">
                                        {{ __('main.sales_total') }}</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.payments_received') }}</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.expense_total') }}</th>
                                </thead>
                        </div>
                        </table>
                        <div class="table-responsive mt-0 report-scroll">
                            <table class="table table-bordered">
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
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_invoices') }}:
                                        </div>
                                        <div class="fw-bold">{{ $gross_invoices }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_sales') }}:</div>
                                        <div class="fw-bold">{{ getFormattedCurrency($gross_sales) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_payments') }}:
                                        </div>
                                        <div class="fw-bold">{{ getFormattedCurrency($gross_payments) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_expense') }}:
                                        </div>
                                        <div class="fw-bold">{{ getFormattedCurrency($gross_expense) }}</div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary me-2" type="button"
                                        wire:click.prevent="downloadFile()">{{ __('main.download_report') }}</button>
                                    <a href="{{ url('admin/reports/print/day-wise/' . $start_date . '/' . $end_date . '/' . $branch) }}"
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