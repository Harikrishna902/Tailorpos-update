<div class="col-lg-4">
    <div class="card">
        <div class="card-header p-2">
            <div class="row gx-3 mb-0 align-items-start">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <div class="customer-icon rounded text-center text-primary p-4" wire:ignore>
                            <i class="" data-feather="user"></i>
                        </div>
                        <div class="ms-2">
                            <div class="mb-1 fw-bold text-sm">
                                {{ $customer->file_number }}
                            </div>
                            <div class="mb-2 h6 fw-bolder">
                                {{ $customer->first_name }}
                            </div>
                            <div class="mb-0 text-sm fw-bold">
                                {{ getCountryCode() }} {{ $customer->phone_number_1 }} @if ($customer->phone_number_2)
                                    , {{ getCountryCode() }} {{ $customer->phone_number_2 }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto mt-1 me-1">
                    <div class="media-body switch-lg">
                        <label class="switch" id="active">
                            <input id="active" type="checkbox"
                                @if ($customer->is_active == 1) checked @endif
                                wire:click="toggle({{ $customer->id }})" /><span class="switch-state"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-light mt-0 mb-0">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon rounded text-primary" wire:ignore><i
                                data-feather="trending-up"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-secondary">
                                {{ __('main.total_invoices') }}</div>
                            <div class="fw-bold">
                                {{ \App\Models\Invoice::where('customer_id', $customer->id)->count() }}</div>
                        </div>
                    </div>
                    @php
                        $total = \App\Models\Invoice::where('customer_id', $customer->id)->sum('total');
                        $paid = \App\Models\InvoicePayment::where('customer_id', $customer->id)->sum('paid_amount');
                        $invoice_paid = \App\Models\InvoicePayment::where('customer_id', $customer->id)
                            ->where('payment_type', 1)
                            ->sum('paid_amount');
                        $opening_received = \App\Models\InvoicePayment::where('customer_id', $customer->id)
                            ->where('payment_type', 2)
                            ->sum('paid_amount');
                        $opening_balance = $customer->opening_balance != '' ? $customer->opening_balance : 0;
                        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id', $customer->id)->sum('amount');
                        $salesreturn = \App\Models\SalesReturnPayment::where('customer_id', $customer->id)->sum('paid_amount');
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon rounded text-primary" wire:ignore><i
                                data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-secondary">
                                {{ __('main.ob_balance') }}</div>
                            <div class="fw-bold">
                                {{ getFormattedCurrency($opening_balance - $opening_received) }} </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon rounded text-primary" wire:ignore><i
                                data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-secondary">
                                {{ __('main.invoice_balance') }}</div>
                            <div class="fw-bold">{{ getFormattedCurrency($total - $invoice_paid) }} </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon rounded text-primary" wire:ignore><i
                                data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-secondary">
                                {{ __('main.total_balance') }}</div>
                            <div class="fw-bold">{{ $customer->getBalance() }} </div>
                        </div>
                    </div>
                    @if (isset($customer->group) && $customer->group->name != '')
                        <div class="d-flex align-items-center mb-3">
                            <div class="content-icon rounded text-primary" wire:ignore> <i
                                    data-feather="briefcase"></i></div>
                            <div class="ms-2">
                                <div class="ledger-text text-secondary">{{ __('main.group') }}
                                </div>
                                <div class="fw-bold">{{ $customer->group->name ?? '' }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($customer->family_name != '')
                        <div class="d-flex align-items-center mb-3">
                            <div class="content-icon rounded text-primary" wire:ignore><i
                                    data-feather="home"></i></div>
                            <div class="ms-2">
                                <div class="ledger-text text-secondary">{{ __('main.family') }}
                                </div>
                                <div class="fw-bold">{{ $customer->family_name ?? '' }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($customer->email != '')
                        <div class="d-flex align-items-center mb-3">
                            <div class="content-icon rounded text-primary" wire:ignore><i
                                    data-feather="mail"></i></div>
                            <div class="ms-2">
                                <div class="ledger-text text-secondary">{{ __('main.email') }}
                                </div>
                                <div class="fw-bold">{{ $customer->email ?? '' }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($customer->address != '')
                        <div class="d-flex align-items-center mb-0">
                            <div class="content-icon rounded text-primary" wire:ignore><i
                                    data-feather="map-pin"></i></div>
                            <div class="ms-2">
                                <div class="ledger-text text-secondary">{{ __('main.address') }}
                                </div>
                                <div class="fw-bold">{{ $customer->address ?? '' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>