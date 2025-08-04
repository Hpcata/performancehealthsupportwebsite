<?php

namespace App\Constants;

class AgeGroups
{
    const AGE_15_18 = '15-18';
    const AGE_18_22 = '18-22';
    const AGE_22_30 = '22-30';
    const OVER_30 = 'Over-30';
    
    /**
     * Get all age groups
     *
     * @return array
     */
    public static function getAll(): array
    {
        return [
            '15_18' => self::AGE_15_18,
            '18_22' => self::AGE_18_22,
            '22_30' => self::AGE_22_30,
            'over_30' => self::OVER_30,
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