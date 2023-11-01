<div class="col-lg-4">
    <div class="card">
        <div class="card-header p-2">
            <div class="row gx-3 mb-0 align-items-start">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <div class="supplier-icon rounded text-center text-secondary p-4" wire:ignore>
                            <i class="" data-feather="truck"></i>
                        </div>
                        <div class="ms-2">
                            <div class="mb-1 h6 fw-bolder">
                                {{$supplier->name??""}}
                            </div>
                            <div class="mb-2 text-sm">
                                {{$supplier->tax_number??""}}
                            </div>
                            <div class="mb-0 text-sm">
                                {{getCountryCode()}}{{$supplier->phone??""}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto mt-1 me-1">
                    <div class="media-body switch-lg">
                        <label class="switch" id="active">
                            <input id="active" type="checkbox" @if($supplier->is_active==1) checked @endif wire:click="toggle({{$supplier->id}})" /><span class="switch-state"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-light mt-0 mb-0">
        @php
              $opening_balance = $supplier->opening_balance;
              $total_purchase = \App\Models\Purchase::where('purchase_type',2)->where('supplier_id',$supplier_id)->sum('total');
              $purchase_count = \App\Models\Purchase::where('purchase_type',2)->where('supplier_id',$supplier_id)->count();
              $total_paid =  \App\Models\SupplierPayment::where('supplier_id',$supplier_id)->sum('paid_amount');
              $balance = ($total_purchase+$opening_balance) - $total_paid;
        @endphp
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.opening_balance') }}</div>
                            <div class="fw-bold">{{ getFormattedCurrency($opening_balance)}} </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="truck"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.total_purchases') }}</div>
                            <div class="fw-bold">{{$purchase_count}}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.total_amount') }}</div>
                            <div class="fw-bold">{{getFormattedCurrency($total_purchase)}} </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.paid_amount') }}</div>
                            <div class="fw-bold">{{getFormattedCurrency($total_paid)}} </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="dollar-sign"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.balance_amount') }}</div>
                            <div class="fw-bold">{{getFormattedCurrency($balance)}}</div>
                        </div>
                    </div>
                    @if($supplier->email!='')
                    <div class="d-flex align-items-center mb-3">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="mail"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.email') }}</div>
                            <div class="fw-bold">{{$supplier->email??""}}</div>
                        </div>
                    </div>
                    @endif
                    @if($supplier->supplier_address!='')
                    <div class="d-flex align-items-center mb-0">
                        <div class="content-icon-supplier rounded text-secondary" wire:ignore><i data-feather="map-pin"></i></div>
                        <div class="ms-2">
                            <div class="ledger-text text-primary">{{ __('main.address') }}</div>
                            <div class="fw-bold">{{$supplier->supplier_address}}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>