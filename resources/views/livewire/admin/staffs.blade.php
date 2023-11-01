<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.staffs') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" wire:click="resetFields" data-bs-target="#addstaff"
                                        class="btn btn-primary px-2" type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_staff') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text" wire:model="search"
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
                                            {{ __('main.staff_info') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.role') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.contact') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.branch') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffs as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $item->name }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-primary p-2 text-uppercase">{{ getUserType($item->user_type) }}</span>
                                            </td>
                                            <td>
                                                <div class="mb-50">{{ getCountryCode() }} {{ $item->phone }}</div>
                                                <div class="mb-0">{{ $item->email }}</div>
                                            </td>
                                            <td>
                                                @if ($item->branch_id == 0)
                                                    <span
                                                        class="badge bg-secondary p-2 text-uppercase">{{ __('main.all_branches') }}</span>
                                                @endif
                                                <span
                                                    class="badge bg-secondary p-2 text-uppercase">{{ $item->branch->name ?? '' }}</span>
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
                                                    data-bs-target="#editstaff"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($staffs) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addstaff" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_staff') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.staff_name') }}<span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control"
                                placeholder="{{ __('main.enter_staff_name') }}"
                                wire:model="name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.staff_role') }} <span
                                    class="text-danger">*</span> </label>
                            <select required class="form-select" wire:model="role">
                                <option value="">{{ __('main.select_a_role') }}</option>
                                <option value="4">{{ __('main.salesman') }}</option>
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.phone_number') }}<span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ getCountryCode() }}</span>
                                <input class="form-control" required type="number"
                                    placeholder="{{ __('main.enter_phone_number') }}"
                                    wire:model="phone" />
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.email') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="email" required class="form-control"
                                placeholder="{{ __('main.enter_email') }}"
                                wire:model="email" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.password') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="password" required class="form-control"
                                placeholder="{{ __('main.enter_password') }}"
                                wire:model="password" />
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }} </label>
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
                        <button class="btn btn-primary" wire:click.prevent="create"
                            type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editstaff" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_staff') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body pb-1">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.staff_name') }}<span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control"
                                placeholder="{{ __('main.enter_staff_name') }}"
                                wire:model="name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.staff_role') }} <span
                                    class="text-danger">*</span> </label>
                            <select required class="form-select" wire:model="role">
                                <option value="">{{ __('main.select_a_role') }}</option>
                                <option value="4">{{ __('main.salesman') }}</option>
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.phone_number') }}<span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ getCountryCode() }}</span>
                                <input class="form-control" required type="number"
                                    placeholder="{{ __('main.enter_phone_number') }}"
                                    wire:model="phone" />
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.email') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="email" required class="form-control"
                                placeholder="{{ __('main.enter_email') }}"
                                wire:model="email" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.password') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="password" required class="form-control"
                                placeholder="{{ __('main.enter_password') }}"
                                wire:model="password" />
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.is_active') }} </label>
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
                        <button class="btn btn-primary" wire:click.prevent="update"
                            type="submit">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
