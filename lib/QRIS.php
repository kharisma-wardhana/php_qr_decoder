<?php

namespace ZxingSPE;

use ZxingSPE\QrReader;

final class QRIS
{
    public const MERCHANT_INFO_DOMESTIC = 2;
    public const MERCHANT_INFO_CENTRAL = 3;

    public $response = array();

    public function parsingQRISFile($filepath)
    {
        $qrcode = new QrReader($filepath, QrReader::SOURCE_TYPE_FILE);
        try {
            // return decoded text from QR Code File
            $data_qr = $qrcode->text();

            // check if the text is a valid qris value
            $validQRIS = $this->isQRISValid($data_qr);

            if (!$validQRIS) {
                throw new \Exception("Invalid QRIS");
            }

            // decode qris text to get data nmid 
            $dataQRIS = $this->parsingRootId($data_qr);
            $this->parsingMerchantInfo($dataQRIS);
            $merchantInfoDomestic = $dataQRIS[self::MERCHANT_INFO_DOMESTIC];
            $merchantInfoCentral = $dataQRIS[self::MERCHANT_INFO_CENTRAL];
            $mid = $merchantInfoDomestic->getData()->getMid();
            $nmid = $merchantInfoCentral->getData()->getMid();
            $mpan = $merchantInfoDomestic->getData()->getMpan();

            $this->response['success'] = true;
            $this->response['message'] = 'Success Get QRIS data';
            $this->response['data'] = [
                'mid' => $mid,
                'nmid' => $nmid,
                'mpan' => $mpan
            ];
        } catch (\Exception $err) {
            $this->response['success'] = false;
            $this->response['message'] = $err->getMessage() ?: 'Unable to catch error message';
        }
        return $this->response;
    }

    public function parsingQRIS($data_qr)
    {
        try {
            $validQRIS = $this->isQRISValid($data_qr);

            if (!$validQRIS) {
                throw new \Exception("Invalid QRIS");
            }

            $dataQRIS = $this->parsingRootId($data_qr);
            $this->parsingMerchantInfo($dataQRIS);
            $merchantInfoDomestic = $dataQRIS[self::MERCHANT_INFO_DOMESTIC];
            $merchantInfoCentral = $dataQRIS[self::MERCHANT_INFO_CENTRAL];
            $mid = $merchantInfoDomestic->getData()->getMid();
            $nmid = $merchantInfoCentral->getData()->getMid();
            $mpan = $merchantInfoDomestic->getData()->getMpan();

            $this->response['success'] = true;
            $this->response['message'] = 'Success Get QRIS data';
            $this->response['data'] = [
                'mid' => $mid,
                'nmid' => $nmid,
                'mpan' => $mpan
            ];
        } catch (\Exception $ex) {
            $this->response['success'] = false;
            $this->response['message'] = $ex->getMessage() ?: 'Unable to catch error message';
        }
    }

    private function isQRISValid($qrdata)
    {
        //checsum 4 digit ascii
        if ($qrdata != NULL && strlen($qrdata) > 4) {
            $qrDataNonCRC = substr($qrdata, 0, strlen($qrdata) - 4 - 0);
            $qrCRC = strtoupper(substr($qrdata, strlen($qrdata) - 4));
            $byte_array = unpack('C*', $qrDataNonCRC);
            $checkCRC = strtoupper($this->checkCRC($byte_array));

            if (
                substr($qrDataNonCRC, 0, strlen("00")) == "00"
                && strtolower($qrCRC) == strtolower($checkCRC)
            ) {
                return true;
            }
        }
        return false;
    }

    private function checkCRC($bytes)
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        $sCRC = "";
        foreach ($bytes as $b) {
            for ($i = 0; $i < 8; $i++) {
                $bit = (($b >> (7 - $i) & 1) == 1);
                $c15 = (($crc >> 15 & 1) == 1);
                $crc <<= 1;
                if ($c15 ^ $bit) {
                    $crc ^= $polynomial;
                }
            }
        }
        $crc &= 0xFFFF;
        $sCRC = sprintf("%04x", $crc);
        return $sCRC;
    }

    private function parsingRootId($payload)
    {
        $listSegment = array();
        for ($tag = 0; $tag < 100; $tag++) {
            $rootId = sprintf("%02d", $tag);
            try {
                // check if payload string start with root id
                if (substr($payload, 0, strlen($rootId)) == $rootId) {
                    $payload = substr($payload, 2);
                    $data_len = substr($payload, 0, 2);

                    $payload = substr($payload, 2);

                    $data = substr($payload, 0, $data_len);
                    $field = $this->getFieldName(QRISTag::$QRISFieldName, $rootId);

                    $segment = new QRISSegment($rootId, $field, $data_len, $data);
                    array_push($listSegment, $segment);

                    $payload = substr($payload, $data_len);
                }
            } catch (\Exception $ex) {
                throw new \Exception("Error parsing QRIS " . $ex->getMessage());
            }
        }
        return $listSegment;
    }

    private function getFieldName($fieldName, $rootId)
    {
        foreach ($fieldName as $key => $value) {
            if ($key == $rootId) {
                return $value;
            }
        }
        return "";
    }

    private function parsingMerchantInfo($payload)
    {
        // looping payload to get merchant info
        foreach ($payload as $segment) {
            $srootId = (int) $segment->getRootId();
            if ($srootId >= 2 && $srootId <= 51) {
                $payload = $segment->getData();
                $segment->setData($this->getMerchantInfo($payload));
            }
        }
    }

    private function getMerchantInfo($payload)
    {
        $merchantInfo = new QRISMerchantInfo();
        for ($tag = 0; $tag <= 3; $tag++) {
            $srootId = sprintf("%02d", $tag);
            if (substr($payload, 0, strlen($srootId)) == $srootId) {
                $payload = substr($payload, 2);
                $data_len = substr($payload, 0, 2);
                $payload = substr($payload, 2);
                $data = substr($payload, 0, $data_len);
                if ($tag == 0) {
                    $merchantInfo->setGlobalId($data);
                } else if ($tag == 1) {
                    $merchantInfo->setMPAN($data);
                } else if ($tag == 2) {
                    $merchantInfo->setMid($data);
                } else if ($tag == 3) {
                    $merchantInfo->setMCriteria($data);
                }

                $payload = substr($payload, $data_len);
            }
        }
        return $merchantInfo;
    }
}
