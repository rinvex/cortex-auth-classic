<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Support\Str;
use Cortex\Fort\Models\Admin;
use Spatie\MediaLibrary\Models\Media;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class AdminsMediaController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'admin';

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
     * Destroy given admin media.
     *
     * @param \Cortex\Fort\Models\Admin          $admin
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin, Media $media)
    {
        $admin->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('adminarea.admins.edit', ['admin' => $admin]),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'media', 'id' => $media->getKey()])],
        ]);
    }
}
