# Parcel API

A Parcel API é uma aplicação em PHP para gerar e apresentar parcelas de um carnê. Esta API permite a criação de carnês com parcelas mensais ou semanais, incluindo a opção de adicionar uma entrada.

## Funcionalidades

1. **Criação de um Carnê**:
   - **Parâmetros**:
     - `valor_total` (float): O valor total do carnê.
     - `qtd_parcelas` (int): A quantidade de parcelas.
     - `data_primeiro_vencimento` (string, formato YYYY-MM-DD): A data do primeiro vencimento.
     - `periodicidade` (string, valores possíveis: "mensal", "semanal"): A periodicidade das parcelas.
     - `valor_entrada` (float, opcional): O valor da entrada.
   - **Resposta**:
     - `total` (float): O valor total do carnê.
     - `valor_entrada` (float): O valor da entrada.
     - `parcelas` (lista):
       - `data_vencimento` (string, formato YYYY-MM-DD): A data de vencimento da parcela.
       - `valor` (float): O valor da parcela.
       - `numero` (int): O número da parcela.
       - `entrada` (boolean, opcional): Indica se a parcela é a entrada.

2. **Recuperação de Parcelas**:
   - **Parâmetros**:
     - `id` (int): O identificador do carnê.
   - **Resposta**:
     - Retorna as parcelas do carnê com os mesmos campos da criação.

## Cenários de Teste

1. **Divisão de R$ 100,00 em 12 Parcelas**:
   - **Entrada**:
     ```json
     {
       "valor_total": 100.00,
       "qtd_parcelas": 12,
       "data_primeiro_vencimento": "2024-08-01",
       "periodicidade": "mensal"
     }
     ```
   - **Saída**: O somatório das parcelas deve ser igual a 100.00.

2. **Divisão de R$ 0,30 em 2 Parcelas com Entrada de R$ 0,10**:
   - **Entrada**:
     ```json
     {
       "valor_total": 0.30,
       "qtd_parcelas": 2,
       "data_primeiro_vencimento": "2024-08-01",
       "periodicidade": "semanal",
       "valor_entrada": 0.10
     }
     ```
   - **Saída**: O somatório das parcelas deve ser igual a 0.30, a entrada deve ser considerada como uma parcela, e a quantidade de parcelas deve ser 2.

## Instalação

### Requisitos

- PHP 8.1 ou superior
- Extensões PHP: `ext-dom`, `ext-xml`, `ext-xmlwriter`
- Composer

### Passos de Instalação

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/ThiagoDev202/parcel-api.git
   cd parcel-api
   composer install
   php -S localhost:8000 -t public


### Deploy

Para o deploy da aplicação, siga os seguintes passos:
Configure o Ambiente de Produção

Certifique-se de que o servidor possui PHP 8.1 ou superior e as extensões necessárias instaladas.
Copie os Arquivos para o Servidor de Produção

Utilize ferramentas como SCP ou rsync para transferir os arquivos para o servidor de produção.
Instale as Dependências no Servidor de Produção

No servidor de produção, instale as dependências com:

```bash

composer install --no-dev