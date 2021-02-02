<?php


namespace Test\unit;

use PHPUnit\Framework\TestCase;
use Purt09\Payeer\PayeerClient;

class PayeerClientTest extends TestCase
{
    private $wallet = 'P1027549216';
    private $id = 1056280602;
    private $secret_key = '123';


    public function testAuth(): void
    {
        $client = new PayeerClient($this->wallet, $this->id, $this->secret_key);

        $this->assertTrue($client->isAuth());
    }

    public function testWrongAuth(): void
    {
        $client = new PayeerClient($this->wallet, $this->id, '123');

        $this->assertFalse($client->isAuth());
    }

    public function testBalance(): void
    {
        $client = new PayeerClient($this->wallet, $this->id, $this->secret_key);

        $this->assertTrue(array_key_exists('balance', $client->getBalance()));
    }

    public function testTransferApi()
    {
        $client = new PayeerClient($this->wallet, $this->id, $this->secret_key);

        $params = [
            'curIn' => 'RUB',
            'sum' => 1.00,
            'curOut' => 'RUB',
            'to' => 'P1041811353',
            'comment' => 'id'
        ];

        $result = $client->transfer($params);
        $this->assertTrue($result['success']);
    }
}