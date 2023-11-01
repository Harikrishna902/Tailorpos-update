<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.suppliers_list') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" wire:click="resetFields" data-bs-target="#addsupplier"
                                        class="btn btn-primary px-2" type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_supplier') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.search_here') }}" />
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
                                        <th class="text-primary" scope="col">{{ __('main.contact') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
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
                                                        <div class="mb-50">{{ $item->name }}</div>
                                                        <div class="mb-0">{{ $item->tax_number }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-50">{{ getCountryCode() }} {{ $item->phone }}</div>
                                                <div class="mb-0">{{ $item->email ?? '' }}</div>
                                            </td>
                                            <td>
                                                <div class="media-body switch-lg">
                                                    <label class="switch" id="active">

                                                        <input id="active{{ $item->id }}" type="checkbox"
                                                            @if ($item->is_active == 1) checked="" @endif
                                                            wire:click="toggle({{ $item->id }})" /><span
                                                            class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.suppliers_view', $item->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-file-text-o me-2"></i>{{ __('main.ledger') }}
                                                </a>
                                                <a wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                                    data-bs-target="#editsupplier"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($suppliers) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addsupplier" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_supplier') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label
                                        class="form-label">{{ __('main.supplier_name') }}<span
                                            class="text-danger">*</span> </label>
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
                                    <label class="form-label">{{ __('main.phone_number') }}<span
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
                                        placeholder="{{ __('main.enter_email') }}"
                                        wire:model="email" />
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.tax_number') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('main.tax_number') }}"
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
                                        <label class="form-label">{{ __('main.is_active') }} </label>
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
                                    <label
                                        class="form-label">{{ __('main.opening_balance') }}</label>
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
                            wire:click.prevent="create">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editsupplier" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_supplier') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label
                                        class="form-label">{{ __('main.supplier_name') }}<span
                                            class="text-danger">*</span> </label>
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
                                    <label class="form-label">{{ __('main.phone_number') }}<span
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
                                        placeholder="{{ __('main.enter_email') }}"
                                        wire:model="email" />
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.tax_number') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('main.tax_number') }}"
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
                                        <label class="form-label">{{ __('main.is_active') }} </label>
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
                                    <label
                                        class="form-label">{{ __('main.opening_balance') }}</label>
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
                        <button class="btn btn-primary" wire:click.prevent="update"
                            type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>