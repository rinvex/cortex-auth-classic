<div class="profile-sidebar">
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            {{ app('request.user')->full_name }}
        </div>
        @if(app('request.user')->title)
            <div class="profile-usertitle-job">
                {{ app('request.user')->title }}
            </div>
        @endif
    </div>
    <div class="profile-usermenu">
        {!! Menu::render('managerarea.account.sidebar', 'account.sidebar') !!}
    </div>
</div>
