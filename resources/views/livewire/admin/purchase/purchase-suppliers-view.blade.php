<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.supplier_ledger') }}</h5>
                </div>
                <div class="col-auto">
                    <a data-bs-toggle="modal" data-bs-target="#addpayment" class="btn btn-primary px-2" type="button">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment') }}
                    </a>
                </div>
                <div class="col-auto">
                    <a data-bs-toggle="modal" data-bs-target="#editsupplier" class="btn btn-secondary px-2"
                        type="button">
                        <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.suppliers') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                @livewire('components.supplier-ledger', ['id' => $supplier_id])
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2 mb-0">
                                <div class="col-auto">
                                    <a href="{{ route('admin.suppliers_view', $supplier_id) }}" type="button"
                                        class="btn btn-nav-secondary active">{{ __('main.purchase_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.suppliers_viewpayment', $supplier_id) }}" type="button"
                                        class="btn btn-nav-secondary">{{ __('main.payments_list') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary w-table-10" scope="col"># </th>
                                        <th class="text-primary w-table-30" scope="col">
                                            {{ __('main.purchase_info') }}</th>
                                        <th class="text-primary w-table-20" scope="col">
                                            {{ __('main.total_amount') }}</th>
                                        <th class="text-primary w-table-30" scope="col">
                                            {{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 customer-view-scroll">
                            @php
                                $purchases = \App\Models\Purchase::where('supplier_id', $supplier_id)
                                    ->latest()
                                    ->get();
                            @endphp
                            <table class="table">
                                <tbody>
                                    @foreach ($purchases as $row)
                                        <tr>
                                            <th class="w-table-10" scope="row">{{ $loop->index + 1 }}</th>
                                            <td class="w-table-30">
                                                <div class="mb-0 fw-bold">{{ $row->purchase_number }}</div>
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($row->purchase_date)->format('d/m/Y') }}
                                                </div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $row->createdBy->name ?? '' }}
                                                </div>
                                            </td>
                                            <td class="w-table-20">
                                                <div class="mb-0 fw-bolder">{{ getFormattedCurrency($row->total) }}
                                                </div>
                                            </td>
                                            @if ($row->purchase_type == 1)
                                                <td class="w-table-30">
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
                                                <td class="w-table-30">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('components.edit-supplier', ['id' => $supplier_id])
    @livewire('components.supplier-add-payment', ['id' => $supplier_id])
</div>