{{-- Part of phoenix project. --}}

<button type="button" class="btn btn-success btn-sm" onclick="Phoenix.post();">
    <span class="glyphicon glyphicon-floppy-disk fa fa-save"></span>
    @translate('phoenix.toolbar.save')
</button>

<button type="button" class="btn btn-primary btn-sm" onclick="Phoenix.post(null, {task: 'save2close'});">
    <span class="glyphicon glyphicon-ok fa fa-check"></span>
    @translate('phoenix.toolbar.save2close')
</button>

{{--<button type="button" class="waves-effect btn btn-default" onclick="Phoenix.post(null, {task: 'save2copy'});">--}}
{{--<span class="glyphicon glyphicon-duplicate fa fa-copy text-info"></span>--}}
{{--@translate('phoenix.toolbar.save2copy')--}}
{{--</button>--}}

{{--<button type="button" class="waves-effect btn btn-default" onclick="Phoenix.post(null, {task: 'save2new'});">--}}
{{--<span class="glyphicon glyphicon-plus fa fa-plus text-primary"></span>--}}
{{--@translate('phoenix.toolbar.save2new')--}}
{{--</button>--}}

<a role="button" class="btn btn-default btn-outline-secondary btn-sm" href="{{ $router->route('users') }}">
    <span class="glyphicon glyphicon-remove fa fa-remove fa-times"></span>
    @translate('phoenix.toolbar.cancel')
</a>
