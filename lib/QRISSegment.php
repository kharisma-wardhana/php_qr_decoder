<?php

namespace ZxingSPE;

final class QRISSegment
{
    private $rootId;
    private $field;
    private $length;
    private $data;

    public function __construct($rootId, $field, $length, $data)
    {
        $this->rootId = $rootId;
        $this->field = $field;
        $this->length = $length;
        $this->data = $data;
    }

    public function getRootId()
    {
        return $this->rootId;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setRootId($rootId)
    {
        $this->rootId = $rootId;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function toString()
    {
        return $this->rootId . ':' . $this->field . ':' . $this->length . ':' . $this->data;
    }
}