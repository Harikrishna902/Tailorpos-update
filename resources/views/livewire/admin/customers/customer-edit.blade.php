<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.edit_customer') }} </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.customers') }}" class="btn btn-custom-primary px-2"
                                        type="button">
                                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.file_number') }}
                                                <span class="text-danger">*</span> </label>
                                            <input type="text" required class="form-control"
                                                placeholder="{{ __('main.enter_customer_id') }}"
                                                wire:model="file_number">
                                        </div>
                                        @error('file_number')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.customer_first_name')}}
                                                <span class="text-danger">*</span> </label>
                                            <input type="text" required class="form-control"
                                                placeholder="{{ __('main.enter_first_name') }}"
                                                wire:model="first_name" />
                                        </div>
                                        @error('first_name')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{__('main.customer_last_name') }}
                                            </label>
                                            <input type="text" class="form-control"
                                                placeholder="{{__('main.enter_last_name') }}"
                                                wire:model="second_name" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.customer_family_name')}}
                                            </label>
                                            <input type="text" class="form-control"
                                                placeholder="{{__('main.enter_family_name') }}"
                                                wire:model="family_name" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.Customer Email') }}
                                            </label>
                                            <input type="email" class="form-control"
                                                placeholder="{{ __('main.enter_customer_email') }}"
                                                wire:model="email" />
                                        </div>
                                        @error('email')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.primary_phone_number') }}
                                                <span class="text-danger">*</span> </label>
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
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.secondary_phone_number') }}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ getCountryCode() }}</span>
                                                <input class="form-control" type="number"
                                                    placeholder="{{ __('main.enter_phone_number') }}"
                                                    wire:model="phone_number_2" />
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $groups = \App\Models\CustomerGroup::where('is_active', 1)
                                        ->where('created_by', Auth::user()->id)
                                        ->latest()
                                        ->get();
                                    @endphp
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{ __('main.customer_group') }}
                                            </label>
                                            <select wire:model="customer_group_id" class="form-select">
                                                <option value="">
                                                    {{ __('main.select_group') }} </option>
                                                @foreach ($groups as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label
                                                class="form-label">{{__('main.opening_balance') }}
                                            </label>
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
                                            <label
                                                class="form-label">{{ __('main.customer_address')}}
                                            </label>
                                            <textarea class="form-control" placeholder="{{ __('main.enter_address') }}" wire:model="address"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="bg-light py-1 mt-0 mb-3">
                            <div class="card-body pt-0">
                                <div class="row mb-4">
                                </div>
                                <div class="row g-3" class="collapse" id="thopp">
                                    <div class="col-lg-12 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.notes') }} </label>
                                            <textarea class="form-control" placeholder="{{__('main.enter_notes') }}" wire:model="notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center">
                                    <div class="col">
                                        <label
                                            class="form-label">{{ __('main.is_active') }}</label>
                                        <div class="media-body switch-lg align-items-center">
                                            <label class="switch" id="active">
                                                <input id="active" type="checkbox" checked=""
                                                    wire:model="is_active" /><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary me-2" type="reset"
                                            wire:click.prevent="$emit('reloadpage')">
                                            {{ __('main.clear_all') }}</button>
                                        <button class="btn btn-primary" type="submit"
                                            wire:click.prevent="save">{{ __('main.submit') }} </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>