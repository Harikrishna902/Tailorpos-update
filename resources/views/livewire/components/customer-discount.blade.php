<div>
    <div wire:ignore.self  class="modal fade" id="addpaymentdiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_payment_discount') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Select Date" wire:model="discount_date"/>
                                </div>
                                @error('discount_date')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.amount') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control"  type="number" placeholder="{{ __('main.enter_amount') }}" wire:model="discount_amount" />
                                    <span class="input-group-text">{{getCurrency()}}</span>
                                </div>
                                @error('discount_amount')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                                @error('discount')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="addDiscount">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>