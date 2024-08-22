<?php

namespace Thiago\ParcelApi\Helpers;

class DateHelper
{
    public static function adicionarPeriodo($data, $periodicidade)
    {
        $data = new \DateTime($data);
        if ($periodicidade === 'mensal') {
            $data->modify('+1 month');
        } elseif ($periodicidade === 'semanal') {
            $data->modify('+1 week');
        }
        return $data->format('Y-m-d');
    }
}
