<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.income_report') }}</h5>
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
                                            <input class="form-control" type="date" wire:model="end_date"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="report-income-scroll">
                            <div class="row px-4 align-items-center mb-2">
                                <div class="col">
                                    <h5>{{ __('main.net_sales') }}</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-0">
                                        <span>{{ __('main.duration') }}:</span>
                                        <span>{{\Carbon\Carbon::parse($start_date)->format('d/m/Y')}}</span>
                                        <span class="px-1">{{ __('main.to') }}</span>
                                        <span>{{\Carbon\Carbon::parse($end_date)->format('d/m/Y')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <thead class="bg-light text-xs">
                                        <th class="text-primary w-table-25" scope="col">{{ __('main.date') }}</th>
                                        <th class="text-primary w-table-25" scope="col">{{ __('main.no_of_invoices') }}</th>
                                        <th class="text-primary w-table-50"  scope="col">{{ __('main.invoice_total') }}</th>
                                    </thead>
                                    <tbody>
                                        @if(count($mycollection) > 0)
                                        @foreach ($mycollection as $key => $item)
                                            
                                        <tr class="tag-text">
                                            <td class="w-table-25">{{$key}}</td>
                                            <td class="w-table-25">{{$item['invoices']}}</td>
                                            <td class="w-table-50">{{$item['sales']}}</td>
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
                                    <h5>{{ __('main.net_expense') }}</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-0">
                                        <span>{{ __('main.duration') }}:</span>
                                        <span>{{\Carbon\Carbon::parse($start_date)->format('d/m/Y')}}</span>
                                        <span class="px-1">{{ __('main.to') }}</span>
                                        <span>{{\Carbon\Carbon::parse($end_date)->format('d/m/Y')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <thead class="bg-light text-xs">
                                        <th class="text-primary w-table-25" scope="col">{{ __('main.date') }}</th>
                                        <th class="text-primary w-table-25" scope="col">{{ __('main.no_of_expense') }}</th>
                                        <th class="text-primary w-table-50"  scope="col">{{ __('main.expense_total') }}</th>
                                    </thead>
                                    <tbody>
                                        @if(count($expensecollect) > 0)
                                        @foreach ($expensecollect as $key => $item)
                                        <tr class="tag-text">
                                            <td class="w-table-25">{{$key}}</td>
                                            <td class="w-table-25">{{$item['no']}}</td>
                                            <td class="w-table-50">{{$item['expense']}}</td>
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
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_sales') }}:</div>
                                        <div class="fw-bold">{{getFormattedCurrency($gross_sales)}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_expense') }}:</div>
                                        <div class="fw-bold">{{getFormattedCurrency($gross_expense)}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.net_income') }}:</div>
                                        <div class="fw-bold">{{getFormattedCurrency($net_income)}}</div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary me-2" type="button" wire:click.prevent="downloadFile()">{{ __('main.download_report') }}</button>
                                    <a href="{{url('admin/reports/print/income/'.$start_date.'/'.$end_date)}}" target="_blank">  <button class="btn btn-primary" type="button">{{ __('main.print_report') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>