<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.add_measurement') }} </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{route('admin.measurements')}}" class="btn btn-custom-primary px-2" type="button">
                                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.measurement_name') }} <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" placeholder="{{ __('main.enter_measurement_name') }}" wire:model="name">
                                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="bg-light">
                                <div class="row g-3">
                                    @php
                                        $attributes = \App\Models\MeasurementAttribute::where('is_active',1)->latest()->get();
                                    @endphp
                                    @foreach($attributes as $row)
                                    <div class="col-lg-2 col-12">
                                        <div class="mb-0">
                                            <label class="d-block" for="attri{{ $row->id }}">
                                                <input class="checkbox_animated"  id="attri{{ $row->id }}" type="checkbox" wire:model="selected_attributes.{{$row->id}}"/> {{$row->name}}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center">
                                    <div class="col">
                                        <label class="form-label">{{ __('main.is_active') }}</label>
                                        <div class="media-body switch-lg align-items-center">
                                            <label class="switch" id="active">
                                                <input id="active" type="checkbox" wire:model="is_active" /><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary me-2" type="reset"  wire:click.prevent="$emit('reloadpage')">{{ __('main.clear_all') }}</button>
                                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
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