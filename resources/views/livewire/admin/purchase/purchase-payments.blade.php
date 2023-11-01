<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.purchase_payments') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" data-bs-target="#addpayment" class="btn btn-primary px-2"
                                        type="button" wire:click="resetInputFields">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_payment') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.search_here') }}"
                                            wire:model="search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col">{{ __('main.date') }} </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.supplier') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.amount') }}
                                        </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.payment_mode') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $row)
                                        <tr>
                                            <td>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $row->createdBy->name ?? '' }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="supplier-icon rounded text-center text-secondary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-truck mb-0">
                                                            <rect x="1" y="3" width="15"
                                                                height="13"></rect>
                                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8">
                                                            </polygon>
                                                            <circle cx="5.5" cy="18.5" r="2.5">
                                                            </circle>
                                                            <circle cx="18.5" cy="18.5" r="2.5">
                                                            </circle>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-2 mb-0 fw-bold">
                                                        <div class="mb-50">{{ $row->supplier->name ?? '' }}</div>
                                                        <div class="mb-0">{{ $row->supplier->tax_number ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ getFormattedCurrency($row->paid_amount) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-0 text-uppercase">
                                                    {{ getPaymentMode($row->payment_mode) }}</div>
                                                @if ($row->reference_number)
                                                    <div class="mt-50 text-xs">{{ __('main.ref') }}:
                                                        {{ $row->reference_number }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" wire:click="edit({{ $row->id }})"
                                                    data-bs-target="#editpayment"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                                <a data-bs-toggle="modal"
                                                    wire:click="confirmDelete({{ $row->id }})"
                                                    data-bs-target="#confirmdelete"
                                                    class="btn btn-custom-danger btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-trash-o me-2"></i>{{ __('main.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($purchases) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_payment') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.select_supplier') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" required type="text"
                                    placeholder="{{ __('main.search_supplier') }}"
                                    wire:model="supplier_query" />
                                @error('supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if ($suppliers_results)
                                    @if (count($suppliers_results) > 0)
                                        <ul class="list-group position-absolute ">
                                            @forelse ($suppliers_results as $item)
                                                <li class="list-group-item hover-custom"
                                                    id="itemz{{ $item->id }}"
                                                    wire:click="selectSupplier({{ $item->id }})">
                                                    {{ $item->name }}</li>
                                            @empty
                                            @endforelse
                                        </ul>
                                    @else
                                        <ul class="list-group position-absolute ">
                                            <li class="list-group-item hover-custom " id="woah">
                                                {{ __('main.no_supplier_found') }}</li>
                                        </ul>
                                    @endif
                                @endif
                                @error('supplier')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($supplier_id != '')
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center div-border p-2 rounded pb-3 pt-3">
                                        <div class="supplier-icon rounded text-center text-secondary p-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-truck mb-0">
                                                <rect x="1" y="3" width="15" height="13">
                                                </rect>
                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                            </svg>
                                        </div>
                                        <div class="ms-2">
                                            <div class="mb-1">
                                                <span
                                                    class="text-l fw-bold">{{ $chosenSupplier->name ?? 'Supplier Name' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-sm">{{ $chosenSupplier->tax_number ?? '' }}</span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-sm">{{ getCountryCode() }}
                                                    {{ $chosenSupplier->phone ?? 'Phone' }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @php
                                    $paid_inline = 0;
                                    $balance_inline = 0;
                                    $total_inline = 0;
                                @endphp
                                @if ($supplier_id != '')
                                    @php
                                        $paid_inline = \App\Models\SupplierPayment::where('supplier_id', $supplier_id)->sum('paid_amount');
                                        $total_inline = \App\Models\Purchase::where('purchase_type', 2)
                                            ->where('supplier_id', $supplier_id)
                                            ->sum('total');
                                        $openbal = \App\Models\Supplier::where('id', $supplier_id)->first()->opening_balance;
                                        $balance_inline = $total_inline + $openbal - $paid_inline;
                                    @endphp
                                @endif
                                <div class="col-lg-6">
                                    <div class="div-border p-2 rounded">
                                        <div class="row align-items-center mb-2">
                                            <div class="col">{{ __('main.total') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($total_inline) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-2">
                                            <div class="col">
                                                {{ __('main.opening_balance') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($openbal) }} </div>
                                        </div>
                                        <div class="row align-items-center mb-2">
                                            <div class="col">{{ __('main.paid') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($paid_inline) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{ __('main.balance') }}:</div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($balance_inline) }} </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.date') }} <span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en"
                                        placeholder="{{ __('main.select_date') }}"
                                        wire:model="date" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.amount') }} <span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" type="number"
                                        placeholder="{{ __('main.enter_amount') }}"
                                        wire:model="paid_amount" />
                                    <span class="input-group-text">{{ getCurrency() }}</span>
                                </div>
                                @error('paid_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                @error('balance')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.payment_method') }} <span
                                        class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="payment_mode">
                                    <option value="">{{ __('main.select_a_method') }}
                                    </option>
                                    <option value="1">{{ __('main.cash') }}</option>
                                    <option value="2">{{ __('main.card') }}</option>
                                    <option value="3">{{ __('main.upi') }}</option>
                                    <option value="4">{{ __('main.cheque') }}</option>
                                    <option value="5">{{ __('main.bank_transfer') }}
                                    </option>
                                </select>
                                @error('payment_mode')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label
                                    class="form-label">{{ __('main.reference_number') }}</label>
                                <input class="form-control" type="text"
                                    placeholder="{{ __('main.enter_reference_number') }}"
                                    wire:model="reference_number" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editpayment" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_payment') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.select_supplier') }}
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" required type="text"
                                    placeholder="{{ __('main.search_supplier') }}"
                                    wire:model="supplier_query" />
                                @error('supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if ($suppliers_results)
                                    @if (count($suppliers_results) > 0)
                                        <div class="dropdown-menu show" id="dropmenu">
                                            @forelse ($suppliers_results as $item)
                                                <a class="dropdown-item"
                                                    wire:click="selectSupplier({{ $item->id }})">{{ $item->name }}</a>
                                            @empty
                                            @endforelse
                                        </div>
                                    @else
                                        <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                            {{ __('main.no_supplier_found') }}
                                        </div>
                                    @endif
                                @endif
                                @error('supplier')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($supplier_id != '')
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center div-border p-2 rounded">
                                        <div class="supplier-icon rounded text-center text-secondary p-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-truck mb-0">
                                                <rect x="1" y="3" width="15" height="13">
                                                </rect>
                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                            </svg>
                                        </div>
                                        <div class="ms-2">
                                            <div class="mb-1">
                                                <span
                                                    class="text-l fw-bold">{{ $chosenSupplier->name ?? 'Supplier Name' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-sm">{{ $chosenSupplier->tax_number ?? '' }}</span>
                                            </div>
                                            <div class="mb-0">
                                                <span class="text-sm">{{ getCountryCode() }}
                                                    {{ $chosenSupplier->phone ?? 'Phone' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $paid_inline = 0;
                                    $balance_inline = 0;
                                    $total_inline = 0;
                                @endphp
                                @if ($supplier_id != '')
                                    @php
                                        $paid_inline = \App\Models\SupplierPayment::where('supplier_id', $supplier_id)->sum('paid_amount');
                                        $total_inline = \App\Models\Purchase::where('purchase_type', 2)
                                            ->where('supplier_id', $supplier_id)
                                            ->sum('total');
                                        $openbal = \App\Models\Supplier::where('id', $supplier_id)->first()->opening_balance;
                                        $balance_inline = $total_inline + $openbal - $paid_inline;
                                    @endphp
                                @endif
                                <div class="col-lg-6">
                                    <div class="div-border p-2 rounded">
                                        <div class="row align-items-center mb-2">
                                            <div class="col">{{ __('main.total') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($total_inline) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-2">
                                            <div class="col">
                                                {{ __('main.opening_balance') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($openbal) }} </div>
                                        </div>
                                        <div class="row align-items-center mb-2">
                                            <div class="col">{{ __('main.paid') }}:</div>
                                            <div class="col-auto fw-bold">{{ getFormattedCurrency($paid_inline) }}
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col">{{ __('main.balance') }}:</div>
                                            <div class="col-auto fw-bolder text-secondary">
                                                {{ getFormattedCurrency($balance_inline) }} </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.date') }} <span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en"
                                        placeholder="{{ __('main.select_date') }}"
                                        wire:model="date" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.amount') }} <span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" type="number"
                                        placeholder="{{ __('main.enter_amount') }}"
                                        wire:model="paid_amount" />
                                    <span class="input-group-text">{{ getCurrency() }}</span>
                                </div>
                                @error('paid_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                @error('balance')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.payment_method') }} <span
                                        class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="payment_mode">
                                    <option value="">{{ __('main.select_a_method') }}
                                    </option>
                                    <option value="1">{{ __('main.cash') }}</option>
                                    <option value="2">{{ __('main.card') }}</option>
                                    <option value="3">{{ __('main.upi') }}</option>
                                    <option value="4">{{ __('main.cheque') }}</option>
                                    <option value="5">{{ __('main.bank_transfer') }}
                                    </option>
                                </select>
                                @error('payment_mode')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label
                                    class="form-label">{{ __('main.reference_number') }}</label>
                                <input class="form-control" type="text"
                                    placeholder="{{ __('main.enter_reference_number') }}"
                                    wire:model="reference_number" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delete') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_to_delete_selected_payment_entry') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="delete">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>