<?php 
require_once "head.php";
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Analitcas</h1>
            </div>
        </div>
    </div>
    <select id="numero_id" class="form-control col-md" style="width: 100%;">
        <option value="0">Seleccionar numero</option>
    </select>
    <div class="d-flex justify-content-between">
      <select id="granualidad" class="btn btn-default dropdown-toggle dropdown-hover dropdown-icon">
        <option value="1">DIA</option>
        <option value="2">MES</option>
      </select>
      <input type="date" class="form-control" id="fecha_1">
        <div class="col-sm">
          <h5>Entre</h5>
        </div>
      <input type="date" class="form-control" id="fecha_2">
      <button id="buscar_datos" class="btn btn-block btn-default">
        Buscar
        <i class="fas fa-search fa-fw"></i>
      </button>
    </div>
</section>
  <div class="col-md">
  <div class="card">
    <div class="card-header border-0">
      <div class="d-flex justify-content-between">
        <h2 class="card-title"><b>Mensajes</b></h2>
        <a href="javascript:void(0);">View Report</a>
      </div>
    </div>
    <div class="card-body">
      <div class="d-flex">
        <p class="d-flex flex-column">
        </p>
        <p class="ml-auto d-flex flex-column text-right">
          <span class="text-success">
            <i class="fas fa-arrow-up"></i> 99.1%
          </span>
          <span class="text-muted">Since last month</span>
        </p>
      </div>
      <!-- /.d-flex -->

      <div class="position-relative mb-4">
        <canvas id="sales-chart" height="200"></canvas>
      </div>

      <div class="d-flex flex-row justify-content-end">
        <span class="mr-2">
          <i class="fas fa-square text-primary"></i> Enviados
        </span>
        <span>
          <i class="fas fa-square text-gray"></i> Entregados
        </span>
      </div>
    </div>
  </div>
  <!-- /.card -->

  <div class="card">
    <div class="card-header border-0">
      <h3 class="card-title">Conversaciones</h3>
      <div class="card-tools">
        <a class="btn btn-tool btn-sm">
          <i class="fas fa-download"></i>
        </a>
        <a class="btn btn-tool btn-sm">
          <i class="fas fa-bars"></i>
        </a>
      </div>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-striped table-valign-middle">
        <thead>
        <tr>
          <th>Fecha</th>
          <th>Conversaciones</th>
          <th>Pais</th>
          <th>Tipo Conversacion</th>
          <th>Categoria Conversacion</th>
          <th>Costo</th>
        </tr>
        </thead>
        <tbody id="contenido_conversaciones">

        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="./js/controlador_analiticas.js"></script>
<script src="./js/grafica_analiticas.js"></script>

<?php
require_once "footer.html";
?>