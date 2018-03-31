<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Session;
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
        return view('cortex/auth::adminarea.pages.account-sessions');
    }

    /**
     * Destroy given session.
     *
     * @param \Cortex\Auth\Models\Session $session
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return intend([
            'back' => true,
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'session', 'identifier' => $session->getKey()])],
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
            'with' => ['warning' => trans('cortex/auth::messages.auth.session.flushed')],
        ]);
    }
}
