<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Cortex\Auth\Models\Admin;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountMediaController extends AuthenticatedController
{
    /**
     * Destroy given admin media.
     *
     * @param \Cortex\Auth\Models\Admin                          $admin
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin, Media $media)
    {
        $admin->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('adminarea.account.settings'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $media->getRouteKey()]),],
        ]);
    }
}
