<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" enctype="multipart/form-data" action="<?= e(url('prescriptions', 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <input type="hidden" name="appointment_id" value="<?= (int) $appointment['id'] ?>">
    <div class="form-group"><label>Diagnosis</label><textarea class="form-control" name="diagnosis" required></textarea></div>
    <div class="form-group"><label>Medications</label><textarea class="form-control" name="medications" required></textarea></div>
    <div class="form-group"><label>Notes</label><textarea class="form-control" name="notes"></textarea></div>
    <div class="form-group"><label>Scanned PDF</label><input class="form-control" type="file" name="prescription_file" accept="application/pdf"></div>
    <button class="btn btn-primary">Save Prescription</button>
  </form>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

