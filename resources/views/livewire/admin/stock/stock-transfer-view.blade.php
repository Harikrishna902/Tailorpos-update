<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.stock_transfer_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.stock_transfer') }}" class="btn btn-custom-primary px-2" type="button">
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
                                            <h4 class="text-uppercase mb-2 text-primary">{{ $firm_name }}</h4>
                                            <p class="mb-0">{{ __('main.transferred_to') }}:</p>
                                            <div class="mb-0">
                                                <span
                                                    class="badge bg-secondary p-2 text-uppercase">{{ $transfer->branch->name ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-0">
                                        <span>{{ __('main.date') }}:</span>
                                        <span>{{ \Carbon\Carbon::parse($transfer->date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="mb-3 text-secondary">
                                        <span>{{ __('main.by') }} {{ $transfer->createdBy->name ?? '' }}</span>
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
                                            {{ __('main.materials') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $materials = \App\Models\StockTransferDetail::where('stock_transfer_id', $transfer->id)
                                            ->latest()
                                            ->get();
                                    @endphp
                                    @foreach ($materials as $row)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $row->material_name }}</div>
                                            </td>
                                            <td>
                                                {{ $row->quantity }} {{ getUnitType($row->material_unit) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row g-3 mb-0 align-items-center justify-content-between">
                                <div class="col-lg-4 col-12">
                                    <div class="row align-items-center mb-0">
                                        <div class="col-auto fw-bold">{{ __('main.total_items') }}:
                                        </div>
                                        <div class="col fw-bolder text-secondary">{{ $transfer->total_items }}
                                            {{ __('main.items') }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>