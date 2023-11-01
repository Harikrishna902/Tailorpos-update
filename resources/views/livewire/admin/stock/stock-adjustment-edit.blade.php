<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.edit_stock_adjustment') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{route('admin.stock_adjustments')}}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-sm-12">
                    @php
                        $total_items = 0;
                    @endphp
                    <form>
                        <div class="card">
                            <div class="card-header">
                                <div class="row g-3 mb-0">
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input class="form-control" type="text" placeholder="{{ __('main.search_materials') }}" wire:model="material_query" />
                                            
                                        </div>
                                        @if($materials && count($materials) > 0)
                                            <ul class="list-group position-absolute ">
                                                @foreach ($materials as $item)
                                                    <li class="list-group-item hover-custom" wire:click="selectMaterial({{$item->id}})">{{$item->name}} </li>
                                                @endforeach
                                            </ul>
                                        @elseif($material_query!='' && count($materials) == 0 )
                                            <ul class="list-group position-absolute ">
                                                <li id="no-mat" class="list-group-item hover-disabled" >{{ __('main.no_materials_found') }}</li>
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="form-control" value="{{$date}}" required type="date" wire:model="date" />
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary w-table-10" scope="col"># </th>
                                            <th class="text-primary w-table-30" scope="col">{{ __('main.materials') }}</th>
                                            <th class="text-primary w-table-20" scope="col">{{ __('main.qty') }}</th>
                                            <th class="text-primary w-table-20" scope="col">{{ __('main.action_in_stock') }}</th>
                                            <th class="text-primary w-table-20" scope="col"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive mt-0 stock-add-scroll">
                                <table class="table">
                                    <tbody>
                                        @foreach ($cartItems as $key => $item)
                                        @php
                                            $itemtotalquantity = 0;
                                            $total_items ++;
                                            $isoutofstock = false;
                                            $openstock = \App\Models\Material::where('id',$item['id'])->first()->opening_stock ?? 0;
                                            $iteminpurchase = \App\Models\PurchaseDetail::where('material_id',$item['id'])->sum('purchase_quantity');
                                            $iteminbills = \App\Models\InvoiceDetail::where('type',2)->where('item_id',$item['id'])->sum('quantity');
                                            $iteminadjustadd = \App\Models\StockAdjustmentDetail::whereNotIn('stock_adjustment_id',[$stockadjust->id])->where('material_id',$item['id'])->where('type',2)->sum('quantity');
                                            $iteminadjustsub = \App\Models\StockAdjustmentDetail::whereNotIn('stock_adjustment_id',[$stockadjust->id])->where('material_id',$item['id'])->where('type',1)->sum('quantity');
                                            $itemtransfer = \App\Models\StockTransferDetail::where('material_id',$item['id'])->sum('quantity');
                                            $salesreturn = \App\Models\SalesReturnDetail::where('item_id',$item['id'])->whereType(2)->sum('quantity');
                                            $itemtotalquantity = ($iteminpurchase + $iteminadjustadd + $openstock + $salesreturn) - ($iteminbills + $iteminadjustsub + $itemtransfer);
                                            $notenoughstock = false;
                                            if($itemtotalquantity <= 0 && $type[$key] == 1)
                                            {
                                                $isoutofstock=true;
                                            }
                                            if($type[$key] == 1 && $itemtotalquantity > 0)
                                            {
                                                if($qty[$key] > $itemtotalquantity)
                                                {
                                                    $notenoughstock = true;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <th class="w-table-20" scope="row">{{$loop->index + 1}}</th>
                                            <td class="w-table-30">
                                                <div class="mb-0 fw-bold">{{$item['name']}}</div>
                                            </td>
                                            <td class="w-table-20">
                                                <div class="input-group">
                                                    <input class="form-control @error('qty.'.$key) is-invalid @endif @if($notenoughstock == true ) is-invalid @endif input-sm-2" required wire:model="qty.{{$key}}" type="number" value="100" placeholder="Enter Value" />
                                                    <span class="input-group-text input-sm-2">{{getUnitType($item['unit'])}}</span>
                                                </div>
                                            </td>
                                            <td class="w-table-20">
                                                <select required class="form-select @if($isoutofstock == true || $notenoughstock == true) is-invalid @endif" wire:model="type.{{$key}}">
                                                    <option value="1" >{{ __('main.deduction') }}</option>
                                                    <option value="2">{{ __('main.addition') }}</option>
                                                </select>
                                            </td>
                                            <td class="w-table-20" x-data="{open : false}">
                                                @if($isoutofstock == true || $notenoughstock == true)
                                                <a href="#" class="text-warning" type="button" @mouseover="open=true" @mouseover.away="open=false"><i class="fa fa-exclamation-triangle"></i></a>
                                                <div class="position-absolute mt-4 bg-dark text-white p-2 rounded" x-show="open" x-transition>
                                                    @if($notenoughstock == false)
                                                    Material Is Out Of Stock
                                                    @else
                                                    Not Enough Material In Stock, <br> Quantity Left : {{$itemtotalquantity}}
                                                    @endif
                                                </div>
                                                @endif
                                                <a href="#" class="text-danger mx-2" wire:click.prevent="remove({{$key}})"  type="button"><i class="fa fa-times"></i></a>
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
                                            <div class="col-auto fw-bold">{{ __('main.total_items') }}:</div>
                                            <div class="col fw-bolder text-secondary">{{$total_items}} {{ __('main.items') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="row align-items-center gx-3">
                                            <div class="col-6">
                                                <button class="btn btn-secondary w-100" wire:click.prevent="$emit('reloadpage')" type="reset">{{ __('main.clear_all') }}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary w-100" wire:click.prevent="save" wire:loading.class="disabled" wire:target="save" type="submit">
                                                    <div  wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status">
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
    </div>
</div>