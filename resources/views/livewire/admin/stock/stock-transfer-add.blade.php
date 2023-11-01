<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.add_stock_transfer') }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.stock_transfer') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-sm-12">
                    <form>
                        <div class="card">
                            <div class="card-header">
                                <div class="row g-3 mb-0">
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input class="form-control" type="text"
                                                placeholder="{{ __('main.search_materials') }}"
                                                wire:model="material_query" />
                                            @if ($material_results)
                                                @if (count($material_results) > 0)
                                                    <div class="dropdown-menu show" id="dropmenuMaterial"
                                                        wire:ignore.self>

                                                        @forelse ($material_results as $row)
                                                            <a class="dropdown-item"
                                                                wire:click="selectMaterial({{ $row->id }})">{{ $row->name }}</a>
                                                        @empty
                                                        @endforelse

                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <select wire:model="branch_id" class="form-select">
                                            <option value="">
                                                {{ __('main.select_a_branch') }}</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="form-control" required type="date" wire:model="date" />
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-0">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-primary w-table-10" scope="col"># </th>
                                            <th class="text-primary w-table-50"  scope="col">
                                                {{ __('main.materials') }}</th>
                                            <th class="text-primary w-table-20" scope="col">
                                                {{ __('main.qty') }}</th>
                                            <th class="text-primary w-table-20" scope="col"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive mt-0 stock-add-scroll">
                                <table class="table">
                                    <tbody>
                                        @forelse($inputs as $key => $value)
                                            <tr>
                                                <th class="w-table-10" scope="row">{{ $loop->index + 1 }}</th>
                                                <td class="w-table-50">
                                                    <div class="mb-0 fw-bold">{{ $material_name[$key] }}</div>
                                                </td>
                                                <td class="w-table-20">
                                                    <div class="input-group">
                                                        <input class="form-control input-sm-2" type="number"
                                                            placeholder="{{ __('main.enter_a_value') }}"
                                                            value="{{ $quantity[$key] }}"
                                                            wire:model="quantity.{{ $key }}"
                                                            wire:input="changeQuantity({{ $key }})">
                                                        <span
                                                            class="input-group-text input-sm-2">{{ getUnitType($material_unit[$key]) }}</span>
                                                    </div>
                                                </td>
                                                <td class="w-table-20">
                                                    <a href="#" class="text-danger" type="button"
                                                        wire:click.prevent="delete({{ $key }})"><i
                                                            class="fa fa-times"></i></a>
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
                                            <div class="col-auto fw-bold">
                                                {{ __('main.total_items') }}:</div>
                                            <div class="col fw-bolder text-secondary">{{ $total_items }} Items</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="row align-items-center gx-3">
                                            <div class="col-6">
                                                <button class="btn btn-secondary w-100" type="reset"
                                                    wire:click.prevent="$emit('reloadpage')">{{ __('main.clear_all') }}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary w-100" type="submit"
                                                    wire:click.prevent="save">{{ __('main.submit') }}</button>
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