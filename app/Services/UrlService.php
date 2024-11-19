<?php

namespace App\Services;

use Hashids\Hashids;

class UrlService
{
    protected $hashId;

    public function __construct()
    {
        $this->hashId = new Hashids('', 12);
    }

    public function encodeId($id)
    {
        return $this->hashId->encode($id);
    }

    public function decodeId($id)
    {
        $decryptId = $this->hashId->decode($id);
        return reset($decryptId);
    }
}
