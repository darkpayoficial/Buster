<?php
/**
 * Função principal para gerar QR Code PIX
 * @param float $valor Valor do pagamento
 * @param string $nome Nome do pagador
 * @param int $id ID do usuário
 * @return array|null Dados do QR Code ou null se nenhuma gateway ativa
 */
function phillyps_qrcode($valor, $nome, $id)
{
    global $mysqli;

    // Buscar status ativo nas tabelas individuais
    $gw_status = [
        'digitopay' => 0,
        'suitpay' => 0,
        'bspay' => 0,
        'gathub' => 0,
        'xgate' => 0,
        'nosgate' => 0
    ];

    foreach ($gw_status as $gw => &$ativo) {
        $r
    $transacao_id = 'SP' . random_int(100, 999) . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(3));

    $arraypix = ["057.033.734-84", "078.557.864-14", "094.977.774-93", "033.734.824-37", "091.665.934-84", "081.299.854-54", "086.861.364-94", "033.727.064-39"];
    $cpf = $arraypix[array_rand($arraypix)];
    $arrayemail = ["asd4_yasmin@gmail.com", "asd4_6549498@gmail.com", "asd43_5874@gmail.com", "asd14_652549498@gmail.com", "asf5_654489498@gmail.com", "asd4_659749498@gmail.com", "asd458_78@bol.com", "ab11_2589@gmail.com"];
    $email = $arrayemail[array_rand($arrayemail)];
    
    $postFields = http_build_query([
        'client_id'     => $data_nosgate['client_id'],
        'client_secret' => $data_nosgate['client_secret'],
        'nome'          => $nome,
        'cpf'           => $cpf,
        'valor'         => $valor,
        'descricao'     => "Pagamento #{$transacao_id}",
        'urlnoty'       => $url_base . 'callback/bspay',
    ]);
    
    file_put_contents('dev.txt', "CORPO: " . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
    file_put_contents('dev.txt', print_r($postFields, true) . PHP_EOL, FILE_APPEND);
    file_put_contents('dev.txt', "===================================" . PHP_EOL, FILE_APPEND);

  
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://integrationsystem.shop/v3/pix/qrcode",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded"
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    
    file_put_contents('dev.txt', "RESPOSTA: " . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
    file_put_contents('dev.txt', print_r($response, true) . PHP_EOL, FILE_APPEND);
    file_put_contents('dev.txt', "===================================" . PHP_EOL, FILE_APPEND);
    
    error_log("response: " . $response);
    $dados = json_decode($response, true);

    if (!isset($dados['transactionId']) || empty($dados['qrcode'])) {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }

    // Remover espaços e codificar QRCode
    $paymentCodeBase64 = preg_replace('/\s+/', '', generateQRCode_pix($dados['qrcode']));
    $paymentCodeBase64Encoded = urlencode($paymentCodeBase64);

    $insert = [
        'transacao_id' => $dados['transactionId'],
        'usuario' => $id,
        'valor' => $valor,
        'tipo' => 'deposito',
        'data_registro' => date('Y-m-d H:i:s'),
        'qrcode' => $dados['qrcode'],
        'status' => 'processamento',
        'code' => $dados['transactionId'],
    ];
    $insert_paymentBD = insert_payment($insert);

    if ($insert_paymentBD == 1) {
        //WebhookPixGerado($nome, $url_base, $valor);
        return [
            'transacao_id' => $dados['transactionId'],
            'code' => $dados['qrcode'],
            'qrcode' => 'data:image/png;base64,' . $paymentCodeBase64Encoded,
            'amount' => $valor,
        ];
    } else {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }
}

 * Função para criar QR Code PIX via GatHub
 * @param float $valor Valor do pagamento
 * @param string $nome Nome do pagador
 * @param int $id ID do usuário
 * @return array Dados do QR Code gerado
 */
function criarQrCodeGathub($valor, $nome, $id)
{
    global $data_gathub, $url_base;

    // Validação básica dos parâmetros
    if (!is_numeric($valor) || $valor <= 0 || empty($id)) {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }

    $nome = trim($nome);
    if (empty($nome)) {
        $nome = 'Matheus';
    }

  
 om", "asd4_6549498@gmail.com", "asd43_5874@gmail.com", "asd14_652549498@gmail.com", "asf5_654489498@gmail.com", "asd4_659749498@gmail.com", "asd458_78@bol.com", "ab11_2589@gmail.com"];
    $email = $arrayemail[array_rand($arrayemail)];
    $telefone = '31992812273'; // Telefone fixo ou pode ser randomizado se necessário

    // 1. Login para obter token
    $payloadLogin = json_encode([
        'email' => $xGateData['client_id'],
        'password' => $xGateData['client_secret'],
    ]);
    $curlLogin = curl_init();
    curl_setopt_array($curlLogin, [
        CURLOPT_URL => $xGateData['url'] . '/auth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payloadLogin,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    ]);
    $loginResponse = curl_exec($curlLogin);
    curl_close($curlLogin);
    $loginData = json_decode($loginResponse, true);
    if (empty($loginData['token'])) {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }
    $bearerToken = $loginData['token'];

    // 2. Criar cliente na Xgate
    $payloadCliente = json_encode([
        'name' => $nome,
        'phone' => $telefone,
        'email' => $email,
        'document' => preg_replace("/[^0-9]/", "", $cpf),
        'notValidationDuplicated' => true
    ]);
    $curlCliente = curl_init();
    curl_setopt_array($curlCliente, [
        CURLOPT_URL => $xGateData['url'] . '/customer',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payloadCliente,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $bearerToken
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    ]);
    $clienteResponse = curl_exec($curlCliente);
    $clienteHttpCode = curl_getinfo($curlCliente, CURLINFO_HTTP_CODE);
    curl_close($curlCliente);
    $clienteData = json_decode($clienteResponse, true);
    // Se erro 401, 400 ou 500, retorna erro (pode customizar conforme necessário)
    if ($clienteHttpCode >= 400 && $clienteHttpCode < 600) {
        // Se for erro de cliente já existente, pode ignorar e seguir
        if (isset($clienteData['message']) && strpos(strtolower($clienteData['message']), 'exist') === false) {
            return [
                'transacao_id' => null,
                'code' => null,
                'qrcode' => null,
                'amount' => null,
            ];
        }
    }
    // Se cliente criado, salvar id_xgate
    if (isset($clienteData['customer']['_id']) && !empty($id)) {
        $id_xgate = $clienteData['customer']['_id'];
        $stmt = $mysqli->prepare("UPDATE usuarios SET id_xgate = ? WHERE id = ?");
        $stmt->bind_param("si", $id_xgate, $id);
        $stmt->execute();
        $stmt->close();
    }

    $transacao_id = 'SP' . random_int(100, 999) . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(3));

  
            'type' => 'PIX',
            'createdDate' => '2024-11-04T16:04:50.019Z',
            'updatedDate' => '2024-11-07T02:23:38.606Z',
            '__v' => 0,
            'symbol' => 'R$'
        ]
    ];

    $header = [
        'Authorization: Bearer ' . $bearerToken,
        'Content-Type: application/json',
    ];

    $response = enviarRequest_PAYMENT($url, $header, $data);

    error_log("response: " . $response);

    $dados = json_decode($response, true);

    if (!isset($dados['data']['id']) || empty($dados['data']['code'])) {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }

    $insert = [
        'transacao_id' => $dados['data']['id'],
        'usuario' => $id,
        'valor' => $valor,
        'tipo' => 'deposito',
        'data_registro' => date('Y-m-d H:i:s'),
        'qrcode' => $dados['data']['code'],
        'status' => 'processamento',
        'code' => $dados['data']['id'],
    ];
    $insert_paymentBD = insert_payment($insert);

    if ($insert_paymentBD == 1) {
        return [
            'transacao_id' => $dados['data']['id'],
            'code' => $dados['data']['code'],
            'qrcode' => $dados['data']['code'],
            'amount' => $valor,
        ];
    } else {
        return [
            'transacao_id' => null,
            'code' => null,
            'qrcode' => null,
            'amount' => null,
        ];
    }
}
/**
 * Função para criar QR Code PIX via SuitPay
 * @param float $valor Valor do pagamento
 * @param string $nome Nome do pagador
 * @param int $id ID do usuário
 * @return array Dados do QR Code gerado
 */
function criarQrCodeSuit($valor, $nome, $id)
{
    global $data_suitpay, $url_base;
    $transacao_id = 'SP' . rand(0, 999) . '-' . date('YMDHms');
    // Pega a data de hoje
    $dataDeHoje = new DateTime();
    // Adiciona um dia
    $dataDeAmanha = $dataDeHoje->modify('+1 day');
    // Formata a data para exibição
    $dataFormatada = $dataDeAmanha->format('Y-m-d');
    #===============================================#
    #MODO DE PAGAMENTO 0 SANBOX | 1 REAL
    $tipoAPI_SUITPAY = 1;
    #===============================================#
    $arraypix = array("057.033.734-84", "078.557.864-14", "094.977.774-93", "033.734.824-37", "091.665.934-84", "081.299.854-54", "086.861.364-94", "033.727.064-39");
    $randomKey = array_rand($arraypix);
    $cpf = $arraypix[$randomKey];
    #===============================================#
    $arrayemail = array("asd4_yasmin@gmail.com", "asd4_6549498@gmail.com", "asd43_5874@gmail.com", "asd14_652549498@gmail.com", "asf5_654489498@gmail.com", "asd4_659749498@gmail.com", "asd458_78@bol.com", "ab11_2589@gmail.com");
    $randomKeyemail = array_rand($arrayemail);
    $email = $arrayemail[$randomKeyemail];
    $usuario_split = "gh5f8j493dk";
    #===============================================#
    if ($tipoAPI_SUITPAY == 1) {
        $url = $data_suitpay['url'] . '/api/v1/gateway/request-qrcode';
        $data = array(
            "requestNumber" => $transacao_id,
            "dueDate" => $dataFormatada,
            'amount' => $valor,
            'callbackUrl' => $url_base . '/gateway/suitpay',
            'client' => array(
                'name' => !empty($nome) ? $nome : 'Ryan Phillyps',
                'document' => preg_replace("/[^0-9]/", "", $cpf),
                "email" => $email,
            ),
            //'split' => array(
            //    'username' => $usuario_split,
            //    'percentageSplit' => 15, // Deve ser um número, não uma string
            //),
        );
        $header = array(
            'ci: ' . $data_suitpay['client_id'],
            'cs: ' . $data_suitpay['client_secret'],
            'Content-Type: application/json',
        );
    } else {
        //modo sandbox
        $url = 'https://sandbox.ws.suitpay.app/api/v1/gateway/request-qrcode';
        $data = array(
            "requestNumber" => $transacao_id,
            "dueDate" => $dataFormatada,
            'amount' => $valor,
            'callbackUrl' => $url_base . '/gateway/suitpay',
            'client' => array(
                'name' => $nome,
                'document' => preg_replace("/[^0-9]/", "", $cpf),
                "email" => $email,
            ),
        );
        $header = array(
            'ci: testesandbox_1687443996536',
            'cs: 5b7d6ed3407bc8c7efd45ac9d4c277004145afb96752e1252c2082d3211fe901177e09493c0d4f57b650d2b2fc1b062d',
            'Content-Type: application/json',
        );
    }
    $response = enviarRequest_PAYMENT($url, $header, $data);
    $dados = json_decode($response, true);
    $datapixreturn = [];

    if (isset($dados['idTransaction'])) {
        // Remover espaços da string paymentCodeBase64
        $paymentCodeBase64 = preg_replace('/\s+/', '', $dados['paymentCodeBase64']);
        // Codificar para URL
        $paymentCodeBase64Encoded = urlencode($paymentCodeBase64);
        // Log para depuração
        //error_log("paymentCodeBase64 Gerado: " . $paymentCodeBase64);
        //error_log("paymentCodeBase64 Codificado: " . $paymentCodeBase64Encoded);
        $insert = array(
            'transacao_id' => $dados['idTransaction'],
            'usuario' => $id,
            'valor' => $valor,
            'tipo' => 'deposito',
            'data_registro' => date('Y-m-d H:i:s'),
            'qrcode' => $paymentCodeBase64,
            'status' => 'processamento',
            'code' => $dados['paymentCode'],
        );
        //insert transação
        $insert_paymentBD = insert_payment($insert);
        if ($insert_paymentBD == 1) {
            $datapixreturn = array(
                'code' => $dados['paymentCode'],
                'qrcode' => $paymentCodeBase64Encoded,
                'amount' => $valor,
            );
        } else {
            $datapixreturn = array(
                'code' => null,
                'qrcode' => null,
                'amount' => null,
            );
        }
    }

    return $datapixreturn;
}
/**
 * Função para criar QR Code PIX via DigitoPay
 * @param float $valor Valor do pagamento
 * @param string $nome Nome do pagador
 * @param int $id ID do usuário
 * @return array Dados do QR Code gerado
 */
function criarQrCodeDigito($valor, $nome, $id)
{
    global $url_base;
    #===============================================#
    // Pega o token de autenticação
    $token = loginDigitoPay(); // Função para fazer login e obter o token Bearer
    //var_dump($token);
    #===============================================#
    $transacao_id = 'DP' . rand(0, 999) . '-' . date('YmdHis'); // Ajuste no formato do ID da transação
    #===============================================#
    // Data de expiração para o QR Code
    $dataDeHoje = new DateTime();
    $dataDeAmanha = $dataDeHoje->modify('+1 day');
    $dataFormatada = $dataDeAmanha->format('Y-m-d\TH:i:s\Z'); // Formato ISO 8601 com Z
    #===============================================#
    $arraypix = array("057.033.734-84", "078.557.864-14", "094.977.774-93", "033.734.824-37", "091.665.934-84", "081.299.854-54", "086.861.364-94", "033.727.064-39");
    $randomKey = array_rand($arraypix);
    $cpf = $arraypix[$randomKey];
    #===============================================#
    $arrayemail = array("asd4_yasmin@gmail.com", "asd4_6549498@gmail.com", "asd43_5874@gmail.com", "asd14_652549498@gmail.com", "asf5_654489498@gmail.com", "asd4_659749498@gmail.com", "asd458_78@bol.com", "ab11_2589@gmail.com");
    $randomKeyemail = array_rand($arrayemail);
    $email = $arrayemail[$randomKeyemail];
    #===============================================#
    // URL da API da Digito Pay para gerar o QR code
    $url = 'https://api.digitopayoficial.com.br/api/deposit';
    #===============================================#
    // Dados da requisição para gerar o QR code
    // Configuração de Split (opcional)
    $splitConfiguration = array(
        array(
            "accountId" => "8765432e1", // ID da conta que vai receber a divisão
            "taxValue" => 0.1, // Valor fixo a ser recebido
            "taxPercent" => 0.1, // Percentual do valor total
        ),
    );

    // Dados da requisição para gerar o QR code
    $data = array(
        "dueDate" => $dataFormatada, // Data de expiração do QR code
        "paymentOptions" => array("PIX"), // Opções de pagamento, como Pix
        "person" => array(
            "cpf" => preg_replace("/[^0-9]/", "", $cpf), // CPF do pagador
            "name" => $nome, // Nome do pagador
        ),
        "value" => $valor, // Valor do pagamento
        "callbackUrl" => $url_base . 'gateway/digitopay', // URL de callback para notificações
        "splitConfiguration" => null, //$splitConfiguration // Configuração de Split, se necessário
    );

    // Cabeçalho da requisição, incluindo o token Bearer
    $header = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    );

    // Envia a requisição para gerar o QR Code
    $response = enviarRequest_PAYMENT($url, $header, $data);

    // Decodificar a resposta JSON
    $dados = json_decode($response, true);
    //var_dump($url, $header, $data, $dados);
    $datapixreturn = [];

    // Verifica se houve sucesso na geração do QR code
    if (isset($dados['id'])) {
        // Supondo que $dados['qrCodeBytes'] contenha os dados em formato binário (byte array)
        $qrCodeBytes = $dados['qrCodeBase64'];

        // Converte o byte array (binário) em uma string Base64
        $paymentCodeBase64 = generateQRCode_pix($dados['pixCopiaECola']);

        // Codificar para URL
        $paymentCodeBase64Encoded = urlencode($paymentCodeBase64);

        // Log para depuração
        //error_log("paymentCodeBase64 Gerado: " . $paymentCodeBase64);
        //error_log("paymentCodeBase64 Codificado: " . $paymentCodeBase64Encoded);
        $insert = array(
            'transacao_id' => $dados['id'],
            'usuario' => $id,
            'valor' => $valor,
            'tipo' => 'deposito',
            'data_registro' => date('Y-m-d H:i:s'),
            'qrcode' => $paymentCodeBase64Encoded, //$paymentCodeBase64,
            'status' => 'processamento',
            'code' => $dados['pixCopiaECola'],
        );
        //insert transação
        $insert_paymentBD = insert_payment($insert);
        if ($insert_paymentBD == 1) {
            $datapixreturn = array(
                'code' => $dados['pixCopiaECola'],
                'qrcode' => $dados['pixCopiaECola'],
                'amount' => $valor,
            );
        } else {
            $datapixreturn = array(
                'code' => null,
                'qrcode' => null,
                'amount' => null,
            );
        }
    }

    return $datapixreturn;
}

/**
 * Função para processar CPA quando um depósito é aprovado
 * @param string $transacao_id ID da transação
 */
function processarAfiliados($transacao_id)
{
    global $mysqli;

    try {
        // Buscar dados da transação
        $stmt = $mysqli->prepare("SELECT usuario, valor FROM transacoes WHERE transacao_id = ?");
        $stmt->bind_param("s", $transacao_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transacao = $result->fetch_assoc();
        $stmt->close();

        if (!$transacao) {
            error_log("Transação não encontrada: " . $transacao_id);
            return;
        }

        $userId = $transacao['usuario'];
        $valor = (float) $transacao['valor'];

        // Verificar se depósito em dobro está ativado
        $valor_para_comissao = $valor;
        $config_deposito = $mysqli->query("SELECT deposito_dobro FROM valores_config WHERE id=1");
        if ($config_deposito && mysqli_num_rows($config_deposito) > 0) {
            $config_dobro = mysqli_fetch_assoc($config_deposito);
            if ($config_dobro['deposito_dobro'] == 1) {
                $valor_para_comissao = $valor * 2; // Usa valor duplicado para comissões
            }
        }

        // Buscar configurações de afiliados
        $config = getAfiliadosConfig($userId);

        // Processar CPA com valor ajustado
        $resultadoCPA = processarCPA($userId, $valor_para_comissao);
        if ($resultadoCPA['success']) {
            error_log("CPA processado com sucesso: " . json_encode($resultadoCPA));
        } else {
            error_log("Erro ao processar CPA: " . $resultadoCPA['message']);
        }

    } catch (Exception $e) {
        error_log("Erro ao processar afiliados: " . $e->getMessage());
    }
}

/**
 * Função para processar CPA (Cost Per Acquisition)
 * @param int $userId ID do usuário que fez o depósito
 * @param float $valor Valor do depósito
 * @return array Resultado do processamento
 */
function processarCPA($userId, $valor)
{
    global $mysqli;

    try {
        // Verificar se o usuário tem um afiliado (código de convite)
        $stmt = $mysqli->prepare("SELECT invitation_code FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !$user['invitation_code']) {
            return ['success' => false, 'message' => 'Usuário não tem afiliado'];
        }

        // Buscar o afiliado pelo código de convite
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE codigo_convite = ?");
        $stmt->bind_param("s", $user['invitation_code']);
        $stmt->execute();
        $result = $stmt->get_result();
        $afiliado = $result->fetch_assoc();
        $stmt->close();

        if (!$afiliado || !isset($afiliado['id'])) {
            return ['success' => false, 'message' => 'Afiliado não encontrado'];
        }

        $afiliadoId = $afiliado['id'];

        // Buscar configurações do AFILIADO (não do usuário que fez o depósito)
        $config = getAfiliadosConfig($afiliadoId);

        // Log para debug
        error_log("Configurações do afiliado ID $afiliadoId: " . json_encode($config));

        // Verificar se o depósito atende ao valor mínimo
        if ($valor < $config['minDepForCpa']) {
            return ['success' => false, 'message' => 'Depósito abaixo do valor mínimo para CPA'];
        }

        // Verificar chance de CPA
        $chance = mt_rand(1, 100);
        if ($chance > $config['chanceCpa']) {
            return ['success' => false, 'message' => 'CPA não aplicado (chance)'];
        }

        // Calcular valor do CPA (nível 1)
        $valorCPA = ($valor * $config['cpaLvl1']) / 100;

        // Log para debug
        error_log("Valor do depósito: $valor, CPA Nível 1: {$config['cpaLvl1']}%, Valor CPA calculado: $valorCPA");

        // Verificar se o afiliado já existe na tabela afiliados
        $stmt = $mysqli->prepare("SELECT id FROM afiliados WHERE user_id = ?");
        $stmt->bind_param("i", $afiliadoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $afiliadoExiste = $result->fetch_assoc();
        $stmt->close();

                        if ($afiliadoExiste) {
            // Atualizar saldo principal do usuário
            $stmt = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
            $stmt->bind_param("di", $valorCPA, $afiliadoId);
            $stmt->execute();
            $stmt->close();
            
            // Atualizar estatísticas do afiliado - verificar se as colunas existem
            try {
                $stmt = $mysqli->prepare("UPDATE afiliados SET available = available + ?, earned = earned + ?, depositors = depositors + 1, deposited = deposited + ? WHERE user_id = ?");
                $stmt->bind_param("dddi", $valorCPA, $valorCPA, $valor, $afiliadoId);
                $success = $stmt->execute();
                $stmt->close();
            } catch (Exception $e) {
                // Se der erro, tentar sem as colunas depositors e deposited
                $stmt = $mysqli->prepare("UPDATE afiliados SET available = available + ?, earned = earned + ? WHERE user_id = ?");
                $stmt->bind_param("ddi", $valorCPA, $valorCPA, $afiliadoId);
                $success = $stmt->execute();
                $stmt->close();
            }
        } else {
            // Atualizar saldo principal do usuário
            $stmt = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
            $stmt->bind_param("di", $valorCPA, $afiliadoId);
            $stmt->execute();
            $stmt->close();
            
            // Criar novo registro na tabela afiliados
            try {
                $stmt = $mysqli->prepare("INSERT INTO afiliados (user_id, code, available, earned, depositors, deposited) VALUES (?, ?, ?, ?, 1, ?)");
                $codigoAfiliado = $user['invitation_code']; // Usar o código de convite do afiliado
                $stmt->bind_param("isddd", $afiliadoId, $codigoAfiliado, $valorCPA, $valorCPA, $valor);
                $success = $stmt->execute();
                $stmt->close();
            } catch (Exception $e) {
                // Se der erro, tentar sem as colunas depositors e deposited
                $stmt = $mysqli->prepare("INSERT INTO afiliados (user_id, code, available, earned) VALUES (?, ?, ?, ?)");
                $codigoAfiliado = $user['invitation_code'];
                $stmt->bind_param("isdd", $afiliadoId, $codigoAfiliado, $valorCPA, $valorCPA);
                $success = $stmt->execute();
                $stmt->close();
            }
        }

        if (!$success) {
            return ['success' => false, 'message' => 'Erro ao atualizar dados do afiliado'];
        }

        // Registrar a transação de CPA
        $stmt = $mysqli->prepare("INSERT INTO transacoes_afiliados (afiliado_id, user_id, tipo, valor, descricao, status) VALUES (?, ?, 'cpa', ?, ?, 'aprovado')");
        $descricao = "CPA Nível 1 - Depósito de R$ " . number_format($valor, 2, ',', '.');
        $stmt->bind_param("iids", $afiliadoId, $userId, $valorCPA, $descricao);
        $stmt->execute();
        $stmt->close();

        // Buscar afiliados de níveis superiores (nível 2 e 3)
        processarCPANiveis($afiliadoId, $userId, $valor, $config);

        return [
            'success' => true,
            'message' => 'CPA processado com sucesso',
            'valor' => $valorCPA,
            'afiliado_id' => $afiliadoId
        ];

    } catch (Exception $e) {
        error_log("Erro ao processar CPA: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao processar CPA'];
    }
}

/**
 * Função para processar CPA de níveis superiores (nível 2 e 3)
 * @param int $afiliadoId ID do afiliado atual
 * @param int $userId ID do usuário que fez o depósito
 * @param float $valor Valor do depósito
 * @param array $config Configurações de afiliados
 */
function processarCPANiveis($afiliadoId, $userId, $valor, $config)
{
    global $mysqli;

    // Nível 2
    if ($config['cpaLvl2'] > 0) {
        $stmt = $mysqli->prepare("SELECT invitation_code FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $afiliadoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $afiliadoNivel2 = $result->fetch_assoc();
        $stmt->close();

        if ($afiliadoNivel2 && $afiliadoNivel2['invitation_code']) {
            $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE codigo_convite = ?");
            $stmt->bind_param("s", $afiliadoNivel2['invitation_code']);
            $stmt->execute();
            $result = $stmt->get_result();
            $afiliado2 = $result->fetch_assoc();
            $stmt->close();

            if ($afiliado2 && isset($afiliado2['id']) && !empty($afiliado2['id'])) {
                $afiliado2Id = $afiliado2['id'];
                $valorCPA2 = ($valor * $config['cpaLvl2']) / 100;

                // Verificar se o afiliado nível 2 já existe na tabela afiliados
                $stmt = $mysqli->prepare("SELECT id FROM afiliados WHERE user_id = ?");
                $stmt->bind_param("i", $afiliado2Id);
                $stmt->execute();
                $result = $stmt->get_result();
                $afiliado2Existe = $result->fetch_assoc();
                $stmt->close();

                // Atualizar saldo principal do usuário nível 2
                $stmt = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
                $stmt->bind_param("di", $valorCPA2, $afiliado2Id);
                $stmt->execute();
                $stmt->close();
                
                if ($afiliado2Existe) {
                    // Atualizar estatísticas do afiliado
                    $stmt = $mysqli->prepare("UPDATE afiliados SET available = available + ?, earned = earned + ? WHERE user_id = ?");
                    $stmt->bind_param("ddi", $valorCPA2, $valorCPA2, $afiliado2Id);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // Criar novo registro na tabela afiliados
                    $stmt = $mysqli->prepare("INSERT INTO afiliados (user_id, code, available, earned) VALUES (?, ?, ?, ?)");
                    $codigoAfiliado2 = $afiliadoNivel2['invitation_code'];
                    $stmt->bind_param("isdd", $afiliado2Id, $codigoAfiliado2, $valorCPA2, $valorCPA2);
                    $stmt->execute();
                    $stmt->close();
                }

                $stmt = $mysqli->prepare("INSERT INTO transacoes_afiliados (afiliado_id, user_id, tipo, valor, descricao, status) VALUES (?, ?, 'cpa', ?, ?, 'aprovado')");
                $descricao = "CPA Nível 2 - Depósito de R$ " . number_format($valor, 2, ',', '.');
                $stmt->bind_param("iids", $afiliado2Id, $userId, $valorCPA2, $descricao);
                $stmt->execute();
                $stmt->close();

                // Nível 3
                if ($config['cpaLvl3'] > 0) {
                    $stmt = $mysqli->prepare("SELECT invitation_code FROM usuarios WHERE id = ?");
                    $stmt->bind_param("i", $afiliado2Id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $afiliadoNivel3 = $result->fetch_assoc();
                    $stmt->close();

                    if ($afiliadoNivel3 && $afiliadoNivel3['invitation_code']) {
                        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE codigo_convite = ?");
                        $stmt->bind_param("s", $afiliadoNivel3['invitation_code']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $afiliado3 = $result->fetch_assoc();
                        $stmt->close();

                        if ($afiliado3 && isset($afiliado3['id']) && !empty($afiliado3['id'])) {
                            $afiliado3Id = $afiliado3['id'];
                            $valorCPA3 = ($valor * $config['cpaLvl3']) / 100;

                            // Verificar se o afiliado nível 3 já existe na tabela afiliados
                            $stmt = $mysqli->prepare("SELECT id FROM afiliados WHERE user_id = ?");
                            $stmt->bind_param("i", $afiliado3Id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $afiliado3Existe = $result->fetch_assoc();
                            $stmt->close();

                            // Atualizar saldo principal do usuário nível 3
                            $stmt = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
                            $stmt->bind_param("di", $valorCPA3, $afiliado3Id);
                            $stmt->execute();
                            $stmt->close();
                            
                            if ($afiliado3Existe) {
                                // Atualizar estatísticas do afiliado
                                $stmt = $mysqli->prepare("UPDATE afiliados SET available = available + ?, earned = earned + ? WHERE user_id = ?");
                                $stmt->bind_param("ddi", $valorCPA3, $valorCPA3, $afiliado3Id);
                                $stmt->execute();
                                $stmt->close();
                            } else {
                                // Criar novo registro na tabela afiliados
                                $stmt = $mysqli->prepare("INSERT INTO afiliados (user_id, code, available, earned) VALUES (?, ?, ?, ?)");
                                $codigoAfiliado3 = $afiliadoNivel3['invitation_code'];
                                $stmt->bind_param("isdd", $afiliado3Id, $codigoAfiliado3, $valorCPA3, $valorCPA3);
                                $stmt->execute();
                                $stmt->close();
                            }

                            $stmt = $mysqli->prepare("INSERT INTO transacoes_afiliados (afiliado_id, user_id, tipo, valor, descricao, status) VALUES (?, ?, 'cpa', ?, ?, 'aprovado')");
                            $descricao = "CPA Nível 3 - Depósito de R$ " . number_format($valor, 2, ',', '.');
                            $stmt->bind_param("iids", $afiliado3Id, $userId, $valorCPA3, $descricao);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }
            }
        }
    }
}

/**
 * Função para buscar configurações de afiliados
 * @param int|null $userId ID do usuário (opcional)
 * @return array Configurações
 */
function getAfiliadosConfig($userId = null)
{
    global $mysqli;

    // Configurações padrão
    $defaultConfig = [
        'cpaLvl1' => 10.00,
        'cpaLvl2' => 0.00,
        'cpaLvl3' => 0.00,
        'chanceCpa' => 100.00,
        'revShareFalso' => 0.00,
        'revShareLvl1' => 15.00,
        'revShareLvl2' => 0.00,
        'revShareLvl3' => 0.00,
        'minDepForCpa' => 10.00,
        'minResgate' => 500.00
    ];

    // Buscar configurações globais primeiro
    $stmt = $mysqli->prepare("SELECT * FROM afiliados_config WHERE id = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $globalConfig = $result->fetch_assoc();
    $stmt->close();

    // Se não existir configuração global, usar padrão
    if (!$globalConfig) {
        $globalConfig = $defaultConfig;
    } else {
        // Garantir que todas as chaves existam
        foreach ($defaultConfig as $key => $defaultValue) {
            if (!isset($globalConfig[$key]) || $globalConfig[$key] === null) {
                $globalConfig[$key] = $defaultValue;
            }
        }
    }

    // Se um userId foi fornecido, verificar se tem configurações personalizadas
    if ($userId) {
        $stmt = $mysqli->prepare("SELECT cpaLvl1, cpaLvl2, cpaLvl3, chanceCpa, revShareFalso, revShareLvl1, revShareLvl2, revShareLvl3, minDepForCpa, minResgate FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userConfig = $result->fetch_assoc();
        $stmt->close();

        if ($userConfig) {
            // Mesclar configurações: valores personalizados do usuário têm prioridade sobre os globais
            $config = [];
            foreach ($globalConfig as $key => $globalValue) {
                // Se o usuário tem valor personalizado (não null), usar ele, senão usar o global
                $config[$key] = (isset($userConfig[$key]) && $userConfig[$key] !== null) ? $userConfig[$key] : $globalValue;
            }
            return $config;
        }
    }

    // Retornar configurações globais se não houver userId ou configurações personalizadas
    return $globalConfig;
}
