<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.stock_transfer') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{ route('admin.stock_transfer_create') }}" class="btn btn-primary px-2"
                                        type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_stock_transfer') }}
                                    </a>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.search_here') }}"
                                            wire:model="search" />
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <select required class="form-select" wire:model="branch_id">
                                        <option value="">{{ __('main.stock_transfer') }}
                                        </option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col">{{ __('main.date') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.branch') }}
                                        </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.total_items') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $row)
                                        <tr>
                                            <td>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $row->createdBy->name ?? '' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary p-2 text-uppercase">{{ $row->branch->name ?? '' }}</span>
                                            </td>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $row->total_items ?? '' }}
                                                    {{ __('main.items') }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.stock_transfer_view', $row->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
                                                </a>
                                                <a href="{{ route('admin.stock_transfer_edit', $row->id) }}"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                                <a data-bs-toggle="modal"
                                                    wire:click.prevent="deleteConfirm({{ $row->id }})"
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
                        @if (count($stocks) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delete') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_to_delete_selected_stock_entry') }}
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