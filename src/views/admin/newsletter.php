<?php
$msg = $_SESSION['nl_success'] ?? null;
unset($_SESSION['nl_success']);

$total_active = (int)$pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();
$total_all = (int)$pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();

$filter = $_GET['status'] ?? 'active';
$status_map = [
  'active' => "status = 'active'",
  'unsubscribed' => "status = 'unsubscribed'",
  'deleted' => "status = 'deleted'",
  'all' => "1=1",
];
$where = $status_map[$filter] ?? $status_map['active'];
$subscribers = $pdo->query("SELECT * FROM subscribers WHERE $where ORDER BY subscribed_at DESC LIMIT 50")->fetchAll();
$newsletters = $pdo->query("SELECT * FROM newsletters ORDER BY sent_at DESC LIMIT 10")->fetchAll();

$csrf = generate_csrf_token();
?>

<main id="content">
  <div class="container">

    <?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-solid fa-circle-check me-2"></i><?= $msg ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Kirim Newsletter -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
            <i class="fa-solid fa-paper-plane fa-sm"></i>
          </span>
          <span class="fw-semibold">Kirim Newsletter</span>
          <span class="ms-auto badge bg-primary"><?= number_format($total_active) ?> subscribers aktif</span>
        </div>
      </div>
      <div class="card-body px-4 py-3">
        <form method="POST" action="/admin/newsletter">
          <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
          <div class="mb-3">
            <label class="form-label fw-medium">Subject</label>
            <input type="text" name="subject" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Pesan</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
          </div>
          <button type="submit" name="send_newsletter" class="btn btn-primary">
            <i class="fa-solid fa-rocket me-1"></i> Kirim Sekarang
          </button>
        </form>
      </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="fs-3 fw-bold text-primary">
            <?= $total_active ?>
          </div>
          <small class="text-muted">Aktif</small>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="fs-3 fw-bold text-secondary">
            <?= $total_all ?>
          </div>
          <small class="text-muted">Total</small>
        </div>
      </div>
    </div>

    <!-- Riwayat -->
    <div class="card border-0 shadow-sm mb-4" id="history">
      <div class="card-header py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
            <i class="fa-solid fa-clock-rotate-left fa-sm"></i>
          </span>
          <span class="fw-semibold">Riwayat</span>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Subject</th>
              <th width="100">Status</th>
              <th width="100">Penerima</th>
              <th width="110">Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($newsletters as $nl): ?>
            <tr>
              <td><?= safe_html($nl['subject']) ?></td>
              <td><span class="badge bg-success">Sent</span></td>
              <td><?= (int)$nl['sent_recipients'] ?>/<?= (int)$nl['total_recipients'] ?></td>
              <td><small class="text-muted"><?= fmt_date($nl['sent_at'] ?? '', 'd/m/y H:i') ?></small></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($newsletters)): ?>
            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Subscribers -->
    <div class="card border-0 shadow-sm" id="subscribers">
      <div class="card-header py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
            <i class="fa-solid fa-users fa-sm"></i>
          </span>
          <span class="fw-semibold">Subscribers</span>
        </div>
        <div class="mt-2 d-flex gap-2 flex-wrap">
          <?php foreach (['active' => 'Aktif', 'unsubscribed' => 'Unsubscribed', 'deleted' => 'Deleted', 'all' => 'Semua'] as $k => $v): ?>
          <a href="?status=<?= $k ?>#subscribers"
            class="btn btn-sm <?= $filter === $k ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= $v ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Email</th>
              <th width="110">Status</th>
              <th width="110">Tanggal</th>
              <th width="80">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subscribers as $s): ?>
            <tr>
              <td><?= safe_html($s['email']) ?></td>
              <td>
                <span class="badge <?= $s['status'] === 'active' ? 'bg-success' : ($s['status'] === 'deleted' ? 'bg-danger' : 'bg-secondary') ?>">
                  <?= safe_html($s['status']) ?>
                </span>
              </td>
              <td><small class="text-muted"><?= fmt_date($s['subscribed_at'] ?? '') ?></small></td>
              <td>
                <?php if ($s['status'] !== 'deleted'): ?>
                <form method="POST" action="/admin/newsletter"
                  onsubmit="return confirm('Hapus subscriber ini?')">
                  <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                  <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                  <button type="submit" name="delete_subscriber" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($subscribers)): ?>
            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>