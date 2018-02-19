{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$basicFieldset = array_shift($fieldsets);

$form->removeField('password')->removeField('password2');

\Phoenix\Html\HtmlHeader::setTitle($item->name);
?>

@extends($warder->extends)

@section('content')
    <div class="container warder-page profile-page">
        <div class="row">

            <div class="col-md-6 col-md-offset-3">

                <fieldset>
                    @foreach ($form->getFields($basicFieldset) as $field)
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">
                                <strong>
                                    {{ $field->getLabel() }}
                                </strong>
                            </div>
                            <div class="col-md-8">
                                {!! $field->renderView() !!}
                            </div>
                        </div>
                    @endforeach
                </fieldset>

                @foreach ($fieldsets as $fieldset)
                    <fieldset>
                        @foreach ($form->getFields($fieldset) as $field)
                            <legend>{{ ucfirst($fieldset) }}</legend>

                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4">
                                    <strong>
                                        {{ $field->getLabel() }}
                                    </strong>
                                </div>
                                <div class="col-md-8">
                                    {!! $field->renderView() !!}
                                </div>
                            </div>
                        @endforeach

                    </fieldset>
                @endforeach

                @if ($item->id == $user->id)
                    <div class="edit-button" style="margin-top: 30px">
                        <a class="btn btn-default" href="{{ $router->route('profile_edit') }}">
                            <span class="glyphicon glyphicon-edit fa fa-edit"></span>
                            @translate($warder->langPrefix . 'profile.edit.button.title')
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
@stop
