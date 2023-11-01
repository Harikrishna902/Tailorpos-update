<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.expense_list') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a wire:click="resetFields" data-bs-toggle="modal" data-bs-target="#addexpense" class="btn btn-primary px-2" type="button">
                                        <i class="fa fa-plus me-2"></i>{{ __('main.add_new_expense') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text" placeholder="{{ __('main.search_here') }}" wire:model="search"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col">{{ __('main.date') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.title') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.amount') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.towards') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.vat_included') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.payment_mode') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $item)
                                    <tr>
                                        <td>
                                            <div class="mb-0">{{$item->date->format('d/m/Y')}}</div>
                                            <div class="mt-50 text-xs text-secondary fw-bold">{{$item->createdBy->name}}</div>
                                        </td>
                                        <td>
                                            @if($item->title)
                                            <div class="mt-50">{{Str::limit($item->title,20)}}</div>
                                            @else 
                                            - 
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mb-0 fw-bold">{{getFormattedCurrency($item->amount)}}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary p-2 text-uppercase">{{$item->head->name ?? ''}}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary p-2 text-uppercase">{{$item->tax_included == 1 ? __('main.yes') : __('main.no')}}</span>
                                        </td>
                                        <td>
                                            <div class="mb-0 text-uppercase">{{getPaymentMode($item->payment_mode)}}</div>
                                        </td>
                                        <td>
                                            <a data-bs-toggle="modal" wire:click="view({{$item->id}})" data-bs-target="#viewexpense" class="btn btn-custom-primary btn-sm px-2" type="button">
                                                <i class="fa fa-eye me-2"></i>{{ __('main.view') }}
                                            </a>
                                            <a data-bs-toggle="modal"  wire:click="edit({{$item->id}})" data-bs-target="#editexpense" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                            <a data-bs-toggle="modal"  wire:click="confirmDelete({{$item->id}})" data-bs-target="#confirmdelete" class="btn btn-custom-danger btn-sm px-2" type="button">
                                                <i class="fa fa-trash-o me-2"></i>{{ __('main.delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($expenses) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewexpense" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.expense_details') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    @if($expense)
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <div class="row mb-2 align-items-center">
                                    <div class="col">{{ __('main.date') }}:</div>
                                    <div class="col-auto">{{$expense->date->format('d/m/Y')}}</div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <div class="col fw-bold">{{ __('main.expense_amount') }}:</div>
                                    <div class="col-auto fw-bolder text-dark">{{getFormattedCurrency($expense->amount)}}</div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col">{{ __('main.towards') }}:</div>
                                    <div class="col-auto"><span class="badge bg-secondary p-2 text-uppercase">{{$expense->head->name ?? ''}}</span></div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col">{{ __('main.vat_included') }}:</div>
                                    <div class="col-auto"><span class="badge bg-light-primary text-primary p-2 text-uppercase">{{$expense->tax_included == 1 ? __('main.yes') : __('main.no')}}</span></div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col">{{ __('main.payment_method') }}:</div>
                                    <div class="col-auto text-uppercase">{{getPaymentMode($expense->payment_mode)}}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <h6 class="text-l">{{ __('main.notes_remarks') }}:</h6>
                                <p class="mb-0">{{$expense->note}}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-bs-dismiss="modal" type="submit">{{ __('main.close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addexpense" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_expense') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.title') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" wire:model="title" placeholder="{{ __('main.enter_title') }}" />
                                </div>
                                @error('title') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.amount') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" required type="number" wire:model="amount" placeholder="{{ __('main.enter_amount') }}" />
                                    <span class="input-group-text">{{getCurrency()}}</span>
                                    @error('amount') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" type="date" data-language="en"  wire:model="date"/>
                                </div>
                                @error('date') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.expense_head') }} <span class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="category_id">
                                    <option value="">{{ __('main.select_a_head') }} </option>
                                    @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}} </option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-3">{{ __('main.vat_included') }}<span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <label class="d-block" for="no">
                                        <input class="radio_animated" wire:model="tax_included" value="0" id="no" type="radio" name="vat" /> {{ __('main.no') }}
                                    </label>
                                    <label class="d-block ms-5" for="yes">
                                        <input class="radio_animated" wire:model="tax_included" value="1" id="yes" type="radio" name="vat" checked /> {{ __('main.yes') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.payment_method') }} <span class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="payment_mode">
                                    <option value="">{{ __('main.select_a_method') }} </option>
                                    <option value="1">{{__('main.cash')}}</option>
                                    <option value="2">{{ __('main.card') }}</option>
                                    <option value="3">{{ __('main.upi') }}</option>
                                    <option value="4">{{ __('main.cheque') }}</option>
                                    <option value="5">{{ __('main.bank_transfer') }}</option>
                                </select>
                                @error('payment_mode') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.notes_remarks') }}</label>
                                <textarea class="form-control" placeholder="{{ __('main.notes_remarks') }}" wire:model="notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="create">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editexpense" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_expense') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.title') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" wire:model="title" placeholder="{{ __('main.enter_title') }}" />
                                </div>
                                @error('title') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.amount') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control" required type="number" wire:model="amount" placeholder="{{ __('main.enter_amount') }}" />
                                    <span class="input-group-text">{{getCurrency()}} </span>
                                    @error('amount') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en"  wire:model="date"/>
                                </div>
                                @error('date') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.expense_head') }} <span class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="category_id">
                                    <option >{{ __('main.select_a_head') }} </option>
                                    @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}} </option>>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-3">{{ __('main.vat_included') }}<span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <label class="d-block" for="no">
                                        <input class="radio_animated" wire:model="tax_included" value="0" id="no" type="radio" name="vat" /> {{{ __('main.no') }}}
                                    </label>
                                    <label class="d-block ms-5" for="yes">
                                        <input class="radio_animated" wire:model="tax_included" value="1" id="yes" type="radio" name="vat" checked /> {{ __('main.yes') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ __('main.payment_method') }} <span class="text-danger">*</span></label>
                                <select required class="form-select" wire:model="payment_mode">
                                    <option value="">{{ __('main.select_a_method') }} </option>
                                    <option value="1">{{__('main.cash')}}</option>
                                    <option value="2">{{ __('main.card') }}</option>
                                    <option value="3">{{ __('main.upi') }}</option>
                                    <option value="4">{{ __('main.cheque') }}</option>
                                    <option value="5">{{ __('main.bank_transfer') }}</option>
                                </select>
                                @error('payment_mode') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.notes_remarks') }}</label>
                                <textarea class="form-control" placeholder="{{ __('main.enter_notes_remarks') }}" wire:model="notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="update">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delete') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">{{ __('main.do_you_want_to_delete_selected_expense_entry') }}</p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit" wire:click.prevent="delete">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>