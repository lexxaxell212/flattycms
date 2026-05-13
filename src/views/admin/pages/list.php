<?php
$stmt = $pdo->query("SELECT * FROM pages ORDER BY updated_at DESC");
?>

<div style="background: #1e293b; padding: 25px; border-radius: 12px; margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #f8fafc;">📄 All Pages (<?= $stmt->rowCount(); ?>)</h3>
        <a href="index.php" class="btn btn-primary">➕ New Page</a>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #334155;">
                    <th style="padding: 15px; text-align: left; color: #cbd5e1;">Title</th>
                    <th style="padding: 15px; text-align: left; color: #cbd5e1;">Slug</th>
                    <th style="padding: 15px; text-align: left; color: #cbd5e1;">Updated</th>
                    <th style="padding: 15px; text-align: left; color: #cbd5e1;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($page = $stmt->fetch()): ?>
                <tr style="border-bottom: 1px solid #475569;">
                    <td style="padding: 15px;"><?php echo htmlspecialchars($page['title']); ?></td>
                    <td style="padding: 15px; font-family: monospace; color: #60a5fa;"><?php echo $page['slug']; ?></td>
                    <td style="padding: 15px;"><?php echo date('d M Y H:i', strtotime($page['updated_at'])); ?></td>
                    <td style="padding: 15px;">
                        <a href="index.php?edit=<?php echo $page['slug']; ?>" class="btn" style="padding: 6px 12px; font-size: 12px; background: #3b82f6;">
                            ✏️ Edit
                        </a>
                        <a href="../../pages/<?php echo $page['slug']; ?>/" target="_blank" class="btn" style="padding: 6px 12px; font-size: 12px; background: #10b981;">
                            👁️ View
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>