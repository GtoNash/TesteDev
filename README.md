# TesteDev

<h3>Instruções de como Testar/Executar</h3>

Subir servidor xampp ou Wamp;<br>
Clonar arquivos para dentro da pasta htdocs (xampp), www (Wamp) https://github.com/GtoNash/TesteDev.git;<br>
Abrir o navegador;<br>
Digitar http://localhost/TesteDev/index.php<br>
Será mostrado IDMENSAGEM;IDBROKER das mensagens aptas para envio

<h3>Descrição do que utilizou para desenvolver</h3>
Utilizado PHP 7.4.8;<br>
Servidor xampp com apache;<br>
Sistema Operacional Windows 10;<br>
IDE PhpStorm 2020.2.1

# Observação

No código foi utilizado a função array_key_exists para tratar o retorno da API, se o retorno no json estiver a chave 'message', o numero não está na blacklist, se vier a chave 'phone' o numero está na blacklist, sendo assim seguirá o fluxo para o próximo item do for;

Porém pode-se usar a função get_headers() para obter um array contendo o retorno, sendo 200 ou 404, conforme codigo abaixo:<br>

<pre>
$url = "https://front-test-pg.herokuapp.com/blacklist/21914683666";
$array = get_headers($url);
$final = count($array);
for ($i = 0; $i < $final; $i++) {

 if ($i === 0){

     $retorno = explode(' ', $array[$i]);
     echo $retorno[1] . '<br>';
     break;
 }

}
exit;

Ou

$url = "https://front-test-pg.herokuapp.com/blacklist/21914683666";
$array = get_headers($url);
$retorno = explode(' ', $array[0])[1];
echo $retorno;
exit;

</pre>


