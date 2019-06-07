{{-- Part of phoenix project. --}}

<a role="button" class="btn btn-primary btn-sm" href="{{ $router->route('user', array('new' => true)) }}">
    <span class="glyphicon glyphicon-plus fa fa-plus"></span>
    @translate('phoenix.toolbar.new')
</a>

<button type="button" class="btn btn-success btn-sm" onclick="Phoenix.Grid.hasChecked();Phoenix.Grid.batch('unblock');">
    <span class="glyphicon glyphicon-ok fa fa-check"></span>
    @translate($warder->langPrefix . 'toolbar.enable')
</button>

<button type="button" class="btn btn-danger btn-sm" onclick="Phoenix.Grid.hasChecked();Phoenix.Grid.batch('block');">
    <span class="glyphicon glyphicon-remove fa fa-remove fa-times"></span>
    @translate($warder->langPrefix . 'toolbar.disable')
</button>

<button type="button" class="btn btn-info btn-sm" onclick="Phoenix.Grid.hasChecked();Phoenix.Grid.batch('resend');">
    <span class="glyphicon glyphicon-ok fa fa-envelope"></span>
    @lang('warder.button.resend.activate.mail')
</button>

<button type="button" class="btn btn-default btn-outline-danger btn-sm"
        onclick="Phoenix.Grid.hasChecked().deleteList();">
    <span class="glyphicon glyphicon-trash fa fa-trash"></span>
    @translate('phoenix.toolbar.delete')
</button>
