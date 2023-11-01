<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.sales_return_list') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{ route('admin.sales_returns') }}" class="btn btn-secondary px-2"
                                        type="button">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_sales_return') }}
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.sales') }}" class="btn btn-custom-primary px-2"
                                        type="button">
                                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
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
                                            {{ __('main.sales_return') }} </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.customer') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.total') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">#{{ $item->sales_return_number }}</div>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.towards') }} :
                                                    #{{ $item->invoice->invoice_number }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="customer-icon rounded text-center text-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-user mb-0">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="12" cy="7" r="4">
                                                            </circle>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-2 mb-0 fw-bold">
                                                        <div class="mb-50">{{ $item->customer_file_number }}</div>
                                                        <div class="mb-0">{{ $item->customer_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center mb-50 ledger-text">
                                                    <div class="me-1">{{ __('main.taxable') }}:</div>
                                                    <div class="fw-bolder">
                                                        {{ getFormattedCurrency($item->taxable_amount) }}</div>
                                                </div>
                                                <div class="d-flex align-items-center mb-0 ledger-text">
                                                    <div class="me-1">{{ __('main.tax') }}:</div>
                                                    <div class="fw-bolder">
                                                        {{ getFormattedCurrency($item->tax_amount) }}</div>
                                                </div>
                                                <div class="d-flex align-items-center mb-50 ledger-text">
                                                    <div class="me-1">{{ __('main.total') }}:</div>
                                                    <div class="fw-bolder">{{ getFormattedCurrency($item->total) }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.sales_return_view', $item->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-eye me-2"></i>{{ __('main.view_sales_return') }}
                                                </a>
                                                <a href="{{ url('admin/sales/returns/print/' . $item->id) }}"
                                                    target="_blank" class="btn btn-custom-primary btn-sm px-2"
                                                    type="button">
                                                    <i class="fa fa-print me-2"></i>{{ __('main.print') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($hasMorePages)
                                <div x-data="{
                                    init() {
                                        let observer = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                if (entry.isIntersecting) {
                                                    @this.call('loadOrders')
                                                    console.log('loading...')
                                                }
                                            })
                                        }, {
                                            root: null
                                        });
                                        observer.observe(this.$el);
                                    }
                                }"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                    <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                        Loading...
                                        <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if (count($invoices) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_payment') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @if ($invoice)
                        <div class="modal-body">
                            <div class="row align-items-start g-3">
                                <div class="col-lg-12 col-12">
                                    <div class="row g-3 align-items-center">
                                        <div class="col">
                                            <div class="d-flex align-items-center">
                                                <div class="customer-icon rounded text-center text-primary p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user mb-0">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4">
                                                        </circle>
                                                    </svg>
                                                </div>
                                                <div class="ms-2 mb-0 fw-bold">
                                                    <div class="mb-50">{{ $invoice->customer_file_number }}</div>
                                                    <div class="mb-0">{{ $invoice->customer_name }}</div>
                                                    <div class="mb-0">{{ getCountryCode() }}
                                                        {{ $invoice->customer_phone }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="">
                                                <h6 class="mb-50">{{ __('main.invoice') }} #
                                                    <span>{{ $invoice->invoice_number }}</span></h6>
                                                <div class="mb-0"><span>{{ __('main.date') }}:</span>
                                                    {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</div>
                                                <div class="mb-0"><span>{{ __('main.time') }}:</span>
                                                    {{ \Carbon\Carbon::parse($invoice->date)->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="bg-light mt-3 mb-0">
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.total_amount') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($invoice->total) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.paid_amount') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($mypaid) }}</div>
                                    </div>
                                    <hr class="bg-light mt-1 mb-1">
                                    <div class="row align-items-center mb-2">
                                        <div class="col fw-bold">
                                            {{ __('main.balance_amount') }}:</div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($balance) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="">
                                        <label
                                            class="form-label">{{ __('main.paid_amount') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="number"
                                                placeholder="{{ __('main.paid_amount') }}"
                                                wire:model="paid_amount" />
                                            <span class="input-group-text">{{ getCurrency() }}</span>
                                        </div>
                                        @error('paid_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="">
                                        <label
                                            class="form-label">{{ __('main.payment_method') }}</label>
                                        <select required class="form-select" wire:model="pay_mode">
                                            <option value="">{{ __('main.all') }}</option>
                                            <option value="1">{{ __('main.cash') }}</option>
                                            <option value="2">{{ __('main.card') }}</option>
                                            <option value="3">{{ __('main.upi') }}</option>
                                            <option value="4">{{ __('main.cheque') }}</option>
                                            <option value="5">{{ __('main.bank_transfer') }}
                                            </option>
                                        </select>
                                        @error('pay_mode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="">
                                        <label class="form-label">{{ __('main.reference') }}</label>
                                        <input class="form-control" type="number"
                                            placeholder="{{ __('main.reference') }}"
                                            wire:model="reference" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="save"
                            wire:loading.attr="disabled"
                            wire:target="save">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>