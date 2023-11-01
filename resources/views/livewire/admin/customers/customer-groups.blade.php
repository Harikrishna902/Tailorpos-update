<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.customer_groups') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" wire:click="resetFields" data-bs-target="#addgroup"  class="btn btn-primary px-2"  
                                       type="button">
                                        <i class="fa fa-plus me-2"
                                            wire:ignore></i>{{ __('main.add_new_group') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text" wire:ignore><i class="fa fa-search"></i></span>
                                        <input class="form-control" wire:model="search_query" type="text"
                                            placeholder="{{ __('main.search_here') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col"># </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.group_name') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.no_of_customers') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}
                                        </th>
                                            <th class="text-primary" scope="col">
                                                {{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_groups as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $item->name }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary p-2 text-uppercase">{{ \App\Models\Customer::where('customer_group_id', $item->id)->where('is_active', 1)->count() }}
                                                    {{ __('main.customers') }}</span>
                                            </td>
                                            <td>
                                                <div class="media-body switch-lg">
                                                    <label class="switch" id="active">

                                                        <input id="active{{ $item->id }}" type="checkbox"
                                                            @if ($item->is_active == 1) checked="" @endif
                                                            wire:click="toggle({{ $item->id }})" /><span
                                                            class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            @if (Auth::user()->id == $item->created_by)
                                                <td>
                                                    <a data-bs-toggle="modal" wire:click="edit({{ $item->id }})"
                                                        data-bs-target="#editgroup"
                                                        class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                        <i
                                                            class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($customer_groups) == 0)
                        <x-empty-item-component :title="__('main.empty_item_title')"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addgroup" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_group') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.group_name') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control"
                                placeholder="{{ __('main.enter_group_name') }}"
                                wire:model="name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }} </label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="active">
                                    <input id="active" type="checkbox" wire:model="is_active" checked="" /><span
                                        class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" wire:click.prevent="create"
                            type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editgroup" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_group') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.group_name') }}<span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control"
                                placeholder="{{ __('main.enter_group_name') }}"
                                wire:model="name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }}</label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="active">
                                    <input id="active" type="checkbox" wire:model="is_active"
                                        checked="" /><span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="update">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>