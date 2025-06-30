<div class="container mt-4">
    <h3>Activity Log</h3>
    <div class="card mt-3">
        <div class="card-body" style="max-height: 500px; overflow:auto;">
            <?php if (count($data['logs']) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($data['logs'] as $log): ?>
                        <li class="list-group-item small"><?= htmlspecialchars($log); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada log aktivitas.</p>
            <?php endif; ?>
        </div>
    </div>
</div>