<?php

//Criar array com os dados de entrada
$lista = array(

    'bff58d7b-8b4a-456a-b852-5a3e000c0e63;12;996958849;NEXTEL;21:24:03;sapien sapien non mi integer ac neque duis bibendum',
    'b7e2af69-ce52-4812-adf1-395c8875ad30;69;949360612;CLARO;19:05:21;justo lacinia eget tincidunt eget',
    'e7b87f43-9aa8-414b-9cec-f28e653ac25e;34;990171682;VIVO;18:35:20;dui luctus rutrum nulla tellus in sagittis dui',
    'c04096fe-2878-4485-886b-4a68a259bac5;43;940513739;NEXTEL;14:54:16;nibh fusce lacus purus aliquet at feugiat',
    'd81b2696-8b62-4b8b-af82-586ce0875ebc;21;983522711;TIM;16:42:48;sit amet eros suspendisse accumsan tortor quis turpis sed ante',
    'd81b2696-8b62-4b8b-af82-586ce0875ebc;21;983522711;TIM;16:25:48;sit amet eros suspendisse accumsan tortor quis turpis sed ante'

);

//criar array com ddd são paulo
$dddSP = array(
    11,
    12,
    13,
    14,
    15,
    16,
    17,
    18,
    19
);

//Variavel horario não permitido
$horarioBloqueio = '19:59:59';

//Variavel para guardar o tamanho do array
$final = count($lista);

//Loop para trabalhar os dados
for ($i = 0; $i < $final; $i++) {

    //Verificar se tem mais que 1 numero destino
    $totalIguais = 0;
    $horarioDuplicado = [];

    //Usar função explode para transformar string em array
    $explode = explode(';', $lista[$i]);

    //Armazenar nas variaveis conforme posição do array
    $idMensagem = $explode[0];
    $ddd = $explode[1];
    $telefone = $explode[2];
    $operadora = $explode[3];
    $horario = $explode[4];
    $mensagem = $explode[5];
    $telefoneCompleto = $ddd . $telefone;

    //Verificar se telefone é valido
    if (preg_match("/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", $telefoneCompleto)) {

        //Verificar se celular tem 9 digitos
        if (strlen($telefone) === 9) {

            //numero celular deve começar com 9
            if (substr($telefone, 0, 1) === '9') {

                //o segundo dígito deve ser > 6
                if (substr($telefone, 1, 1) > '6') {

                    //ver se telefone esta na Black List
                    $url = "https://front-test-pg.herokuapp.com/blacklist/" . $telefoneCompleto;
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $blackList = json_decode(curl_exec($ch), true);

                    //mensagens que estão na blacklist deverão ser bloqueadas
                    //o retorno para os que não estão na black, é a chave 'message', caso esteja na black, o retorno será 'phone'
                    if (array_key_exists('message', $blackList)) {

                        //mensagens para o estado de São Paulo deverão ser bloqueadas
                        if (!in_array($ddd, $dddSP)) {

                            //mensagens com agendamento após as 19:59:59 deverão ser bloqueadas
                            if (strtotime($horario) <= strtotime($horarioBloqueio)) {

                                //as mensagens com mais de 140 caracteres deverão ser bloqueadas
                                if (strlen($mensagem) <= 140) {

                                    //caso possua mais de uma mensagem para o mesmo destino, apenas a mensagem apta com o menor horário deve ser considerada
                                    //verificar se o mesmo numero for maior que 1
                                    for ($z = 0; $z < $final; $z++) {

                                        $explode1 = explode(';', $lista[$z]);
                                        $ddd1 = $explode1[1];
                                        $telefone1 = $explode1[2];
                                        $horario1 = $explode1[4];
                                        $telefoneCompleto1 = $ddd1 . $telefone1;

                                        if ($telefoneCompleto === $telefoneCompleto1) {
                                            $totalIguais++;
                                            $horarioDuplicado[] = $horario1;
                                        }

                                    }

                                    //se existir mesmo destino maior que 1 apenas a mensagem apta com o menor horário deve ser considerada
                                    if ($totalIguais > 1) {

                                        //verificar menor horario
                                        print_r($horarioDuplicado);


                                    } else {

                                        if (trim($operadora) == 'VIVO' || trim($operadora) == 'TIM') {

                                            echo $idMensagem . ';' . 1 . '<br>';

                                        } elseif (trim($operadora) == 'CLARO' || trim($operadora) == 'OI') {

                                            echo $idMensagem . ';' . 2 . '<br>';

                                        } elseif (trim($operadora) == 'NEXTEL') {

                                            echo $idMensagem . ';' . 3 . '<br>';

                                        }

                                    }

                                }

                            }

                        }

                    } else {

                        echo 'Telefone está na black List: ' . $telefoneCompleto . '<br>';

                    }

                }

            }


        }

    }

}



