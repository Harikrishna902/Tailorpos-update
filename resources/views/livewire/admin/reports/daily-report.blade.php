<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.daily_sales_report') }}</h5>
                                </div>
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.date') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="date" wire:model="date" />
                                        </div>
                                    </div>
                                </div>
                                @if (Auth::user()->user_type == 2)
                                    <div class="col-3">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.branch') }}</label>
                                            <select required class="form-select" wire:model="branch">
                                                <option value="">{{ __('main.all_branches') }}
                                                </option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row px-4 mt-3 align-items-center mb-2">
                            <div class="col">
                                <h5>{{ __('main.daily_sales') }}</h5>
                            </div>
                        </div>
                        <div class="table-responsive mt-0 mb-3">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <th class="text-primary" scope="col">
                                        {{ __('main.invoice_no') }}</th>
                                    <th class="text-primary" scope="col">{{ __('main.file_no') }}
                                    </th>
                                    <th class="text-primary" scope="col">{{ __('main.qty') }}</th>
                                    <th class="text-primary" scope="col">{{ __('main.amount') }}</th>
                                    <th class="text-primary" scope="col">{{ __('main.payment') }}
                                    </th>
                                    <th class="text-primary" scope="col">{{ __('main.balance') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @php
                                        $total_balance = 0;
                                        $total_payment = 0;
                                    @endphp
                                    @foreach ($invoices as $item)
                                        <tr>
                                            <td>
                                                <div class="mb-0 fw-bold">#{{ $item->invoice_number }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 ">{{ $item->customer_file_number }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $localquantity = \App\Models\InvoiceDetail::where('invoice_id', $item->id)->sum('quantity');
                                                @endphp
                                                <div class="mb-0 ">{{ $localquantity }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 ">{{ getFormattedCurrency($item->total) }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $payment = \App\Models\InvoicePayment::where('invoice_id', $item->id)
                                                        ->whereDate('date', \Carbon\Carbon::parse($date))
                                                        ->sum('paid_amount');
                                                    $total_balance += $item->total - $payment;
                                                    $total_payment += $payment;
                                                @endphp
                                                <div class="mb-0 ">{{ getFormattedCurrency($payment) }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 ">{{ getFormattedCurrency($item->total - $payment) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row g-3 align-items-center justify-content-between px-4 mb-2">
                            <div class="col-lg-2 col-12">
                                <div class="">
                                    <div class="fw-bold">{{ __('main.total_amount') }}:</div>
                                    <div class="fw-bold">{{ getFormattedCurrency($invoices->sum('total')) }}</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <div class="">
                                    <div class="fw-bold">{{ __('main.total_payment') }} : </div>
                                    <div class="fw-bold">{{ getFormattedCurrency($total_payment) }}</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <div class="">
                                    <div class="fw-bold">{{ __('main.total_balance') }}:</div>
                                    <div class="fw-bold">{{ getFormattedCurrency($total_balance) }}</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4 mt-4 align-items-center mb-2">
                            <div class="col">
                                <h5>{{ __('main.other_data') }}</h5>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <th class="text-primary" scope="col">
                                        {{ __('main.particulars') }}</th>
                                    <th class="text-primary" scope="col">{{ __('main.value') }}</th>
                                    <th class="text-primary" scope="">...&nbsp;</th>
                                    <th class="text-primary" scope=" ">...</th>
                                </thead>
                                <tbody>
                                    @php
                                        if ($branch == '') {
                                            $opening_balance = \App\Models\InvoicePayment::whereDate('date', $date)
                                                ->where('payment_type', 2)
                                                ->sum('paid_amount');
                                            $all_payment = \App\Models\InvoicePayment::whereDate('date', $date)
                                                ->where('payment_type', 1)
                                                ->sum('paid_amount');
                                            $max_payment = \App\Models\InvoicePayment::whereDate('date', $date)->sum('paid_amount');
                                        } else {
                                            $opening_balance = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->where('payment_type', 2)
                                                ->sum('paid_amount');
                                            $all_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->where('payment_type', 1)
                                                ->sum('paid_amount');
                                            $max_payment = \App\Models\InvoicePayment::whereCreatedBy($branch)
                                                ->whereDate('date', $date)
                                                ->sum('paid_amount');
                                        }
                                    @endphp
                                    <tr>
                                        <th class="" scope="row">
                                            {{ __('main.no_of_invoices') }} </th>
                                        <td class="">
                                            <div class="mb-0 fw-bold">{{ $no_of_invoices }}</div>
                                        </td>
                                        <th class="">
                                            {{ __('main.opening_balance_received') }}
                                        </th>
                                        <td class="">
                                            <div class=" fw-bold">{{ getFormattedCurrency($opening_balance) }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="" scope="row">
                                            {{ __('main.total_sales') }} </th>
                                        <td class="">
                                            <div class="mb-0 fw-bold">{{ getFormattedCurrency($total_sales) }}</div>
                                        </td>
                                        <th class="" scope="row">
                                            {{ __('main.old_invoice_balance_received') }}
                                        </th>
                                        <td class="">
                                            <div class="mb-0 fw-bold">
                                                {{ getFormattedCurrency($all_payment - $total_payment) }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="" scope="row">
                                            {{ __('main.total_expense') }}</th>
                                        <td class="">
                                            <div class="mb-0 fw-bold">{{ getFormattedCurrency($total_expense) }}</div>
                                        </td>
                                        <th class="" scope="row">
                                            {{ __('main.total_payment_received') }}</th>
                                        <td class="">
                                            <div class="mb-0 fw-bold">{{ getFormattedCurrency($max_payment) }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row g-3 align-items-center">
                                <div class="col">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary me-2" type="button"
                                        wire:click.prevent="downloadFile()">{{ __('main.download_report') }}</button>
                                    <a href="{{ url('admin/reports/print/daily/' . $date . '/' . $branch) }}"
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