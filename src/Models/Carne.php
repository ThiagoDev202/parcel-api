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
        $valorRestante = $this->valorTotal - $this->valorEntrada;
        $valorParcela = $valorRestante / $this->qtdParcelas;
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
            // Avança para o próximo vencimento
            $this->qtdParcelas -= 1;  // Reduz o número de parcelas restantes
            if ($this->periodicidade === 'mensal') {
                $dataAtual->add(new \DateInterval('P1M'));
            } elseif ($this->periodicidade === 'semanal') {
                $dataAtual->add(new \DateInterval('P1W'));
            } else {
                throw new \Exception("Periodicidade inválida: " . $this->periodicidade);
            }
        }

        for ($i = 1; $i <= $this->qtdParcelas; $i++) {
            $valorParcelaFormatado = number_format($valorParcela, 2, '.', '');

            $parcelas[] = [
                'data_vencimento' => $dataAtual->format('Y-m-d'),
                'valor' => $valorParcelaFormatado,
                'numero' => $i + ($this->valorEntrada > 0 ? 1 : 0), // Ajusta o número da parcela
                'entrada' => false
            ];

            // Avança para a próxima data com base na periodicidade
            if ($this->periodicidade === 'mensal') {
                $dataAtual->add(new \DateInterval('P1M')); 
            } elseif ($this->periodicidade === 'semanal') {
                $dataAtual->add(new \DateInterval('P1W')); 
            }
        }

        // Ajusta a última parcela para garantir que o somatório seja exato
        $somatorioParcelas = array_reduce($parcelas, function ($sum, $parcela) {
            return $sum + $parcela['valor'];
        }, 0);

        if (number_format($somatorioParcelas, 2, '.', '') != number_format($this->valorTotal, 2, '.', '')) {
            $diferenca = $this->valorTotal - $somatorioParcelas;
            $parcelas[count($parcelas) - 1]['valor'] = number_format($parcelas[count($parcelas) - 1]['valor'] + $diferenca, 2, '.', '');
        }

        // Formata o valor total com no máximo 2 casas decimais
        $valorTotalFormatado = number_format($this->valorTotal, 2, '.', '');

        return [
            'total' => $valorTotalFormatado,
            'valor_entrada' => $valorEntradaFormatado,
            'parcelas' => $parcelas,
            'qtd_parcelas' => count($parcelas) // Inclui a quantidade de parcelas no resultado
        ];
    }

    public static function findById($id)
    {
        $fakeDatabase = [
            // Mensal
            1  => new Carne(10000, 12, '2024-08-01', 'mensal', 0),
            2  => new Carne(5000, 6, '2024-09-15', 'mensal', 500),
            3  => new Carne(12000, 24, '2024-07-10', 'mensal', 1000),
            4  => new Carne(3000, 3, '2024-10-05', 'mensal', 0),
            5  => new Carne(7500, 10, '2024-08-20', 'mensal', 100),
            6  => new Carne(2000, 4, '2024-12-01', 'mensal', 500),
            7  => new Carne(6000, 12, '2024-11-11', 'mensal', 0),
            8  => new Carne(4500, 15, '2024-09-25', 'mensal', 200),
            9  => new Carne(8000, 8, '2024-08-15', 'mensal', 0),
            10 => new Carne(9000, 18, '2024-07-30', 'mensal', 0),
        
            // Semanal
            11 => new Carne(2000, 8, '2024-08-01', 'semanal', 0),
            12 => new Carne(1500, 10, '2024-09-01', 'semanal', 0),
            13 => new Carne(3000, 6, '2024-07-20', 'semanal', 100),
            14 => new Carne(5000, 15, '2024-06-15', 'semanal', 200),
            15 => new Carne(1000, 12, '2024-08-10', 'semanal', 50),
            16 => new Carne(4000, 8, '2024-08-20', 'semanal', 300),
            17 => new Carne(2500, 10, '2024-09-05', 'semanal', 0),
            18 => new Carne(6000, 14, '2024-07-25', 'semanal', 100),
            19 => new Carne(3500, 8, '2024-08-15', 'semanal', 0),
            20 => new Carne(2000, 6, '2024-08-01', 'semanal', 200),
        ];
        
        return isset($fakeDatabase[$id]) ? $fakeDatabase[$id] : null;
    }
}
