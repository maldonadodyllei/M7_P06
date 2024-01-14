//DOM

document.addEventListener("DOMContentLoaded", function () {
  viewUsername();

  let profilePictureData = localStorage.getItem("profilePicture");
  let bannerPictureData = localStorage.getItem("bannerPicture");

  if (profilePictureData) {
    document.getElementById("imgPreview").src = profilePictureData;
  }

  if (bannerPictureData) {
    document.getElementById("bannerPreview").src = bannerPictureData;
  }
});

function openFileInput() {
  document.getElementById("fileInput").click();
}

function changeProfilePicture(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      //document.getElementById("imgPreview").src = e.target.result;
      localStorage.setItem("profilePicture", e.target.result);

      document.getElementById("formAvatar").submit();
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function openBannerInput() {
  document.getElementById("bannerInput").click();
}

function changeBannerPicture(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      //document.getElementById("bannerPreview").src = e.target.result;

      localStorage.setItem("bannerPicture", e.target.result);
      
      document.getElementById("formBanner").submit();
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function titleClick() {
    window.location.href = '../html/homeGZ.html';
}

function viewUsername() {
  fetch("../php/obtener_datos_user.php")
    .then((response) => {

      if (!response.ok) {
        throw new Error("Error al obtener el nombre de usuario");
      }
      // Parsea la respuesta como JSON
      return response.json();
    })
    .then( data => {
      document.getElementById("userNameView").textContent = data.username;
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
      alert("Formato de archivo no permitido.");
  }else if(error === '2'){
      alert("No se ha seleccionado ningún archivo.");
  }
}
window.onload = mostrarMensajeError;