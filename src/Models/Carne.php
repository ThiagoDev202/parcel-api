<?php

namespace Thiago\ParcelApi\Models;

use Thiago\ParcelApi\Helpers\DateHelper;

class Carne
{
    private $valorTotal;
    private $qtdParcelas;
    private $dataPrimeiroVencimento;
    private $periodicidade;
    private $valorEntrada;

    public function __construct($valorTotal, $qtdParcelas, $dataPrimeiroVencimento, $periodicidade, $valorEntrada)
    {
        $this->valorTotal = $valorTotal;
        $this->qtdParcelas = $qtdParcelas;
        $this->dataPrimeiroVencimento = $dataPrimeiroVencimento;
        $this->periodicidade = $periodicidade;
        $this->valorEntrada = $valorEntrada;
    }

    public function gerarParcelas()
    {
        $parcelas = [];
        $valorParcela = ($this->valorTotal - $this->valorEntrada) / $this->qtdParcelas;
        $dataAtual = new \DateTime($this->dataPrimeiroVencimento);

        // Formata o valor da entrada com no máximo 2 casas decimais
        $valorEntradaFormatado = number_format($this->valorEntrada, 2, '.', '');

        // Adiciona a entrada se houver
        if ($this->valorEntrada > 0) {
            $parcelas[] = [
                'data_vencimento' => $dataAtual->format('Y-m-d'),
                'valor' => $valorEntradaFormatado,
                'numero' => 1,
                'entrada' => true
            ];
            // Avança para o próximo dia para a primeira parcela
            $dataAtual->add(new \DateInterval('P1D')); 
        }

        for ($i = 1; $i <= $this->qtdParcelas; $i++) {
            $valorParcelaFormatado = number_format($valorParcela, 2, '.', '');

            $parcelas[] = [
                'data_vencimento' => $dataAtual->format('Y-m-d'),
                'valor' => $valorParcelaFormatado,
                'numero' => $i + ($this->valorEntrada > 0 ? 1 : 0), // Ajusta o número da parcela para incluir a entrada
                'entrada' => false
            ];

            // Avança para a próxima data com base na periodicidade
            if ($this->periodicidade === 'mensal') {
                $dataAtual->add(new \DateInterval('P1M'));
            } elseif ($this->periodicidade === 'semanal') {
                $dataAtual->add(new \DateInterval('P1W'));
            }
        }

        // Formata o valor total com no máximo 2 casas decimais
        $valorTotalFormatado = number_format($this->valorTotal, 2, '.', '');

        return [
            'total' => $valorTotalFormatado,
            'valor_entrada' => $valorEntradaFormatado,
            'parcelas' => $parcelas
        ];
    }
}
