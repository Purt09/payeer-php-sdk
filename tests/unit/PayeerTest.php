<?php


namespace Test\unit;

use PHPUnit\Framework\TestCase;
use Purt09\Payeer\Payeer;

class PayeerTest extends TestCase
{
    private $merchant_id = 1147929546;
    private $secret_key = 'EC23E85416F4AB3045A88D45EC071683';

    public function testGetDesc(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $this->assertEquals('dGVzdA==', $payeer->getDesc('test'));
    }

    public function testGetAmount(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $this->assertEquals('150.00', $payeer->getAmount(150));
    }

    public function testGetSign(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $arHash = [
            12345,
            number_format(150, 2, '.', ''),
            'RUB',
            $payeer->getDesc('123'),
        ];
        $this->assertEquals('F58357124F326853A91EE698EB13CE81B2245BB516CFC9F673AC7EF67ACDD182', $payeer->getSign($arHash));
    }

    public function testGetNotSign(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $arHash = [
            1234,
            number_format(150, 2, '.', ''),
            'RUB',
            $payeer->getDesc('123'),
        ];
        $this->assertNotEquals('F58357124F326853A91EE698EB13CE81B2245BB516CFC9F673AC7EF67ACDD182', $payeer->getSign($arHash));
    }



    public function testCheckSign(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $str = "m_operation_id=1293284312&m_operation_ps=2609&m_operation_date=01.02.2021%2005:13:13&m_operation_pay_date=01.02.2021%2005:13:19&m_shop=1147929546&m_orderid=shopdigital-367815&m_amount=1.00&m_curr=RUB&m_desc=0J%2FQvtC60YPQv9C60LAg0LIg0LHQvtGC0LUg0J%2FRgNC40LzQtdGAINC80LDQs9Cw0LfQuNC90LAg4oSWMSBCb1QtVC5ydQ%3D%3D&m_status=success&m_sign=C867B2E2D267E9C46CF0A1D2A07B1658D0C1317CB2911BEFA4F6E43BFBDA6D51&lang=ru";
        parse_str($str, $output);
        $this->assertEquals($payeer->checkSign($output), true);
    }

    public function testGenerateLink(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $link = $payeer->generateLink(12345,150,'123');

        $this->assertEquals('https://payeer.com/merchant/?m_shop=1147929546&m_orderid=12345&m_amount=150.00&m_curr=RUB&m_desc=MTIz&m_sign=F58357124F326853A91EE698EB13CE81B2245BB516CFC9F673AC7EF67ACDD182&lang=ru', $link);
    }
}