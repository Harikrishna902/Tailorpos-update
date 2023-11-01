<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.stock_adjustments') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{ route('admin.stock_adjustments_create') }}" class="btn btn-primary px-2"
                                        type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_stock_adjustment') }}
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
                                        <th class="text-primary" scope="col">{{ __('main.date') }} </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.total_items') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockadjustments as $item)
                                        <tr>
                                            <td>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $item->createdBy->name }}</div>
                                            </td>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $item->total_items }}
                                                    {{ __('main.items') }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.stock_adjustments_view', $item->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
                                                </a>
                                                <a href="{{ route('admin.stock_adjustments_edit', $item->id) }}"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                                <a data-bs-toggle="modal" data-bs-target="#confirmdelete"
                                                    wire:click="deleteConfirm({{ $item->id }})"
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
                        @if (count($stockadjustments) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
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
                                {{ __('main.delete_adjustment_confirm') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" wire:click.prevent="delete"
                            type="submit">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>