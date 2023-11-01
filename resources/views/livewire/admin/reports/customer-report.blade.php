<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-1">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.customer_report') }}</h5>
                                </div>
                                <div class="col-6">
                                    <div class="mb-0">
                                        <label
                                            class="form-label">{{ __('main.select_customer') }}</label>
                                        <input class="form-control" type="text"
                                            placeholder="@if ($selected_customer) {{ $selected_customer->first_name }} @else {{ __('main.search_customer') }} @endif"
                                            wire:model="customer_query" />
                                        @if ($customers && count($customers) > 0)
                                            <ul class="list-group position-absolute ">
                                                @foreach ($customers as $item)
                                                    <li class="list-group-item hover-custom"
                                                        wire:click="selectCustomer({{ $item->id }})">
                                                        {{ $item->file_number }} - {{ $item->first_name }} -
                                                        {{ $item->phone_number_1 }} </li>
                                                @endforeach
                                            </ul>
                                        @elseif($customer_query != '' && count($customers) == 0)
                                            <ul class="list-group position-absolute ">
                                                <li id="no-mat" class="list-group-item hover-disabled">
                                                    {{ __('main.no_customers_found') }}</li>
                                            </ul>
                                        @endif
                                    </div>
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
                            </div>
                        </div>
                        @if (!$selected_customer)
                            <div class="pb-2">
                                &nbsp;
                            </div>
                        @endif
                        @if ($selected_customer)
                            <hr class="bg-light">
                            <div class="card-body pb-1">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="d-flex align-items-start">
                                            <div
                                                class="customer-icon rounded text-center text-primary p-customer-report">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-user mb-0">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <div class="ms-2">
                                                <div class="mb-1 fw-bold text-sm">
                                                    {{ $selected_customer->file_number }}
                                                </div>
                                                <h6 class="mb-3"> {{ $selected_customer->first_name }}</h6>
                                                <div class="mb-1 text-sm fw-bold">
                                                    {{ getCountryCode() }} {{ $selected_customer->phone_number_1 }}
                                                    @if ($selected_customer->phone_number_2)
                                                        , {{ getCountryCode() }}
                                                        {{ $selected_customer->phone_number_2 }}
                                                    @endif
                                                </div>
                                                <div class="mb-1 text-sm fw-bold">
                                                    {{ $selected_customer->email ?? '' }}
                                                </div>
                                                <div class="mb-0 text-sm fw-bold">
                                                    {{ $selected_customer->address ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $total = \App\Models\Invoice::where('customer_id', $selected_customer->id)->sum('total');
                                        $paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)->sum('paid_amount');
                                        $invoice_paid = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                                            ->where('payment_type', 1)
                                            ->sum('paid_amount');
                                        $opening_received = \App\Models\InvoicePayment::where('customer_id', $selected_customer->id)
                                            ->where('payment_type', 2)
                                            ->sum('paid_amount');
                                        $opening_balance = $selected_customer->opening_balance != '' ? $selected_customer->opening_balance : 0;
                                        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id', $selected_customer->id)->sum('amount');
                                    @endphp
                                    <div class="col-lg-6 ledger-text">
                                        <div class="row align-items-center mb-1">
                                            <div class="col">
                                                {{ __('main.opening_balance') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($opening_balance) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{ __('main.total_invoices') }}:
                                            </div>
                                            <div class="col-auto fw-bold">
                                                {{ \App\Models\Invoice::where('customer_id', $selected_customer->id)->count() }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{ __('main.total_amount') }}:
                                            </div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($total) }}</div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">{{ __('main.paid_amount') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($paid) }}</div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col">
                                                {{ __('main.payment_discount') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($discount) }}</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{ __('main.balance_amount') }}:
                                            </div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($total + $selected_customer->opening_balance - ($paid + $discount)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <thead class="bg-light text-xs">
                                        <th class="text-primary w-table-15" scope="col">
                                            {{ __('main.date') }}</th>
                                        <th class="text-primary w-table-35" scope="col">
                                            {{ __('main.particulars') }}</th>
                                        <th class="text-primary w-table-25" scope="col">
                                            {{ __('main.debit') }}</th>
                                        <th class="text-primary w-table-25" scope="col">
                                            {{ __('main.credit') }}</th>
                                    </thead>
                                </table>
                            </div>
                            @php
                                $total_invoices = 0;
                                $total_sales = 0;
                                $total_payments = 0;
                                $total_discounts = 0;
                            @endphp
                            <div class="table-responsive mt-0 report-customer-scroll">
                                <table class="table table-bordered">
                                    <tbody>
                                        @foreach ($mycollection as $key => $value)
                                            @foreach ($value['invoice'] as $item)
                                                @php
                                                    $total_invoices++;
                                                    $total_sales += $item->total;
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-15">{{ $key }}</td>
                                                    <td class="w-table-35">
                                                        <span
                                                            class="me-1">{{ __('main.sales') }}</span>
                                                        <span>- #{{ $item->invoice_number }}</span>
                                                    </td>
                                                    <td class="w-table-25">{{ getFormattedCurrency($item->total) }}
                                                    </td>
                                                    <td class="w-table-25"></td>
                                                </tr>
                                            @endforeach
                                            @foreach ($value['payment'] as $item)
                                                @php
                                                    $total_payments += $item->paid_amount;
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-15">{{ $key }}</td>
                                                    <td class="w-table-35">
                                                        <span class="me-1">{{ __('main.payment') }} -
                                                            #{{ $item->voucher_no }}</span>
                                                    </td>
                                                    <td class="w-table-25"></td>
                                                    <td class="w-table-25">
                                                        {{ getFormattedCurrency($item->paid_amount) }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach ($value['discount'] as $item)
                                                @php
                                                    $total_discounts += $item->amount;
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-15">{{ $key }}</td>
                                                    <td class="w-table-35">
                                                        <span
                                                            class="me-1">{{ __('main.payment_discount') }}</span>
                                                    </td>
                                                    <td class="w-table-25"></td>
                                                    <td class="w-table-25">{{ getFormattedCurrency($item->amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer py-3">
                                <div class="row g-3 align-items-center justify-content-between">
                                    <div class="col-lg-2 col-12">
                                        <div class="">
                                            <div class="fw-bold">
                                                {{ __('main.total_invoices') }}:</div>
                                            <div class="fw-bold">{{ $total_invoices }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="">
                                            <div class="fw-bold">{{ __('main.total_sales') }}:
                                            </div>
                                            <div class="fw-bold">{{ getFormattedCurrency($total_sales) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="">
                                            <div class="fw-bold">
                                                {{ __('main.total_payments') }}:</div>
                                            <div class="fw-bold">{{ getFormattedCurrency($total_payments) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="">
                                            <div class="fw-bold">
                                                {{ __('main.payment_discounts') }}:</div>
                                            <div class="fw-bold">{{ getFormattedCurrency($total_discounts) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary me-2" type="button"
                                            wire:click.prevent="downloadFile()">{{ __('main.download_report') }}</button>
                                        <a href="{{ url('admin/reports/print/customer/' . $start_date . '/' . $end_date . '/' . $selected_customer->id) }}"
                                            target="_blank"> <button class="btn btn-primary"
                                                type="button">{{ __('main.print_report') }}</button></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>