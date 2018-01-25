<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Models\Session;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountSessionsController extends AuthenticatedController
{
    /**
     * Show the account sessions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cortex/fort::tenantarea.pages.account-sessions');
    }

    /**
     * Delete the given session.
     *
     * @param \Rinvex\Fort\Models\Session $session
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Session $session)
    {
        $session->delete();

        return intend([
            'back' => true,
            'with' => ['warning' => trans('cortex/fort::messages.auth.session.deleted', ['sessionId' => $session->getKey()])],
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
