jQuery(document).ready(function(){
    insertar_numeros();
    window.labels_fecha = [];
    window.msg_enviados = [];
    window.msg_entregados = [];
    window.nombres_meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio',
        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
      ];

      $("#buscar_datos").on('click', function () {
        insertar_analiticas();
        insertar_conversaciones();
      })
});


async function extraer_conversaciones () { 
    const param = {
        id_numero: $("#numero_id").val(),
        fecha_1: $("#fecha_1").val(),
        fecha_2: $("#fecha_2").val(),
        granualidad: $("#granualidad").val(),
        funcion: 'extraer_conversaciones',
    }
    return new Promise((resolve, reject) => {
        const recibir = peticion_datos(param);
        recibir.then(
          (response) => {
            const data = response;
            resolve(data);
          },
          (error) => {
            reject(error);
          }
        );
    });
}


async function peticion_datos(datos) {
    const url = './back/api_administracion/manejo_api_admin.php';
    const peticion = {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos),
    };
    
    try {
        const response = await fetch(url, peticion);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.log('Error en la solicitud para', datos, ':', error);
        return null;
    }
}


async function extraer_numeros () {
    const param = {
        id_numero: $("#numero_id").val(),
        fecha_1: $("#fecha_1").val(),
        fecha_2: $("#fecha_2").val(),
        granualidad: $("#granualidad").val(),
        funcion: 'extraer_numeros' 
    }
    return new Promise((resolve, reject) => {
        const recibir = peticion_datos(param);
        recibir.then(
          (response) => {
            const data = response;
            resolve(data); // Resuelve la promesa con los datos  
          },
          (error) => {
            reject(error); // Rechaza la promesa en caso de error
          }
        );
    });
}


async function extraer_analiticas () {
    const param = {
        id_numero: $("#numero_id").val(),
        fecha_1: $("#fecha_1").val(),
        fecha_2: $("#fecha_2").val(),
        granualidad: $("#granualidad").val(),
        funcion: 'extraer_analiticas',
    }
    return new Promise((resolve, reject) => {
        const recibir = peticion_datos(param);
        recibir.then(
          (response) => {
            const data = response;
            resolve(data); // Resuelve la promesa con los datos
          },
          (error) => {
            reject(error); // Rechaza la promesa en caso de error
          }
        );
    });
}

async function insertar_analiticas () {
    window.msg_enviados = [];
    window.labels_fecha = [];
    window.msg_entregados = [];

    let parametros=[];
    destruir_grafico_existente(); 
    let datos = await extraer_analiticas();

    if (datos){
        for (let i=0; i<datos.length; i++){
            fecha = new Date(datos[i].puntos_datos.fecha_ini);
            numero_mes = fecha.getMonth();

            if(datos[i].granularity == "DAY" || datos[i].granualidad == "DAILY" ) {
                window.labels_fecha[i] = datos[i].puntos_datos.fecha_ini;
            } else {
                window.labels_fecha[i] = window.nombres_meses[numero_mes+1];
            }
            window.msg_enviados[i] = datos[i].puntos_datos.enviados;
            window.msg_entregados[i] = datos[i].puntos_datos.entregados;

            numero_mes = 0;
            fecha = "";
        }

        parametros = [
            {
                label: 'Enviados',
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                data: window.msg_enviados
            },
            {
                label: 'Entregados',
                backgroundColor: '#ced4da',
                borderColor: '#ced4da',
                data: window.msg_entregados
            }
          ];

        crear_grafico(parametros);
    }
}

async function insertar_numeros() {
  
    let numeros = await extraer_numeros();
    cargar_select_numero(numeros);
}

async function insertar_conversaciones () {

  $("#contenido_conversaciones").html("");
  let conversaciones = await extraer_conversaciones();

  if(conversaciones) {
    for(let i=0; i<conversaciones.length; i++) {
      fecha = new Date(conversaciones[i].puntos_datos.fecha_ini);
      numero_mes = fecha.getMonth();

      if(conversaciones[i].granularity == 1) {
        fecha = conversaciones[i].puntos_datos.fecha_ini;
      } else {
          fecha = window.nombres_meses[numero_mes+1];
      }
        
        html = await cargar_tabla_conversaciones(conversaciones[i].puntos_datos, fecha);
        $("#contenido_conversaciones").append(html);

        numero_mes = 0;
        fecha = "";
        html = "";
    }
  }
}

async function cargar_tabla_conversaciones (datos, fecha) {
  let html = '<tr><td><a>' + fecha + ' </a></td><td><a>' + datos.conversacion + ' </a></td><td><a>' + datos.pais + '</a></td><td><a>' + datos.tipo_conversacion + '</a></td><td><a class="text-muted">' + datos.categoria_conversacion + '</a></td><td><a class="text-success"><span>$US ' + datos.costo + '</span></a></td></tr>';
  return html;
}

function cargar_select_numero(opciones) {
    const select = $('#numero_id');
    
    $.each(opciones.data, function(index, opcion) {
        select.append($('<option>', {
            value: opcion.id,
            text: opcion.name
        }));
    });
}


function destruir_grafico_existente() {
    let $salesChart = $('#sales-chart');
    let existingChart = $salesChart.data('chart');
  
    if (existingChart) {
      existingChart.destroy();
      $salesChart.removeData('chart');
    }
}


function crear_grafico(datos) {
    var ticksStyle = {
      fontColor: '#ffff',
      fontStyle: 'bold'
    };
  
    var mode = 'index';
    var intersect = true;
  
    var $salesChart = $('#sales-chart');
  
    var nuevoChart = new Chart($salesChart, {
      type: 'bar',
      data: {
        labels: window.labels_fecha,
        datasets: datos // Pasa los nuevos datos como parámetro
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
        },
        legend: {
          display: true
        },
        scales: {
          yAxes: [{
            display: true,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: false,
              callback: function (value) {
                if (value) {
                  value += ' Msg';
                }
                return value;
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: true
            },
            ticks: ticksStyle
          }]
        }
      }
    });
  
    // Almacena la instancia del nuevo gráfico en los datos del elemento
    $salesChart.data('chart', nuevoChart);
}


$(function () {
crear_grafico([
    {
        backgroundColor: '#007bff',
        borderColor: '#007bff',
        data: window.msg_enviados
      },
      {
          backgroundColor: '#ced4da',
      borderColor: '#ced4da',
      data: window.msg_entregados
    }
  ]);
});
  