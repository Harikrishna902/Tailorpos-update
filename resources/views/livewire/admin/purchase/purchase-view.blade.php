<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.purchase_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.purchases') }}" class="btn btn-custom-primary px-2" type="button">
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
                                        <div class="supplier-icon rounded text-center text-secondary p-purchase">
                                            <i class="mb-0" data-feather="truck"></i>
                                        </div>
                                        <div class="ms-2">
                                            <h5 class="mb-2">{{ $purchase->supplier->name ?? '' }}</h5>
                                            <div class="mb-3 text-sm">
                                                <span>{{ $purchase->supplier->tax_number ?? '' }}</span>
                                            </div>
                                            <div class="mb-0 text-sm">
                                                <span>{{ getCountryCode() }} {{ $purchase->supplier->phone ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <h6 class="mb-1 text-xl">
                                        <span>{{ __('main.purchase') }} #</span>
                                        <span>{{ $purchase->purchase_number }}</span>
                                    </h6>
                                    <div class="mb-0">
                                        <span>Date:</span>
                                        <span>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="mb-2 text-secondary">
                                        <span>{{ __('main.by') }}
                                            {{ $purchase->createdBy->name ?? '' }}</span>
                                    </div>
                                    <div class="mb-0">
                                        @if ($purchase->purchase_type == 2)
                                            <span
                                                class="badge bg-primary text-uppercase p-2">{{ __('main.pushed') }}</span>
                                        @endif
                                        @if ($purchase->purchase_type == 1)
                                            <span
                                                class="badge bg-secondary text-uppercase p-2">{{ __('main.draft') }}</span>
                                        @endif`
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
                                            {{ __('main.particulars') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.rate') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.qty') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.tax') }} %</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.tax_amount') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.total') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $details = \App\Models\PurchaseDetail::where('purchase_id', $current_id)->get();
                                    @endphp
                                    @foreach ($details as $row)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $row->material_name }}</div>
                                            </td>
                                            <td>
                                                {{ getFormattedCurrency($row->purchase_price) }}
                                            </td>
                                            <td>
                                                {{ $row->purchase_quantity }} {{ getUnitType($row->material_unit) }}
                                            </td>
                                            <td>
                                                {{ $row->purchase->tax_percentage ?? '' }}
                                            </td>
                                            <td>
                                                {{ getFormattedCurrency($row->tax_amount) }}
                                            </td>
                                            <td>
                                                {{ getFormattedCurrency($row->purchase_item_total) }}
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
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.sub_total') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($purchase->sub_total) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.tax_percentage') }}:
                                        </div>
                                        <div class="col-auto">{{ getFormattedCurrency($purchase->tax_percentage) }}
                                        </div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col">{{ __('main.tax_amount') }}:</div>
                                        <div class="col-auto">{{ getFormattedCurrency($purchase->tax_amount) }}</div>
                                    </div>
                                    <hr class="bg-light mt-2 mb-1">
                                    <div class="row align-items-center mb-2 mt-1">
                                        <div class="col fw-bold">{{ __('main.gross_total') }}:</div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($purchase->total) }}</div>
                                    </div>
                                    <hr class="bg-light mt-2 mb-1">
                                    <div class="row align-items-center mb-2 mt-1">
                                        <div class="col fw-bold">{{ __('main.discount') }}:</div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($purchase->discount) }}</div>
                                    </div>
                                    <hr class="bg-light mt-2 mb-1">
                                    <div class="row align-items-center mb-2 mt-1">
                                        <div class="col fw-bold">{{ __('main.service_charge') }}:
                                        </div>
                                        <div class="col-auto fw-bolder text-secondary">
                                            {{ getFormattedCurrency($purchase->service_charge) }}</div>
                                    </div>
                                    @if ($purchase->purchase_type == 1)
                                        <hr class="bg-light mt-2 mb-1">
                                        <div class="row align-items-center mb-2 mt-1">
                                            <div class="row align-items-center gx-3">
                                                <div class="col-6">
                                                    <a href="{{ route('admin.purchase_edit', $purchase->id) }}"
                                                        class="btn btn-secondary w-100"
                                                        type="button">{{ __('main.edit_purchase') }}</a>
                                                </div>
                                                <div class="col-6">
                                                    <button data-bs-toggle="modal" data-bs-target="#confirmpurchase"
                                                        class="btn btn-primary w-100"
                                                        type="button">{{ __('main.save_as_pushed') }}</button>
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
        </div>
    </div>
    <div class="modal fade" id="confirmpurchase" tabindex="-1" role="dialog" aria-hidden="true" wire:igore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_purchase') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_to_push_the_service_into_the_stock') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="changeStatus({{ $purchase->id }})">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>