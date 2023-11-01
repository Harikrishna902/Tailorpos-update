<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.purchase_list') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{ route('admin.purchase_create') }}" class="btn btn-primary px-2"
                                        type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_new_purchase') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.search_here') }}"
                                            wire:model="search" />
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
                                            {{ __('main.purchase_info') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.supplier') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.total') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $row)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $row->purchase_number }}</div>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($row->purchase_date)->format('d/m/Y') }}
                                                </div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $row->createdBy->name ?? '' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="supplier-icon rounded text-center text-secondary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-truck mb-0">
                                                            <rect x="1" y="3" width="15"
                                                                height="13"></rect>
                                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8">
                                                            </polygon>
                                                            <circle cx="5.5" cy="18.5" r="2.5">
                                                            </circle>
                                                            <circle cx="18.5" cy="18.5" r="2.5">
                                                            </circle>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-2 mb-0 fw-bold">
                                                        <div class="mb-50">{{ $row->supplier->name ?? '' }}</div>
                                                        <div class="mb-0">{{ $row->supplier->tax_number ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bolder">{{ getFormattedCurrency($row->total) }}</div>
                                            </td>
                                            @if ($row->purchase_type == 1)
                                                <td>
                                                    <span
                                                        class="badge bg-secondary p-2 text-uppercase">{{ __('main.draft') }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.purchase_view', $row->id) }}"
                                                        class="btn btn-custom-primary btn-sm px-2" type="button">
                                                        <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
                                                    </a>
                                                    <a href="{{ route('admin.purchase_edit', $row->id) }}"
                                                        class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                        <i
                                                            class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                    </a>
                                                </td>
                                            @endif
                                            @if ($row->purchase_type == 2)
                                                <td>
                                                    <span
                                                        class="badge bg-primary p-2 text-uppercase">{{ __('main.pushed') }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.purchase_view', $row->id) }}"
                                                        class="btn btn-custom-primary btn-sm px-2" type="button">
                                                        <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($purchases) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.delete') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_todelete_selected_payment_entry') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">{{__('main.cancel')}}</button>
                        <button class="btn btn-primary"
                            type="submit">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>