<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Tenantarea;

use Cortex\Auth\Models\Member;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountMediaController extends AuthenticatedController
{
    /**
     * Destroy given member media.
     *
     * @param \Cortex\Auth\Models\Member                         $member
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member, Media $media)
    {
        $member->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('tenantarea.account.settings'),
            'with' => [
                'warning' => trans('cortex/foundation::messages.resource_deleted', [
                    'resource' => trans('cortex/foundation::common.media'),
                    'identifier' => $media->getRouteKey(),
                ]),
            ],
        ]);
    }
}
