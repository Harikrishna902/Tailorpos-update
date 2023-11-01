<div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col mb-4">
                                    <h5 class="mb-0">{{ __('main.measurements') }}</h5>
                                </div>
                                <div class="col-auto mb-4">
                                    <a href="{{route('admin.measurements.add')}}" class="btn btn-primary px-2" type="button">
                                        <i class="fa fa-plus me-2"></i>{{ __('main.add_new_measurement') }}
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
                                        <th class="text-primary" scope="col"># </th>
                                        <th class="text-primary" scope="col">{{ __('main.name') }}</th>
                                        <th class="text-primary" scope="col">{{ __('main.status') }} </th>
                                        <th class="text-primary" scope="col">{{ __('main.actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($measurements)
                                    @foreach($measurements as $row)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>
                                            <div class="fw-bold">{{$row->name}}</div>
                                        </td>
                                        <td>
                                            <div class="media-body switch-lg">
                                                <label class="switch">
                                                    <input id="{{$row->id}}" type="checkbox" @if($row->is_active==1) checked @endif wire:click="toggle({{$row->id}})"/><span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{route('admin.measurements.edit',$row->id)}}" class="btn btn-custom-secondary btn-sm px-2" type="button">
                                                <i class="fa fa-pencil-square-o me-2"></i>{{ __('main.edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(count($measurements) == 0)
                            <x-empty-item-component :title="__('main.empty_item_title')"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmdelete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('main.confirm_delete') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body text-center">
                        <div class="pb-4 pt-3">
                            <h5 class="mb-3">{{ __('main.are_you_sure') }}</h5>
                            <p class="mb-0 text-sm">{{ __('main.do_you_want_to_delete_selected_measurement_entry') }}</p>
                        </div>
                    </div>
                    <div class="text-center pb-4">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">{{ __('main.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ __('main.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>