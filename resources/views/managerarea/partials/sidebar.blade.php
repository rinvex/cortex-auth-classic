<div class="profile-sidebar">
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            {{ request()->user()->full_name }}
        </div>
        @if(request()->user()->title)
            <div class="profile-usertitle-job">
                {{ request()->user()->title }}
            </div>
        @endif
    </div>
    <div class="profile-usermenu">
        {!! Menu::render('managerarea.cortex.auth.account.sidebar', 'account.sidebar') !!}
    </div>
</div>
