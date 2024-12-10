<?php

namespace App\Models;

use App\Models\Media;
use App\Models\Product;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteSettings extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function saveSettings($pageId, $data, $section) {
        if ($pageId == 'home' && $section == 'offer') {
            $this->saveOfferSection($data, $pageId);
            return $this;
        } else if ($pageId == 'home' && $section == 'banner') {
            $this->saveBannerSection($data, $pageId);
            return $this;
        } else if ($pageId == 'home' && $section == 'category') {
            $this->saveCategorySection($data, $pageId);
            return $this;
        } else if ($pageId == 'general' && $section == 'header') {
            $this->saveHeaderSetting($data, $pageId);
            return $this;
        } else if ($pageId == 'general' && $section == 'footer') {
            $this->saveFooterSetting($data, $pageId);
            return $this;
        } else if ($pageId == 'general' && $section == 'common') {
            $this->saveCommonSetting($data, $pageId);
            return $this;
        } else {
            $res = Self::where('page_id', $pageId)->where('meta_key', 'LIKE', "{$section}_%")->delete(); // use db object model here
        }

        if ($data) {
            $data = $this->flattenArray($data);

            if ($data) {
                foreach ($data as $key => $value) {
                    if (is_object($value) && get_class($value) == 'Illuminate\Http\UploadedFile') {
                        $fileService = new FileService;
                        $uploadedMediaObj = $fileService->uploadFile($value);
                        $value = $uploadedMediaObj->id;
                    }

                    $tempKey = explode('_', $key);
                    $tempKey = end($tempKey);
                    $sortOrder = $data['offer_sortorder_' . $tempKey] ?? 0;

                    $this->updateOrCreate([
                        'meta_key' => $key,
                    ], [
                        'page_id' => $pageId,
                        'meta_key' => $key,
                        'meta_value' => $value ?? '',
                        'sort_order' => $sortOrder,
                    ]);
                }
            }
        }
    }

    public function flattenArray($array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $prefix . $key . '_'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    public function unflattenArray($array)
    {
        $result = [];
        foreach ($array as $flatKey => $value) {
            $keys = explode('_', $flatKey);
            $temp = &$result;

            foreach ($keys as $key) {
                if (!isset($temp[$key])) {
                    $temp[$key] = [];
                }
                $temp = &$temp[$key];
            }
            $temp = $value;
        }
        return $result;
    }

    public function getSettings($pageId, $isForFront = false)
    {
        $currentPageData = $this->where('page_id', $pageId)->get();
        $currentPageDataArray = $currentPageData->toArray();
        $dataToReturn = array_combine($currentPageData->pluck('meta_key')->toArray(), $currentPageData->pluck('meta_value')->toArray());
        $dataToReturn = $this->unflattenArray($dataToReturn);

        if ($pageId == 'home' && isset($dataToReturn['offer']) && $dataToReturn['offer']) {
            $offers = [];

            foreach ($currentPageDataArray as $d) {
                if (strpos($d['meta_key'], 'offer_') === 0) {
                    $temp = json_decode($d['meta_value'], true);
                    $mediaObj = new Media;
                    if (isset($temp['image'])) {
                        $temp['image'] = $mediaObj->getMediaPathByMediaID($temp['image']);
                    } else {
                        $temp['image'] = '';
                    }
                    $temp['id'] = $d['id'];
                    $temp['order'] = $d['sort_order'];
                    $offers[] = $temp;
                }
            }

            usort($offers, function ($a, $b) {
                return $a['order'] - $b['order'];
            });
            $dataToReturn['offer'] = $offers;
        }

        if ($pageId == 'home' && isset($dataToReturn['banner']) && $dataToReturn['banner']) {
            $banners = [];

            foreach ($currentPageDataArray as $d) {
                if (strpos($d['meta_key'], 'banner_') === 0) {
                    $temp = json_decode($d['meta_value'], true);
                    $mediaObj = new Media;
                    if (isset($temp['image'])) {
                        $temp['image'] = $mediaObj->getMediaPathByMediaID($temp['image']);
                    } else {
                        $temp['image'] = '';
                    }
                    $temp['id'] = $d['id'];
                    $temp['order'] = $d['sort_order'];
                    $banners[] = $temp;
                }
            }

            usort($banners, function ($a, $b) {
                return $a['order'] - $b['order'];
            });
            $dataToReturn['banner'] = $banners;
        }

        if($pageId == 'general') {
            if(isset($dataToReturn['header'])) {
                if(isset($dataToReturn['header']['headermenu'])) {
                    $dataToReturn['header']['headermenu'] = json_decode($dataToReturn['header']['headermenu'], true);
                }

                if(isset($dataToReturn['header']['site'])) {
                    $mediaObj = new Media;
                    $dataToReturn['header']['site']['logo'] = $mediaObj->getMediaPathByMediaID($dataToReturn['header']['site']['logo']);
                }
            }

            if(isset($dataToReturn['footer'])) {
                if(isset($dataToReturn['footer']['footermenu1'])) {
                    $dataToReturn['footer']['footermenu1'] = json_decode($dataToReturn['footer']['footermenu1'], true);
                }

                if(isset($dataToReturn['footer']['footermenu2'])) {
                    $dataToReturn['footer']['footermenu2'] = json_decode($dataToReturn['footer']['footermenu2'], true);
                }

                if(isset($dataToReturn['footer']['site'])) {
                    $mediaObj = new Media;
                    $dataToReturn['footer']['site']['logo'] = $mediaObj->getMediaPathByMediaID($dataToReturn['footer']['site']['logo']);
                }
            }
        }

        // echo '<pre>';
        // print_r($dataToReturn);
        // die;

        return $dataToReturn;
    }

    public function saveOfferSection($data, $pageId)
    {
        $count = 0;
        foreach ($data['offer'] as $key => $offer) {
            if ($key == 'remove') {
                if ($offer) {
                    $offer = explode(',', $offer);
                    $res = Self::where('page_id', $pageId)->whereIn('id', $offer)->delete();
                }
                continue;
            }

            $where = [
                'meta_key' => 'offer_' . $key,
            ];

            $origImage = null;
            if (isset($offer['id']) && $offer['id']) {
                $where = [
                    'id' => $offer['id'],
                ];

                //fetch current offer image
                $origImage = Self:: where('id', $offer['id'])->value('meta_value');
                $origImage = json_decode($origImage, true);
                $origImage = isset($origImage['image']) ? $origImage['image'] : null;
            }

            foreach ($offer as $k => $field) {
                if ($k == 'image' && is_object($field) && get_class($field) == 'Illuminate\Http\UploadedFile') {
                    $fileService = new FileService;
                    $uploadedMediaObj = $fileService->uploadFile($field);
                    $offer[$k] = $uploadedMediaObj->id;
                }
            }

            if (!isset($offer['image'])) {
                $offer['image'] = $origImage;
            }

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'offer_' . $key,
                'meta_value' => json_encode($offer),
                'sort_order' => $count,
            ]);

            $count++;
        }

        return $this;
    }

    public function saveBannerSection($data, $pageId)
    {
        $count = 0;
        foreach ($data['banner'] as $key => $banner) {
            if ($key == 'remove') {
                if ($banner) {
                    $banner = explode(',', $banner);
                    $res = Self::where('page_id', $pageId)->whereIn('id', $banner)->delete();
                }
                continue;
            }

            $where = [
                'meta_key' => 'banner_' . $key,
            ];

            $origImage = null;
            if (isset($banner['id']) && $banner['id']) {
                $where = [
                    'id' => $banner['id'],
                ];

                //fetch current banner image
                $origImage = Self::where('id', $banner['id'])->value('meta_value');
                $origImage = json_decode($origImage, true);
                $origImage = isset($origImage['image']) ? $origImage['image'] : null;
            }

            foreach ($banner as $k => $field) {
                if ($k == 'image' && is_object($field) && get_class($field) == 'Illuminate\Http\UploadedFile') {
                    $fileService = new FileService;
                    $uploadedMediaObj = $fileService->uploadFile($field);
                    $banner[$k] = $uploadedMediaObj->id;
                }
            }

            if (!isset($banner['image'])) {
                $banner['image'] = $origImage;
            }

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'banner_' . $key,
                'meta_value' => json_encode($banner),
                'sort_order' => $count,
            ]);

            $count++;
        }

        return $this;
    }

    public function saveCategorySection($data, $pageId)
    {
        $count = 0;
        if (isset($data['category']) && $data['category']) {
            // print_r($categoryDataToSave);

            foreach ($data['category'] as $key => $category) {
                if ($key == 'remove') {
                    if ($category) {
                        $category = explode(',', $category);
                        $res = Self::where('page_id', $pageId)->whereIn('id', $category)->delete();
                    }
                    continue;
                }

                if (!isset($category['parent']) || (!isset($category['subcategory']))) {
                    continue;
                }

                $where = [
                    'meta_key' => 'category_' . $key,
                ];

                if (isset($category['id']) && $category['id']) {
                    $where = [
                        'id' => $category['id'],
                    ];
                }

                $this->updateOrCreate($where, [
                    'page_id' => $pageId,
                    'meta_key' => 'category_' . $key,
                    'meta_value' => json_encode($category),
                    'sort_order' => $count,
                ]);

                $count++;
            }
        }

        return $this;
    }

    public function findCategoryNameById(array $array, $id)
    {
        foreach ($array as $element) {
            if (isset($element['id']) && $element['id'] == $id) {
                return $element;
            }
            if (isset($element['children'])) {
                $result = $this->findCategoryNameById($element['children'], $id);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return null;
    }

    public function saveHeaderSetting($data, $pageId)
    {
        $dataToSave = [];
        if(isset($data['headermenu'])) {
            $where = [
                'meta_key' => 'header_headermenu',
            ];

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'header_headermenu',
                'meta_value' => json_encode($data['headermenu']),
            ]);
        }

        if(isset($data['topnotification'])) {
            $where = [
                'meta_key' => 'header_topnotification',
            ];

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'header_topnotification',
                'meta_value' => $data['topnotification'],
            ]);
        }

        if(isset($data['site_logo'])) {
            if(is_object($data['site_logo']) && get_class($data['site_logo']) == 'Illuminate\Http\UploadedFile') {
                $fileService = new FileService;
                $uploadedMediaObj = $fileService->uploadFile($data['site_logo']);

                $where = [
                    'meta_key' => 'header_site_logo',
                ];

                $this->updateOrCreate($where, [
                    'page_id' => $pageId,
                    'meta_key' => 'header_site_logo',
                    'meta_value' => $uploadedMediaObj->id,
                ]);
            }
        }

        return $this;
    }

    public function saveFooterSetting($data, $pageId)
    {
        $dataToSave = [];
        if(isset($data['footermenu1'])) {
            $where = [
                'meta_key' => 'footer_footermenu1',
            ];

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'footer_footermenu1',
                'meta_value' => json_encode($data['footermenu1']),
            ]);
        }

        if(isset($data['footermenu2'])) {
            $where = [
                'meta_key' => 'footer_footermenu2',
            ];

            $this->updateOrCreate($where, [
                'page_id' => $pageId,
                'meta_key' => 'footer_footermenu2',
                'meta_value' => json_encode($data['footermenu2']),
            ]);
        }

        $fieldToStore = ['footeraddress','footermenu1title','footermenu2title','footersignupforemailtitle','footersignupforemailtext','footercopyrighttext'];

        foreach ($fieldToStore as $fieldKey) {
            if(isset($data[$fieldKey])) {
                $where = [
                    'meta_key' => 'footer_'.$fieldKey,
                ];

                $this->updateOrCreate($where, [
                    'page_id' => $pageId,
                    'meta_key' => 'footer_'.$fieldKey,
                    'meta_value' => $data[$fieldKey],
                ]);
            }
        }

        if(isset($data['footer_site_logo'])) {
            if(is_object($data['footer_site_logo']) && get_class($data['footer_site_logo']) == 'Illuminate\Http\UploadedFile') {
                $fileService = new FileService;
                $uploadedMediaObj = $fileService->uploadFile($data['footer_site_logo']);

                $where = [
                    'meta_key' => 'footer_site_logo',
                ];

                $this->updateOrCreate($where, [
                    'page_id' => $pageId,
                    'meta_key' => 'footer_site_logo',
                    'meta_value' => $uploadedMediaObj->id,
                ]);
            }
        }

        return $this;
    }

    public function saveCommonSetting($data, $pageId) {
        $fieldToStore = ['maxdayforcancellation'];
        foreach ($fieldToStore as $fieldKey) {
            if(isset($data[$fieldKey])) {
                $where = [
                    'meta_key' => 'common_'.$fieldKey,
                ];

                $this->updateOrCreate($where, [
                    'page_id' => $pageId,
                    'meta_key' => 'common_'.$fieldKey,
                    'meta_value' => $data[$fieldKey],
                ]);
            }
        }

        return $this;
    }
}
