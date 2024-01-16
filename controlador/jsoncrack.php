<?php
require_once('head.php');
?>

<div class="content-header">
    <select id="flujos_id" class="form-control" style="width: 100%;" >
        <option value="0">Selecciona un flujo</option>
    </select>
</div>
<div id="iframe">
    <iframe id="jsoncrackEmbed" src="https://jsoncrack.com/widget?json=https://institutotesla.ar/BOT/Admin/back/arbol/construir_json.php?id=72" frameborder="0" style="height: 75vh; width: 100%;" ></iframe>
</div>
    
<script src="./js/controlador_jscrack.js"></script>
<?php
include_once('footer.html');
?>