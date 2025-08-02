<?php

namespace App\Constants;

class AgeGroups
{
    const UNDER_15 = '< 15';
    const AGE_16_20 = '16 - 20';
    const AGE_21_25 = '21 - 25';
    const AGE_26_30 = '26 - 30';
    
    /**
     * Get all age groups
     *
     * @return array
     */
    public static function getAll(): array
    {
        return [
            'under_15' => self::UNDER_15,
            '16_20' => self::AGE_16_20,
            '21_25' => self::AGE_21_25,
            '26_30' => self::AGE_26_30,
        ];
    }
    
    /**
     * Get age group by key
     *
     * @param string $key
     * @return string|null
     */
    public static function getByKey(string $key): ?string
    {
        return self::getAll()[$key] ?? null;
    }
    
    /**
     * Get key by value
     *
     * @param string $value
     * @return string|null
     */
    public static function getKeyByValue(string $value): ?string
    {
        $all = self::getAll();
        return array_search($value, $all) ?: null;
    }
} 