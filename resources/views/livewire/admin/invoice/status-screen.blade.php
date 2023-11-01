<div>
    <div class="page-body" wire:ignore.self>
        <div class="container-fluid disable-user-select">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        {{ __('main.status_screen') }}
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="">
                        <p class="mb-0 text-sm text-secondary fw-bold"><span ty class="text-secondary me-2"><i
                                    class="fa fa-info"></i></span>{{ __('main.status_screen_info') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="scrum-board-container order-status-scroll-x">
                        <div class="flex">
                            <div class="scrum-board pending">
                                <h5 class="text-uppercase text-center text-secondary fw-bolder mb-2">
                                    {{ __('main.pending') }}</h5>
                                <div class="scrum-board-column" id="pending">
                                    @foreach ($pending_orders as $item)
                                        <div class="scrum-task overflow shadow-sm" id="{{ $item->id }}">
                                            <div class="">
                                                <div class="d-flex align-item-center justify-content-between">
                                                    <a href="{{ route('admin.sales_view', $item->id) }}" type="button">
                                                        <div class="mt-md-0">
                                                            <h6 class="mb-25">
                                                                #{{ $item->invoice_number }}
                                                            </h6>
                                                            <p class="ledger-text mb-0">
                                                                <span>{{ __('main.date') }}:</span>
                                                                <span>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</span>
                                                            </p>
                                                            <p class="ledger-text mb-0 text-secondary">
                                                                <span>{{ $item->createdBy->name }}</span>
                                                            </p>
                                                        </div>
                                                    </a>
                                                    <div class="mb-0">
                                                        <span
                                                            class="badge bg-secondary text-uppercase">{{ __('main.pending') }}</span>
                                                    </div>
                                                </div>
                                                <hr class="bg-light mt-2 mb-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-start mb-0">
                                                        <div class="customer-icon rounded text-center text-primary"
                                                            wire:ignore>
                                                            <i class="mb-0" data-feather="user"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="mb-50">
                                                                <span>{{ $item->customer_file_number }}</span>
                                                            </div>
                                                            <div class="mb-0 fw-bolder">
                                                                <span>{{ $item->customer_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ledger-text">
                                                        <a data-bs-toggle="modal"
                                                            wire:click="viewOrder({{ $item->id }})"
                                                            data-bs-target="#confirmdelivery" type="button"
                                                            class="badge bg-info py-50 px-50 text-uppercase">{{ __('main.mark_as_delivered') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="scrum-board processing">
                                <h5 class="text-uppercase text-center text-warning fw-bolder mb-2">
                                    {{ __('main.processing') }}</h5>
                                <div class="scrum-board-column" id="processing">
                                    @foreach ($processing_orders as $item)
                                        <div class="scrum-task overflow shadow-sm" id="{{ $item->id }}">
                                            <div class="">
                                                <div class="d-flex align-item-center justify-content-between">
                                                    <a href="{{ route('admin.sales_view', $item->id) }}"
                                                        type="button">
                                                        <div class="mt-md-0">
                                                            <h6 class="mb-25">
                                                                #{{ $item->invoice_number }}
                                                            </h6>
                                                            <p class="ledger-text mb-0">
                                                                <span>{{ __('main.date') }}:</span>
                                                                <span>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</span>
                                                            </p>
                                                            <p class="ledger-text mb-0 text-secondary">
                                                                <span>{{ $item->createdBy->name }}</span>
                                                            </p>
                                                        </div>
                                                    </a>
                                                    <div class="mb-0">
                                                        <span
                                                            class="badge bg-warning text-uppercase">{{ __('main.processing') }}</span>
                                                    </div>
                                                </div>
                                                <hr class="bg-light mt-2 mb-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-start mb-0">
                                                        <div class="customer-icon rounded text-center text-primary"
                                                            wire:ignore>
                                                            <i class="mb-0" data-feather="user"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="mb-50">
                                                                <span>{{ $item->customer_file_number }}</span>
                                                            </div>
                                                            <div class="mb-0 fw-bolder">
                                                                <span>{{ $item->customer_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ledger-text">
                                                        <a data-bs-toggle="modal"
                                                            wire:click="viewOrder({{ $item->id }})"
                                                            data-bs-target="#confirmdelivery" type="button"
                                                            class="badge bg-info py-50 px-50 text-uppercase">{{ __('main.mark_as_delivered') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="scrum-board ready">
                                <h5 class="text-uppercase text-center text-success fw-bolder mb-2">
                                    {{ __('main.ready_to_deliver') }}</h5>
                                <div class="scrum-board-column" id="ready">
                                    @foreach ($ready_orders as $item)
                                        <div class="scrum-task overflow shadow-sm" id="{{ $item->id }}">
                                            <div class="">
                                                <div class="d-flex align-item-center justify-content-between">
                                                    <a href="{{ route('admin.sales_view', $item->id) }}"
                                                        type="button">
                                                        <div class="mt-md-0">
                                                            <h6 class="mb-25">
                                                                #{{ $item->invoice_number }}
                                                            </h6>
                                                            <p class="ledger-text mb-0">
                                                                <span>{{ __('main.date') }}:</span>
                                                                <span>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</span>
                                                            </p>
                                                            <p class="ledger-text mb-0 text-secondary">
                                                                <span>{{ $item->createdBy->name }}</span>
                                                            </p>
                                                        </div>
                                                    </a>
                                                    <div class="mb-0">
                                                        <span
                                                            class="badge bg-success text-uppercase">{{ __('main.ready_to_deliver') }}</span>
                                                    </div>
                                                </div>
                                                <hr class="bg-light mt-2 mb-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-start mb-0">
                                                        <div class="customer-icon rounded text-center text-primary"
                                                            wire:ignore>
                                                            <i class="mb-0" data-feather="user"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="mb-50">
                                                                <span>{{ $item->customer_file_number }}</span>
                                                            </div>
                                                            <div class="mb-0 fw-bolder">
                                                                <span>{{ $item->customer_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ledger-text">
                                                        <a data-bs-toggle="modal"
                                                            wire:click="viewOrder({{ $item->id }})"
                                                            data-bs-target="#confirmdelivery" type="button"
                                                            class="badge bg-info py-50 px-50 text-uppercase">{{ __('main.mark_as_delivered') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            "use strict";
            var drake = dragula([document.querySelector('#ready'), document.querySelector('#processing'), document
                .querySelector('#pending')
            ]);
            drake.on("drop", function(el, target, source, sibling) {
                "use strict";
                @this.changestatus(el.id, target.id);
            });
            $(document).ready(function() {
                "use strict";
                $('.page-body').css('overflow-y', 'hidden');
            })
        </script>
    @endpush
    <div class="modal fade" id="confirmdelivery" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delivery') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.confirm_delivery_msg') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="confirmDelivery()">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>