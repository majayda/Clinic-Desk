  <footer class="main-footer"><strong><?= APP_NAME ?></strong><span>Clinic Management Dashboard</span></footer>
</div>
<script src="public/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="public/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="public/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="public/assets/adminlte/dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    $('.table').DataTable({
      paging: false,
      searching: true,
      info: false,
      ordering: true,
      responsive: true
    });
  });
</script>
</body>
</html>
