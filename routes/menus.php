<?php

declare(strict_types=1);

use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Cortex\Foundation\Models\Menu as MenuModel;

Menu::adminareaSidebar('access')->routeIfCan('list-abilities', 'adminarea.abilities.index', '<i class="fa fa-sliders"></i> <span>'.trans('cortex/fort::common.abilities').'</span>');
Menu::adminareaSidebar('access')->routeIfCan('list-roles', 'adminarea.roles.index', '<i class="fa fa-users"></i> <span>'.trans('cortex/fort::common.roles').'</span>');
Menu::adminareaSidebar('users')->routeIfCan('list-users', 'adminarea.users.index', '<i class="fa fa-user"></i> <span>'.trans('cortex/fort::common.users').'</span>');

Menu::tenantareaSidebar('resources')->routeIfCan('list-roles', 'tenantarea.roles.index', '<i class="fa fa-users"></i> <span>'.trans('cortex/fort::common.roles').'</span>');
Menu::tenantareaSidebar('resources')->routeIfCan('list-users', 'tenantarea.users.index', '<i class="fa fa-user"></i> <span>'.trans('cortex/fort::common.users').'</span>');

if ($user = auth()->user()) {
    $userMenuHeader = Link::to('#', $user->username.' <span class="caret"></span>')->addClass('dropdown-toggle')->setAttribute('data-toggle', 'dropdown');
    $userMenuBody = function (MenuModel $menu) {
        $menu->addClass('dropdown-menu');
        $menu->addParentClass('dropdown');
        $menu->route('frontarea.account.settings', '<i class="fa fa-user"></i> '.trans('cortex/fort::common.settings'));
        $menu->route('frontarea.account.sessions', '<i class="fa fa-id-badge"></i> '.trans('cortex/fort::common.sessions'));
        $menu->add(Html::raw('')->addParentClass('divider')->setParentAttribute('role', 'separator'));

        $logoutLink = Link::toRoute('frontarea.logout', '<i class="fa fa-sign-out"></i> '.trans('cortex/fort::common.logout'))->setAttribute('onclick', "event.preventDefault(); document.getElementById('logout-form').submit();");
        $menu->add(Html::raw($logoutLink.Form::open(['url' => route('frontarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close()));
    };

    Menu::frontareaTopbar()->submenu($userMenuHeader, $userMenuBody);
    Menu::tenantareaTopbar()->submenu($userMenuHeader, $userMenuBody);
    Menu::adminareaTopbar()->submenu($userMenuHeader, $userMenuBody);
} else {
    Menu::frontareaTopbar()->route('frontarea.login', trans('cortex/fort::common.login'));
    Menu::frontareaTopbar()->route('frontarea.register', trans('cortex/fort::common.register'));
}
