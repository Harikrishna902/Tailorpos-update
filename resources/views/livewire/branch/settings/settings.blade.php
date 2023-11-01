<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.branch_settings') }} </h5>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-subtitle mt-3">
                                        {{ __('main.account_settings') }}
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('main.branch_name') }} <span class="text-danger">*</span> </label>
                                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_branch_name') }}" wire:model="name">
                                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <label class="form-label">{{ __('main.phone_number') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{getCountryCode()}}</span>
                                            <input class="form-control" required type="number" placeholder="{{ __('main.enter_phone_number') }}" wire:model="phone">
                                            @error('phone') <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.email') }} <span class="text-danger">*</span> </label>
                                            <input type="email" required class="form-control" placeholder="{{ __('main.enter_email') }}" wire:model="email">
                                            @error('email') <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <label class="form-label">{{ __('main.password') }} </label>
                                            <input type="password" required class="form-control" placeholder="{{ __('main.enter_password') }}" wire:model="password">
                                            @error('password') <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('main.address') }} <span class="text-danger">*</span> </label>
                                            <textarea class="form-control" required placeholder="{{ __('main.enter_branch_address') }}" wire:model="address"></textarea>
                                                @error('address') <span class="error text-danger">{{ $message }}</span>
                                                @enderror
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