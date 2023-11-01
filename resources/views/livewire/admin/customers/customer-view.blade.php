<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.customer_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a data-bs-toggle="modal" data-bs-target="#addpaymentdiscount" class="btn btn-light px-2 text-primary"
                        wire:click="$emit('resetdiscount')" type="button">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment_discount') }}
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.add_payments') }}" class="btn btn-primary px-2" type="button">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_receipt') }}
                    </a>
                </div>
                @if (Auth::user()->id == $customer->created_by)
                    <div class="col-auto">
                        <a href="{{ route('admin.edit_customer', $customer_id) }}" class="btn btn-secondary px-2"
                            type="button">
                            <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                        </a>
                    </div>
                @endif
                <div class="col-auto">
                    <a href="{{ route('admin.customers') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                @livewire('components.customer-profile', ['id' => $customer_id])
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2 mb-0">
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary active">{{ __('main.invoice_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_payments', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary">{{ __('main.payment_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_measurement', $customer_id) }}"
                                        type="button"
                                        class="btn btn-nav-primary">{{ __('main.measurements') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_discount', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary">{{ __('main.payment_discount') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary w-table-10" scope="col"># </th>
                                        <th class="text-primary w-table-25" scope="col">
                                            {{ __('main.invoice_info') }}</th>
                                        <th class="text-primary w-table-20" scope="col">
                                            {{ __('main.total_amount') }}</th>
                                        <th class="text-primary w-table-30" scope="col">
                                            {{ __('main.payments') }} </th>
                                        <th class="text-primary w-table-15" scope="col">
                                            {{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 customer-view-scroll">
                            <table class="table">
                                <tbody>
                                    @foreach ($invoices as $item)
                                        <tr>
                                            <th class="w-table-10" scope="row">{{ $loop->index + 1 }}</th>
                                            <td class="w-table-25">
                                                <div class="mb-0 fw-bold">#{{ $item->invoice_number }}</div>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $item->createdBy->name }}</div>
                                            </td>
                                            <td class="w-table-20">
                                                <div class="mb-0 fw-bolder">{{ getFormattedCurrency($item->total) }}
                                                </div>
                                            </td>
                                            <td class="w-table-30">
                                                <div class="d-flex align-items-center mb-50 ledger-text">
                                                    @php
                                                        $paid = \App\Models\InvoicePayment::where('invoice_id', $item->id)->sum('paid_amount');
                                                    @endphp
                                                    <div class="me-1">{{ __('main.paid') }}:</div>
                                                    <div class="fw-bolder">{{ getFormattedCurrency($paid) }}</div>
                                                </div>
                                                <div class="d-flex align-items-center mb-0 ledger-text">
                                                    <div class="me-1">{{ __('main.balance') }}:</div>
                                                    <div class="fw-bolder">
                                                        {{ getFormattedCurrency($item->total - $paid) }}</div>
                                                </div>
                                            </td>
                                            <td class="w-table-15">
                                                <a href="{{ route('admin.sales_view', $item->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('components.customer-discount', ['id' => $customer_id])
</div>