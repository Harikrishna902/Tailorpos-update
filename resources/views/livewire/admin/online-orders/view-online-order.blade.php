<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.invoice_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.sales') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-3 mb-0">
                                <div class="col">
                                    <div class="d-flex align-items-center">
                                        <img class="img-90 rounded" src="{{ $firm_logo }}" alt="" />
                                        <div class="ms-3 text-left">
                                            <h4 class="text-uppercase mb-1 text-primary">{{ $firm_name }} </h4>
                                            <p class="mb-2 text-uppercase">{{ $invoice->address }}</p>
                                            <p class="mb-2 text-uppercase">{{ $invoice->createdBy->name ?? '' }}</p>

                                            @if ($tax != '')
                                            <p class="mb-0 text-uppercase">{{ __('main.tax') }}:
                                                {{ $tax }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <h6 class="mb-1 text-xl">
                                        <span>{{ __('main.invoice') }} #</span>
                                        <span>{{ $invoice->order_number }}</span>
                                    </h6>
                                    <div class="mb-0">
                                        <span>{{ __('main.date') }}:</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="mb-0">
                                        <span>{{ __('main.time') }}:</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->date)->format('h:i A') }}</span>
                                    </div>

                                    <div class="mb-2 text-secondary">
                                        <span>{{ __('main.branch') }}:
                                            {{ $invoice->branch->name ?? '' }}</span>
                                    </div>

                                    <div class="mb-2 text-secondary">
                                        <label class="form-label">{{ __('main.status') }}</label>
                                        <select class="form-select" wire:model="order_status" wire:change="changeStatus()">
                                            <option value="0">{{ __('main.pending') }}</option>
                                            <option value="1">{{ __('main.processing') }}</option>
                                            <option value="2">{{ __('main.ready_to_deliver') }}</option>
                                            <option value="3">{{ __('main.delivered') }}</option>
                                        </select>
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
                                            {{ __('main.particulars') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.rate') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.qty') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.tax') }} %</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.tax_amount') }}
                                        </th>
                                        <th class="text-primary" scope="col">{{ __('main.total') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->details as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }} </th>
                                        <td>
                                            <div class="mb-0 fw-bold">{{ $item->item_name }}</div>
                                            <div class="mb-0 " style="max-width: 30rem; font-size: 12px;">{{ $item->notes }}</div>
                                        </td>
                                        <td>
                                            {{ getFormattedCurrency($item->rate) }}
                                        </td>
                                        <td>
                                            {{ $item->quantity }} @if ($item->unit_type)
                                            {{ getUnitType($item->unit_type) }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $invoice->tax_percentage }}
                                        </td>
                                        <td>
                                            {{ getFormattedCurrency($item->tax_amount) }}
                                        </td>
                                        <td>
                                            {{ getFormattedCurrency($item->total) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row g-3 mb-0 justify-content-between">
                                <div class="col-lg-5 col-12">
                                    <div class="mb-4">
                                        <h6 class="text-l mb-3">{{ __('main.invoice_to') }}:</h6>
                                        <div class="d-flex align-items-start mb-2">
                                            <div class="customer-icon rounded text-center text-primary">
                                                <i class="mb-0" data-feather="user"></i>
                                            </div>
                                            <div class="ms-2">

                                                <div class="mb-0 fw-bolder">
                                                    <span>{{ $invoice->customer_name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="">
                                        <h6 class="text-l">{{ __('main.notes_remarks') }}:</h6>
                                        <p class="mb-0">{{ $invoice->notes ?? '-' }}</p>
                                    </div>
                                    <div class="mb-0">
                                        <span>{{ __('main.preferred_delivery_time') }}:</span>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($invoice->preferred_delivery_time)->format('d/m/Y h:i A') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.sub_total') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($invoice->sub_total) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.discount') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($invoice->discount) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.taxable_amount') }}:
                                        </div>
                                        <div class="col-auto">{{ getFormattedCurrency($invoice->taxable_amount) }}
                                        </div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.tax_amount') }}
                                            ({{ $invoice->tax_percentage }}%):</div>
                                        <div class="col-auto">{{ getFormattedCurrency($invoice->tax_amount) }}</div>
                                    </div>
                                    <hr class="bg-light mt-2 mb-1">
                                    <div class="row align-items-center mb-2 mt-1">
                                        <div class="col fw-bold">{{ __('main.gross_total') }}:</div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($invoice->total) }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center gx-3 mt-2 text-right w-full bg-red-600">

                                        <div class="row align-items-center gx-3 mt-2">
                                            <input type="hidden" id="inv_id" value="{{ $invoice->id }}" />
                                            @if(getDefaultPrinter()==1)
                                            <div class="col-6">
                                                <a wire:click.prevent="downloadPdf()"> <button class="btn btn-secondary me-2 w-100">{{ __('main.download_pdf') }}</button> </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('admin.print-online-order',$invoice->id)}}" target="_blank"> <button class="btn btn-primary w-100" type="button">{{ __('main.print_invoice') }}</button>
                                            </div>
                                            @else
                                            <div class="col-6">
                                                <a wire:click.prevent="downloadPdf()"> <button class="btn btn-secondary me-2 w-100">{{ __('main.download_pdf') }}</button> </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('admin.print-online-order-a4',$invoice->id) }}" target="_blank"> <button class="btn btn-primary w-100" type="button">{{ __('main.print_invoice') }}</button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.livewire.on('printWindow', id => {
            "use strict";
            var $id = id;
            $('#save_btn').hide();
            window.open(
                '{{ url('
                admin / invoice / print - invoice / ') }}' + '/' + $id,
                '_blank'
            );
            window.location.href = '{{ url('
            admin / sales / view / ') }}' + '/' + $id;
        });
    </script>
</div>