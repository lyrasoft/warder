{{-- Part of phoenix project. --}}

@extends('_global.' . $warder->get('admin.package') . '.pure')

@section('toolbar')
    @include('toolbar')
@stop

@section('body')
<div id="phoenix-admin" class="users-container grid-container">
    <form name="admin-form" id="admin-form" action="{{ $uri['full'] }}" method="POST" enctype="multipart/form-data">

        {{-- FILTER BAR --}}
        <div class="filter-bar">
            <button class="btn btn-default pull-right" onclick="parent.{{ $function }}('{{ $selector }}', '', '');">
                <span class="glyphicon glyphicon-remove fa fa-remove text-danger"></span>
                @translate('phoenix.grid.modal.button.cancel')
            </button>
            {!! $filterBar->render(array('form' => $filterForm, 'show' => $showFilterBar)) !!}
        </div>

        {{-- RESPONSIVE TABLE DESC --}}
        <p class="visible-xs-block">
            @translate('phoenix.grid.responsive.table.desc')
        </p>

        <div class="grid-table table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
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

                    {{-- EMAIL --}}
                    <th>
                    {!! $grid->sortTitle($langPrefix . 'field.email', 'user.email') !!}
                    </th>

                    {{-- ENABLED --}}
                    <th>
                        {!! $grid->sortTitle($langPrefix . 'field.enabled', 'user.blocked') !!}
                    </th>

                    {{-- ACTIVATED --}}
                    <th>
                        {!! $grid->sortTitle($langPrefix . 'field.activation', 'user.activation') !!}
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
                        {{-- NAME --}}
                        <td class="searchable">
                            <a href="#" onclick="parent.{{ $function }}('{{ $selector }}', '{{ $item->id }}', '{{ $item->name }}');">
                                <span class="glyphicon glyphicon-menu-left fa fa-angle-right text-muted"></span> {{ $item->name }}
                            </a>
                        </td>

                        @if ($warder->get('user.login_name') != 'email')
                            {{-- USERNAME --}}
                            <td class="searchable" class="searchable">
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
                            $grid->createIconButton(!$item->blocked, array('only_icon' => true))
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
                                <span class="glyphicon glyphicon-remove fa fa-remove text-danger hasTooltip" title="@translate($langPrefix . 'button.unactivated.desc')"></span>
                            @else
                                <span class="glyphicon glyphicon-ok fa fa-check text-success hasTooltip" title="@translate($langPrefix . 'button.activated.desc')"></span>
                            @endif
                        </td>

                        {{-- ID --}}
                        <td class="searchable">
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
