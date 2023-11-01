<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.edit_purchase') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.purchases') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-sm-12">
                    <form>
                        <div class="card">
                            <div class="card-header">
                                <div class="row g-3 mb-0">
                                    <div class="col-lg-6">
                                        <div class="row g-3">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('main.search_supplier') }}"
                                                    wire:model="supplier_query" />
                                                @error('supplier_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                @if ($suppliers_results)
                                                    @if (count($suppliers_results) > 0)
                                                        <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
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
                                            </div>
                                            <div class="col-auto">
                                                <a data-bs-toggle="modal" data-bs-target="#addsupplier"
                                                    class="btn btn-primary px-2" type="button">
                                                    <i class="fa fa-plus me-2"></i>{{ __('main.add') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" required
                                            placeholder="{{ __('main.purchase_number') }}"
                                            wire:model="purchase_number" />
                                        @error('purchase_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="form-control digits" type="date" data-language="en"
                                            placeholder="{{ __('main.purchase_date') }}"
                                            wire:model="purchase_date" />
                                        @error('purchase_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <input class="form-control" type="number"
                                                placeholder="{{ __('main.discount') }}"
                                                wire:model="discount" />
                                            <span class="input-group-text">{{ getCurrency() }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input class="form-control" type="text"
                                                placeholder="{{ __('main.search_materials') }}"
                                                wire:model="material_query" />
                                            @if ($material_results)
                                                @if (count($material_results) > 0)
                                                    <div class="dropdown-menu show" id="dropmenuMaterial"
                                                        wire:ignore.self>

                                                        @forelse ($material_results as $row)
                                                            <a class="dropdown-item"
                                                                wire:click="selectMaterial({{ $row->id }})">{{ $row->name }}</a>
                                                        @empty
                                                        @endforelse

                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <input class="form-control" type="number"
                                                placeholder="{{ __('main.service_charge') }}"wire:model="service_charge" />
                                            <span class="input-group-text">{{ getCurrency() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary w-table-5" scope="col"># </th>
                                            <th class="text-primary w-table-20" scope="col">
                                                {{ __('main.particulars') }}</th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.rate') }} <span
                                                    class="text-xs text-secondary">[{{ getCurrency() }}]</span></th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.qty') }}</th>
                                            <th class="text-primary w-table-10" scope="col">
                                                {{ __('main.tax') }} %</th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.tax_amount') }} </th>
                                            <th class="text-primary w-table-15" scope="col">
                                                {{ __('main.total') }}</th>
                                            <th class="text-primary w-table-5" scope="col"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive mt-0 purchase-add-scroll">
                                <table class="table">
                                    <tbody>
                                        @forelse($inputs as $key => $value)
                                            <tr>
                                                <th class="w-table-5" scope="row">{{ $loop->index + 1 }}</th>
                                                <td class="w-table-20">
                                                    <div class="mb-0 fw-bold">{{ $material_name[$key] }}</div>
                                                </td>
                                                <td class="w-table-15">
                                                    <input type="number"
                                                        class="form-control input-sm ledger-text text-center"
                                                        value="{{ $price[$key] }}"
                                                        wire:model="price.{{ $key }}"
                                                        wire:change="changePrice({{ $key }})">
                                                </td>
                                                <td class="w-table-15">
                                                    <div class="input-group">
                                                        <input class="form-control input-sm-2" type="number"
                                                            placeholder="{{ __('main.enter_value') }}"
                                                            value="{{ $quantity[$key] }}"
                                                            wire:model="quantity.{{ $key }}"
                                                            wire:change="changeQuantity({{ $key }})">
                                                        <span
                                                            class="input-group-text input-sm-2">{{ getUnitType($material_unit[$key]) }}</span>
                                                    </div>
                                                </td>
                                                <td class="w-table-10">
                                                    {{ getTaxPercentage() }}
                                                </td>
                                                <td class="w-table-15">
                                                    {{ getFormattedCurrency($tax_amount[$key]) }}
                                                </td>
                                                <td class="w-table-15">
                                                    {{ getFormattedCurrency($total[$key]) }}
                                                </td>
                                                <td class="w-table-5">
                                                    <a href="#" class="text-danger" type="button"
                                                        wire:click.prevent="delete({{ $key }})"><i
                                                            class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-lg-2 col-12">
                                        <div class="row mb-0 align-items-center">
                                            <div class="col-auto">{{ __('main.sub_total') }}:</div>
                                            <div class="col"> {{ getFormattedCurrency($this->sub_total) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="row mb-0 align-items-center">
                                            <div class="col-auto">
                                                {{ __('main.tax_percentage') }}:</div>
                                            <div class="col">{{ getTaxPercentage() }} %</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="row mb-0 align-items-center">
                                            <div class="col-auto">{{ __('main.tax_amount') }}:</div>
                                            <div class="col"> {{ getFormattedCurrency($this->tax_total) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <div class="row align-items-center mb-0">
                                            <div class="col-auto fw-bold">
                                                {{ __('main.gross_total') }}:</div>
                                            <div class="col fw-bolder text-secondary">
                                                {{ getFormattedCurrency($this->gross_amount) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="row align-items-center gx-3">
                                            <div class="col-6">
                                                <button class="btn btn-secondary w-100" type="submit"
                                                    wire:click.prevent="saveAsDraft">{{ __('main.save_as_draft') }}</button>
                                            </div>
                                            <div class="col-6">
                                                <button data-bs-toggle="modal" data-bs-target="#confirmpurchase"
                                                    class="btn btn-primary w-100"
                                                    type="button">{{ __('main.save_as_pushed') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addsupplier" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_supplier') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.supplier_name') }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ __('main.enter_supplier_name') }}"
                                        wire:model="name" />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.phone_number') }} <span
                                            class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ getCountryCode() }}</span>
                                        <input class="form-control" required type="number"
                                            placeholder="{{ __('main.enter_phone_number') }}"
                                            wire:model="phone" />
                                    </div>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.email_id') }} </label>
                                    <input type="email" class="form-control"
                                        placeholder="{{ __('main.enter_email_id') }}"
                                        wire:model="email" />
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.tax_number') }} </label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('main.enter_tax_number') }}"
                                        wire:model="tax" />
                                    @error('tax')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label
                                        class="form-label">{{ __('main.supplier_address') }}</label>
                                    <textarea class="form-control" placeholder="{{ __('main.enter_address') }}"
                                        wire:model="address"></textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.is_active') }}
                                        </label>
                                        <div class="media-body switch-lg">
                                            <label class="switch" id="active">
                                                <input id="active" type="checkbox" wire:model="is_active"
                                                    checked="" /><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.opening_balance') }}
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" required type="number"
                                            placeholder="{{ __('main.enter_amount') }}"
                                            wire:model="opening_balance" />
                                        <span class="input-group-text">{{ getCurrency() }}</span>
                                        @error('opening_balance')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="addSupplier">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmpurchase" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_purchase') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3"{{ __('main.are_you_sure') }}</h5>
                                <p class="mb-0 text-sm">
                                    {{ __('main.do_you_want_to_push_the_service_into_the_stock') }}
                                </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="saveAsPushed">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>