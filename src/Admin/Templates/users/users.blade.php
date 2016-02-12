{{-- Part of phoenix project. --}}

<?php
\Phoenix\Script\JQueryScript::highlight('.searchable', $state['input.search.content']);
?>

@extends($parentTemplate)

@section('toolbar')
    @include('toolbar')
@stop

@section('admin-body')
<div id="phoenix-admin" class="users-container grid-container">
    <form name="admin-form" id="admin-form" action="{{ $router->html('users') }}" method="POST" enctype="multipart/form-data">

        {{-- FILTER BAR --}}
        <div class="filter-bar">
            {!! $filterBar->render(array('form' => $filterForm, 'show' => $showFilterBar)) !!}
        </div>

        {{-- RESPONSIVE TABLE DESC --}}
        <p class="visible-xs-block">
            @translate($langPrefix . 'grid.responsive.table.desc')
        </p>

        <div class="grid-table table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    {{-- CHECKBOX --}}
                    <th>
                        {!! $grid->checkboxesToggle(array('duration' => 150)) !!}
                    </th>

                    {{-- NAME --}}
                    <th>
                        {!! $grid->sortTitle($langPrefix . 'field.name', 'user.name') !!}
                    </th>

                    @if ($warder->get('user.login_name') != 'email')
                        {{-- USERNAME --}}
                        <th>
                            {!! $grid->sortTitle($langPrefix . 'field.' . $warder->get('user.login_name'), 'user.' . $warder->get('user.login_name')) !!}
                        </th>
                    @endif

                    {{-- Email --}}
                    <th width="5%" class="nowrap">
                        {!! $grid->sortTitle($langPrefix . 'field.email', 'user.email') !!}
                    </th>

                    {{-- ENABLED --}}
                    <th  width="3%">
                        {!! $grid->sortTitle($langPrefix . 'field.enabled', 'user.blocked') !!}
                    </th>

                    {{-- Activation --}}
                    <th width="3%">
                        {!! $grid->sortTitle($langPrefix . 'field.activation', 'user.activation') !!}
                    </th>

                    @section('users-custom-fields-head')

                    {{-- REGISTERED --}}
                    <th>
                        {!! $grid->sortTitle($langPrefix . 'field.registered', 'user.registered') !!}
                    </th>

                    @show

                    {{-- Delete --}}
                    <th>
                        Delete
                    </th>

                    {{-- ID --}}
                    <th>
                        {!! $grid->sortTitle($langPrefix . 'field.id', 'user.id') !!}
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $i => $item)
                    <?php
                    $grid->setItem($item, $i);
                    ?>
                    <tr>
                        {{-- CHECKBOX --}}
                        <td>
                            {!! $grid->checkbox() !!}
                        </td>

                        {{-- NAME --}}
                        <td class="searchable">
                            <a href="{{ $router->html('user', array('id' => $item->id)) }}">
                                {{ $item->name }}
                            </a>
                        </td>

                        @if ($warder->get('user.login_name') != 'email')
                            {{-- USERNAME --}}
                            <td class="searchable">
                                {{ $item->username }}
                            </td>
                        @endif

                        {{-- EMAIL --}}
                        <td class="searchable">
                            {{ $item->email }}
                        </td>

                        {{-- ENABLED --}}
                        <td>
                            {!!
                            $grid->createIconButton(!$item->blocked)
                                ->addState(
                                    1,
                                    'block',
                                    'ok fa fa-check text-success',
                                    \Windwalker\Core\Language\Translator::translate($langPrefix . 'button.enabled.desc')
                                )
                                ->addState(
                                    0,
                                    'unblock',
                                    'remove fa fa-remove text-danger',
                                    \Windwalker\Core\Language\Translator::translate($langPrefix . 'button.disabled.desc')
                                )
                            !!}
                        </td>

                        {{-- Activation --}}
                        <td>
                            @if ($item->activation)
                                <button type="button" class="waves-effect btn btn-default btn-xs hasTooltip" onclick="Phoenix.Grid.updateRow({{ $i }}, null, {task: 'activate'});"
                                    title="@translate($langPrefix . 'button.unactivated.desc')">
                                    <span class="glyphicon glyphicon-remove fa fa-remove text-danger"></span>
                                </button>
                            @else
                                <span class="glyphicon glyphicon-ok fa fa-check text-success hasTooltip" title="@translate($langPrefix . 'button.activated.desc')"></span>
                            @endif
                        </td>

                        @section('users-custom-fields-body')

                        {{-- REGISTERED --}}
                        <td>
                            {{ Windwalker\Core\DateTime\DateTime::toLocalTime($item->created) }}
                        </td>

                        @show

                        {{-- Delete --}}
                        <td>
                            <button type="button" class="waves-effect btn btn-default btn-xs hasTooltip" onclick="Phoenix.Grid.deleteRow({{ $i }});"
                                title="@translate('phoenix.toolbar.delete')">
                                <span class="glyphicon glyphicon-trash fa fa-trash"></span>
                            </button>
                        </td>

                        {{-- ID --}}
                        <td>
                            {{ $item->id }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    {{-- PAGINATION --}}
                    <td colspan="25">
                        {!! $pagination->render($package->getName() . ':users', 'windwalker.pagination.phoenix') !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="hidden-inputs">
            {{-- METHOD --}}
            <input type="hidden" name="_method" value="PUT" />

            {{-- TOKEN --}}
            {!! \Windwalker\Core\Security\CsrfProtection::input() !!}
        </div>

        @include('batch')
    </form>
</div>
@stop
