<?php


class Payeer
{
    const LINK = "https://payeer.com/merchant/?";
    /**
     * @var int ид мерчанта
     */
    private $merchant_id;

    /**
     * @var string Секретный ключ
     */
    private $secret_key;

    public function __construct(int $merchant_id, string $secret_key)
    {
        $this->merchant_id = $merchant_id;
        $this->secret_key = $secret_key;
    }

    public function getSign(array $params): string
    {
        return strtoupper(hash('sha256', implode(':', $params)));
    }

    public function create(string $m_orderid, float $m_amount, string $m_desc, string $m_curr = 'RUB')
    {
        $m_desc = base64_encode($m_desc);
        $arHash = [
            $this->merchant_id,
            $m_orderid,
            $m_amount,
            $m_curr,
            $m_desc,
            $this->secret_key
        ];
        $arGetParams = array(
            $this->merchant_id,
            'm_orderid' => $m_orderid,
            'm_amount' => $m_amount,
            'm_curr' => $m_curr,
            'm_desc' => $m_desc,
            'm_sign' => $this->getSign($arHash),
        );

        return self::LINK.http_build_query($arGetParams);
    }
}