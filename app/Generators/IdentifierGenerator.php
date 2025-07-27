<?php

declare(strict_types=1);

namespace App\Generators;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\UniqueIdentifierGenerator;
use Throwable;

class IdentifierGenerator implements UniqueIdentifierGenerator
{
    /**
     * @example "Company Name" => "Company_Name__c8dDppEyy95eoe7KT7Pr3"
     * @see https://github.com/hidehalo/nanoid-php
     */
    public static function generate(Model $model): string
    {
        try {
            if (!$model instanceof Tenant) {
                return Str::nanoid();
            }

            return Str::of($model->name)
                    ->replaceMatches('/[^a-z0-9 ]/i', '')
                    ->ucwords()
                    ->replace(' ', '_') . '__' . Str::nanoid();
        } catch (Throwable $e) {
            Log::error('Cannot generate custom tenant ID. Falling back to plain nanoid. Reason: ' . $e->getMessage());
            return Str::nanoid();
        }
    }
}
