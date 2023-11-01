<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.master_settings') }}</h5>
                                </div>
                                <div class="col-auto"></div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-subtitle pt-0 mt-3">
                                        {{ __('main.app_settings') }}
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.application_name')}} </label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.application_name')}}"  wire:model="default_application_name"/>
                                        </div>
                                        @error('default_application_name') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.application_logo')}} </label>
                                            <input type="file" class="form-control" wire:model="app_logo"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.application_favicon')}}</label>
                                            <input type="file" class="form-control" wire:model="favicon"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.currency_symbol')}}</label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.currency_symbol')}}"  wire:model="default_currency_code"/>
                                        </div>
                                        @error('default_currency_code') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.currency_align')}}</label>
                                            <select  class="form-select"  wire:model="default_currency_align">
                                                <option value="1">{{ __('main.currency_align_left')}}</option>
                                                <option value="2">{{ __('main.currency_align_right')}}</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.invoice_print')}}</label>
                                            <select  class="form-select" wire:model="default_printer">
                                                <option value="1">{{ __('main.invoice_print_thermal')}}</option>
                                                <option value="2">{{ __('main.invoice_print_a4')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.discount_type')}}</label>
                                            <select  class="form-select" wire:model="default_discount_type">
                                                <option value="1">{{ __('main.before_tax')}}</option>
                                                <option value="2">{{ __('main.after_tax')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.tax_name')}}</label>
                                            <input type="text" class="form-control text-uppercase" placeholder="{{ __('main.tax_name')}}" wire:model="default_tax_name"/>
                                        </div>
                                        @error('default_tax_name') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.tax_percentage')}}</label>
                                            <div class="input-group">
                                                <input class="form-control"  type="number" placeholder="{{ __('main.tax_percentage')}}" wire:model="default_tax_percentage"/>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        @error('default_tax_percentage') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.country_code')}}</label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.country_code')}}" wire:model="default_country_code"/>
                                        </div>
                                        @error('default_country_code') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.product_price')}}</label>
                                            <select  class="form-select" wire:model="default_tax_mode">
                                                <option value="1">{{ __('main.excluding_tax')}}</option>
                                                <option value="2">{{ __('main.including_tax')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    @php
                                        $inline_financial_year = App\Models\FinancialYear::latest()->get();
                                    @endphp
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.financial_year')}}</label>
                                            <select  class="form-select" wire:model="default_financial_year">
                                                <option val=""> {{ __('main.choose_financial_year')}} </option>
                                                @foreach ($inline_financial_year as $row)
                                                <option value="{{ $row->id }}">{{ $row->year }} @if ($row->starting_date)
                                                        [ {{ \Carbon\Carbon::parse($row->starting_date)->format('d/m/Y') }} to
                                                        {{ \Carbon\Carbon::parse($row->ending_date)->format('d/m/Y') }} ]
                                                    @endif
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                        @error('default_financial_year') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.allow_branches_to_create_products')}}</label>
                                            <select  class="form-select" wire:model="allow_branches_to_create_products">
                                                <option value="1">{{ __('main.allow')}}</option>
                                                <option value="2">{{ __('main.not_allowed')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.allow_branches_to_create_materials')}}</label>
                                            <select  class="form-select" wire:model="allow_branches_to_create_materials">
                                                <option value="1">{{ __('main.allow')}}</option>
                                                <option value="2">{{ __('main.not_allowed')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.frontend_enabled')}}</label>
                                            <select  class="form-select" wire:model="frontend_enabled">
                                                <option value="0">{{ __('main.no')}}</option>
                                                <option value="1">{{ __('main.yes')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center justify-content-end">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.save_settings')}}</button>
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