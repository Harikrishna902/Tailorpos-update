<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">Purchase Return </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{route('admin.purchases')}}" class="btn btn-custom-primary px-2" type="button">
                                        <i class="fa fa-arrow-left me-2"></i>Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="card-body pb-1">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-12">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <input type="date" readonly class="form-control" wire:model="date"/>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" readonly class="form-control" value="{{generatePurchaseReturnNumber()}}"/>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" placeholder="Search Supplier" wire:model="supplier_query"/>
                                                @error('supplier_id')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                                @if ($suppliers_results)
                                                @if(count($suppliers_results)>0)
                                                <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                    @forelse ($suppliers_results as $item)
                                                        <a class="dropdown-item" wire:click="selectSupplier({{ $item->id }})">{{ $item->name }}</a>
                                                    @empty
                                                    @endforelse
                                            </div>
                                            @else
                                            <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                No supplier found
                                            </div> 
                                            @endif
                                            @endif
                                            </div>
                                            <div class="col-6">
                                                <input type="text" required class="form-control" placeholder="Enter Purchase Number" wire:model="purchase_query" />
                                                @if ($purchase_results)
                                                @if(count($purchase_results)>0)
                                                <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                    @forelse ($purchase_results as $item)
                                                        <a class="dropdown-item" wire:click="selectPurchase({{ $item->id }})">{{ $item->purchase_number }}</a>
                                                    @empty
                                                    @endforelse
                                            </div>
                                            @else
                                            <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                No Purchase found
                                            </div> 
                                            @endif
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="row g-3">
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
                                                            <span class="text-sm">{{getCountryCode()}} {{$chosenSupplier->phone??"Phone"}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="div-border p-2 rounded">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col">Purchase #:</div>
                                                        <div class="col-auto fw-bold">{{$chosenPurchase->purchase_number??""}}</div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col">Date:</div>
                                                        <div class="col-auto fw-bold">@isset($chosenPurchase->purchase_date) {{\Carbon\Carbon::parse($chosenPurchase->purchase_date)->format('d/m/Y')}} @endisset</div>
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col">Purchase Total:</div>
                                                        <div class="col-auto fw-bolder text-secondary">@isset($chosenPurchase->total) {{number_format($chosenPurchase->total,2)}} @endisset</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table table-borderless">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary" scope="col">Particulars</th>
                                            <th class="text-primary" scope="col">P. QTY</th>
                                            <th class="text-primary" scope="col">P. Price</th>
                                            <th class="text-primary" scope="col">R. QTY</th>
                                            <th class="text-primary" scope="col">R. Price</th>
                                            <th class="text-primary" scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="add-input">
                                            <td>
                                                <input class="form-control" required type="text" placeholder="Select Particular" wire:model="material_query" />
                                                @if ($material_results)
                                                @if(count($material_results)>0)
                                                <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                    @forelse ($material_results as $item)
                                                        <a class="dropdown-item" wire:click="selectMaterial({{ $item->id }})">{{ $item->material_name}}</a>
                                                    @empty
                                                    @endforelse
                                            </div>
                                            @else
                                            <div class="dropdown-menu show" id="dropmenu" wire:ignore.self>
                                                No Purchase found
                                            </div> 
                                            @endif
                                            @endif
                                            </td>
                                            <td>
                                                <div class="fw-bolder">5 Unit</div>
                                            </td>
                                            <td>
                                                <div class="fw-bolder">500 SAR</div>
                                            </td>
                                            <td class="w-table-20">
                                                <div class="input-group">
                                                    <input class="form-control" required type="number" placeholder="Enter QTY" />
                                                    <span class="input-group-text">Unit</span>
                                                </div>
                                            </td>
                                            <td class="w-table-20">
                                                <div class="input-group">
                                                    <input class="form-control" required type="number" placeholder="Enter Amount" />
                                                    <span class="input-group-text">SAR</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="text-danger" type="button"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                        @foreach($inputs as $key => $value)
                                        {{
                                            $key
                                        }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end p-4">
                                <div class="col-auto">
                                    <a href="#" class="btn btn-custom-primary btn-sm" type="button" wire:click.prevent="addLocal({{$i}})">
                                        <i class="fa fa-plus me-2"></i>New Item
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row mb-0 justify-content-between align-items-center">
                                    <div class="col-lg-6 col-12">
                                        <div class="row align-items-center gx-3">
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">Returned QTY:</div>
                                                    <div class="col">2</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-auto fw-bold">Debit Value:</div>
                                                    <div class="col fw-bolder text-secondary">₹1200.00</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="row align-items-center gx-3">
                                            <div class="col-6">
                                                <button class="btn btn-secondary w-100" type="reset">Clear All</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary w-100" type="submit" wire:click.p="save">Submit</button>
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
</div>