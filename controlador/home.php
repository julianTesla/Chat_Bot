<?php
require_once('head.php');
?>

<!-- SECCION DE PAGINA PARA CREACION Y EDICION DE LOS COMPONENTES DE LOS MENSAJES DE RESPUESTA -->
<!-- TITULO DE LA CABEZERA -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>CREAR Y MODIFICAR FLUJOS</h1>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<div id="flujos_container">

</div>

<div class="col-md">
  <button id="nuevo_flujo" type="button" data-toggle="modal" class="btn btn-info" data-target="#modal-lg">Nuevo Flujo</button>
</div>

<!-- INICIO MODALES - POPUP -->
<form action="" id="datos_mesaje">
  <div class="modal fade" id="modal-xl" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div id="contenido_mensaje" class="modal-content" data-msjid="0">
        <div class="modal-header">
          <input id="nombre_mensage" class="modal-title form-control form-control-lg" type="text" placeholder="Nombre de la opción" maxlength="20">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            Construye el mensaje…
            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
              <label id="marcado_btn_texto" class="btn btn-primary">
                <input type="radio" name="tipo" id="texto" value="1" > Texto
              </label>
              <label id="marcado_btn_boton" class="btn btn-primary ">
                <input type="radio" name="tipo" id="botones" value="2"> Botones
              </label>
              <label id="marcado_btn_lista" class="btn btn-primary">
                <input type="radio" name="tipo" id="lista"value="3"> Lista
              </label>
            </div>
          </div>
          <input id="flujo_id_modal" type="hidden">
          <br>
          <input id="encabezado" class="modal-title form-control form-control-lg" type="text" placeholder="Encabezado del mensaje  / o ingrese URL para imagen o video" maxlength="60">
          <br>
          <textarea id="cuerpo" class="form-control" rows="3" placeholder="Cuerpo del mensaje" style="height: 130px;" maxlength="1024"></textarea>
          <br>
          <input id="pie" class="form-control" type="text" placeholder="Pie del mensaje" maxlength="60">
          <br>
          <div id="ocultar">
            <div id="boton_opciones">
              <select class="js-example-basic-single js-states select2-search__field options_list" id="options" style="width: 100%;" multiple="multiple" placeholder="Agrega opciones">
                
              </select>
            </div>
            <div id="lista_opciones">
              <select class="js-example-basic-single js-states select2-search__field options_list" id="option_list" style="width: 100%;" multiple="multiple" placeholder="Agrega opciones">
                
              </select>
            </div>
          </div>
        </div>          
        <div class="modal-footer justify-content-between">
          <button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" id="guardar_mensaje" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</form>
<!-- FIN MODALES - POPUP -->


<!-- INICIO MODAL MEDIUM CREAR FLUJO -->
<div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Crea un nuevo flujo de mensajes</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <input id="modal_id_flujo" type="hidden" value="0">
        <small>Ingresa el nombre del flujo…</small>
        <input id="nombre_flujo" class="modal-title form-control form-control-xl" type="text" placeholder="" >
        <small>Ingresa la palabra clabe para volver al menu…</small>
        <input id="flujo_palabra_clave" class="modal-title form-control" type="text" placeholder="" maxlength="10">
      </div>
      <div class="modal-footer justify-content-between">
        <button id="cerrar_modal_flujo" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button  id="guardar_flujo" type="button" class="btn btn-primary">Guardar Flujo</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- FIN MODAL MEDIUM -->

<script src="./js/controller.js"></script>
<script src="./js/manejo_datos.js"></script>
<?php
include_once('footer.html');