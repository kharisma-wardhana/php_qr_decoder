<?php

namespace ZxingSPE;

final class QRISMerchantInfo
{
    private $globalId;
    private $mPan;
    private $mCriteria;
    private $mid;

    public function setGlobalId($globalId)
    {
        $this->globalId = $globalId;
    }

    public function setMPAN($mPan)
    {
        $this->mPan = $mPan;
    }

    public function setMCriteria($mCriteria)
    {
        $this->mCriteria = $mCriteria;
    }

    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    public function getGlobalId()
    {
        return $this->globalId;
    }

    public function getMPAN()
    {
        return $this->mPan;
    }

    public function getMCriteria()
    {
        return $this->mCriteria;
    }

    public function getMid()
    {
        return $this->mid;
    }

    public function toString()
    {
        return "globalId: " . $this->globalId . " mPan: " . $this->mPan . " mCriteria: " . $this->mCriteria . " mid: " . $this->mid;
    }
}