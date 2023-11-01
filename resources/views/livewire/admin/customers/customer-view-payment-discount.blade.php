<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row gx-3 mb-3 align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('main.customer_details') }}</h5>
                </div>
                <div class="col-auto">
                    <a data-bs-toggle="modal" data-bs-target="#addpaymentdiscount" class="btn btn-light px-2 text-primary"
                        type="button" wire:click="$emit('resetdiscount')">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment_discount') }}
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.add_payments') }}" class="btn btn-primary px-2" type="button">
                        <i class="fa fa-plus me-2"></i>{{ __('main.add_payment') }}
                    </a>
                </div>
                @if (Auth::user()->user_type != 2)
                    <div class="col-auto">
                        <a href="{{ route('admin.edit_customer', $customer_id) }}" class="btn btn-secondary px-2"
                            type="button">
                            <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                        </a>
                    </div>
                @endif
                <div class="col-auto">
                    <a href="{{ route('admin.customers') }}" class="btn btn-custom-primary px-2" type="button">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('main.back') }}
                    </a>
                </div>
            </div>
            <div class="row gx-3">
                @livewire('components.customer-profile', ['id' => $customer_id])
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2 mb-0">
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary ">{{ __('main.invoice_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_payments', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary ">{{ __('main.payments_list') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_measurement', $customer_id) }}"
                                        type="button" class="btn btn-nav-primary">{{ __('main.measurements') }}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.view_customer_discount', $customer_id) }}" type="button"
                                        class="btn btn-nav-primary active">{{ __('main.payment_discount') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary w-table-20" scope="col">
                                            {{ __('main.date') }}</th>
                                        <th class="text-primary w-table-40" scope="col">
                                            {{ __('main.amount') }}</th>
                                        <th class="text-primary w-table-40" scope="col">
                                            {{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mt-0 customer-view-scroll">
                            <table class="table">
                                <tbody>
                                    @foreach ($discounts as $row)
                                        <tr>
                                            <td class="w-table-20">
                                                <div class="mb-0">
                                                    {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</div>
                                                <div class="mt-50 text-xs text-secondary fw-bold">
                                                    {{ __('main.by') }} {{ $row->createdBy->name ?? '' }}
                                                </div>
                                            </td>
                                            <td class="w-table-40">
                                                <div class="mb-0 fw-bolder">{{ getFormattedCurrency($row->amount) }}
                                                </div>
                                            </td>
                                            <td class="w-table-40">
                                                <a data-bs-toggle="modal" wire:click="edit({{ $row->id }})"
                                                    data-bs-target="#editpaymentdiscount"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                    <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                                <a data-bs-toggle="modal"
                                                    wire:click="confirmDelete({{ $row->id }})"
                                                    data-bs-target="#confirmdelete"
                                                    class="btn btn-custom-danger btn-sm px-2" type="button">
                                                    <i class="fa fa-trash-o me-2"></i>{{ __('main.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($hasMorePages)
                                <div x-data="{
                                    init() {
                                        let observer = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                if (entry.isIntersecting) {
                                                    @this.call('loadDiscounts')
                                                    console.log('loading...')
                                                }
                                            })
                                        }, {
                                            root: null
                                        });
                                        observer.observe(this.$el);
                                    }
                                }"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                    <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                        Loading...
                                        <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('components.customer-discount', ['id' => $customer_id])
    <div wire:ignore.self class="modal fade" id="editpaymentdiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_payment_discount') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row align-items-start g-3">
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.date') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en"
                                        placeholder="{{ __('main.select_date') }}" wire:model="discount_date" />
                                </div>
                                @error('discount_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ __('main.amount') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="number"
                                        placeholder="{{ __('main.enter_amount') }}" wire:model="discount_amount" />
                                    <span class="input-group-text">{{ getCurrency() }}</span>
                                </div>
                                @error('discount_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('discount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"
                            wire:click="resetDiscount">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="addDiscount">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> {{ __('main.confirm_delete') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.measurements') }}</h5>
                            <p class="mb-0 text-sm">
                                {{ __('main.do_you_want_to_delete_selected_payment_entry') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="delete">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
