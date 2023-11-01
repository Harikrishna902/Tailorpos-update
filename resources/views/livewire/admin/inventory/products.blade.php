<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.products') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" data-bs-target="#addproduct" class="btn btn-primary px-2" type="button" wire:click="resetInputFields">
                                        <i class="fa fa-plus me-2"></i>{{ __('main.add_new_product') }}
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
                                        <th class="text-primary" scope="col">{{ __('main.item_code') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.product_name') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.stitching_cost') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($products)
                                    @foreach($products as $row)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <th scope="row">{{$row->item_code ? $row->item_code : '-'}}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($row->image && file_exists(public_path($row->image)))
                                                <img src="{{$row->image}}" class="img-50 rounded max-h-invoice-image">
                                                @else
                                                <img src="{{asset('assets/images/sample.jpg')}}" class="img-50 rounded max-h-invoice-image">
                                                @endif
                                                <div class="ms-2 mb-0 fw-bold">
                                                    <div class="mb-0">{{$row->name}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{getFormattedCurrency($row->stitching_cost)}}
                                        </td>
                                        <td>
                                        @if(Auth::user()->user_type == 3)
                                            @if(Auth::user()->id == $row->created_by)
                                            <div class="media-body switch-lg">
                                                <label class="switch" id="active">
                                                    <input id="active" type="checkbox" @if($row->is_active==1) checked @endif wire:click="toggle({{$row->id}})"/><span class="switch-state"></span>
                                                </label>
                                            </div>
                                            @endif
                                            @endif
                                            @if(Auth::user()->user_type == 2)
                                            <div class="media-body switch-lg">
                                                <label class="switch" id="active">
                                                    <input id="active" type="checkbox" @if($row->is_active==1) checked @endif wire:click="toggle({{$row->id}})"/><span class="switch-state"></span>
                                                </label>
                                            </div>
                                            @endif

                                            
                                        </td>
                                        <td>
                                            @if(Auth::user()->user_type == 3)
                                            @if(Auth::user()->id == $row->created_by)
                                            <a wire:click="edit({{$row->id}})" data-bs-toggle="modal" data-bs-target="#editproduct" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                            @endif
                                            @endif
                                            @if(Auth::user()->user_type == 2)
                                            <a wire:click="edit({{$row->id}})" data-bs-toggle="modal" data-bs-target="#editproduct" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(count($products) == 0)
                        <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="addproduct" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_product') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.product_image') }} (300X300 px)</label>
                            <input type="file" class="form-control" wire:model="image" id="upload({{$i}})" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.product_name') }} <span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_product_name') }}" wire:model="name">
                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.item_code') }}</label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_item_code') }}" wire:model="item_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.stitching_cost') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" required type="number" placeholder="{{ __('main.enter_amount') }}" wire:model="stitching_cost">
                                <span class="input-group-text">{{getCurrency()}} / Nos</span>
                            </div>
                            @error('stitching_cost') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.description') }} </label>
                                <textarea class="form-control" required type="number" placeholder="{{ __('main.description') }}" wire:model="description" rows="5"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('main.is_featured') }}</label>
                                    <div class="media-body switch-lg">
                                        <label class="switch" id="feature">
                                            <input id="feature" type="checkbox" wire:model="is_featured" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.is_active') }}</label>
                                    <div class="media-body switch-lg">
                                        <label class="switch" id="active">
                                            <input id="active" type="checkbox" wire:model="is_active" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editproduct" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_product') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.product_image') }} (300X300 px)</label>
                            <input type="file" class="form-control" wire:model="image" id="{{$i}}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.product_name') }} <span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_product_name') }}" wire:model="name">
                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.item_code') }}</label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_item_code') }}" wire:model="item_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.stitching_cost') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" required type="number" placeholder="{{ __('main.enter_amount') }}" wire:model="stitching_cost">
                                <span class="input-group-text">{{getCurrency()}} / Nos</span>
                            </div>
                            @error('stitching_cost') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.description') }}  </label>
                                <textarea class="form-control" required type="number" placeholder="{{ __('main.description') }}" wire:model="description" rows="5"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('main.is_featured') }}</label>
                                    <div class="media-body switch-lg">
                                        <label class="switch" id="feature">
                                            <input id="feature" type="checkbox" wire:model="is_featured" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-0">
                                    <label class="form-label">{{ __('main.is_active') }}</label>
                                    <div class="media-body switch-lg">
                                        <label class="switch" id="active">
                                            <input id="active" type="checkbox" wire:model="is_active" /><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>