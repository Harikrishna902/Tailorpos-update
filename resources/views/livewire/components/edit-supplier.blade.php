<div class="modal fade" id="editsupplier" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('main.edit_supplier') }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body pb-1">
                    <div class="row align-items-start g-3">
                        <div class="col-lg-12 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.supplier_name') }} <span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" placeholder="{{ __('main.enter_supplier_name') }}" wire:model="name"/>
                                @error('name') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.phone_number') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{getCountryCode()}}</span>
                                    <input class="form-control" required type="number" placeholder="{{ __('main.enter_phone_number') }}" wire:model="phone"/>
                                </div>
                                @error('phone') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.email') }} </label>
                                <input type="email" class="form-control" placeholder="{{ __('main.enter_email') }}" wire:model="email"/>
                                @error('email') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.tax_number') }} </label>
                                <input type="text" class="form-control" placeholder="{{ __('main.enter_tax_number') }}" wire:model="tax"/>
                                @error('tax') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.supplier_address') }}</label>
                                <textarea class="form-control" placeholder="{{ __('main.enter_address') }}" wire:model="address"></textarea>
                                @error('address') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-0">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.is_active') }}</label>
                                    <div class="media-body switch-lg">
                                        <label class="switch" id="active">
                                            <input id="active" type="checkbox" wire:model="is_active" checked="" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-0">
                                <label class="form-label">{{ __('main.opening_balance') }}</label>
                                <div class="input-group">
                                    <input class="form-control" required type="number" placeholder="{{__('main.enter_amount')}}" wire:model="opening_balance"/>
                                    <span class="input-group-text">{{getCurrency()}}</span>
                                    @error('opening_balance') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                    <button class="btn btn-primary" wire:click.prevent="update" type="submit">{{ __('main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>