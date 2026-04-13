<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>FAQ Management</h1>
        <div class="header-actions">
            <button class="btn-add-faq" onclick="openFAQModal()">➕ Add New FAQ</button>
        </div>
    </div>

    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; background: rgba(0, 255, 0, 0.1); border: 1px solid #28a745; color: #28a745; padding: 12px; border-radius: 8px; z-index: 1000;">
            <?php echo $_SESSION['admin_message'];
            unset($_SESSION['admin_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin_error'])): ?>
        <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; z-index: 1000;">
            <?php echo $_SESSION['admin_error'];
            unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>

    <!-- FAQ Stats -->
    <div class="faq-stats">
        <div class="stat-item">
            <div class="stat-icon">❓</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count($data['faqs']); ?></span>
                <span class="stat-label">Total FAQs</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📝</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count(array_filter($data['faqs'], function ($faq) {
                                                return $faq['status'] === 'published';
                                            })); ?></span>
                <span class="stat-label">Published</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo array_sum(array_column($data['categories'], 'count')); ?></span>
                <span class="stat-label">Category FAQs</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🔥</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo max(0, count($data['faqs']) - count(array_filter($data['faqs'], function ($faq) {
                                                return $faq['status'] !== 'published';
                                            }))); ?></span>
                <span class="stat-label">Showing Active</span>
            </div>
        </div>
    </div>

    <!-- FAQ Categories Filter -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach ($data['categories'] as $categorySlug => $category): ?>
                    <option value="<?php echo htmlspecialchars($categorySlug); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search FAQs..." id="faqSearch">
        </div>
    </div>

    <!-- FAQ Management Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Frequently Asked Questions</h3>
            <span class="total-count"><?php echo count($data['faqs']); ?> total FAQs</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['faqs'])): ?>
                        <?php foreach ($data['faqs'] as $faq): ?>
                            <tr class="faq-row" data-category="<?php echo htmlspecialchars($faq['category_slug']); ?>" data-status="<?php echo htmlspecialchars($faq['status']); ?>">
                                <td>#<?php echo str_pad($faq['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="faq-question">
                                        <strong><?php echo htmlspecialchars($faq['question']); ?></strong>
                                        <div class="faq-preview">
                                            <?php echo htmlspecialchars(strlen($faq['answer']) > 100 ? substr($faq['answer'], 0, 100) . '...' : $faq['answer']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge <?php echo htmlspecialchars($faq['category_slug']); ?>">
                                        <?php echo htmlspecialchars($faq['category_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo htmlspecialchars($faq['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($faq['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($faq['updated_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action-sm btn-edit" onclick="editFAQ(<?php echo $faq['id']; ?>)">Edit</button>
                                        <?php if ($faq['status'] === 'published'): ?>
                                            <button type="button" class="btn-action-sm btn-unpublish" onclick="toggleStatus(<?php echo $faq['id']; ?>, 'unpublish')">Unpublish</button>
                                        <?php else: ?>
                                            <button type="button" class="btn-action-sm btn-publish" onclick="toggleStatus(<?php echo $faq['id']; ?>, 'publish')">Publish</button>
                                        <?php endif; ?>
                                        <button type="button" class="btn-action-sm btn-delete" onclick="deleteFAQ(<?php echo $faq['id']; ?>)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 24px;">No FAQs found. Add a new FAQ to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQ Categories Management -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>FAQ Categories</h3>
        </div>
        <div class="categories-grid">
            <?php if (!empty($data['categories'])): ?>
                <?php foreach ($data['categories'] as $slug => $category): ?>
                    <div class="category-card">
                        <div class="category-info">
                            <h4><?php echo htmlspecialchars($category['name']); ?></h4>
                            <p><?php echo htmlspecialchars($category['count']); ?> FAQs</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="category-card">
                    <div class="category-info">
                        <h4>No categories available</h4>
                        <p>Please add categories in the database first.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<form id="faqActionForm" method="POST" style="display:none;">
    <input type="hidden" name="id" id="faqActionId">
    <input type="hidden" name="status" id="faqActionStatus">
</form>

<!-- Add/Edit FAQ Modal -->
<div id="faqModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3 id="faqModalTitle">Add New FAQ</h3>
            <span class="close" onclick="closeFAQModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="faqForm" class="faq-form" method="POST" action="<?php echo URLROOT; ?>/admin/createFaq">
                <input type="hidden" id="faqId" name="id" value="">
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" id="faqQuestion" name="question" required placeholder="Enter the frequently asked question">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select id="faqCategory" name="category" required>
                            <option value="">Select Category</option>
                            <?php foreach ($data['categories'] as $categorySlug => $category): ?>
                                <option value="<?php echo htmlspecialchars($categorySlug); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="faqStatus" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Answer</label>
                    <textarea id="faqAnswer" name="answer" rows="6" required placeholder="Enter the detailed answer to this question"></textarea>
                </div>

                <div class="form-group">
                    <label>Tags (Optional)</label>
                    <input type="text" id="faqTags" name="tags" placeholder="Enter tags separated by commas (e.g., booking, cancellation, refund)">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeFAQModal()">Cancel</button>
                    <button type="submit" class="btn-save-faq">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const faqData = <?php echo json_encode($data['faqs'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

    function openFAQModal() {
        document.getElementById('faqModalTitle').textContent = 'Add New FAQ';
        document.getElementById('faqForm').action = '<?php echo URLROOT; ?>/admin/createFaq';
        document.getElementById('faqId').value = '';
        document.getElementById('faqQuestion').value = '';
        document.getElementById('faqCategory').value = '';
        document.getElementById('faqStatus').value = 'draft';
        document.getElementById('faqAnswer').value = '';
        document.getElementById('faqTags').value = '';
        document.getElementById('faqModal').style.display = 'block';
    }

    function closeFAQModal() {
        document.getElementById('faqModal').style.display = 'none';
    }

    function editFAQ(id) {
        const faq = faqData.find(item => item.id === id);
        if (!faq) {
            alert('Unable to locate the selected FAQ for editing.');
            return;
        }

        document.getElementById('faqModalTitle').textContent = 'Edit FAQ';
        document.getElementById('faqForm').action = '<?php echo URLROOT; ?>/admin/updateFaq';
        document.getElementById('faqId').value = faq.id;
        document.getElementById('faqQuestion').value = faq.question;
        document.getElementById('faqCategory').value = faq.category_slug;
        document.getElementById('faqStatus').value = faq.status;
        document.getElementById('faqAnswer').value = faq.answer;
        document.getElementById('faqTags').value = '';
        document.getElementById('faqModal').style.display = 'block';
    }

    function toggleStatus(id, action) {
        const actionText = action === 'publish' ? 'publish' : 'unpublish';
        if (!confirm(`Are you sure you want to ${actionText} this FAQ?`)) {
            return;
        }

        const form = document.getElementById('faqActionForm');
        form.action = '<?php echo URLROOT; ?>/admin/toggleFaqStatus';
        document.getElementById('faqActionId').value = id;
        document.getElementById('faqActionStatus').value = action;
        form.submit();
    }

    function deleteFAQ(id) {
        if (!confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')) {
            return;
        }

        const form = document.getElementById('faqActionForm');
        form.action = '<?php echo URLROOT; ?>/admin/deleteFaq';
        document.getElementById('faqActionId').value = id;
        document.getElementById('faqActionStatus').value = '';
        form.submit();
    }

    // Filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const faqSearch = document.getElementById('faqSearch');
    const faqRows = document.querySelectorAll('.faq-row');

    function applyFaqFilters() {
        const category = categoryFilter.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const searchTerm = faqSearch.value.toLowerCase();

        faqRows.forEach(row => {
            const matchesCategory = !category || row.dataset.category === category;
            const matchesStatus = !status || row.dataset.status === status;
            const questionText = row.querySelector('.faq-question').textContent.toLowerCase();
            const matchesSearch = !searchTerm || questionText.includes(searchTerm);

            row.style.display = matchesCategory && matchesStatus && matchesSearch ? '' : 'none';
        });
    }

    categoryFilter.addEventListener('change', applyFaqFilters);
    statusFilter.addEventListener('change', applyFaqFilters);
    faqSearch.addEventListener('input', applyFaqFilters);

    window.onclick = function(event) {
        const modal = document.getElementById('faqModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>