jQuery(document).ready(function(){
  cargar_tabla();
  cargar_area_flujo();

    $('#guardar_numero').on('click', function() {
        guardar_numero();
        $('#cerrar_modal').click();
      });
    
    $("#btn_nevo_numero").on('click', function () {
      limpiar_modal_numero();
    })
});
 
function limpiar_modal_numero () {
    $("#id_numero_modal").val(0);
    $("#numero").val(549);
    $("#numero_id").val("");
    $("#select_area").val(0);
    $("#select_flujo").val(0);
    $("#select_estado").val(0);
    $("#token").val("");
}


async function peticion_datos(datos) {
  
  const url = './back/numeros/manejo_numeros.php';
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


function extraer_datos_modal (){
    var datos = {
        ID: $("#id_numero_modal").val(),
        NUMERO: $('#numero').val(),
        NUMERO_ID: $('#numero_id').val(),
        AREA_ID: $('#select_area').val(),
        FLUJO_ID: $('#select_flujo').val(),
        TOKEN: $("#token").val(),
        ESTADO: $("#select_estado").val()
    };
    return datos;
}


async function guardar_numero () {
    
    const datos = extraer_datos_modal();
    const param = {
      id: datos.ID,
      numero: datos.NUMERO,
      numero_id: datos.NUMERO_ID,
      area_id: datos.AREA_ID,
      flujo_id: datos.FLUJO_ID,
      estado: datos.ESTADO,
      token: datos.TOKEN,
      funcion: "guardar_numero"
    };

    //console.log(param);
    peticion_datos(param);
    setTimeout(cargar_tabla, 300);
}


async function extraer_numero(id_numero) {
    const datos = extraer_datos_modal();
    datos.ID = id_numero;

    return new Promise((resolve, reject) => {
      const param = {
          id: datos.ID,
          numero: datos.NUMERO,
          numero_id: datos.NUMERO_ID,
          area_id: datos.AREA_ID,
          flujo_id: datos.FLUJO_ID,
          token: datos.TOKEN,
          estado: datos.ESTADO,
          funcion: "extraer_numeros"
      };
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


async function insertar_numero_modal(id_numero) {
  limpiar_modal_numero();
  try {
      const response = await extraer_numero(id_numero);
      if (response) {
        $("#id_numero_modal").val(response[0].id_numero);
        $('#numero').val(response[0].numero);
        $('#numero_id').val(response[0].telefono_id);
        $('#select_area').val(response[0].area);
        $('#select_flujo').val(response[0].flujo_id);
        $('#token').val(response[0].token_api);
        $("#select_estado").val(response[0].estado);
      } else {
          console.error('La respuesta es undefined o null.');
      }
  } catch (error) {
      console.log(error);
  }
}


function eliminar_numero (id_numero) {
  
  let datos = extraer_datos_modal();
  datos.ID = id_numero;
    
  const param = {
        id: datos.ID,
        numero: datos.NUMERO,
        numero_id: datos.NUMERO_ID,
        area_id: datos.AREA_ID,
        flujo_id: datos.FLUJO_ID,
        estado: datos.ESTADO,
        token: datos.TOKEN,
        funcion: "eliminar_numero"
    };
    peticion_datos(param);
    setTimeout(cargar_tabla, 300);
}

async function cargar_tabla () {
  try {
    const datos = await extraer_numero(0);
    if(datos){
      let elemento_final = "";

      for (let element of datos) {
        fila = "";
        fila = await tabla_html(element);
        elemento_final = elemento_final+fila;
      }
      $("#tabla_numeros").html(elemento_final);
    }
  } catch (error) {
    console.log(error);
  }
}


async function cargar_area_flujo () {
  let area = await extraer_area();
  let flujos = await extraer_flujos();
  cargar_select_area(area);
  cargar_select_flujo(flujos);
}

function cargar_select_area(opciones) {
  const select = $('#select_area');
  select.empty();
  $.each(opciones, function(index, opcion) {
    select.append($('<option>', {
      value: opcion.id_area,
      text: opcion.nombre_area
    }));
  });
}


function cargar_select_flujo(opciones) {
  const select = $('#select_flujo');
  select.empty();
  $.each(opciones, function(index, opcion) {
    select.append($('<option>', {
      value: opcion.id_flujo,
      text: opcion.nombre_flujo
    }));
  });
}


async function extraer_area () {
  return new Promise((resolve, reject) => {
    let datos = extraer_datos_modal();
      const param = {
          id: datos.ID,
          numero: datos.NUMERO,
          numero_id: datos.NUMERO_ID,
          area_id: datos.AREA_ID,
          flujo_id: datos.FLUJO_ID,
          estado: datos.ESTADO,
          token: datos.TOKEN,
          funcion: "extraer_area"
      };
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


async function extraer_flujos () {
  return new Promise((resolve, reject) => {
    let datos = extraer_datos_modal();
      const param = {
          id: datos.ID,
          numero: datos.NUMERO,
          numero_id: datos.NUMERO_ID,
          area_id: datos.AREA_ID,
          flujo_id: datos.FLUJO_ID,
          estado: datos.ESTADO,
          token: datos.TOKEN,
          funcion: "extraer_flujos"
      };
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


async function tabla_html(datos) {

  let estado = "";
  if(datos.estado == 0){
    estado = '<span class="badge badge-success">Activo</span>';
  }
  if(datos.estado == 1){
    estado = '<span class="badge badge-danger">Inactivo</span>';
  }

  let html  = '<tr><td><a>#</a></td><td><a>+'+datos.numero+'</a><br/><small>Created 01.01.2019</small></td><td><a><small>'
  +datos.telefono_id+'</small></a></td><td><a>'+datos.nombre_area+'</a></td><td class="project_progress"><a>'+datos.nombre_flujo+
  '</a></td><td class="project-state">'+estado+'</td><td class="project-actions text-right"><a id="btn_editar_numero"'+
  ' class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-lg" onclick="insertar_numero_modal('+datos.id_numero+
  ')"><i class="fas fa-pencil-alt"></i>Editar</a><a class="btn btn-danger btn-sm" onclick="eliminar_numero('+datos.id_numero+
  ')"><i class="fas fa-trash"></i>Eliminar</a></td></tr>';

  return html;
}