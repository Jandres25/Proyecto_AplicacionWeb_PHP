</main>
<!-- FOOTER -->
<footer class="bg-dark text-white py-4 border-top border-secondary">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-md-12">
                <p class="mb-0 small text-white-50">
                    &copy; <?= e((string) $layout->currentYear); ?> Derechos reservados <strong>UPDS</strong>. &middot;
                    <a href="#" class="text-white-50 text-decoration-none border-bottom">Privacidad</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- DataTables Core & Bootstrap 5 Integration -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Extensions: Responsive -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<!-- DataTables Buttons: dependencias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- DataTables Buttons: core + Bootstrap 5 + exportadores + ColVis -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

<!-- Custom Scripts -->
<script src="<?= e(app_url('/js/toast-config.js')); ?>"></script>
<?php if (!empty($layout->toastMessages)) : ?>
    <script>
        <?php foreach ($layout->toastMessages as $toast) : ?>
            window.showToast(
                <?= json_encode($toast['type'], JSON_UNESCAPED_UNICODE); ?>,
                <?= json_encode($toast['message'], JSON_UNESCAPED_UNICODE); ?>
            );
        <?php endforeach; ?>
    </script>
<?php endif; ?>
</body>

</html>
