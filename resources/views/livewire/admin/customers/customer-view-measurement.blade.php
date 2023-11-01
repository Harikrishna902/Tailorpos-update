<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.customer_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a data-bs-toggle="modal" data-bs-target="#addpaymentdiscount" class="btn btn-light px-2 text-primary"
                        type="button" wire:click="$emit('resetdiscount')">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment_discount') }}
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.add_payments') }}" class="btn btn-primary px-2" type="button">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment') }}
                    </a>
                </div>
                @if (Auth::user()->user_type != 2)
                    <div class="col-auto">
                        <a href="{{ route('admin.edit_customer', $customer_id) }}" class="btn btn-secondary px-2"
                            type="button">
                            <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                        </a>
                    </div>
                @endif
                <div class="col-auto">
                    <a href="{{ route('admin.customers') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                @livewire('components.customer-profile', ['id' => $customer_id])
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2 mb-0">
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary ">{{ __('main.invoice_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_payments', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary ">{{ __('main.payments_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_measurement', $customer_id) }}"
                                        type="button"
                                        class="btn btn-nav-primary active">{{ __('main.measurements') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_discount', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary ">{{ __('main.payment_discount') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row gx-3 mb-4">
                                <div class="col-lg-6">
                                    <label
                                        class="form-label">{{ __('main.select_product_type') }}
                                        <span class="text-danger">*</span></label>
                                    <select required class="form-select" wire:model="type">
                                        <option value="">
                                            {{ __('main.select_an_option') }}</option>
                                        @foreach ($measurements as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if ($attributes && count($attributes) > 0)
                                    <div class="col-lg-3">
                                        <label
                                            class="form-label">{{ __('main.measurement_unit') }}
                                            <span class="text-danger">*</span></label>
                                        <select required class="form-select" wire:model="unit">
                                            <option value="">{{ __('main.select_unit') }}
                                            </option>
                                            <option value="1">{{ __('main.inches ') }}</option>
                                            <option value="2">{{ __('main.cm') }} </option>
                                            <option value="3">{{ __('main.mtr') }}</option>
                                            <option value="4">{{ __('main.yrd') }}</option>
                                            <option value="5">{{ __('main.ft') }}</option>
                                        </select>
                                        @error('unit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="translation-add-scroll">
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    @if ($attributes && count($attributes) > 0)
                                        @foreach ($attributes as $item)
                                            <div class="col-lg-6 col-12">
                                                <div class="mb-0">
                                                    <div class="input-group">
                                                        <span
                                                            class="input-group-text col-6">{{ $item->attribute->name ?? '' }}</span>
                                                        <input class="form-control col-6" type="number"
                                                            placeholder="{{ __('main.enter_value') }}"
                                                            wire:model="userattributes.{{ $item->id }}" />
                                                    </div>
                                                    @error('userattributes.' . $item->id)
                                                        <span class="text-danger">The {{ $item->attribute->name ?? '' }}
                                                            field is required</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-12 col-12">
                                            <div class="mb-0">
                                                <label class="form-label">{{ __('main.notes') }}</label>
                                                <textarea class="form-control" placeholder="{{ __('main.enter_notes') }}" wire:model="notes"> </textarea>
                                                @error('notes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <button class="btn btn-light text-primary me-2" type="submit"
                                        wire:click.prevent="save">{{ __('main.save_measurements') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('components.customer-discount', ['id' => $customer_id])
</div>