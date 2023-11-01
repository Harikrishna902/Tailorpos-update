<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.expense_heads') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" wire:click="resetFields" data-bs-target="#addhead"
                                        class="btn btn-primary px-2" type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_head') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" wire:model="search" type="text"
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
                                            {{ __('main.expense_head_name') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.type') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }} </th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $item->name }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary p-2 text-uppercase">{{ $item->type == 1 ? __('main.asset') : __('main.liability') }}</span>
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
                                            <td>
                                                <a data-bs-toggle="modal" wire:click="edit({{ $item->id }})"
                                                    data-bs-target="#edithead"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                                <a data-bs-toggle="modal"
                                                    wire:click="deleteConfirm({{ $item->id }})"
                                                    data-bs-target="#confirmdelete"
                                                    class="btn btn-custom-danger btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-trash-o me-2"></i>{{ __('main.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($categories) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addhead" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_expense_head') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.expense_head_name') }}
                                <span class="text-danger">*</span> </label>
                            <input type="text" wire:model="name" required class="form-control"
                                placeholder="{{ __('main.enter_expense_head_name') }}" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.expense_type') }}<span
                                    class="text-danger">*</span></label>
                            <select required class="form-select" wire:model="type">
                                <option value="">{{ __('main.select_a_type') }}</option>
                                <option value="1">{{ __('main.assets') }}</option>
                                <option value="2">{{ __('main.liability') }}</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }}</label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="active">
                                    <input id="active" wire:model="is_active" type="checkbox" checked="" /><span
                                        class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="create">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edithead" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_expense_head') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.expense_head_name') }}
                                <span class="text-danger">*</span> </label>
                            <input type="text" wire:model="name" required class="form-control"
                                placeholder="{{ __('main.enter_expense_head_name') }}" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.expense_type') }}<span
                                    class="text-danger">*</span></label>
                            <select required class="form-select" wire:model="type">
                                <option value="">{{ __('main.select_a_type') }} </option>
                                <option value="1">{{ __('main.assets') }}</option>
                                <option value="2">{{ __('main.liability') }}</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }}</label>
                            <div class="media-body switch-lg">
                                <label class="switch" id="eactive">
                                    <input id="eactive" wire:model="is_active" type="checkbox" /><span
                                        class="switch-state"></span>
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

    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delete') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_to_delete_selected_expense_head') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="delete">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>