<!doctype html>
<html lang="en">

<head>
    <title>Huawei password utility</title>
    <link href="css/style.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/constants.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/he/1.2.0/he.min.js" integrity="sha512-PEsccDx9jqX6Dh4wZDCnWMaIO3gAaU0j46W//sSqQhUQxky6/eHZyeB3NrXD2xsyugAKd4KPiDANkcuoEa2JuA==" crossorigin="anonymous"></script>

</head>

<body>
    
    <main>
    

        <div id="passgen" style="display: none;">
            <h1 class="content">Password generator</h1>
            <div class="divider"></div>
            <div class="content">
                <div class="input-description">
                    Password to hash:
                    <input id="PasswordField" class="user-input" placeholder="password" type="text" oninput="OnPasswordChange()" value>
                </div>
                <div class="input-description">
                    EncryptMode:
                    <select id="EncryptionMode" name="hashing" class="user-input" onchange="EncryptionModeChange()">
                        <option value="1">1. MD5(password)</option>
                        <option value="2">2. Sha256(MD5(password))</option>
                        <option value="3">3. PBKDF2(password, 256 key size, 5000 iterations, Sha256, salt)</option>
                    </select>
                </div>
                <div class="input-description">
                    Salt:
                    <div style="display: flex">
                        <div style="flex-grow: 1;">
                            <input id="SaltField" class="user-input" placeholder="salt" type="text" oninput="OnPasswordChange()" value>
                        </div>
                        <div class="user-input-checkbox" style="width: 6.8em;">
                            Randomize:
                            <input type="checkbox" id="SaltRandomizeField" onclick="OnRandomizeFieldChange()" checked>
                        </div>
                    </div>
                </div>
                <div class="input-description">
                    Result:
                    <div style="display: flex">
                        <div style="flex-grow: 1;">
                            <input id="ResultField" class="user-input no-input" placeholder="result" type="text" value disabled>
                        </div>
                        <div class="user-input-checkbox" style="width: 5.1em;">
                            Encrypt:
                            <input type="checkbox" id="EncryptResultField" onclick="OnEncryptResultClick()" checked>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário para enviar o arquivo XML -->
        <div class="container">
            <h1>Procurar e Ler Arquivo XML</h1>

            <form method="POST" action="" enctype="multipart/form-data">
                <label for="xmlFile">Escolha um arquivo XML:</label>
                <input type="file" name="xmlFile" id="xmlFile" accept=".xml" required>
                <button type="submit">Enviar e Processar</button>
            </form>

			<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlFile'])) {
    if ($_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
        // Caminho temporário do arquivo
        $tmpFile = $_FILES['xmlFile']['tmp_name'];

        // Ler o conteúdo do arquivo XML
        $xmlContent = file_get_contents($tmpFile);

        // Usar expressão regular para encontrar o valor do PreSharedKey
        if (preg_match_all('/PreSharedKey="(.*?)"/', $xmlContent, $matches)) {
            // Exibir cada PreSharedKey encontrado
           // echo "<h3>Ocorrências encontradas:</h3><ul>";
            $count=0;
            foreach ($matches[1] as $key) { // $matches[1] contém os valores capturados
                //echo "<li>" . htmlspecialchars($key) . "</li>";
                if($count == 0){
                    $preSharedKey = str_replace('"', '', $key); // Pega o valor sem as aspas
                }
                if($count == 1){
                    $preSharedKey2 = str_replace('"', '', $key); // Pega o valor sem as aspas
                }

                $count++;
            }
            // Aqui, você pode usar htmlspecialchars apenas quando for exibir o valor no HTML
            echo "<div class='result'>";
           // echo htmlspecialchars($preSharedKey) . "<br>"; // Exibe no HTML com caracteres escapados
            //echo htmlspecialchars($preSharedKey2); // Exibe no HTML com caracteres escapados
            echo "</div>";
        } else {
            echo "<div class='error'><strong>Nenhum PreSharedKey encontrado no arquivo selecionado.</strong></div>";
        }
    } else {
        echo "<div class='error'><strong>Erro ao enviar o arquivo!</strong></div>";
    }
}
?>

<script>
    var preSharedKey = "<?php echo $preSharedKey; ?>"; // Passa o valor diretamente sem htmlspecialchars
    var preSharedKey2 = "<?php echo $preSharedKey2; ?>"; // Passa o valor diretamente sem htmlspecialchars


    // Preencher o campo CipherInputField com o valor do PreSharedKey
   window.onload = function() {
    if (preSharedKey) {
        var cipherInputField = document.getElementById('CipherInputField');
        OnCipherInputChange();
    }
    if (preSharedKey2) {    
        var cipherInputField2 = document.getElementById('CipherInputField2');
        OnCipherInputChange2();
    }
};

</script>



        </div>

        <div id="cipher">
            <div class="content">
               
                <div class="input-description">
                Input:
                <input id="CipherInputField" class="user-input" placeholder="input" type="text" oninput="OnCipherInputChange()" value="<?php echo htmlspecialchars($preSharedKey); ?>">
            </div>
            <div class="input-description">
                Input:
                <input id="CipherInputField2" class="user-input" placeholder="input" type="text" oninput="OnCipherInputChange2()" value="<?php echo htmlspecialchars($preSharedKey2); ?>">
            </div>

                <div class="input-description no-display">
                    Function:
                    <select id="CipherFunctionField" name="cipherfunction" class="user-input no-display" onchange="OnCipherInputChange()">
                        <option value="1">Decipher</option>
                        <option value="2">Cipher</option>
                    </select>
                </div>
                <div class="input-description">
                    2.4Ghz:
                    <input id="CipherResultField" class="user-input no-input" placeholder="result" type="text" value disabled>
                </div>
                <div class="input-description">
                    5Ghz:
                    <input id="CipherResultField2" class="user-input no-input" placeholder="result" type="text" value disabled>
                </div>
                
            </div>
        </div>
    </main>

    <script src="js/index.js"></script>

</body>
</html>