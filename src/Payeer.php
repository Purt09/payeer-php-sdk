<?php

namespace Purt09\Payeer;

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
        array_unshift($params, $this->merchant_id);
        $params[] = $this->secret_key;
        return $this->hash($params); ;
    }

    public function hash(array $params): string
    {
        return strtoupper(hash('sha256', implode(':', $params)));
    }

    public function getAmount(float $amount)
    {
        return number_format($amount, 2, '.', '');
    }

    public function generateLink(string $m_orderid, float $m_amount, string $m_desc, string $m_curr = 'RUB', string $lang = 'ru')
    {
        $arHash = [
            $m_orderid,
            $this->getAmount($m_amount),
            $m_curr,
            $this->getDesc($m_desc),
        ];
        $arGetParams = array(
            'm_shop' => $this->merchant_id,
            'm_orderid' => $m_orderid,
            'm_amount' => $this->getAmount($m_amount),
            'm_curr' => $m_curr,
            'm_desc' => $this->getDesc($m_desc),
            'm_sign' => $this->getSign($arHash),
            'lang' => $lang
        );

        return self::LINK.http_build_query($arGetParams);
    }

    public function getDesc(string $desc): string
    {
        return base64_encode($desc);
    }

    public function checkSign(array $request): bool
    {
        if (isset($request['m_operation_id']) && isset($request['m_sign']))
        {
            $arHash = array(
                $request['m_operation_id'],
                $request['m_operation_ps'],
                $request['m_operation_date'],
                $request['m_operation_pay_date'],
                $request['m_shop'],
                $request['m_orderid'],
                $request['m_amount'],
                $request['m_curr'],
                $request['m_desc'],
                $request['m_status']
            );

            if (isset($request['m_params']))
            {
                $arHash[] = $request['m_params'];
            }

            $arHash[] = $this->secret_key;

            if ($request['m_sign'] == $this->hash($arHash) && $request['m_status'] == 'success')
            {
                return true;
            }
        }
        return false;
    }
}