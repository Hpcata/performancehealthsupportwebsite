<?php

namespace App\Services;
use App\Models\Tracking;
use App\Models\TrackingType;
use App\Models\UserClick;
use App\Models\SectionElement;
use Illuminate\Support\Facades\Request;

class ActivityTracker
{
    public static function log(string $type, ?int $userId = null, array $details = [])
    {
        $typeId = TrackingType::where('type', $type)->value('id');
        if (!$typeId) return;

        Tracking::create([
            'type_id' => $typeId,
            'user_id' => $userId,
            'ip' => Request::ip(),
            'section_element_id' => $details['section_element_id'] ?? null,
            'user_click_id' => $details['user_click_id'] ?? null,
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function click(string $element, ?int $userId = null)
    {
        $elementModel = SectionElement::firstOrCreate(
            ['section_element_name' => $element],
            ['description' => null]
        );

        return UserClick::create([
            'user_id' => $userId,
            'section_element_id' => $elementModel->id,
            'ip' => Request::ip(),
            'clicked_at' => now(),
        ]);
    }
}