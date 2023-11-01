<div x-data="dashboardInit">
    @php
        $settings = new App\Models\MasterSetting();
        $site = $settings->siteData();
    @endphp
    <div class="page-body">
        <div class="container-fluid general-widget">
            <div class="row align-items-start">
                <div class="col-lg-12">
                    <div class="row g-3 ">
                        <div class="col-lg-3">
                            <div class="card o-hidden border-0">
                                <div class="bg-primary card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <h4>
                                                {{ getFormattedCurrency($total_sales) }}
                                            </h4>
                                            {{ __('main.total_sales') }}
                                        </div>
                                        <div class="">
                                            <i class="fa fa-line-chart text-3xl" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card o-hidden border-0">
                                <div class="bg-secondary card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <h4>
                                                {{ getFormattedCurrency($total_expense) }}
                                            </h4>
                                            {{ __('main.total_expense') }}
                                        </div>
                                        <div class="">
                                            <i class="fa fa-money text-3xl" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card o-hidden border-0">
                                <div class="bg-info card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <h4>
                                                {{ $total_branches }}
                                            </h4>
                                            {{ __('main.total_branches') }}
                                        </div>
                                        <div class="">
                                            <i class="fa fa-university text-3xl" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card o-hidden border-0">
                                <div class="bg-dark card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <h4>
                                                {{ getFormattedCurrency($total_payments) }}
                                            </h4>
                                            {{ __('main.total_payments') }}
                                        </div>
                                        <div class="">
                                            <i class="fa fa-money text-3xl" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card rounded target-sec h-100">
                                <div class="card-header">
                                    {{ __('main.recent_sales') }}
                                </div>
                                <div class="card-body p-0 rounded">
                                    <table class="table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-primary" scope="col"># </th>
                                                <th class="text-primary" scope="col">{{ __('main.invoice') }}
                                                </th>
                                                <th class="text-primary" scope="col">
                                                    {{ __('main.customer') }}</th>
                                                <th class="text-primary" scope="col">
                                                    {{ __('main.total') }}</th>
                                                <th class="text-primary" scope="col">{{ __('main.actions') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recent_sales as $item)
                                                <tr >
                                                    <th scope="row">{{$loop->index +1 }}</th>
                                                    <td>
                                                        <div class="mb-0 fw-bold">#{{ $item->invoice_number }}</div>
                                                        <div class="mb-0">
                                                            {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</div>
                                                        <div class="mt-50 text-xs text-secondary fw-bold">
                                                            {{ $item->createdBy->name }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-2 mb-0 fw-bold">
                                                                <div class="mb-0">{{ $item->customer_name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center mb-50 ledger-text">
                                                            <div class="me-1">{{__('main.total')}}:</div>
                                                            <div class="fw-bolder">{{ getFormattedCurrency($item->total) }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.sales_view', $item->id) }}"
                                                            class="btn btn-custom-primary btn-sm px-2" type="button"
                                                            data-bs-original-title="" title="">
                                                            <i class="fa fa-eye me-2"></i>{{ __('main.view_bill') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if (count($recent_sales) == 0)
                                        <x-empty-item-component :title="__('main.empty_item_title')" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card rounded target-sec h-100">
                                <div class="card-header pb-0">
                                </div>
                                <div class="card-body p-0 rounded">
                                    <div class="" id="chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{ asset('assets/js/chart/apex-chart.js') }}"></script>
        <script>
            function dashboardInit() {
            "use strict";
                return {
                    init() {
                        this.renderChart()
                    },
                    renderChart() {
                        var options = {
                            series: [{
                                    name: '{{ __('main.sales') }}',
                                    data: this.$wire.data
                                },
                                {
                                    name: '{{ __('main.payments') }}',
                                    data: this.$wire.payments_data
                                }
                            ],
                            chart: {
                                type: 'area',
                                stacked: false,
                                height: 350,
                                zoom: {
                                    type: 'x',
                                    enabled: false,
                                    autoScaleYaxis: true
                                },
                                toolbar: {
                                    autoSelected: 'zoom'
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            markers: {
                                size: 0,
                            },
                            title: {
                                text: '{{ __('main.weekly_sales') }}',
                                align: 'left'
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    inverseColors: false,
                                    opacityFrom: 0.5,
                                    opacityTo: 0,
                                    stops: [0, 90, 100]
                                },
                            },
                            yaxis: {
                                title: {
                                    text: '{{ __('main.sales') }}'
                                },
                            },
                            xaxis: {
                                type: 'datetime',
                            },
                        };
                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                    }
                }
            }
        </script>
    @endpush
</div>