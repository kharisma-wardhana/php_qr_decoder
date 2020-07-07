<?php

namespace ZxingSPE;

use Exception;
use ZxingSPE\QrReader;

final class QRIS
{
    public $response = array();

    public function checkQR($filepath)
    {
        $qrcode = new QrReader($filepath, QrReader::SOURCE_TYPE_FILE);
        try {
            $this->response['success'] = false;
            $this->response['message'] = 'failed upload QRIS';
            //return decoded text from QR Code
            $data_qr = $qrcode->text();
            if ($data_qr) {
                $this->response['success'] = false;
                $this->response['message'] = 'NMID not found';
                if ($data = $this->decodeQRIS($data_qr)) {
                    $this->response['success'] = true;
                    $this->response['message'] = 'success QRIS data';
                    $this->response['data'] = $data;
                }
            }
        } catch (Exception $err) {
            $this->response['success'] = false;
            $this->response['message'] = $err->getMessage() ?: 'Unable to catch error message';
        }
        return $this->response;
    }

    public function decodeQRIS($data_qr)
    {
        //======
        // uncomment this to check full qr data
        // var_dump($data_qr);
        //======

        // constant length value
        $tag_len = 2; // example (tag 51 that indicate merchant_account_info)
        $tag_data = 4; // example (tag 51 44 XXXXXX that 44 indicate length value data)

        $fisrt_tag = substr($data_qr, 0, $tag_len);

        // use (-8) cause crc usually has 4 char + 4 tag data (63 04 XXXX)
        $last_tag = substr($data_qr, -8, $tag_len);

        //check format qris tag and crc tag
        if ($fisrt_tag != '00' || $last_tag != '63') {
            $this->response['success'] = false;
            $this->response['message'] = 'Not QRIS';
            return false;
        }

        $merchant_accInfo = '';
        $data_nmid = '';

        //looping to check outer qris tag 
        for ($tag = 0; $tag < strlen($data_qr); $tag++) {
            $tag_id = substr($data_qr, 0, $tag_len);
            $tag_info = substr($data_qr, 0, $tag_data);
            $data_len = substr($tag_info, $tag_len);
            $decode_data = substr($data_qr, $tag_data, (int) $data_len);

            if ($tag_id == '51') {
                $merchant_accInfo = $decode_data;
                //break this loop cause we only need to check NMID in tag 51
                break;
            }

            $data_qr = substr($data_qr, ($tag_data + (int) $data_len));
            // var_dump($data_qr);
        }

        //looping to check data in merchant_account_info tag
        for ($tag = 0; $tag < strlen($merchant_accInfo); $tag++) {
            $tag_sub_id = substr($merchant_accInfo, 0, $tag_len);
            $tag_info = substr($merchant_accInfo, 0, $tag_data);
            $data_len = substr($tag_info, $tag_len);
            $decode_data = substr($merchant_accInfo, $tag_data, (int) $data_len);
            // var_dump($decode_data);
            if ($tag_sub_id == '02') {
                $data_nmid = $decode_data;
                //break this loop cause we only need data in tag 51 XX 02 
                break;
            }
            $merchant_accInfo = substr($merchant_accInfo, ($tag_data + (int) $data_len));
        }
        // var_dump($data_nmid);
        return $data_nmid;
    }
}
