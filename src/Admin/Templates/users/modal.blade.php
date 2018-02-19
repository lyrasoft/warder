{{-- Part of phoenix project. --}}

@extends('_global.' . \Lyrasoft\Warder\Helper\WarderHelper::getAdminPackage(true) . '.pure')

@section('toolbar-buttons')
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
                {!! $filterBar->render(array('form' => $form, 'show' => $showFilterBar)) !!}
            </div>

            {{-- RESPONSIVE TABLE DESC --}}
            <p class="visible-xs-block d-sm-block d-md-none">
                @translate('phoenix.grid.responsive.table.desc')
            </p>

            <div class="grid-table table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        {{-- NAME --}}
                        <th>
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.name', 'user.name') !!}
                        </th>

                        @if ($warder->package->getLoginName() !== 'email')
                            {{-- USERNAME --}}
                            <th>
                                {!! $grid->sortTitle($warder->langPrefix . 'user.field.' . $warder->package->getLoginName(), 'user.' . $warder->package->getLoginName()) !!}
                            </th>
                        @endif

                        {{-- EMAIL --}}
                        <th>
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.email', 'user.email') !!}
                        </th>

                        {{-- ENABLED --}}
                        <th>
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.enabled', 'user.blocked') !!}
                        </th>

                        {{-- ACTIVATED --}}
                        <th>
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.activation', 'user.activation') !!}
                        </th>

                        {{-- ID --}}
                        <th>
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.id', 'user.id') !!}
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
                                <a href="#"
                                   onclick="parent.{{ $function }}('{{ $selector }}', '{{ $item->id }}', '{{ $item->name }}');">
                                    <span
                                        class="glyphicon glyphicon-menu-left fa fa-angle-right text-muted"></span> {{ $item->name }}
                                </a>
                            </td>

                            @if ($warder->package->getLoginName() !== 'email')
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
                                $grid->createIconButton(array('only_icon' => true))
                                    ->addState(
                                        1,
                                        'block',
                                        'ok fa fa-check text-success',
                                        \Windwalker\Core\Language\Translator::translate($warder->langPrefix . 'button.enabled.desc')
                                    )
                                    ->addState(
                                        0,
                                        'unblock',
                                        'remove fa fa-remove text-danger',
                                        \Windwalker\Core\Language\Translator::translate($warder->langPrefix . 'button.disabled.desc')
                                    )->render(!$item->blocked)
                                !!}
                            </td>

                            {{-- Activation --}}
                            <td>
                                @if ($item->activation)
                                    <span class="glyphicon glyphicon-remove fa fa-remove text-danger hasTooltip"
                                          title="@translate($warder->langPrefix . 'button.unactivated.desc')"></span>
                                @else
                                    <span class="glyphicon glyphicon-ok fa fa-check text-success hasTooltip"
                                          title="@translate($warder->langPrefix . 'button.activated.desc')"></span>
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
                            {!! $pagination->route($view->name, [])->render() !!}
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="hidden-inputs">
                {{-- METHOD --}}
                <input type="hidden" name="_method" value="PUT"/>

                {{-- TOKEN --}}
                @formToken()
            </div>

            @include('batch')
        </form>
    </div>
@stop
