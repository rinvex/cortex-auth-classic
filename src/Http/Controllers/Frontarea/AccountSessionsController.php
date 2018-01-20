<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
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
        return view('cortex/fort::frontarea.pages.sessions');
    }

    /**
     * Flush the given session.
     *
     * @param string|null $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function flush(Request $request, $id = null)
    {
        $status = '';

        if ($id) {
            app('rinvex.fort.session')->find($id)->delete();
            $status = trans('cortex/fort::messages.auth.session.flushed');
        } elseif ($request->get('confirm')) {
            app('rinvex.fort.session')->where('user_id', $request->user($this->getGuard())->getKey())->delete();
            $status = trans('cortex/fort::messages.auth.session.flushedall');
        }

        return intend([
            'back' => true,
            'with' => ['warning' => $status],
        ]);
    }
}
