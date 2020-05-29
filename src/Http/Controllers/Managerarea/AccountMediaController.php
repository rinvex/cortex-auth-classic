<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Cortex\Auth\Models\Manager;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountMediaController extends AuthenticatedController
{
    /**
     * Destroy given manager media.
     *
     * @param \Cortex\Auth\Models\Manager                        $manager
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Manager $manager, Media $media)
    {
        $manager->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('managerarea.account.settings'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $media->getRouteKey()])],
        ]);
    }
}
