document.addEventListener("DOMContentLoaded", function () {
    getUserData();
});

var videoSources = {
    'Aventura': '../video/gameAventure.mp4',
    'Acción': '../video/gameAction.mp4',
    'Estrategia': '../video/gameStrategy.mp4',
    'Deportes': '../video/gameSport.mp4'
};

let selectedGenres = [];

function updateVideoSource(valueP) {
    var videoPlayer = document.getElementById('videoPlayer');

    var index = selectedGenres.indexOf(valueP);

    if (index !== -1) {
        selectedGenres.splice(index , 1);
    } else{
        selectedGenres.unshift(valueP);
    }

    if (selectedGenres.length > 0) {
        videoPlayer.src = videoSources[selectedGenres[0]];
    } else {
        videoPlayer.src = ''; 
    }
}

function titleClick() {
    window.location.href = '../html/homeGZ.html';
}


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
        //quiero comprobar si tiene el campo de preferencias
        if (data.preferencias != null) {
            var preferencias = data.preferencias;
            preferencias.forEach(element => {
                document.getElementById(element).checked = true;
                updateVideoSource(element);
            });
        }
      })
      .catch((error) => {
        console.error(error.message);
      });
}

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