# PHP (usando Laravel para facilitar a construção da interface gráfica e a comunicação com a API):
> routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BtgpactualController;

Route::get('/', [BtgpactualController::class, 'index']);
Route::post('/saque', [BtgpactualController::class, 'saque'])->name('saque');
Route::get('/extrato', [BtgpactualController::class, 'extrato'])->name('extrato');
```

app/Http/Controllers/BtgpactualController.php:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BtgpactualController extends Controller
{
    private $extrato = [];

    public function index()
    {
        return view('btgpactual');
    }

    public function saque(Request $request)
    {
        $val = $request->input('amount');
        $payload = [
            'Amount' => $val,
            'Counterpart' => '2C3MSBV5RL8I9EgKaXfhu7hvbjYpcdl5BLmDqqbb',
            'Desc' => 'Pagamento de honorários'
        ];

        $response = Http::withHeaders(['x-api-key' => 'ZtPtIH0U8JUUYTul1VTCBt768u0dez2kUKOOICVh'])
            ->post('https://www.btgpactual.com/btgcode/api/money-movement', $payload);

        try {
            $val2 = number_format($val, 2, '.', '');
            $this->extrato[] = $val2;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Algo deu errado'], 500);
        }

        return response()->json(['message' => $response->body()]);
    }

    public function extrato()
    {
        $string = "Extrato:";
        foreach ($this->extrato as $valor) {
            $string .= "\nsaque: R$" . $valor;
        }

        return response()->json(['message' => $string]);
    }
}
```
resources/views/btgpactual.blade.php:

```php
<!DOCTYPE html>
<html>
<head>
    <title>BTG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>BTG</h1>
    <label for="amount">Entre o valor a ser sacado:</label>
    <input type="text" id="amount" name="amount">
    <button id="saque">Sacar</button>
    <button id="extrato">Extrato</button>
    <button id="sair">Sair</button>

    <textarea id="output" rows="6" cols="20"></textarea>

    <script>
        $(document).ready(function() {
            $('#saque').click(function() {
                var amount = $('#amount').val();
                $.ajax({
                    url: "{{ route('saque') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        amount: amount
                    },
                    success: function(response) {
                        $('#output').val(response.message);
                    },
                    error: function(xhr) {
                        $('#output').val("Algo deu errado");
                    }
                });
            });

            $('#extrato').click(function() {
                $.ajax({
                    url: "{{ route('extrato') }}",
                    type: "GET",
                    success: function(response) {
                        $('#output').val(response.message);
                    },
                    error: function(xhr) {
                        $('#output').val("Algo deu errado");
                    }
                });
            });

            $('#sair').click(function() {
                window.close();
            });
        });
    </script>
</body>
</html>
```

## Arquivo proj2.py convertido para PHP (usando CLI):
> proj2.php:

```php
<?php

function saque($valor)
{
    $payload = [
        'Amount' => $valor,
        'Counterpart' => '2C3MSBV5RL8I9EgKaXfhu7hvbjYpcdl5BLmDqqbb',
        'Desc' => 'Pagamento de honorários'
    ];

    $ch = curl_init('https://www.btgpactual.com/btgcode/api/money-movement');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-api-key: ZtPtIH0U8JUUYTul1VTCBt768u0dez2kUKOOICVh', 'Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$extrato = [];
while (true) {
    $valor = readline("Digite o valor a ser sacado ou digite sair para encerrar as operações e imprimir o extrato\n");

    if ($valor === 'sair') {
        echo "Muito obrigado, volte sempre\n";
        echo "Extrato: " . implode(', ', $extrato) . "\n";
        break;
    }

    if (is_numeric($valor)) {
        $extrato[] = number_format($valor, 2, '.', '');
        $response = saque($valor);
        echo $response . "\n";
    } else {
        echo "Erro na operação\n";
    }
}
```
Esses exemplos mostram como converter a lógica de cada arquivo Python para PHP, utilizando Laravel para a interface gráfica e comunicação com a API no caso do primeiro arquivo, e PHP puro com CLI para o segundo arquivo.


- Renato Lucena
