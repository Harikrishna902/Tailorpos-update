<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.mail_settings') }}</h5>
                                </div>
                                <div class="col-auto"></div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-subtitle pt-0 mt-3">
                                        {{ __('main.mail_settings') }}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_host')}}<span class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_host">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_port')}}<span class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_port">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_username')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_username">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_password')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_from_address')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_from_address">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{__('main.mail_from_name')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" required autofocus class="form-control"
                                            wire:model="mail_from_name">
                                    </div>
                                    <div class="form-group mx-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="active"
                                                        wire:model="enable_forget">
                                                    <label class="form-check-label" for="active">{{__('main.enable_password_recovery')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center justify-content-end">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="submit"
                                            wire:click.prevent="save">{{ __('main.save_settings') }}</button>
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
