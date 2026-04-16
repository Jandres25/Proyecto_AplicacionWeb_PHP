$(document).ready(function () {
  if ($("#tabla_id").length) {
    $("#tabla_id").DataTable({
      pageLength: 5,
      lengthMenu: [
        [3, 5, 10, 25, 50],
        [3, 5, 10, 25, 50]
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
      }
    });
  }
});

function borrar(formId) {
  Swal.fire({
    title: "¿Esta seguro de borrar el registro?",
    text: "¡Una vez borrado no se puede recuperar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, elimínelo"
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.getElementById(formId);
      if (form) {
        form.submit();
      }
    }
  });
}
