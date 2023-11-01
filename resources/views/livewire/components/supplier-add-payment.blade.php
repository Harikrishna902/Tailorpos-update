
<div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('main.add_payment') }} </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row align-items-start g-3">
                        @if($supplier_id!="")
                        @php
                            $chosenSupplier = \App\Models\Supplier::find($supplier_id);
                        @endphp
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center div-border p-2 rounded">
                                <div class="supplier-icon rounded text-center text-secondary p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck mb-0"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                </div>
                                <div class="ms-2">
                                    <div class="mb-1">
                                        <span class="text-l fw-bold">{{$chosenSupplier->name??"Supplier Name"}}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-sm">{{$chosenSupplier->tax_number??""}}</span>
                                    </div>
                                    <div class="mb-0">
                                      @if($chosenSupplier)  <span class="text-sm">{{getCountryCode()}} {{$chosenSupplier->phone??"Phone"}}</span> @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $paid_inline=0;
                            $balance_inline = 0;
                            $total_inline = 0;
                        @endphp
                        @if($supplier_id!="")
                        @php
                            $paid_inline = \App\Models\SupplierPayment::where('supplier_id',$supplier_id)->sum('paid_amount');
                            $total_inline = \App\Models\Purchase::where('purchase_type',2)->where('supplier_id',$supplier_id)->sum('total');
                            $openbal = \App\Models\Supplier::where('id',$supplier_id)->first()->opening_balance;
                            $balance_inline = ($total_inline + $openbal) - $paid_inline;
                        @endphp
                        @endif
                        <div class="col-lg-6">
                            <div class="div-border p-2 rounded">
                                <div class="row align-items-center mb-2">
                                    <div class="col">{{ __('main.total') }}:</div>
                                    <div class="col-auto fw-bold">{{getFormattedCurrency($total_inline)}} </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <div class="col">{{ __('main.opening_balance') }}:</div>
                                    <div class="col-auto fw-bold">{{getFormattedCurrency($openbal)}} </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <div class="col">{{ __('main.paid') }}:</div>
                                    <div class="col-auto fw-bold">{{getFormattedCurrency($paid_inline)}} </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col">{{ __('main.balance') }}:</div>
                                    <div class="col-auto fw-bolder text-secondary">{{getFormattedCurrency($balance_inline)}} </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-6">
                            <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control digits" type="date" data-language="en" placeholder="Select Date" wire:model="date"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ __('main.amount') }} <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control" type="number" placeholder="Enter Amount" wire:model="paid_amount"/>
                                <span class="input-group-text">{{getCurrency()}}</span>
                            </div>
                            @error('paid_amount') <span class="error text-danger">{{ $message }}</span>@enderror
                            @error('balance') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ __('main.payment_method') }} <span class="text-danger">*</span></label>
                            <select required class="form-select" wire:model="payment_mode">
                                <option value="">{{ __('main.select_a_method') }} </option>
                                <option value="1">{{ __('main.cash') }}</option>
                                <option value="2">{{ __('main.card') }}</option>
                                <option value="3">{{ __('main.upi') }}</option>
                                <option value="4">{{ __('main.cheque') }}</option>
                                <option value="5">{{ __('main.bank_transfer') }}</option>
                            </select>
                            @error('payment_mode') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ __('main.reference_number') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('main.enter_reference_number') }}" wire:model="reference_number"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                    <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>