<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Cortex\Fort\Models\Session;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountSessionsController extends AuthenticatedController
{
    /**
     * Show account sessions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cortex/fort::tenantarea.pages.account-sessions');
    }

    /**
     * Destroy given session.
     *
     * @param \Cortex\Fort\Models\Session $session
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return intend([
            'back' => true,
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'session', 'id' => $session->getKey()])],
        ]);
    }

    /**
     * Flush all sessions.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function flush(Request $request)
    {
        $request->user($this->getGuard())->sessions()->delete();

        return intend([
            'back' => true,
            'with' => ['warning' => trans('cortex/fort::messages.auth.session.flushed')],
        ]);
    }
}
