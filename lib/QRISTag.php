<?php

namespace ZxingSPE;

final class QRISTag
{
    public static $QRISFieldName = [
        "00" => "payloadFormatIndocator",
        "01" => "pointOfInitiationMethod",
        "02" => "merchantAccountInformationVisa",
        "03" => "merchantAccountInformationVisa",
        "04" => "merchantAccountInformationMasterCard",
        "05" => "merchantAccountInformationMasterCard",
        "06" => "merchantAccountInformationEMVCo",
        "07" => "merchantAccountInformationEMVCo",
        "08" => "merchantAccountInformationEMVCo",
        "09" => "merchantAccountInformationDiscover",
        "10" => "merchantAccountInformationDiscover",
        "11" => "merchantAccountInformationAmex",
        "12" => "merchantAccountInformationAmex",
        "13" => "merchantAccountInformationJCB",
        "14" => "merchantAccountInformationJCB",
        "15" => "merchantAccountInformationUnionPay",
        "16" => "merchantAccountInformationUnionPay",
        "17" => "merchantAccountInformationEMVCo",
        "18" => "merchantAccountInformationEMVCo",
        "19" => "merchantAccountInformationEMVCo",
        "20" => "merchantAccountInformationEMVCo",
        "21" => "merchantAccountInformationEMVCo",
        "22" => "merchantAccountInformationEMVCo",
        "23" => "merchantAccountInformationEMVCo",
        "24" => "merchantAccountInformationEMVCo",
        "25" => "merchantAccountInformationEMVCo",
        "26" => "merchantAccountInformationDomestic",
        "27" => "merchantAccountInformationDomestic",
        "28" => "merchantAccountInformationDomestic",
        "29" => "merchantAccountInformationDomestic",
        "30" => "merchantAccountInformationDomestic",
        "31" => "merchantAccountInformationDomestic",
        "32" => "merchantAccountInformationDomestic",
        "33" => "merchantAccountInformationDomestic",
        "34" => "merchantAccountInformationDomestic",
        "35" => "merchantAccountInformationDomestic",
        "36" => "merchantAccountInformationDomestic",
        "37" => "merchantAccountInformationDomestic",
        "38" => "merchantAccountInformationDomestic",
        "39" => "merchantAccountInformationDomestic",
        "40" => "merchantAccountInformationDomestic",
        "41" => "merchantAccountInformationDomestic",
        "42" => "merchantAccountInformationDomestic",
        "43" => "merchantAccountInformationDomestic",
        "44" => "merchantAccountInformationDomestic",
        "45" => "merchantAccountInformationDomestic",
        "46" => "merchantAccountInformationReservedDomesticId",
        "47" => "merchantAccountInformationReservedDomesticId",
        "48" => "merchantAccountInformationReservedDomesticId",
        "49" => "merchantAccountInformationReservedDomesticId",
        "50" => "merchantAccountInformationReservedDomesticId",
        "51" => "merchantAccountInformationDomesticCentralRepository",
        "52" => "merchantCategoryCode",
        "53" => "transactionCurrency",
        "54" => "transactionAmount",
        "55" => "tipIndicator",
        "56" => "tipValueOfFixed",
        "57" => "tipValueOfPercentage",
        "58" => "countryCode",
        "59" => "merchantName",
        "60" => "merchantCity",
        "61" => "postalCode",
        "62" => "addionalData",
        "64" => "merchantInfoLanguage",
        "65" => "rfu",
        "80" => "unreservedTemplates",
        "63" => "crc",
    ];
}