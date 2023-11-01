<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.invoice_settings') }} </h5>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-subtitle pt-0 mt-3">
                                        {{ __('main.invoice_settings') }} 
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.invoice_prefix') }}  <span class="text-secondary text-xs">{{__('main.invoice_prefix_help')}}</span></label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.invoice_prefix') }}"  wire:model="default_invoice_prefix"/>
                                        </div>
                                        @error('default_invoice_prefix') <span class="text-danger">{{$message}}</span>  @enderror
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.invoice_index') }} </label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.invoice_index') }}"  wire:model="default_invoice_index"/>
                                        </div>
                                        @error('default_invoice_index') <span class="text-danger">{{$message}}</span>  @enderror
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