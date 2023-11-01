<div>
    <div class="page-body">
        <div class="container-fluid" x-data>
            <form>
                <div class="row gx-3">
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-header">
                                <div class="row g-3 mb-0">
                                    <div class="col-lg-5">
                                        <div class="row g-3">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                    placeholder="@if ($selected_customer) {{ $selected_customer->first_name }} @else {{ __('main.search_customer_shrtcut') }} @endif"
                                                    wire:model="customer_query" />
                                                @if ($customer_results && count($customer_results) > 0)
                                                    <ul class="list-group position-absolute ">
                                                        @foreach ($customer_results as $item)
                                                            <li class="list-group-item hover-custom"
                                                                wire:click="selectCustomer({{ $item->id }})">
                                                                {{ $item->file_number }} - {{ $item->first_name }} -
                                                                {{ $item->phone_number_1 }} </li>
                                                        @endforeach
                                                    </ul>
                                                @elseif($customer_query != '' && count($customer_results) == 0)
                                                    <ul class="list-group position-absolute ">
                                                        <li id="no-mat" class="list-group-item hover-disabled">
                                                            {{ __('main.no_customers_found') }}
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="col-auto">
                                                <a data-bs-toggle="modal" data-bs-target="#addcustomer"
                                                    class="btn btn-primary px-2" type="button">
                                                    <i class="fa fa-plus me-2"></i>{{ __('main.add') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" readonly
                                            value="{{ $invoice_number }}" disabled />
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="date" class="form-control" readonly disabled
                                            value="{{ $date }}" />
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col">
                                                <select required class="form-select" wire:model="selected_salesman">
                                                    <option value="">
                                                        {{ __('main.select_salesman') }}
                                                    </option>
                                                    @foreach ($salesmen as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary w-table-5" scope="col"># </th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.particulars') }}</th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.selling_price') }} <span
                                                    class="text-xs text-secondary">[{{ getCurrency() }}]</span></th>
                                            <th class="text-primary w-table-12" scope="col">
                                                {{ __('main.rate') }} <span
                                                    class="text-xs text-secondary">[{{ getCurrency() }}]</span></th>
                                            <th class="text-primary w-table-12" scope="col">
                                                {{ __('main.qty') }}</th>
                                            <th class="text-primary w-table-10" scope="col">
                                                {{ __('main.tax') }} %</th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.tax_amount') }}</th>
                                            <th class="text-primary w-table-10" scope="col">
                                                {{ __('main.total') }}</th>
                                            <th class="text-primary w-table-5" scope="col"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive mt-0 my-custom-scrollbar-bill">
                                <table class="table">
                                    <tbody>
                                        @php
                                            $currentcount = 0;
                                        @endphp
                                        @foreach ($cart_items as $key => $item)
                                            <tr>
                                                @php
                                                    $product = \App\Models\Product::find($item[0]['product']);
                                                    $currentcount++;
                                                    $itemtaxtotal = 0;
                                                    $itemtotal = 0;
                                                    $localrate = 0;
                                                    if (getTaxType() == 2) {
                                                        $localrate = $selling_price[$key] * (100 / (100 + $tax ?? 15));
                                                        $itemtotallocal = $selling_price[$key] * $quantity[$key] * (100 / (100 + $tax ?? 15));
                                                        $itemtaxtotal = $selling_price[$key] * $quantity[$key] - $itemtotallocal ?? 0;
                                                        $itemtotal = $selling_price[$key] * $quantity[$key];
                                                    } else {
                                                        $itemtotallocal = $selling_price[$key] * $quantity[$key];
                                                        $localrate = $selling_price[$key];
                                                        $itemtaxtotal = ($itemtotallocal * $tax) / 100;
                                                        $itemtotal = $itemtotallocal + $itemtaxtotal;
                                                    }
                                                @endphp
                                                <th class="w-table-5" scope="row">{{ $currentcount }}</th>

                                                <td class="w-table-15">
                                                    <div class="mb-0 fw-bold">{{ $product->name }} </div>
                                                </td>
                                                <td class="w-table-15">
                                                    <input type="number"
                                                        class="form-control input-sm ledger-text text-center"
                                                        value="1000"
                                                        wire:model="selling_price.{{ $key }}" />
                                                </td>
                                                <td class="w-table-12">
                                                    {{ getFormattedCurrency($localrate ?? 0) }}
                                                </td>
                                                <td class="w-table-12">
                                                    <div class="">
                                                        <input type="number"
                                                            class="form-control input-sm ledger-text text-center"
                                                            value="1000" wire:model="quantity.{{ $key }}" />
                                                    </div>
                                                </td>
                                                <td class="w-table-10">
                                                    {{ $tax }}
                                                </td>
                                                <td class="w-table-15">
                                                    {{ getFormattedCurrency($itemtaxtotal) }}
                                                </td>
                                                <td class="w-table-10">
                                                    {{ getFormattedCurrency($itemtotal) }}
                                                </td>
                                                <td class="w-table-5">
                                                    <a href="#" class="text-danger"
                                                        @click.prevent="$wire.removeItem({{ $key }})"><i
                                                            class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($material_items as $key => $item)
                                            @php
                                                $currentcount++;
                                                $material = \App\Models\Material::find($item[0]['product']);
                                                $itemtaxtotal = 0;
                                                $itemtotal = 0;
                                                $itemrate = 0;
                                                if (getTaxType() == 2) {
                                                    $itemrate = $matrate[$key] * (100 / (100 + $tax ?? 15));
                                                    $itemtotallocal = $matrate[$key] * $matqty[$key] * (100 / (100 + $tax ?? 15));
                                                    $itemtaxtotal = $matrate[$key] * $matqty[$key] - $itemtotallocal ?? 0;
                                                    $itemtotal = $matrate[$key] * $matqty[$key];
                                                } else {
                                                    $itemtotallocal = $matqty[$key] * $matrate[$key];
                                                    $itemrate = $itemtotallocal;
                                                    $itemtaxtotal = ($itemtotallocal * $tax) / 100;
                                                    $itemtotal = $itemtotallocal + $itemtaxtotal;
                                                }
                                            @endphp
                                            <tr>
                                                <th class="w-table-5" scope="row">{{ $currentcount }}</th>
                                                <td class="w-table-15">
                                                    <div class="mb-0 fw-bold">{{ $material->name }}</div>
                                                </td>
                                                <td class="w-table-15">
                                                    {{ $matrate[$key] }}
                                                </td>
                                                <td class="w-table-12">
                                                    {{ number_format($itemrate, 2) }}
                                                </td>
                                                <td class="w-table-12" x-data="{ showqtyedit: false }">
                                                    <div class="" @mouseover="showqtyedit = true"
                                                        @mouseover.away="showqtyedit = false">
                                                        {{ $matqty[$key] }} <span
                                                            class="text-secondary text-sm">{{ getUnitType($material->type) }}</span>
                                                        <span class="pointer-cursor" data-bs-toggle="modal"
                                                            data-bs-target="#changeQuantityMat"
                                                            x-show="showqtyedit == true" x-transition
                                                            wire:click="changeqty({{ $key }})"> <i
                                                                class="fa fa-pencil" aria-hidden="true"></i></span>
                                                    </div>
                                                </td>
                                                <td class="w-table-10">
                                                    {{ $tax }}
                                                </td>
                                                <td class="w-table-15">
                                                    {{ getFormattedCurrency($itemtaxtotal) }}
                                                </td>
                                                <td class="w-table-10">
                                                    {{ getFormattedCurrency($itemtotal) }}
                                                </td>
                                                <td class="w-table-5">
                                                    <a href="#"
                                                        @click.prevent="$wire.removeMat({{ $key }})"
                                                        class="text-danger" type="button"><i
                                                            class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 mb-0">
                                    <div class="col-lg-7 col-12">
                                        <div class="row g-3">
                                            <div class="col-lg-12 col-12">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.discount') }}</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="number"
                                                            placeholder="{{ __('main.enter_discount_cut') }}"
                                                            wire:model="discount" />
                                                        <span class="input-group-text">{{ getCurrency() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-12">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.notes_remarks') }}</label>
                                                    <textarea class="form-control" rows="3" placeholder="{{ __('main.enter_notes_remarks') }}"
                                                        wire:model="notes"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-12">
                                        <div class="row mb-50 align-items-center">
                                            <div class="col">{{ __('main.sub_total') }}:</div>
                                            <div class="col-auto">{{ getFormattedCurrency($sub_total) }}</div>
                                        </div>
                                        <div class="row mb-50 align-items-center">
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
                                            <div class="col fw-bold">{{ __('main.gross_total') }}:
                                            </div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($total) }}</div>
                                        </div>
                                        <div class="row align-items-center gx-3 mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-secondary me-2 w-100" type="reset"
                                                    wire:click="$emit('reloadpage')">{{ __('main.clear_all') }}</button>
                                            </div>
                                            <div class="col-6">
                                                <button wire:click.prevent="continue"
                                                    class="btn btn-primary w-100"
                                                    type="button">{{ __('main.checkout') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input class="form-control" type="text" wire:model="product_search"
                                placeholder="{{ __('main.search_here_cut') }}"/>
                        </div>
                        <div class="pos-card-wrapper-scroll-y my-custom-scrollbar-pos-card">
                            <div class="row g-2 ">
                                @foreach ($products as $item)
                                    <div class="col-lg-12">
                                        <div class="card pos-card mb-0">
                                            <a href="#" wire:click.prevent="addToCart({{ $item->id }})"
                                                type="button" class=" ">
                                                <div class="card-body p-1">
                                                    <div class="d-flex align-items-start">
                                                        @if ($item->image && file_exists(public_path($item->image)))
                                                            <img src="{{ $item->image }}" class="img-80 max-h-invoice-image rounded">
                                                        @else
                                                            <img src="{{ asset('assets/images/sample.jpg') }}"
                                                                class="img-80 rounded">
                                                        @endif
                                                        <div class="pt-0 ms-2">
                                                            <div class="mb-2"><span
                                                                    class="fw-bolder">{{ $item->name }}</span></div>
                                                            <div class="mb-0"><span
                                                                    class="fw-bolder text-secondary">{{ getFormattedCurrency($item->stitching_cost) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12">
                                        <button data-bs-toggle="modal" 
                                            data-bs-target="#addmaterial" class="btn btn-secondary w-100"
                                            type="button">{{ __('main.add_materials_in_invoice') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="checkout" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.invoice_checkout') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @if ($selected_customer)
                        <div class="modal-body">
                            <div class="row align-items-start g-3">
                                <div class="col-lg-12 col-12">
                                    <div class="row g-3 align-items-center">
                                        <div class="col">
                                            <div class="d-flex align-items-center">
                                                <div class="customer-icon rounded text-center text-primary p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user mb-0">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4">
                                                        </circle>
                                                    </svg>
                                                </div>
                                                <div class="ms-2 mb-0 fw-bold">
                                                    <div class="mb-50">{{ $selected_customer->file_number }}</div>
                                                    <div class="mb-0">{{ $selected_customer->first_name }}</div>
                                                    <div class="mb-0">{{ getCountryCode() }}
                                                        {{ $selected_customer->phone_number_1 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="">
                                                <h6 class="mb-50">{{ __('main.invoice') }} #
                                                    <span>{{ $invoice_number }}</span>
                                                </h6>
                                                <div class="mb-0"><span>{{ __('main.date') }}:</span>
                                                    {{ \Carbon\Carbon::today()->format('d/m/Y') }}</div>
                                                <div class="mb-0"><span>{{ __('main.time') }}:</span>
                                                    {{ \Carbon\Carbon::now()->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="bg-light mt-3 mb-0">
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.sub_total') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($sub_total) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
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
                                    <hr class="bg-light mt-1 mb-1">
                                    <div class="row align-items-center mb-2">
                                        <div class="col fw-bold">{{ __('main.gross_total') }}:
                                        </div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($total) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="">
                                        <label class="form-label">{{ __('main.paid_amount') }}

                                            <a type="button" wire:click="magicFill"
                                                class="badge bg-secondary text-white text-xxs text-uppercase">Full
                                                Amount</a>
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" wire:model="paid_amount"
                                                placeholder="{{ __('main.paid_amount') }}" />
                                            <span class="input-group-text">{{ getCurrency() }}</span>
                                        </div>
                                        @error('paid_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="">
                                        <label class="form-label">{{ __('main.payment_method') }}</label>
                                        <select required class="form-select" wire:model="pay_mode">
                                            <option value="">
                                                {{ __('main.select_a_method') }}</option>
                                            <option value="1">{{ __('main.cash') }}</option>
                                            <option value="2">{{ __('main.card') }}</option>
                                            <option value="3">{{ __('main.upi') }}</option>
                                            <option value="4">{{ __('main.cheque') }}</option>
                                            <option value="5">{{ __('main.bank_transfer') }}
                                            </option>
                                        </select>
                                        @error('pay_mode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="">
                                        <label class="form-label">{{ __('main.reference') }}</label>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.reference') }}" wire:model="reference" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" id="inv_id" value="{{ $inv_id }}">
                    <div class="modal-footer">
                        <button class="btn btn-light text-primary" type="button" data-bs-dismiss="modal"
                            wire:click.prevent="save(1)" wire:loading.attr="disabled"
                            wire:target="save">{{ __('main.save_invoice') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save(2)"
                            wire:loading.attr="disabled" wire:target="save">{{ __('main.save_print') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addmaterial" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_materials') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.select_material') }}<span
                                        class="text-danger">*</span> </label>
                                <input class="form-control" wire:model="material_query" required type="text"
                                    placeholder="@if ($selected_material) {{ $selected_material->name }} @else {{ __('main.search_material') }} @endif" />
                                @if ($materials && count($materials) > 0)
                                    <ul class="list-group position-absolute ">
                                        @foreach ($materials as $item)
                                            <li class="list-group-item hover-custom"
                                                wire:click="selectMaterial({{ $item->id }})">
                                                {{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @error('mat_error')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.rate_per_unit') }}<span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" required type="number" wire:model="material_rate"
                                        placeholder="{{ __('main.enter_amount') }}" />
                                    <span class="input-group-text">{{ getCurrency() }}</span>
                                </div>
                                @error('material_rate')
                                    <span class="text-danger">{{ __('main.enter_rate_error') }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.qty') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" required type="number" wire:model="material_qty"
                                        placeholder="{{ __('main.enter_qty') }}" />
                                    @if ($selected_material)
                                        <span class="input-group-text">
                                            {{ getUnitType($selected_material->unit) }}
                                        </span>
                                    @endif
                                </div>
                                @error('material_qty')
                                    <span class="text-danger">{{ __('main.enter_qty_error') }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" wire:click.prevent="addMatToCart"
                            type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addcustomer" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_customer') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.file_number') }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ __('main.enter_customer_id') }}" wire:model="file_number">
                                </div>
                                @error('file_number')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @php
                                $groups = \App\Models\CustomerGroup::where('is_active', 1)
                                    ->latest()
                                    ->get();
                            @endphp
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.customer_group') }}
                                    </label>
                                    <select wire:model="customer_group_id" class="form-select">
                                        <option value="">{{ __('main.select_group') }}
                                        </option>
                                        @foreach ($groups as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.customer_first_name') }}<span
                                            class="text-danger">*</span> </label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ __('main.enter_first_name') }}" wire:model="first_name" />
                                </div>
                                @error('first_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.customer_last_name') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('main.enter_last_name') }}" wire:model="second_name" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.customer_family_name') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('main.enter_family_name') }}" wire:model="family_name" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.primary_phone_number') }}<span
                                            class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ getCountryCode() }}</span>
                                        <input class="form-control" required type="number"
                                            placeholder="{{ __('main.enter_phone_number') }}"
                                            wire:model="phone_number_1" />
                                    </div>
                                    @error('phone_number_1')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.secondary_phone_number') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ getCountryCode() }}</span>
                                        <input class="form-control" type="number"
                                            placeholder="{{ __('main.enter_phone_number') }}"
                                            wire:model="phone_number_2" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.opening_balance') }}</label>
                                    <div class="input-group">
                                        <input class="form-control" required type="number"
                                            placeholder="{{ __('main.enter_amount') }}"
                                            wire:model="opening_balance" />
                                        <span class="input-group-text">{{ getCurrency() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.customer_address') }}</label>
                                    <textarea class="form-control" placeholder="{{ __('main.enter_address') }}" wire:model="address"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.is_active') }} </label>
                                    <div class="media-body switch-lg align-items-center">
                                        <label class="switch" id="active">
                                            <input id="active" type="checkbox" checked=""
                                                wire:model="is_active" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="customerCreate">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeQuantityMat" wire:ignore.self tabindex="-1"
        aria-labelledby="changeQuantityMatLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeQuantityMat">
                        {{ __('main.change_quantity') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" wire:model="material_qty"
                        placeholder="{{ __('main.enter_new_quantity') }}">
                    @error('material_qty')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('main.close') }}</button>
                    <button type="button" class="btn btn-primary"
                        wire:click.prevent="saveChanges()">{{ __('main.save_changes') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="shortcuts" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('main.invoice_keyboard_shortcuts') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">C</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.search_customer') }}</p>
                            </div>
                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">F</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.search_product') }}</p>
                            </div>
                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">A</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.add_customer') }}</p>
                            </div>
                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">M</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.add_materials') }}</p>
                            </div>
                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">D</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.add_discount') }}</p>
                            </div>
                        </div>
                        <div class="row align-items-center mb-0">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <div class="div-border py-2 px-3 fw-bolder">CTRL</div>
                                    <h6 class="px-2 mb-0">+</h6>
                                    <div class="div-border py-2 px-3 fw-bolder">S</div>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-l mb-0 fw-bold"> <span class="px-2">-</span>
                                    {{ __('main.invoice_checkout') }}</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        Livewire.on('openmodal', () => {
        "use strict";
            $('#checkout').modal('show');
        })
    </script>
    <script>
        window.livewire.on('printWindow', () => {
        "use strict";
            var $id = $('#inv_id').val();
            $('#save_btn').hide();
            window.open(
                '{{ url('admin/invoice/print-invoice/') }}' + '/' + $id,
                '_blank'
            );
            window.location.href = '{{ url('admin/invoice/') }}';
        });
    </script>
</div>
