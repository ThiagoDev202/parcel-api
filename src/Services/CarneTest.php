<?php

use PHPUnit\Framework\TestCase;
use Thiago\ParcelApi\Models\Carne;

class CarneTest extends TestCase
{
    public function testGerarParcelasSemEntrada()
    {
        $carne = new Carne(100.00, 12, '2024-08-01', 'mensal', 0);
        $resultado = $carne->gerarParcelas();

        // Testa o total
        $this->assertEquals('100.00', $resultado['total']);
        $this->assertEquals('0.00', $resultado['valor_entrada']);

        // Testa a quantidade de parcelas
        $this->assertCount(12, $resultado['parcelas']);

        // Testa se o valor das parcelas somam o valor total
        $totalParcelas = array_reduce($resultado['parcelas'], function($carry, $parcela) {
            return $carry + floatval($parcela['valor']);
        }, 0);

        $this->assertEquals('100.00', number_format($totalParcelas, 2, '.', ''));
    }

    public function testGerarParcelasComEntrada()
    {
        $carne = new Carne(0.30, 2, '2024-08-01', 'semanal', 0.10);
        $resultado = $carne->gerarParcelas();

        // Testa o total
        $this->assertEquals('0.30', $resultado['total']);
        $this->assertEquals('0.10', $resultado['valor_entrada']);

        // Testa a quantidade de parcelas
        $this->assertCount(2, $resultado['parcelas']);

        // Testa se o valor das parcelas somam o valor total
        $totalParcelas = array_reduce($resultado['parcelas'], function($carry, $parcela) {
            return $carry + floatval($parcela['valor']);
        }, 0);

        $this->assertEquals('0.30', number_format($totalParcelas, 2, '.', ''));
    }
}
