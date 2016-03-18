{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$basicFieldset = array_shift($fieldsets);

$form->removeField('password')->removeField('password2');
?>

@extends($warderExtends)

@section('content')
    <div class="container warder-page profile-page">
        <div class="row">

            <div class="col-md-6 col-md-offset-3">

                    @foreach ($form->getFields($basicFieldset) as $field)
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-4">
                            <strong>
                                {{ $field->getLabel() }}
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $field->getValue() }}
                        </div>
                    </div>
                    @endforeach

            </div>

        </div>
    </div>
@stop