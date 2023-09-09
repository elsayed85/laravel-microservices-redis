<?php

namespace Elsayed85\LmsRedis\Utils;

class Enum
{
    private static function all()
    {
        $services = scandir(__DIR__.'/../Services');
        return collect($services)
            ->filter(function ($service) {
                return is_dir(__DIR__.'/../Services/'.$service);
            })
            ->reject(function ($service) {
                return in_array($service, ['.', '..', 'BaseService']);
            })
            ->map(function ($service) {
                $enumFiles = scandir(__DIR__.'/../Services/'.$service.'/Enum');
                return collect($enumFiles)
                    ->reject(function ($file) {
                        return in_array($file, ['.', '..']);
                    })
                    ->map(function ($file) use ($service) {
                        $fileName = explode('.', $file)[0];
                        return 'Elsayed85\\LmsRedis\\Services\\'.$service.'\\Enum\\'.$fileName;
                    })
                    ->toArray();
            })
            ->flatten()
            ->mapWithKeys(function ($file) {
                return [$file => $file::cases()];
            })
            ->toArray();
    }

    public static function From($type): ?object
    {
        $enums = self::all();
        foreach ($enums as $enum) {
            $case = collect($enum)->firstWhere('value', $type);
            if ($case !== null) {
                return $case;
            }
        }
        return null;
    }
}
