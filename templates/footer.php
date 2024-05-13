</main>
<!-- FOOTER -->
<footer>
      <div class="d-flex align-items-center justify-content-center" style="height: 70px; color: white; background: rgb(52, 58, 64);">
            <b5>&copy;Derechos reservados UPDS 2023, Inc. &middot; <a href="#">Privacidad</a></b5>
      </div>
</footer>
<script>
      $(document).ready(function() {
            $("#tabla_id").DataTable({
                  "pageLength": 5,
                  lengthMenu: [
                        [3, 5, 10, 25, 50],
                        [3, 5, 10, 25, 50]
                  ],
                  "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
                  }
            });
      });
</script>
<script>
      function borrar(ID) {
            Swal.fire({
                  title: '¿Esta seguro de borrar el registro?',
                  text: '¡Una vez borrado no se puede recuperar!',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, elimínelo'
            }).then((result) => {
                  if (result.isConfirmed) {
                        window.location = "index.php?txtID=" + ID;
                  }
            })
      }
</script>
</body>

</html>