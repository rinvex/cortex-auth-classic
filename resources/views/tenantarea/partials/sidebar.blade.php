<div class="profile-sidebar">
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            {{ $currentUser->full_name }}
        </div>
        @if($currentUser->title)
            <div class="profile-usertitle-job">
                {{ $currentUser->title }}
            </div>
        @endif
    </div>
    <div class="profile-usermenu">
        {!! Menu::render('tenantarea.account.sidebar', 'account.sidebar') !!}
    </div>
</div>
