var contador = true;

function vista() {
    var texto = document.getElementById("verPassword");
    if (contador == true) {
        texto.className = "bi bi-eye-slash-fill verPassword";
        document.getElementById("input").type = "text";
        contador = false;
    } else {
        texto.className = "bi bi-eye-fill verPassword";
        document.getElementById("input").type = "password";
        contador = true;
    }
}
