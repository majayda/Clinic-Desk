<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" action="<?= e(url('appointments', 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <div class="form-group">
      <label>Doctor</label>
      <select class="form-control" id="doctor_id" name="doctor_id" required>
        <?php foreach ($doctors as $d): ?>
          <option value="<?= (int) $d['id'] ?>" data-days="<?= e($d['available_days']) ?>"><?= e($d['name']) ?> - <?= e($d['specialization']) ?></option>
        <?php endforeach; ?>
      </select>
      <small class="form-text text-muted" id="doctor_days"></small>
    </div>
    <div class="form-group">
      <label>Available Date</label>
      <select class="form-control" id="appt_date" name="appt_date" required></select>
    </div>
    <div class="form-group">
      <label>Time</label>
      <select class="form-control" name="appt_time" required>
        <?php for ($hour = 9; $hour <= 16; $hour++): ?>
          <?php foreach (['00', '30'] as $minute): $slot = sprintf('%02d:%s', $hour, $minute); ?>
            <option value="<?= $slot ?>"><?= $slot ?></option>
          <?php endforeach; ?>
        <?php endfor; ?>
      </select>
    </div>
    <div class="form-group"><label>Reason</label><input class="form-control" name="reason" maxlength="255"></div>
    <button class="btn btn-primary">Book</button>
  </form>
</div></div>
<script>
  (function () {
    var doctorSelect = document.getElementById('doctor_id');
    var dateSelect = document.getElementById('appt_date');
    var daysLabel = document.getElementById('doctor_days');
    var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    function pad(value) {
      return String(value).padStart(2, '0');
    }

    function dateValue(date) {
      return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
    }

    function dateLabel(date) {
      return dayNames[date.getDay()] + ' - ' + dateValue(date);
    }

    function refreshAvailableDates() {
      var selected = doctorSelect.options[doctorSelect.selectedIndex];
      var days = (selected.getAttribute('data-days') || '').split(',').map(function (day) {
        return day.trim();
      }).filter(Boolean);
      var allowed = new Set(days);
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      daysLabel.textContent = days.length ? 'Available days: ' + days.join(', ') : 'No available days configured.';
      dateSelect.innerHTML = '';

      for (var offset = 0; offset <= 45; offset++) {
        var candidate = new Date(today);
        candidate.setDate(today.getDate() + offset);
        if (!allowed.has(dayNames[candidate.getDay()])) {
          continue;
        }

        var option = document.createElement('option');
        option.value = dateValue(candidate);
        option.textContent = dateLabel(candidate);
        dateSelect.appendChild(option);
      }

      if (!dateSelect.options.length) {
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'No available dates in the next 45 days';
        dateSelect.appendChild(option);
      }
    }

    doctorSelect.addEventListener('change', refreshAvailableDates);
    refreshAvailableDates();
  }());
</script>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
