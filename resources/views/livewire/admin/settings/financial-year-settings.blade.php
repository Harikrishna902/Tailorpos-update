<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.financial_year_settings') }}
                                    </h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a data-bs-toggle="modal" data-bs-target="#addyear" class="btn btn-primary px-2"
                                        type="button" wire:click="resetFields">
                                        <i
                                            class="fa fa-plus me-2"></i>{{ __('main.add_financial_year') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" type="text"
                                            placeholder="{{ __('main.search_here') }}"
                                            wire:model="search_query"/ />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-0">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-primary" scope="col"># </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.financial_year_name') }}</th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.start_date') }} </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.end_date') }} </th>
                                        <th class="text-primary" scope="col">
                                            {{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($years as $item)
                                        <tr>
                                            <th scope="row"> {{ $loop->index + 1 }} </th>
                                            <td>
                                                <div class="mb-0 fw-bold">{{ $item->year }}</div>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->starting_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->ending_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#edityear"
                                                    class="btn btn-custom-secondary btn-sm px-2" type="button"
                                                    wire:click="edit({{ $item->id }})">
                                                    <i
                                                        class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="addyear" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.add_financial_year') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.year_name') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="Enter Year Name"
                                wire:model="year">
                            @error('year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.start_date') }} <span
                                    class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control digits" type="date" data-language="en"
                                    wire:model="start_date" />
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.end_date') }} <span
                                    class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control digits" type="date" data-language="en"
                                    wire:model="end_date" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="create">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="edityear" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.edit_financial_year') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.year_name') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" placeholder="Enter Year Name"
                                wire:model="year">
                            @error('year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('main.start_date') }} <span
                                    class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control digits" type="date" data-language="en"
                                    wire:model="start_date" />
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('main.end_date') }} <span
                                    class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input class="form-control digits" type="date" data-language="en"
                                    wire:model="end_date" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit"
                            wire:click.prevent="update">{{ __('main.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>