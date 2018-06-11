{{-- Part of phoenix project. --}}

<?php
/**
 * @var  $items           \Windwalker\Data\DataSet
 * @var  $item            \Lyrasoft\Warder\Admin\Record\UserRecord
 * @var  $blockedButton   \Phoenix\Html\State\IconButton
 * @var  $grid            \Phoenix\View\Helper\GridHelper
 * @var  $pagination      \Windwalker\Core\Pagination\Pagination
 */

\Phoenix\Script\JQueryScript::highlight('.searchable', $state['input.search.content']);
?>

@extends($warder->extends)

@section('toolbar-buttons')
    @include('toolbar')
@stop

@section('admin-body')
    <style>
        .user-avatar {
            height: 48px;
            width: 48px;
            border-radius: 50%;
            min-width: 48px;
            margin-right: 10px;
        }

        .user-avatar-default {
            background-image: url('{{ \Lyrasoft\Warder\Helper\AvatarUploadHelper::getDefaultImage() }}');
            background-size: cover;
            display: inline-block;
            vertical-align: middle;
        }

        .grid-table table.table > tbody > tr > td {
            vertical-align: middle;
        }
    </style>
    <div id="phoenix-admin" class="users-container grid-container">
        <form name="admin-form" id="admin-form" action="{{ $router->route('users') }}" method="POST"
              enctype="multipart/form-data">

            {{-- FILTER BAR --}}
            <div class="filter-bar">
                {!! $filterBar->render(['form' => $form, 'show' => $showFilterBar]) !!}
            </div>

            {{-- RESPONSIVE TABLE DESC --}}
            <p class="visible-xs-block d-sm-block d-md-none">
                @translate($warder->langPrefix . 'grid.responsive.table.desc')
            </p>

            <div class="grid-table table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        {{-- CHECKBOX --}}
                        <th width="1%" class="text-nowrap">
                            {!! $grid->checkboxesToggle(array('duration' => 150)) !!}
                        </th>

                        {{-- NAME --}}
                        <th class="text-nowrap">
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.name', 'user.name') !!}
                        </th>

                        @if ($warder->package->getLoginName() !== 'email')
                            {{-- USERNAME --}}
                            <th class="text-nowrap">
                                {!! $grid->sortTitle($warder->langPrefix . 'user.field.' . $warder->package->getLoginName(), 'user.' . $warder->package->getLoginName()) !!}
                            </th>
                        @endif

                        {{-- Email --}}
                        <th width="5%" class="text-nowrap">
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.email', 'user.email') !!}
                        </th>

                        {{-- ENABLED --}}
                        <th width="3%" class="text-nowrap">
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.enabled', 'user.blocked') !!}
                        </th>

                        {{-- Activation --}}
                        <th width="3%" class="text-nowrap">
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.activation', 'user.activation') !!}
                        </th>

                        {{-- REGISTERED --}}
                        <th class="text-nowrap">
                            {!! $grid->sortTitle($warder->langPrefix . 'user.field.registered', 'user.registered') !!}
                        </th>

                        {{-- Delete --}}
                        <th width="3%" class="text-nowrap">
                            @translate($warder->langPrefix . 'user.field.delete')
                        </th>

                        {{-- ID --}}
                        <th class="text-nowrap">
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
                            {{-- CHECKBOX --}}
                            <td>
                                {!! $grid->checkbox() !!}
                            </td>

                            {{-- NAME --}}
                            <td class="searchable" style="min-width: 300px;">
                                @if (property_exists($item, 'avatar'))
                                    @if ($item->avatar)
                                        <img class="user-avatar" src="{{ $item->avatar }}" alt="Avatar">
                                    @else
                                        <div class="user-avatar user-avatar-default"></div>
                                    @endif
                                @endif
                                <a href="{{ $router->route('user', ['id' => $item->id]) }}">
                                    {{ $item->name }}
                                </a>
                            </td>

                            @if ($warder->package->getLoginName() !== 'email')
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
                                {!! $blockedButton->render(!$item->blocked, $i) !!}
                            </td>

                            {{-- Activation --}}
                            <td>
                                @if ($item->activation)
                                    <button type="button"
                                            class="waves-effect btn btn-light btn-default btn-sm hasTooltip"
                                            onclick="Phoenix.Grid.updateRow({{ $i }}, null, {task: 'activate'});"
                                            title="@translate($warder->langPrefix . 'button.unactivated.desc')">
                                        <span class="fa fa-remove fa-times text-danger"></span>
                                    </button>
                                @else
                                    <span class="fa fa-check text-success hasTooltip"
                                          title="@translate($warder->langPrefix . 'button.activated.desc')"></span>
                                @endif
                            </td>

                            {{-- REGISTERED --}}
                            <td>
                                {{ Windwalker\Core\DateTime\Chronos::toLocalTime($item->registered) }}
                            </td>

                            {{-- Delete --}}
                            <td class="text-center">
                                <button type="button"
                                        class="waves-effect btn btn-default btn-outline-secondary btn-sm hasTooltip"
                                        onclick="Phoenix.Grid.deleteRow({{ $i }});"
                                        title="@translate('phoenix.toolbar.delete')">
                                    <span class="glyphicon glyphicon-trash fa fa-trash"></span>
                                </button>
                            </td>

                            {{-- ID --}}
                            <td class="searchable text-right">
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
                @formToken
            </div>

            @include('batch')
        </form>
    </div>
@stop
