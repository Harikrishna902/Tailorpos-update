<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.branches') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal"  wire:click.prevent="resetInputFields" data-bs-target="#addbranch" class="btn btn-primary px-2" type="button">
                                        <i class="fa fa-plus me-2"></i>{{ __('main.add_new_branch') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text" placeholder="{{ __('main.search_here') }}"  wire:model="search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col"># </th>
                                        <th class="text-primary" scope="col">{{ __('main.branch_info') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.contact') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($users)
                                    @foreach($users as $row)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>
                                            <div class="mb-50 fw-bold">{{$row->name}}</div>
                                            <div class="mb-0">{{$row->address}}</div>
                                        </td>
                                        <td>
                                            @if($row->phone != '' || $row->phone != null)
                                            <div class="mb-50">{{getCountryCode()}} {{$row->phone}}</div>
                                            @endif
                                            <div class="mb-0">{{$row->email}}</div>

                                        </td>
                                        <td>
                                            <div class="switch-lg mb-0">
                                                <label class="switch">
                                                    <input id="{{$row->id}}" type="checkbox"  @if($row->is_active==1) checked @endif wire:click="toggle({{$row->id}})"/><span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a wire:click="edit({{$row->id}})"data-bs-toggle="modal" data-bs-target="#editbranch" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        @if(count($users) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="addbranch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_branch') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.branch_name') }}<span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_branch_name') }}" wire:model="name">
                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.phone_number') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{getCountryCode()}}</span>
                                <input class="form-control" required type="number" placeholder="{{ __('main.enter_phone_number') }}" wire:model="phone">
                                @error('phone') <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.email') }} <span class="text-danger">*</span> </label>
                            <input type="email" required class="form-control" placeholder="{{ __('main.enter_email') }}" wire:model="email">
                            @error('email') <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.password') }} <span class="text-danger">*</span> </label>
                            <input type="password" required class="form-control" placeholder="{{ __('main.enter_password') }}" wire:model="password">
                            @error('password') <span class="error text-danger">{{ $message }}</span>
                         @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.address') }} <span class="text-danger">*</span> </label>
                            <textarea class="form-control" required placeholder="{{ __('main.enter_branch_address') }}" wire:model="address"></textarea>
                                @error('address') <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }}</label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="active">
                                    <input id="active" type="checkbox" checked="" wire:model="is_active"/><span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" wire:click.prevent="resetInputFields">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="editbranch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_branch') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.branch_name') }}<span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="{{ __('main.enter_branch_name') }}" wire:model="name">
                            @error('name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.phone_number') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{getCountryCode()}}</span>
                                <input class="form-control" required type="number" placeholder="{{ __('main.enter_phone_number') }}" wire:model="phone">
                                @error('phone') <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.email') }} <span class="text-danger">*</span> </label>
                            <input type="email" required class="form-control" placeholder="{{ __('main.enter_email') }}" wire:model="email">
                            @error('email') <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.password') }} <span class="text-danger">*</span> </label>
                            <input type="password" required class="form-control" placeholder="{{ __('main.enter_password') }}" wire:model="password">
                            @error('password') <span class="error text-danger">{{ $message }}</span>
                         @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.address') }} <span class="text-danger">*</span> </label>
                            <textarea class="form-control" required placeholder="{{ __('main.enter_branch_address') }}" wire:model="address"></textarea>
                                @error('address') <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }}</label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="active">
                                    <input id="active" type="checkbox" checked="" wire:model="is_active"/><span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" wire:click.prevent="resetInputFields">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>