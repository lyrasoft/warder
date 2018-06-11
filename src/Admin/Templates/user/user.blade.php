{{-- Part of phoenix project. --}}

@extends($warder->extends)

@section('toolbar-buttons')
    @include('toolbar')
@stop

@section('admin-body')
    <form name="admin-form" id="admin-form" action="{{ $router->route('user', array('id' => $item->id)) }}"
          method="POST" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-7">
                <fieldset class="form-horizontal">
                    <legend>@translate($warder->langPrefix . 'edit.fieldset.basic')</legend>

                    {!! $form->renderFields('basic') !!}
                </fieldset>
            </div>
            <div class="col-md-5">
                <fieldset class="form-horizontal">
                    <legend>@translate($warder->langPrefix . 'edit.fieldset.created')</legend>

                    {!! $form->renderFields('created') !!}
                </fieldset>
            </div>
        </div>

        @yield('user-edit-custom-fields')

        <div class="hidden-inputs">
            @formToken
        </div>

    </form>
@stop
