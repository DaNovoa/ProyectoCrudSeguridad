document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        // Validar los campos del formulario aquí
        const titulo = form.querySelector("#titulo").value;
        const descripcion = form.querySelector("#descripcion").value;
        const ubicacion = form.querySelector("#ubicacion").value;
        const fecha = form.querySelector("#fecha").value;

        if (!titulo || !descripcion || !ubicacion || !fecha) {
            // Mostrar un mensaje de error si algún campo está vacío
            alert("Todos los campos son obligatorios");
            event.preventDefault(); // Evitar que el formulario se envíe
        }
    });
});
