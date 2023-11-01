<div>
    <div class="page-body" x-data="pageSection">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card w-100">
                        <div class="card-header">
                            <div class="row gx-3 mb-0">
                                <div class="col">
                                    <h5 class="mb-0">{{ __('main.terms_conditions') }} </h5>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        <form class="w-100" wire:ignore>
                            <div class="card-body w-100">
                                <div class="row g-3 mt-2 w-100">
                                    <textarea name="" id="ckeditor" class="w-100">{{$content}}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3 align-items-center justify-content-end">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="submit" wire:click.prevent="save">{{ __('main.save_settings') }}</button>
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

@push('js')
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        'use strict';
        function pageSection()
        {
            return{
                init() 
                {
                    let content = this.$wire.content;
                    
                    ClassicEditor
                    .create( document.querySelector( '#ckeditor' ), {
                        removePlugins: ['Title'],
                    } ).then(editor => {
                        editor.model.document.on('change:data', () => {
                            this.$wire.content = editor.getData()
                        })
                    })
                    .catch( error => {
                        console.error( error );
                    } );
                }
            }
        }
    </script>
@endpush