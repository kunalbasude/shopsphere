<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesSku
{
    public static function bootGeneratesSku(): void
    {
        static::creating(function ($model) {
            if (empty($model->sku)) {
                $model->sku = static::createUniqueSku();
            }
        });
    }

    protected static function createUniqueSku(): string
    {
        do {
            $sku = 'SS-' . strtoupper(Str::random(8));
        } while (static::where('sku', $sku)->exists());

        return $sku;
    }
}
