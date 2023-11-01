<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.sales_return') }}</h5>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        <form wire:submit.prevent='save' novalidate>
                            <div class="card-body pb-1">
                                <div class="row mb-3">
                                    <div class="col-lg-12 col-12">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <input type="date" readonly class="form-control"
                                                    value="{{ today()->toDateString() }}" />
                                            </div>
                                            <div class="col-6">
                                                <input type="text" readonly class="form-control"
                                                    value="{{ $code }}" />
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control"
                                                    placeholder="@if ($selected_customer) {{ $selected_customer->first_name }} @else {{ __('main.search_customer') }} @endif"
                                                    wire:model="customer_query" />
                                                @if ($customer_results && count($customer_results) > 0)
                                                    <ul class="list-group position-absolute w-100 pr-custom16">
                                                        @foreach ($customer_results as $item)
                                                            <li class="list-group-item hover-custom"
                                                                wire:click="selectCustomer({{ $item->id }})">
                                                                {{ $item->file_number }} - {{ $item->first_name }} -
                                                                {{ $item->phone_number_1 }} </li>
                                                        @endforeach
                                                    </ul>
                                                @elseif($customer_query != '' && count($customer_results) == 0)
                                                    <ul class="list-group position-absolute w-100 pr-custom16">
                                                        <li id="no-mat" class="list-group-item hover-disabled">
                                                            {{ __('main.no_customers_found') }}
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <input type="text" @if (!$selected_customer) readonly @endif
                                                    class="form-control"
                                                    placeholder="@if ($selected_invoice) {{ $selected_invoice->invoice_number }} @else {{ __('main.enter_invoice_number') }} @endif"
                                                    wire:model="invoice_query" />
                                                @if ($invoice_results && count($invoice_results) > 0)
                                                    <ul class="list-group position-absolute w-100 pr-custom16">
                                                        @foreach ($invoice_results as $item)
                                                            <li class="list-group-item hover-custom"
                                                                wire:click="selectInvoice({{ $item->id }})">
                                                                #{{ $item->invoice_number }} </li>
                                                        @endforeach
                                                    </ul>
                                                @elseif($invoice_query != '' && count($invoice_results) == 0)
                                                    <ul class="list-group position-absolute w-100 pr-custom16">
                                                        <li id="no-mat" class="list-group-item hover-disabled">
                                                            {{ __('main.no_invoices_found') }}
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <input class="form-control" @if (!$selected_customer || !$selected_invoice) disabled @endif
                                        type="text"
                                        placeholder="{{ __('main.search_product_shortcut') }}"
                                        wire:model="products_query" data-bs-original-title="" title="">
                                    @if ($product_results && count($product_results) > 0)
                                        <ul class="list-group position-absolute shadow">
                                            @foreach ($product_results as $item)
                                                @php
                                                    $orderdetail = \App\Models\SalesReturnDetail::where('invoice_detail_id', $item->id)->get();
                                                    $sum = $orderdetail->sum('quantity');
                                                    $localtotalquantity = $item->quantity - $sum;
                                                @endphp
                                                @if ($localtotalquantity > 0)
                                                    <li class="list-group-item hover-custom"
                                                        wire:click="selectProduct({{ $item->id }})">
                                                        {{ $item->item_name }}, Qty : {{ $item->quantity - $sum }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @elseif($products_query != '' && count($product_results) == 0)
                                        <ul class="list-group position-absolute  ">
                                            <li id="no-mat" class="list-group-item hover-disabled">
                                                {{ __('main.no_products_found') }}</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-borderless">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.particulars') }}</th>
                                            <th class="text-primary" scope="col">{{ __('main.rate') }}
                                            </th>
                                            <th class="text-primary" scope="col">{{ __('main.tax') }} %
                                            </th>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.tax_total') }}</th>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.purchased_qty') }}</th>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.returned_qty') }}</th>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.total') }}</th>
                                            <th class="text-primary" scope="col"></th>
                                        </tr>
                                    </thead>
                                    @php
                                        $local_total = 0;
                                    @endphp
                                    <tbody>
                                        @foreach ($cart as $key => $item)
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="text" disabled
                                                        placeholder="{{ $item['item_name'] }}"
                                                        wire:model="search_products.{{ $key }}" />
                                                </td>

                                                <td>
                                                    <div class="fw-bolder">{{ getFormattedCurrency($item['rate']) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bolder">{{ $selected_invoice->tax_percentage ?? 0 }}
                                                        %</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bolder">
                                                        @php
                                                            $itemtotal = 0;
                                                        @endphp
                                                        @if ($quantityarray[$key] > 0)
                                                            @php
                                                                $product = $item;
                                                                $tax = $selected_invoice->tax_percentage ?? 0;
                                                                $itemtaxtotal = 0;
                                                                $selling_price = $item['rate'];
                                                                
                                                                $localrate = 0;
                                                                $stop = 0;
                                                                try {
                                                                    $selling_price = $item['total'] / $item['original_quantity'];
                                                                } catch (\Exception $e) {
                                                                    $stop = 1;
                                                                }
                                                                if ($stop != 1) {
                                                                    if (getTaxType() == 2) {
                                                                        $selling_price = $item['total'] / $item['original_quantity'];
                                                                        $localrate = $selling_price * (100 / (100 + $tax ?? 15));
                                                                        $itemtotallocal = $selling_price * $quantityarray[$key] * (100 / (100 + $tax ?? 15));
                                                                        $itemtaxtotal = $selling_price * $quantityarray[$key] - $itemtotallocal ?? 0;
                                                                        $itemtotal = $selling_price * $quantityarray[$key];
                                                                    } else {
                                                                        $selling_price = $item['rate'];
                                                                        $itemtotallocal = $selling_price * $quantityarray[$key];
                                                                        $localrate = $selling_price;
                                                                        $itemtaxtotal = ($itemtotallocal * $tax) / 100;
                                                                        $itemtotal = $itemtotallocal + $itemtaxtotal;
                                                                    }
                                                                }
                                                                $local_total += $itemtotal;
                                                            @endphp
                                                            <span class="">
                                                                {{ getFormattedCurrency($itemtaxtotal) }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bolder">{{ $item['quantity'] }}
                                                        {{ getUnitType($item['unit_type']) }}</div>
                                                </td>
                                                <td class="w-table-20">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @if (isset($quantityarray[$key]) && $quantityarray[$key] > $item['quantity']) is-invalid @elseif(!isset($quantityarray[$key]) || $quantityarray[$key] == '' || $quantityarray[$key] < 0) is-invalid @endif"
                                                            type="number"
                                                            placeholder="{{ __('main.enter_qty') }}"
                                                            wire:model="quantityarray.{{ $key }}" />
                                                        @if ($item['unit_type'] != '')
                                                            <span
                                                                class="input-group-text">{{ getUnitType($item['unit_type']) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bolder">{{ getFormattedCurrency($itemtotal) }}</div>
                                                </td>
                                                <td>
                                                    <a href="#" class="text-danger"
                                                        wire:click="removeItem({{ $key }})" type="button"><i
                                                            class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end p-4">
                                <div class="col-auto">
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 mb-0 justify-content-between">
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12 col-12">
                                                <div class="mb-0">
                                                    <label
                                                        class="form-label">{{ __('main.discount') }}</label>
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @if ($selected_invoice && $discount > $selected_invoice->discount) is-invalid @endif"
                                                            type="number"
                                                            @keydown.ctrl.d.window.prevent="$el.focus()"
                                                            placeholder="{{ __('main.enter_discount_cut') }}"
                                                            wire:model="discount" />
                                                        <span class="input-group-text">{{ getCurrency() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-12 mt-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="cashpaid" value="true" id="flexCheckDefault1">
                                                    <label class="form-check-label" for="flexCheckDefault1">
                                                        {{ __('main.cash_returned') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="row mb-50 align-items-center">
                                            <div class="col">{{ __('main.returned_qty') }}:
                                            </div>
                                            @php
                                                $sum = '-';
                                                try {
                                                    $sum = collect($quantityarray)->sum();
                                                } catch (\Exception $e) {
                                                    $sum = '-';
                                                }
                                            @endphp
                                            <div class="col-auto">{{ $sum }}</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{ __('main.sub_total') }}:</div>
                                            <div class="col-auto">{{ getFormattedCurrency($subtotal) }}</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{ __('main.discount') }}:</div>
                                            <div class="col-auto">{{ getFormattedCurrency($discount) }}</div>
                                        </div>
                                        <div class="row mb-50 align-items-center">
                                            <div class="col">{{ __('main.taxable_amount') }}:
                                            </div>
                                            <div class="col-auto">{{ getFormattedCurrency($taxable) }}</div>
                                        </div>
                                        <div class="row mb-50 align-items-center">
                                            <div class="col">{{ __('main.tax_amount') }}
                                                ({{ $tax }}%):</div>
                                            <div class="col-auto">{{ getFormattedCurrency($taxamount) }}</div>
                                        </div>
                                        <hr class="bg-light mt-2 mb-1">

                                        <div class="row align-items-center mb-2 mt-1">
                                            <div class="col fw-bold">
                                                {{ __('main.credit_value') }}:</div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($total) }}</div>
                                        </div>
                                        <div class="row align-items-center gx-3 mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-secondary w-100" type="reset"
                                                    wire:click="$emit('reloadpage')">{{ __('main.clear_all') }}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary w-100"
                                                    type="submit">{{ __('main.submit') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.details') }}</h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.sales_return_list') }}"
                                        class="btn btn-custom-primary px-2" type="button">
                                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form wire:submit.prevent='save'>
                            <div class="card-body pb-1">
                                <div class="row">
                                    @if ($selected_customer)
                                        <div class="col-lg-12">
                                            <div class="div-border p-2 rounded">

                                                <div class="d-flex align-items-center">
                                                    <div class="customer-icon rounded text-center text-primary p-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-user mb-0">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="12" cy="7" r="4">
                                                            </circle>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-2 mt-2">
                                                        <div class="mb-1">
                                                            <span>{{ $selected_customer->file_number ?? '' }}</span>
                                                        </div>
                                                        <div class="mb-2 fw-bolder">
                                                            <span>{{ $selected_customer->first_name ?? '' }}</span>
                                                        </div>
                                                        <div class="mb-0 text-sm">
                                                            <span>{{ getCountryCode() }}
                                                                {{ $selected_customer->phone_number_1 ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="bg-light mt-2 mb-3">
                                                <div class="row align-items-center mb-2">
                                                    <div class="col">
                                                        {{ __('main.total_balance') }}:</div>
                                                    <div class="col-auto fw-bold">
                                                        {{ $selected_customer->getBalance() }}</div>
                                                </div>
                                            </div>

                                        </div>
                                    @endif
                                    @if ($selected_invoice)
                                        <div class="col-lg-12 mt-4 div-border p-2 pb-2 mb-4">
                                            <div class="row align-items-center mb-2">
                                                <div class="col">{{ __('main.invoice') }} #:</div>
                                                <div class="col-auto fw-bold">#{{ $selected_invoice->invoice_number }}
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col">{{ __('main.date') }}:</div>
                                                <div class="col-auto fw-bold">
                                                    {{ \Carbon\Carbon::parse($selected_invoice->date)->format('d/m/Y') }}
                                                </div>
                                            </div>


                                            <div class="row p-2">
                                                <table class="table ">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th class="text-primary" scope="col">
                                                                {{ __('main.particulars') }}</th>
                                                            <th class="text-primary" scope="col">
                                                                {{ __('main.rate') }}</th>
                                                            <th class="text-primary" scope="col">
                                                                {{ __('main.qty') }}</th>
                                                            <th class="text-primary" scope="col">
                                                                {{ __('main.total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($selected_invoice->details as $item)
                                                            <tr>
                                                                <td>
                                                                    <div class="mb-0 fw-bold">{{ $item->item_name }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ getFormattedCurrency($item->rate) }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->quantity }} @if ($item->unit_type)
                                                                        {{ getUnitType($item->unit_type) }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ getFormattedCurrency($item->total) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row align-items-center mt-2">
                                                <div class="col">{{ __('main.sub_total') }}:
                                                </div>
                                                <div class="col-auto ">
                                                    {{ getFormattedCurrency($selected_invoice->sub_total) }}</div>
                                            </div>
                                            <div class="row align-items-center mt-2">
                                                <div class="col">{{ __('main.discount') }} :
                                                </div>
                                                <div class="col-auto  ">
                                                    {{ getFormattedCurrency($selected_invoice->discount) }}</div>
                                            </div>
                                            <div class="row align-items-center mt-2">
                                                <div class="col">
                                                    {{ __('main.taxable_amount') }}:</div>
                                                <div class="col-auto">
                                                    {{ getFormattedCurrency($selected_invoice->taxable_amount) }}</div>
                                            </div>
                                            <div class="row align-items-center mt-2">
                                                <div class="col">{{ __('main.tax_amount') }}
                                                    ({{ $selected_invoice->tax_percentage }}%):</div>
                                                <div class="col-auto  ">
                                                    {{ getFormattedCurrency($selected_invoice->tax_amount) }}</div>
                                            </div>

                                            <div class="row align-items-center mt-2">
                                                <div class="col">
                                                    {{ __('main.invoice_total') }}:</div>
                                                <div class="col-auto fw-bolder text-secondary">
                                                    {{ getFormattedCurrency($selected_invoice->total) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>