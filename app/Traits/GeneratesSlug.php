<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesSlug
{
    public static function bootGeneratesSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::createUniqueSlug($model->{static::slugSource()});
            }
        });
    }

    protected static function slugSource(): string
    {
        return 'name';
    }

    protected static function createUniqueSlug(string $value): string
    {
        $slug = Str::slug($value);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
