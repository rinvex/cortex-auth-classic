<aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
    <nav class="space-y-1">
        <div class="text-2xl text-center my-3">
            <div class="profile-usertitle-name">
                {{ request()->user()->full_name }}
            </div>
            @if(request()->user()->title)
                <div class="profile-usertitle-job">
                    {{ request()->user()->title }}
                </div>
            @endif
        </div>

        {!! Menu::render('frontarea.cortex.auth.account.sidebar', 'account.sidebar') !!}

    </nav>
</aside>
