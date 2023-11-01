<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.company_settings') }}</h5>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-subtitle mt-3">
                                        {{ __('main.account_settings') }}
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.admin_name') }}</label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.enter_admin_name') }}" wire:model="name"/>
                                            @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.admin_email') }}</label>
                                            <input type="email" class="form-control" placeholder="{{ __('main.enter_admin_email') }}" wire:model="email"/>
                                            @error('email') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.admin_password') }}</label>
                                            <input type="password" autocomplete="new-password" wire:model="password" class="form-control" placeholder="{{ __('main.enter_admin_password') }}" />
                                            @error('password') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-subtitle">
                                        {{ __('main.company_info') }}
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.company_name_reg_name') }}<span class="text-danger">*</span> </label>
                                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_company_name') }}" wire:model="company_name" />
                                            @error('company_name') <span class="text-danger">{{$message}}</span> @enderror
                                       
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.company_logo') }} (150X150 px) </label>
                                            <input type="file" class="form-control" wire:model="logo" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.company_email_id') }}</label>
                                            <input type="email" class="form-control" placeholder="{{ __('main.enter_company_email_id') }}" wire:model="company_email"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.company_mobile_no') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{getCountryCode()}}</span>
                                                <input class="form-control" type="number" placeholder="{{ __('main.enter_company_mobile_no') }}" wire:model="company_mobile"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.company_land_line') }}</label>
                                            <input type="number" class="form-control" placeholder="{{ __('main.enter_company_land_line') }}" wire:model="company_landline"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.tax_registration_number') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_tax_registration_number') }}"  wire:model="tax"/>
                                            @error('tax') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.building_number') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="building" placeholder="{{ __('main.enter_building_number') }}" />
                                            @error('building') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.street_name') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="street" placeholder="{{ __('main.enter_street_name') }}" />
                                            @error('street') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.district_name') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="district" placeholder="{{ __('main.enter_district_name') }}" />
                                            @error('district') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.city_name') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="city" placeholder="{{ __('main.enter_city_name') }}" />
                                            @error('city') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.country_name') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="country" placeholder="{{ __('main.enter_country_name') }}" />
                                            @error('country') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.postal_code') }}<span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control" wire:model="postal" placeholder="{{ __('main.enter_postal_code') }}" />
                                            @error('postal') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center justify-content-end">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.save_settings') }}</button>
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