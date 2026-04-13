<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="kal-admin-main-content">
    <div class="kal-admin-header">
        <h1>📧 Newsletter Subscribers</h1>
        <p>Enrolled emails and CSV export</p>
    </div>

    <div class="newsletter-subs-stats">
        <div class="stat-pill">
            <div class="stat-title">Total</div>
            <div class="stat-value"><?php echo number_format($data['subscriber_stats']['total'] ?? 0); ?></div>
        </div>
        <div class="stat-pill">
            <div class="stat-title">Active</div>
            <div class="stat-value"><?php echo number_format($data['subscriber_stats']['active'] ?? 0); ?></div>
        </div>
        <div class="stat-pill">
            <div class="stat-title">Inactive</div>
            <div class="stat-value"><?php echo number_format($data['subscriber_stats']['inactive'] ?? 0); ?></div>
        </div>

        <div class="stat-pill export-pill">
            <a class="btn-export-csv" href="<?php echo URLROOT; ?>/admin/newsletter/export">
                ⬇️ Export CSV
            </a>
        </div>
    </div>

    <div class="kal-admin-content-section">
        <div class="table-container">
            <table class="kal-admin-table newsletter-subs-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Subscribed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['subscribers'])): ?>
                        <?php foreach ($data['subscribers'] as $sub): ?>
                            <tr>
                                <td><?php echo $sub->id; ?></td>
                                <td><?php echo htmlspecialchars($sub->email ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($sub->name ?? ''); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($sub->status ?? 'inactive'); ?>">
                                        <?php echo ucfirst($sub->status ?? 'inactive'); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($sub->subscribed_date ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">No subscribers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .newsletter-subs-stats {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .kal-admin-table {
        width: 100%;
        table-layout: fixed;
    }
    .kal-admin-table th,
    .kal-admin-table td {
        padding: 12px 10px;
        vertical-align: middle;
        word-wrap: break-word;
    }
    .stat-pill {
        flex: 1;
        min-width: 180px;
        background: #161616;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 18px;
    }
    .stat-pill.export-pill {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0f2f14;
        border-color: rgba(3,178,0,0.35);
    }
    .stat-title {
        color: #888;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .stat-value {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
    }
    .btn-export-csv {
        display: inline-block;
        padding: 10px 16px;
        background: #03B200;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
    }
    .newsletter-subs-table {
        width: 100%;
        table-layout: fixed;
    }
    .no-data {
        text-align: center;
        padding: 30px;
        color: #999;
    }
</style>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>

