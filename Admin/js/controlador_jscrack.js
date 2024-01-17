jQuery(document).ready(function(){
    insertar_flujos();

    $("#flujos_id").on('change', function() {
        // La función que se ejecutará cuando cambie la selección
        var opcion_seleccionada = $(this).val();
        construir_arbol(opcion_seleccionada)
        // Puedes realizar cualquier acción adicional aquí
    });
});


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

async function extraer_flujos () {
    return new Promise((resolve, reject) => {
        const param = {
            id: 0,
            numero: 0,
            numero_id: 0,
            area_id:0,
            flujo_id: 0,
            token: "",
            estado: 0,
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

async function insertar_flujos() {
    let flujos = await extraer_flujos();
    cargar_select_flujo(flujos);
}

function cargar_select_flujo(opciones) {
    const select = $('#flujos_id');
    
    $.each(opciones, function(index, opcion) {
        select.append($('<option>', {
            value: opcion.id_flujo,
            text: opcion.nombre_flujo
        }));
    });
}

function construir_arbol(id) {
    let html  = '<iframe id="jsoncrackEmbed" src="https://jsoncrack.com/widget?json=https://institutotesla.ar/BOT/Admin/back/arbol/construir_json.php?id='+id+'" frameborder="0" style="height: 100vh; width: 100%;" ></iframe>';
    $("#iframe").html(html);
}