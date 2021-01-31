<?php


namespace Test\unit;

use PHPUnit\Framework\TestCase;
use Purt09\Payeer\Payeer;

class PayeerTest extends TestCase
{
    private $merchant_id = 1;
    private $secret_key = '123';

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

    public function testGenerateLink(): void
    {
        $payeer = new Payeer($this->merchant_id, $this->secret_key);
        $link = $payeer->generateLink(12345,150,'123');

        $this->assertEquals('https://payeer.com/merchant/?m_shop=1147929546&m_orderid=12345&m_amount=150.00&m_curr=RUB&m_desc=MTIz&m_sign=F58357124F326853A91EE698EB13CE81B2245BB516CFC9F673AC7EF67ACDD182&lang=ru', $link);
    }
}