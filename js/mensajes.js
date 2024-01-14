document.addEventListener('DOMContentLoaded', function () {
    obtenerNombresUsuarios();
    getUserData();

        // Selecciona el contenedor .allMessages
        var allMessagesContainer = document.querySelector('.allMessages');

        // quiero que tarde 1 segundo en desplazarse hasta el final
        var delayInMilliseconds = 1000;

        // Desplázate hasta el final del contenedor
        setTimeout(function () {
            allMessagesContainer.style.scrollBehavior = 'smooth';
            allMessagesContainer.scrollTop = allMessagesContainer.scrollHeight;
            
        }, delayInMilliseconds);
      
});

function obtenerNombresUsuarios() {
    fetch('../php/obtener_usuarios.php')
        .then(response => response.json())
        .then(data => {
            mostrarNombresUsuarios(data);
        })
        .catch(error => {
            console.error('Error al obtener nombres de usuario:', error);
        });
}

function mostrarNombresUsuarios(usuarios) {
    const listaUsuarios = document.querySelector('.list');
    const radios = document.querySelector('.radios');

    if (!listaUsuarios) {
        console.error('No se encontró la lista de usuarios en el documento.');
        return;
    }

    usuarios.forEach(usuario => {
        const li = document.createElement('li');
        const label = document.createElement('label');
        const input = document.createElement('input');

        input.type = 'radio';
        input.name = 'item';
        input.id = usuario.username;
        input.title= usuario.username;
        input.value= usuario.username;

        label.htmlFor = usuario.username;
        label.textContent = usuario.username;

        radios.appendChild(input);
        li.appendChild(label);
        listaUsuarios.appendChild(li);
    });
}


function titleClick() {
    window.location.href = '../html/homeGZ.html';
}

var radioButtons = document.querySelectorAll('input[name="item"]');

// Añade un evento de cambio a cada radio button
radioButtons.forEach(function(radioButton) {
    radioButton.addEventListener('change', function() {
        // Verifica si el radio button está marcado
        if (radioButton.checked) {
            // Obtén el valor del radio button seleccionado
            var valorSeleccionado = radioButton.value;
        }
    });
});


function getUserData() {
    fetch("../php/obtener_datos_user.php")
      .then((response) => {
  
        if (!response.ok) {
          throw new Error("Error al obtener el nombre de usuario");
        }
        // Parsea la respuesta como JSON
        return response.json();
      })
      .then( data => {
        if (data.mensajes != null) {
            mostrarMensajes(data.mensajes);
        }
      })
      .catch((error) => {
        console.error(error.message);
      });
}

function mostrarMensajes(mensajes){
    var contenedorMensajes = document.querySelector('.allMessages');

    mensajes.forEach(mensaje => {
        // Crea un nuevo elemento div para representar el mensaje
        var nuevoMensaje = document.createElement('div');
        nuevoMensaje.classList.add('notification');

        // Agrega los elementos internos del mensaje
        nuevoMensaje.innerHTML = `
        <div class="notiglow"></div>
        <div class="notiborderglow"></div>
        <div class="notititle">${mensaje.destinatario}</div>
        <div class="notitime">${obtenerHoraYMinutosDeFecha(mensaje.fecha)}</div>
        <div class="notibody">${mensaje.mensaje}</div>
        `;

        // Agrega el nuevo mensaje al contenedor
        contenedorMensajes.appendChild(nuevoMensaje);
    });
}


function obtenerHoraYMinutosDeFecha(fechaCompleta) {
    var fechaObjeto = new Date(fechaCompleta);
  
    var hora = fechaObjeto.getHours();
    var minutos = fechaObjeto.getMinutes();
  
   
    var horaFormateada = (hora < 10 ? '0' : '') + hora;
    var minutosFormateados = (minutos < 10 ? '0' : '') + minutos;
  
    return horaFormateada + ':' + minutosFormateados;
  }


// Función para obtener parámetros de la URL
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

// Función para mostrar el mensaje emergente si hay un error en la URL
function mostrarMensajeError() {
    var error = getParameterByName('error');
    if (error === '1') {
        alert("Debes proporcionar un mensaje");
    }
}
window.onload = mostrarMensajeError;


document.getElementById('cerrarSesionBtn').addEventListener('click', function() {

    localStorage.clear();
    
    fetch('../php/cerrar_sesion.php', {
        method: 'POST',
        credentials: 'include' // Incluye las credenciales (cookies) en la solicitud
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cerrar la sesión');
        }
        window.location.href = '../html/inicio_sesion.html';
    })
    .catch(error => {
        console.error(error.message);
    });
});