<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col-12 mb-4">
                                    <h5 class="mb-0">
                                        {{ __('main.customer_payment_report') }}</h5>
                                </div>
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.start_date') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="date" wire:model="start_date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label class="form-label">{{ __('main.end_date') }}</label>
                                        <div class="input-group">
                                            <input class="form-control" type="date" wire:model="end_date" />
                                        </div>
                                    </div>
                                </div>
                                @if (Auth::user()->user_type == 2)
                                    <div class="col-3">
                                        <div class="mb-0">
                                            <label class="form-label">{{ __('main.branch') }}</label>
                                            <select required class="form-select" wire:model="branch">
                                                <option value="">
                                                    {{ __('main.all_branches') }}</option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-3">
                                    <div class="mb-0">
                                        <label
                                            class="form-label">{{ __('main.received_via') }}</label>
                                        <select required class="form-select" wire:model="payment_mode">
                                            <option value="">{{ __('main.all') }}</option>
                                            <option value="1">{{ __('main.cash') }}</option>
                                            <option value="2">{{ __('main.card') }}</option>
                                            <option value="3">{{ __('main.upi') }}</option>
                                            <option value="4">{{ __('main.cheque') }}</option>
                                            <option value="5">{{ __('main.bank_transfer') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table table-bordered">
                                <thead class="bg-light text-xs">
                                    <th class="text-primary w-table-8"  scope="col">
                                        {{ __('main.voucher_no') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.date') }}</th>
                                    <th class="text-primary w-table-20" scope="col">
                                        {{ __('main.customer') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.paid_amount') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.payment_method') }}</th>
                                    <th class="text-primary w-table-15" scope="col">
                                        {{ __('main.payment_type') }}</th>
                                    <th class="text-primary w-table-10" scope="col">
                                        {{ __('main.branch') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 report-scroll">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($payments as $item)
                                        <tr class="tag-text">
                                            <td class="w-table-8">#{{ $item->voucher_no }}</td>
                                            <td class="w-table-15">{{ $item->date->format('d/m/Y') }}</td>
                                            <td class="w-table-20">
                                                <span class="me-1">[{{ $item->customer->file_number ?? '' }}]</span>
                                                <span>{{ $item->customer->first_name ?? '' }}</span>
                                            </td>
                                            <td class="w-table-10">{{ getFormattedCurrency($item->paid_amount) }}</td>
                                            <td class="w-table-15"><span
                                                    class="text-uppercase">{{ getPaymentMode($item->payment_mode) }}</span>
                                            </td>
                                            @if ($item->payment_type == 1)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ __('main.invoice') }}</div>
                                                    <div class="mt-50 text-xs fw-bold">
                                                        #{{ $item->invoice->invoice_number ?? '' }}</div>
                                                </td>
                                            @elseif($item->payment_type == 2)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ __('main.opening_balance') }}</div>
                                                </td>
                                            @elseif($item->payment_type == 3)
                                                <td class="w-table-15">
                                                    <div class="mb-0 text-uppercase">
                                                        {{ __('main.cash_receipt') }}</div>
                                                </td>
                                            @endif
                                            <td class="w-table-10">
                                                <span
                                                    class="badge bg-secondary text-uppercase">{{ $item->createdBy->name ?? '' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-3">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">{{ __('main.total_payments') }}:
                                        </div>
                                        <div class="fw-bold">{{ $payments->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12">
                                    <div class="">
                                        <div class="fw-bold">
                                            {{ __('main.total_amount_received') }}:</div>
                                        <div class="fw-bold">{{ getFormattedCurrency($payments->sum('paid_amount')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    @if ($payment_mode == '')
                                        @php
                                            $payment_mode = 'all';
                                        @endphp
                                    @endif
                                    <button class="btn btn-secondary me-2" type="button"
                                        wire:click.prevent="downloadFile()">{{ __('main.download_report') }}</button>
                                    <a href="{{ url('admin/reports/print/payments/' . $start_date . '/' . $end_date . '/' . $payment_mode . '/' . $branch) }}"
                                        target="_blank"> <button class="btn btn-primary"
                                            type="button">{{ __('main.print_report') }}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>