<?php

declare(strict_types=1);

use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Cortex\Foundation\Models\Menu as MenuModel;

Menu::backendSidebar('resources')->routeIfCan('list-abilities', 'backend.abilities.index', '<i class="fa fa-sliders"></i> <span>'.trans('cortex/fort::common.abilities').'</span>');
Menu::backendSidebar('resources')->routeIfCan('list-roles', 'backend.roles.index', '<i class="fa fa-users"></i> <span>'.trans('cortex/fort::common.roles').'</span>');
Menu::backendSidebar('resources')->routeIfCan('list-users', 'backend.users.index', '<i class="fa fa-user"></i> <span>'.trans('cortex/fort::common.users').'</span>');

if ($user = auth()->user()) {
    $userMenuHeader = Link::to('#', $user->username.' <span class="caret"></span>')->addClass('dropdown-toggle')->setAttribute('data-toggle', 'dropdown');
    $userMenuBody = function (MenuModel $menu) {
        $menu->addClass('dropdown-menu');
        $menu->addParentClass('dropdown');
        $menu->route('userarea.account.settings', '<i class="fa fa-user"></i> '.trans('cortex/fort::common.settings'));
        $menu->route('userarea.account.sessions', '<i class="fa fa-id-badge"></i> '.trans('cortex/fort::common.sessions'));
        $menu->add(Html::raw('')->addParentClass('divider')->setParentAttribute('role', 'separator'));

        $logoutLink = Link::toRoute('frontend.auth.logout', '<i class="fa fa-sign-out"></i> '.trans('cortex/fort::common.logout'))->setAttribute('onclick', "event.preventDefault(); document.getElementById('logout-form').submit();");
        $menu->add(Html::raw($logoutLink.Form::open(['url' => route('frontend.auth.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close()));
    };

    Menu::userareaTopbar()->submenu($userMenuHeader, $userMenuBody);
    Menu::frontendTopbar()->submenu($userMenuHeader, $userMenuBody);
    Menu::backendTopbar()->submenu($userMenuHeader, $userMenuBody);
} else {
    Menu::frontendTopbar()->route('frontend.auth.login', trans('cortex/fort::common.login'));
    Menu::frontendTopbar()->route('frontend.auth.register', trans('cortex/fort::common.register'));
}
