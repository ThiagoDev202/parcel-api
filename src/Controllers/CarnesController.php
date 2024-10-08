<?php

namespace Thiago\ParcelApi\Controllers;

use Thiago\ParcelApi\Models\Carne;

class CarnesController
{
    public function criarCarne()
    {
        // Obtém os dados da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Valida os dados
        $requiredFields = ['valor_total', 'qtd_parcelas', 'data_primeiro_vencimento', 'periodicidade'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Campo '$field' é obrigatório"]);
                return;
            }
        }

        $valorTotal = $data['valor_total'];
        $qtdParcelas = $data['qtd_parcelas'];
        $dataPrimeiroVencimento = $data['data_primeiro_vencimento'];
        $periodicidade = $data['periodicidade'];
        $valorEntrada = isset($data['valor_entrada']) ? $data['valor_entrada'] : 0;

        // Cria o objeto Carne
        $carne = new Carne($valorTotal, $qtdParcelas, $dataPrimeiroVencimento, $periodicidade, $valorEntrada);
        $resultado = $carne->gerarParcelas();

        // Inclui a quantidade de parcelas no resultado
        $resultado['qtd_parcelas'] = $qtdParcelas;

        echo json_encode($resultado);
    }

    public function recuperarParcelas()
    {
        // Obtém o ID da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id']) || !is_int($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Campo "id" é obrigatório e deve ser um número inteiro']);
            return;
        }

        $id = (int) $data['id'];

        $carne = Carne::findById($id);

        if (!$carne) {
            http_response_code(404);
            echo json_encode(['error' => 'Carnê não encontrado']);
            return;
        }

        // Gera as parcelas
        $resultado = $carne->gerarParcelas();

        // Retorna apenas as parcelas
        echo json_encode(['parcelas' => $resultado['parcelas']]);
    }
}
