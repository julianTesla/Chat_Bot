<?php
require_once "./head.php";
?>

<!-- TITULO DE LA CABEZERA -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>AGREGAR Y MODIFICAR NUMEROS</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<div class="card-body p-4">
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th style="width: 1%">
                    #
                </th>
                <th style="width: 20%">
                    Numero de Telefono
                </th>
                <th>
                    ID (WABS)
                </th>
                <th style="width: 20%">
                    Area Perteneciente
                </th>
                <th style="width: 20%">
                    Flujo Asignado
                </th>
                <th style="width: 10%" class="text-center">
                    Estado
                </th>
                <th style="width: 15%">
                    Accion
                </th>
            </tr>
        </thead>
        <tbody id="tabla_numeros">
            
        </tbody>
    </table>
    <br>

    <button id="btn_nevo_numero" class="btn bg-gradient-primary"  data-toggle="modal" data-target="#modal-lg">Nuevo numero</button>
</div>

<!--INICIO MODALES -->
<div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Numero</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <input id="id_numero_modal" type="hidden" value="0">
            <small><label for="">Ingresa el N° telefono…</label></small>
            <input id="numero" class="modal-title form-control form-control-xl" type="int" placeholder="Numero" value="549" maxlength="13">
            <small><label for="">Ingresa el ID del telefono…</label></small>
            <input id="numero_id" class="modal-title form-control form-control-xl" type="int" placeholder="ID telefono">
            <small><label for="">Ingresa el token de acceso…</label></small>
            <input id="token" class="modal-title form-control form-control-xl" type="text" placeholder="Token acces">
            <div class="form-group">
                <small><label>Selecciona el area…</label></small>
                <select id="select_area" class="custom-select form-control-border">

                </select>
            </div>
            <div class="form-group">
                <small><label>Selecciona el flujo…</label></small>
                <select id="select_flujo" class="custom-select form-control-border">
                    
                </select>
            </div>
            <div class="form-group">
                <small><label>Estado…</label></small>
                <select id="select_estado" class="custom-select form-control-border">
                    <option value="0">Activo</option>
                    <option value="1">suspendido</option>
                </select>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button id="guardar_numero" type="button" class="btn btn-primary">Guardar cambios</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- FIN MODALES -->

<script src="./js/controlador_numeros.js"></script>

<?php
require_once "./footer.html";