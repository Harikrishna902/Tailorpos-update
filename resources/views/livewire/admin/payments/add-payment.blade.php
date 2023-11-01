<div>
    <div class="page-body">
        <div class="container-fluid">
            <form>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row gx-3 mb-3 align-items-center">
                                    <div class="col">
                                        <h5 class="mb-0">{{ __('main.add_receipt') }}</h5>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{route('admin.payments')}}" class="btn btn-custom-primary px-2" type="button">
                                            <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-start">
                                    <div class="col-lg-6">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.select_customer') }}<span class="text-danger">*</span> </label>
                                                    <input type="text" required class="form-control" placeholder="@if($selected_customer) {{$selected_customer->first_name}} @else {{ __('main.search_customer') }} @endif" wire:model="customer_query"/>
                                                    @if($customers && count($customers) > 0)
                                                    <ul class="list-group position-absolute ">
                                                        @foreach ($customers as $item)
                                                            <li class="list-group-item hover-custom" wire:click="selectCustomer({{$item->id}})">{{$item->file_number}} - {{$item->first_name}} - {{$item->phone_number_1}} </li>
                                                        @endforeach
                                                    </ul>
                                                    @elseif($customer_query!='' && count($customers) == 0 )
                                                    <ul class="list-group position-absolute ">
                                                        <li id="no-mat" class="list-group-item hover-disabled" >{{ __('main.no_customers_found') }}</li>
                                                    </ul>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input class="form-control digits" wire:model="date" type="date" data-language="en" placeholder="Select Date" @if(($customer_id=="" || $disable == true) && $deduct_type != 3) readonly @endif wire:model="date"/>
                                                    </div>
                                                    @error('date') <span class="text-danger">{{$message}}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.paid_amount') }}<span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="number" placeholder="{{ __('main.enter_amount') }}" wire:model="amount" @if(($customer_id=="" || $disable == true) && $deduct_type != 3) readonly @endif/>
                                                        <span class="input-group-text">{{getCurrency()}}</span>
                                                    </div>
                                                    @error('amount') <span class="text-danger">{{$message}}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label mb-3">{{ __('main.deduct_from') }} <span class="text-danger">*</span></label>
                                                <div class="d-flex align-items-center">
                                                    <label class="d-block" for="no">
                                                        <input class="radio_animated " id="no" type="radio" value="1" name="vat" wire:model="deduct_type" @if($customer_id=="" || $disable == true || $openingdisable == true) disabled @endif/> <span @if($openingdisable == true && $selected_customer)class="text-danger" @endif> {{ __('main.opening_balance') }}</span>
                                                    </label>
                                                    <label class="d-block ms-5" for="yes">
                                                        <input class="radio_animated" id="yes" type="radio" value="2" name="vat" wire:model="deduct_type" @if($customer_id=="" || $disable == true || $invoicedisable == true) disabled @endif /> <span @if($invoicedisable == true && $selected_customer)class="text-danger" @endif>{{ __('main.invoice') }}</span> 
                                                    </label>
                                                    <label class="d-block ms-5" for="yes2">
                                                        <input class="radio_animated" id="yes2" type="radio" value="3" name="vat" wire:model="deduct_type" @if($customer_id=="") disabled @endif /> <span >{{ __('main.ledger_payment') }}</span> 
                                                    </label>
                                                    @error('deduct_type') <span class="text-danger">{{$message}}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('main.payment_method') }} <span class="text-danger">*</span> </label>
                                                    <select required class="form-select" wire:model="payment_mode" @if(($customer_id=="" || $disable == true) && $deduct_type != 3) disabled @endif>
                                                            <option value="">{{ __('main.select_a_method') }} </option>
                                                            <option value="1">{{ __('main.cash') }}</option>
                                                            <option value="2">{{ __('main.card') }}</option>
                                                            <option value="3">{{ __('main.upi') }}</option>
                                                            <option value="4">{{ __('main.cheque') }}</option>
                                                            <option value="5">{{ __('main.bank_transfer') }}</option>
                                                    </select>
                                                </div>
                                                @error('payment_mode') <span class="text-danger">{{$message}}</span> @enderror
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">{{ __('main.reference_number') }}</label>
                                                <input class="form-control" type="text" placeholder="{{ __('main.enter_reference_number') }}" wire:model="reference_number" @if(($customer_id=="" || $disable == true) && $deduct_type != 3) readonly @endif/>
                                            </div>
                                        </div>
                                    </div>
                                    @if($customer_id!="")
                                    <div class="col-lg-6 col-12">
                                        <label class="form-label">{{ __('main.customer_info') }} </label>
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="div-border p-2 rounded">
                                                    <div class="d-flex align-items-center">
                                                        <div class="customer-icon rounded text-center text-primary p-4" wire:ignore>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user mb-0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="mb-1">
                                                                <span>{{$selected_customer->file_number??""}}</span>
                                                            </div>
                                                            <div class="mb-2 fw-bolder">
                                                                <span>{{$selected_customer->first_name??""}}</span>
                                                            </div>
                                                            <div class="mb-0 text-sm">
                                                                <span>{{getCountryCode()}} {{$selected_customer->phone_number_1??""}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="bg-light mt-2 mb-3">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col">{{ __('main.balance_ob') }}:</div>
                                                        <div class="col-auto fw-bold">{{getFormattedCurrency($opening_balance)}}</div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col">{{ __('main.invoice_balance') }}:</div>
                                                        <div class="col-auto fw-bold">{{getFormattedCurrency($total - $invoice_paid)}}</div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col">{{ __('main.total_balance') }}:</div>
                                                        <div class="col-auto fw-bold">{{$selected_customer->getBalance()}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center">
                                    <div class="col">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary me-2" type="reset" wire:click.prevent="$emit('reloadpage')">{{ __('main.clear_all') }}</button>
                                        <button class="btn btn-primary" @if(($disable == true || $prevent == true) && $deduct_type !=3 )  disabled @endif wire:click.prevent="save" wire:loading.class="disabled" wire:target="save" type="submit">
                                            <div class="spinner-border spinner-border-sm" wire:loading wire:target="save" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            {{ __('main.submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>