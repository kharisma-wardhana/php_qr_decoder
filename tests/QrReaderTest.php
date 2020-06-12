<?php

namespace Khanamiryan\QrCodeTests;

use ZxingSPE\QrReader;

class QrReaderTest extends \PHPUnit\Framework\TestCase
{

    public function testText1()
    {
        $image = __DIR__ . "/qrcodes/hello_world.png";

        $qrcode = new QrReader($image);
        $this->assertSame("Hello world!", $qrcode->text());
    }
}
