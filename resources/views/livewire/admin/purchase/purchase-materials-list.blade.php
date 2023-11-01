<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.material_list') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" wire:click="resetFields" data-bs-target="#addmaterial" class="btn btn-primary px-2" type="button">
                                        <i class="fa fa-plus me-2"></i>{{ __('main.add_new_material') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text" placeholder="{{ __('main.search_here') }}" wire:model="search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col"># </th>
                                        <th class="text-primary" scope="col">{{ __('main.material') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.price') }} / <span class="text-secondary">{{ __('main.unit') }}</span></th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.in_stock') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materials as $item)
                                    <tr>
                                        <th scope="row">{{$loop->index + 1}}</th>
                                        <td>
                                            <div class="mb-0 fw-bold">{{$item->name}}</div>
                                        </td>
                                        <td>
                                            {{getFormattedCurrency($item->price)}} / <span class="text-secondary">{{getUnitType($item->unit)}}</span>
                                        </td>
                                        <td>
                                        @if(Auth::user()->user_type == 3)
                                            @if(Auth::user()->id == $item->created_by)
                                            <div class="media-body switch-lg">
                                                <label class="switch" id="active">

                                                    <input id="active{{$item->id}}" type="checkbox" @if($item->is_active == 1) checked="" @endif wire:click="toggle({{$item->id}})" /><span class="switch-state"></span>
                                                </label>
                                            </div>
                                            @endif
                                            @endif
                                            @if(Auth::user()->user_type == 2)
                                            <div class="media-body switch-lg">
                                                <label class="switch" id="active">

                                                    <input id="active{{$item->id}}" type="checkbox" @if($item->is_active == 1) checked="" @endif wire:click="toggle({{$item->id}})" /><span class="switch-state"></span>
                                                </label>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{$item->opening_stock ?? 0.00}} {{getUnitType($item->unit)}}
                                        </td>
                                        <td>
                                            @if(Auth::user()->user_type == 3)
                                            @if(Auth::user()->id == $item->created_by)
                                            <a data-bs-toggle="modal" wire:click.prevent="edit({{$item->id}})" data-bs-target="#editmaterial" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                            @endif
                                            @endif
                                            @if(Auth::user()->user_type == 2)
                                            <a data-bs-toggle="modal" wire:click.prevent="edit({{$item->id}})" data-bs-target="#editmaterial" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($materials) == 0)
                        <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addmaterial" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_material') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.material_name') }} <span class="text-danger">*</span> </label>
                                    <input type="text" required class="form-control" wire:model="name" placeholder="{{ __('main.enter_material_name') }}" />
                                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.price') }} <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input class="form-control" wire:model="price" required type="number" placeholder="{{__('main.enter_amount')}}" />
                                        <span class="input-group-text">{{getCurrency()}}</span>
                                        @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.unit') }} <span class="text-danger">*</span> </label>
                                    <select required class="form-select" wire:model="unit">
                                        <option value="">{{ __('main.select_unit') }}</option>
                                        <option value="1">{{ __('main.mtr') }}</option>
                                        <option value="2">{{ __('main.yrd') }}</option>
                                        <option value="3">{{ __('main.cm') }}</option>
                                        <option value="4">{{ __('main.nos') }}</option>
                                        <option value="5">{{ __('main.pcs') }}</option>
                                        <option value="6">{{ __('main.dzn') }}</option>
                                        <option value="7">{{ __('main.box') }}</option>
                                        <option value="8">{{ __('main.gm') }}</option>
                                        <option value="9">{{ __('main.kg') }}</option>
                                    </select>
                                    @error('unit') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.opening_stock') }}</label>
                                    <input type="number" required class="form-control" wire:model="opening_stock" placeholder="{{ __('main.enter_opening_stock') }}" />
                                    @error('opening_stock') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.is_active') }} </label>
                                        <div class="media-body switch-lg">
                                            <label class="switch" id="active">
                                                <input id="active" type="checkbox" wire:model="is_active" checked="" /><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" wire:click.prevent="create" type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editmaterial" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_material') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.material_name') }} <span class="text-danger">*</span> </label>
                                    <input type="text" required class="form-control" wire:model="name" placeholder="{{ __('main.enter_material_name') }}" />
                                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.price') }} <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input class="form-control" wire:model="price" required type="number" placeholder="{{ __('main.enter_amount') }}" />
                                        <span class="input-group-text">{{getCurrency()}}</span>
                                        @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.unit') }} <span class="text-danger">*</span> </label>
                                    <select required class="form-select" wire:model="unit">
                                        <option value="">{{ __('main.select_unit') }}</option>
                                        <option value="1">{{ __('main.mtr') }}</option>
                                        <option value="2">{{ __('main.yrd') }}</option>
                                        <option value="3">{{ __('main.cm') }}</option>
                                        <option value="4">{{ __('main.nos') }}</option>
                                        <option value="5">{{ __('main.pcs') }}</option>
                                        <option value="6">{{ __('main.dzn') }}</option>
                                        <option value="7">{{ __('main.box') }}</option>
                                        <option value="8">{{ __('main.gm') }}</option>
                                        <option value="9">{{ __('main.kg') }}</option>
                                    </select>
                                    @error('unit') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.opening_stock') }}</label>
                                    <input type="number" required class="form-control" wire:model="opening_stock" placeholder="{{ __('main.enter_opening_stock') }}" />
                                    @error('opening_stock') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-0">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.is_active') }} </label>
                                        <div class="media-body switch-lg">
                                            <label class="switch" id="active">
                                                <input id="active" type="checkbox" wire:model="is_active" checked="" /><span class="switch-state"></span>
                                            </label>
                                        </div>
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
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">Are you sure?</h5>
                            <p class="mb-0 text-sm">Do you want to delete selected payment entry?</p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>