<div>
    <div class="modal fade" id="closeledger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.close_daily_ledger') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col fw-bold">{{ __('main.date') }}:</div>
                            <div class="col-auto fw-bold">16/05/2022</div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col fw-bold">{{ __('main.opening_balance_in_hand') }}:</div>
                            <div class="col-auto fw-bolder text-secondary">₹1200.00</div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col fw-bold">{{ __('main.opening_balance_in_cash') }}:</div>
                            <div class="col-auto fw-bolder text-secondary">₹1200.00</div>
                        </div>
                        <hr class="bg-light">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.closing_balance_in_hand') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" value="12500" required type="number" placeholder="{{ __('main.enter_amount') }}" />
                                <span class="input-group-text">{{getCurrency()}}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.closing_balance_in_bank') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" value="1150" required type="number" placeholder="{{ __('main.enter_amount') }}" />
                                <span class="input-group-text">{{getCurrency()}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="openledger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.open_daily_ledger') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col fw-bold">{{ __('main.date') }}:</div>
                            <div class="col-auto fw-bold">16/05/2022</div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col fw-bold">{{ __('main.closing_balance_in_hand') }}:</div>
                            <div class="col-auto fw-bolder text-secondary">₹1200.00</div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col fw-bold">{{ __('main.closing_balance_in_cash') }}:</div>
                            <div class="col-auto fw-bolder text-secondary">₹1200.00</div>
                        </div>
                        <hr class="bg-light">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.opening_balance_in_hand') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" value="12500" required type="number" placeholder="{{ __('main.enter_amount') }}" />
                                <span class="input-group-text">{{getCurrency()}}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.opening_balance_in_bank') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" value="1150" required type="number" placeholder="{{ __('main.enter_amount') }}" />
                                <span class="input-group-text">{{getCurrency()}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
    <script>
        Livewire.on('openledger',() => {
        "use strict";
            $('#checkout').modal('show');
            })
        </script>
    @endpush
</div>