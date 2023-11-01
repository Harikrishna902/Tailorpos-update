<div>
    <div class="page-body" x-data>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.online_orders') }}</h5>
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
                                        <th class="text-primary" scope="col">{{ __('main.invoice') }}
                                        </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.customer') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.sub_total') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.total') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $item)
                                        <tr wire:key="invoice{{ $item->id }}">
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">#{{ $item->order_number }}</div>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ $item->branch->name }}</div>
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
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center mb-50 ledger-text">
                                                    <div class="me-1">{{ __('main.total') }}:</div>
                                                    <div class="fw-bolder">{{ getFormattedCurrency($item->total) }}
                                                    </div>
                                                </div>
                                               
                                            </td>
                                            <td>
                                                
                                                <a href="{{ route('admin.view-online-order', $item->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-eye me-2"></i>{{ __('main.view_bill') }}
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
                        @if (count($orders) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
  
   
</div>