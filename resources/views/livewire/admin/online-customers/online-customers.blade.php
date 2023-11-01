<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.customers_list') }} </h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{ route('admin.create_customer') }}"  class="btn btn-primary px-2 opacity-0"
                                        type="button"
                                       >
                                        <i class="fa fa-plus me-2"></i>
                                        {{ __('main.add_new_customer') }}
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
                                            {{ __('main.customer') }}</th>
                                        <th class="text-primary" scope="col"> {{ __('main.contact') }}
                                        </th>
                                       
                                        <th class="text-primary" scope="col"> {{ __('main.status') }}
                                        </th>
                                        <th class="text-primary" scope="col"> {{ __('main.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $row)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="customer-icon rounded text-center text-primary"
                                                        wire:ignore>
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
                                                        <div class="mb-0">{{ $row->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-50">{{ getCountryCode() }} {{ $row->phone }}
                                                </div>
                                                <div class="mb-0">{{ $row->email ?? '' }}</div>
                                            </td>
                                            
                                            <td wire:key='checkboxfix{{ $row->id }}'>
                                                <div class="media-body switch-lg">
                                                    <label class="switch" id="active{{ $row->id }}">
                                                        <input id="active{{ $row->id }}" type="checkbox"
                                                            @if ($row->is_active == 1) checked @endif
                                                            wire:click="toggle({{ $row->id }})" /><span
                                                            class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.online-customers-measurement', $row->id) }}"
                                                    class="btn btn-custom-primary btn-sm px-2" type="button">
                                                    <i
                                                        class="fa fa-eye me-2"></i>{{ __('main.view_customer') }}
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
                                                    @this.call('loadCustomers')
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
                        @if (count($customers) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>