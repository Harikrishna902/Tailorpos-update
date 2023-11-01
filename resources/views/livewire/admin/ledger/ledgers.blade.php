<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-1">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">{{ __('main.ledger') }}</h5>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="@if ($selected_customer) {{ $selected_customer->first_name }} @else {{ __('main.search_customer') }} @endif"
                                            wire:model="customer_query" />
                                        @if ($customers && count($customers) > 0)
                                            <ul class="list-group position-absolute custom-margin ">
                                                @foreach ($customers as $item)
                                                    <li class="list-group-item hover-custom"
                                                        wire:click="selectCustomer({{ $item->id }})">
                                                        {{ $item->file_number }} - {{ $item->first_name }} -
                                                        {{ $item->phone_number_1 }} </li>
                                                @endforeach
                                            </ul>
                                        @elseif($customer_query != '' && count($customers) == 0)
                                            <ul class="list-group position-absolute custom-margin">
                                                <li id="no-mat" class="list-group-item hover-disabled">
                                                    {{ __('main.no_customers_found') }}</li>
                                            </ul>
                                        @endif
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
                            @php
                                $total_invoices = 0;
                                $total_sales = 0;
                                $total_payments = 0;
                                $total_discounts = 0;
                                $credits = 0;
                                $debits = 0;
                            @endphp
                            <div class="table-responsive mt-0 mb-4">
                                <table class="table table-bordered">
                                    <thead class="bg-light text-xs">
                                        <th class="text-primary w-table-10" scope="col">{{ __('main.date') }}
                                        </th>
                                        <th class="text-primary w-table-10" scope="col">{{ __('main.from') }}
                                        </th>
                                        <th class="text-primary w-table-10" scope="col">
                                            {{ __('main.voucher_no') }}</th>
                                        <th class="text-primary w-table-20" scope="col">
                                            {{ __('main.particulars') }}</th>
                                        <th class="text-primary w-table-15" scope="col">
                                            {{ __('main.debit') }}</th>
                                        <th class="text-primary w-table-15" scope="col">
                                            {{ __('main.credit') }}</th>
                                        <th class="text-primary w-table-15" scope="col">
                                            {{ __('main.balance') }}</th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $debits += $selected_customer->opening_balance;
                                        @endphp
                                        <tr class="tag-text">
                                            <td class="w-table-10">
                                                {{ $selected_customer->created_at->format('d/m/Y h:i A') }}</td>
                                            <td class="w-table-10">
                                                OB
                                            </td>
                                            <td class="w-table-10">
                                                -
                                            </td>
                                            <td class="w-table-20">
                                                <span class="me-1">{{ __('main.opening_balance') }}</span>
                                            </td>
                                            <td class="w-table-15">
                                                {{ number_format($selected_customer->opening_balance, 2) }}</td>
                                            <td class="w-table-15">0</td>
                                            <td class="w-table-15">
                                                {{ number_format($selected_customer->opening_balance, 2) }}</td>
                                        </tr>
                                        @foreach ($data as $item)
                                            @if ($item['identifier'] == 1)
                                                @php
                                                    $debits += $item['total'];
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-10">
                                                        {{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y h:i A') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        {{ __('main.sales') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        #{{ $item['invoice_number'] }}
                                                    </td>
                                                    <td class="w-table-20">
                                                        <span>{{ __('main.sales') }} </span>
                                                    </td>
                                                    <td class="w-table-15">{{ number_format($item['total'], 2) }}</td>
                                                    <td class="w-table-15">0</td>
                                                    <td class="w-table-15">
                                                        @if ($debits - $credits < 0)
                                                            {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                        @else
                                                            {{ number_format($debits - $credits, 2) }} Dr.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($item['identifier'] == 2)
                                                @php
                                                    $credits += $item['paid_amount'];
                                                @endphp
                                                @if ($item['payment_type'] == 1)
                                                    <tr class="tag-text">
                                                        <td class="w-table-10">
                                                            {{ Carbon\Carbon::parse($item['date'])->format('d/m/Y h:i A') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            {{ __('main.cash_receipt') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            #{{ $item['voucher_no'] }}
                                                        </td>
                                                        <td class="w-table-20">
                                                            <span class="me-1">{{ __('main.cash_receipt') }}</span>
                                                            @php
                                                                $invoice = \App\Models\Invoice::where('id', $item['invoice_id'])->first();
                                                            @endphp
                                                            <span>(#{{ $invoice->invoice_number ?? '-' }})</span>
                                                        </td>
                                                        <td class="w-table-15">0</td>
                                                        <td class="w-table-15">
                                                            {{ number_format($item['paid_amount'], 2) }}</td>
                                                        <td class="w-table-15">
                                                            @if ($debits - $credits < 0)
                                                                {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                            @else
                                                                {{ number_format($debits - $credits, 2) }} Dr.
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @elseif($item['payment_type'] == 2)
                                                    <tr class="tag-text">
                                                        <td class="w-table-10">
                                                            {{ Carbon\Carbon::parse($item['date'])->format('d/m/Y h:i A') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            {{ __('main.cash_receipt') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            #{{ $item['voucher_no'] }}
                                                        </td>
                                                        <td class="w-table-20">
                                                            <span class="me-1">{{ __('main.cash_receipt') }}</span>
                                                            <span>({{ __('main.against_opening_balance') }})</span>
                                                        </td>
                                                        <td class="w-table-15">0</td>
                                                        <td class="w-table-15">
                                                            {{ number_format($item['paid_amount'], 2) }}</td>
                                                        <td class="w-table-15">
                                                            @if ($debits - $credits < 0)
                                                                {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                            @else
                                                                {{ number_format($debits - $credits, 2) }} Dr.
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @elseif($item['payment_type'] == 3)
                                                    <tr class="tag-text">
                                                        <td class="w-table-10">
                                                            {{ Carbon\Carbon::parse($item['date'])->format('d/m/Y h:i A') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            {{ __('main.cash_receipt') }}
                                                        </td>
                                                        <td class="w-table-10">
                                                            #{{ $item['voucher_no'] }}
                                                        </td>
                                                        <td class="w-table-20">
                                                            <span class="me-1">{{ __('main.cash_receipt') }}</span>
                                                        </td>
                                                        <td class="w-table-15">0</td>
                                                        <td class="w-table-15">
                                                            {{ number_format($item['paid_amount'], 2) }}</td>
                                                        <td class="w-table-15">
                                                            @if ($debits - $credits < 0)
                                                                {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                            @else
                                                                {{ number_format($debits - $credits, 2) }} Dr.
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                            @if ($item['identifier'] == 3)
                                                @php
                                                    $credits += $item['amount'];
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-10">
                                                        {{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y h:i A') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        {{ __('main.discount') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        -
                                                    </td>
                                                    <td class="w-table-20">
                                                        <span class="me-1">{{ __('main.payment_discount') }}</span>
                                                    </td>
                                                    <td class="w-table-15">0</td>
                                                    <td class="w-table-15">{{ number_format($item['amount'], 2) }}</td>
                                                    <td class="w-table-15">
                                                        @if ($debits - $credits < 0)
                                                            {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                        @else
                                                            {{ number_format($debits - $credits, 2) }} Dr.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($item['identifier'] == 4)
                                                @php
                                                    $credits += $item['total'];
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-10">
                                                        {{ Carbon\Carbon::parse($item['date'])->format('d/m/Y h:i A') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        {{ __('main.sr') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        #{{ $item['sales_return_number'] }}
                                                    </td>
                                                    <td class="w-table-20">
                                                        <span class="me-1">{{ __('main.sales_return') }}</span>
                                                        @php
                                                            $invoice = \App\Models\Invoice::where('id', $item['invoice_id'])->first();
                                                        @endphp
                                                        <span>- #{{ $invoice->invoice_number ?? '-' }}</span>
                                                    </td>
                                                    <td class="w-table-15">0</td>
                                                    <td class="w-table-15">{{ number_format($item['total'], 2) }}</td>
                                                    <td class="w-table-15">
                                                        @if ($debits - $credits < 0)
                                                            {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                        @else
                                                            {{ number_format($debits - $credits, 2) }} Dr.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($item['identifier'] == 5)
                                                @php
                                                    $debits += $item['paid_amount'];
                                                    $salesreturn = \App\Models\SalesReturn::where('id', $item['sales_return_id'])->first();
                                                @endphp
                                                <tr class="tag-text">
                                                    <td class="w-table-10">
                                                        {{ Carbon\Carbon::parse($item['date'])->format('d/m/Y h:i A') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        {{ __('main.cash') }}
                                                    </td>
                                                    <td class="w-table-10">
                                                        #{{ $item['voucher_no'] ?? '-' }}
                                                    </td>
                                                    <td class="w-table-20">
                                                        <span class="me-1">{{ __('main.sales_return') }}</span>

                                                        <span>- #{{ $salesreturn->sales_return_number }}
                                                            ({{ __('main.cash_returned') }})</span>
                                                    </td>
                                                    <td class="w-table-15">{{ number_format($item['paid_amount'], 2) }}
                                                    </td>
                                                    <td class="w-table-15">0</td>
                                                    <td class="w-table-15">
                                                        @if ($debits - $credits < 0)
                                                            {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                        @else
                                                            {{ number_format($debits - $credits, 2) }} Dr.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr class="tag-text fw-bold">
                                            <td class="w-table-10">-</td>
                                            <td class="w-table-10">
                                                -
                                            </td>
                                            <td class="w-table-10">
                                                -
                                            </td>
                                            <td class="w-table-20 fw-bold">
                                                <span class="me-1 fw-bolder">{{ __('main.total') }}</span>
                                            </td>
                                            <td class="w-table-15 fw-bold">
                                                <span class="fw-bolder">
                                                    {{ number_format($debits, 2) }}
                                                </span>
                                            </td>
                                            <td class="w-table-15 fw-bold">
                                                <span class="fw-bolder">
                                                    {{ number_format($credits, 2) }}
                                                </span>
                                            </td>
                                            <td class="w-table-15 fw-bold">
                                                <span class="fw-bolder">
                                                    @if ($debits - $credits < 0)
                                                        {{ number_format(($debits - $credits) * -1, 2) }} Cr.
                                                    @else
                                                        {{ number_format($debits - $credits, 2) }} Dr.
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class=" py-3">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>