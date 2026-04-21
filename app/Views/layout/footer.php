</main>
<!-- FOOTER -->
<footer class="bg-dark text-white py-4 border-top border-secondary">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-md-12">
                <p class="mb-0 small text-white-50">
                    &copy; <?= date('Y'); ?> Derechos reservados <strong>UPDS</strong>. &middot;
                    <a href="#" class="text-white-50 text-decoration-none border-bottom">Privacidad</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- DataTables Core & Bootstrap 5 Integration -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Extensions -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<!-- Custom Scripts -->
<script src="<?= e(app_url('/js/layout.js')); ?>"></script>
</body>

</html>