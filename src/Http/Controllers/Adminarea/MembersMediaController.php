<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Support\Str;
use Cortex\Fort\Models\Member;
use Spatie\MediaLibrary\Models\Media;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class MembersMediaController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'member';

    /**
     * {@inheritdoc}
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null): void
    {
        $middleware = [];
        $parameter = $parameter ?: Str::snake(class_basename($model));

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:update,{$modelName}"][] = $method;
            $middleware["can:{$ability},media"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

    /**
     * Destroy given member media.
     *
     * @param \Cortex\Fort\Models\Member        $member
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member, Media $media)
    {
        $member->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('adminarea.members.edit', ['member' => $member]),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'media', 'id' => $media->getKey()])],
        ]);
    }
}
