<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Blog Management</h1>
        <div class="header-actions">
            <button class="kal-btn-new-post" onclick="openPostModal()">✍️ New Blog Post</button>
        </div>
    </div>

    <!-- Blog Stats -->
    <div class="kal-blog-stats">
        <div class="kal-stat-item">
            <div class="kal-stat-icon">📝</div>
            <div class="kal-stat-details">
                <span class="kal-stat-number"><?php echo count($data['posts']); ?></span>
                <span class="kal-stat-label">Total Posts</span>
            </div>
        </div>
        <div class="kal-stat-item">
            <div class="kal-stat-icon">✅</div>
            <div class="kal-stat-details">
                <span class="kal-stat-number"><?php echo count(array_filter($data['posts'], function($post) { return $post['status'] == 'Published'; })); ?></span>
                <span class="kal-stat-label">Published</span>
            </div>
        </div>
        <div class="kal-stat-item">
            <div class="kal-stat-icon">👁️</div>
            <div class="kal-stat-details">
                <span class="kal-stat-number"><?php echo array_sum(array_column($data['posts'], 'views')); ?></span>
                <span class="kal-stat-label">Total Views</span>
            </div>
        </div>
        <div class="kal-stat-item">
            <div class="kal-stat-icon">📊</div>
            <div class="kal-stat-details">
                <span class="kal-stat-number"><?php echo count(array_filter($data['posts'], function($post) { return $post['status'] == 'Draft'; })); ?></span>
                <span class="kal-stat-label">Drafts</span>
            </div>
        </div>
    </div>

    <!-- Blog Filters -->
    <div class="kal-filters-section">
        <div class="kal-filter-group">
            <select class="kal-filter-select" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="cricket">Cricket</option>
                <option value="football">Football</option>
                <option value="tennis">Tennis</option>
                <option value="basketball">Basketball</option>
                <option value="general">General</option>
            </select>
        </div>
        <div class="kal-filter-group">
            <select class="kal-filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>
        <div class="kal-filter-group">
            <input type="text" class="kal-search-input" placeholder="Search blog posts..." id="blogSearch">
        </div>
    </div>

    <!-- Blog Posts Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Blog Posts</h3>
            <span class="total-count"><?php echo count($data['posts']); ?> total posts</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Published Date</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['posts'] as $post): ?>
                    <tr class="kal-blog-row" data-category="<?php echo strtolower($post['category']); ?>" data-status="<?php echo strtolower($post['status']); ?>">
                        <td>
                            <div class="kal-post-info">
                                <div class="kal-post-thumbnail">
                                    <img src="<?php echo URLROOT; ?>/images/blog/thumb-<?php echo $post['id']; ?>.jpg" alt="Post thumbnail" onerror="this.src='<?php echo URLROOT; ?>/images/blog/default-thumb.jpg'">
                                </div>
                                <div class="kal-post-details">
                                    <h4><?php echo $post['title']; ?></h4>
                                    <p>Blog post about sports and activities...</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="kal-author-info">
                                <div class="kal-author-avatar"><?php echo substr($post['author'], 0, 1); ?></div>
                                <span><?php echo $post['author']; ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="kal-category-badge <?php echo strtolower($post['category']); ?>">
                                <?php echo $post['category']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="kal-status-badge <?php echo strtolower($post['status']); ?>">
                                <?php echo $post['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $post['published'] ? $post['published'] : '-'; ?></td>
                        <td>
                            <span class="kal-views-count"><?php echo number_format($post['views']); ?></span>
                        </td>
                        <td>
                            <div class="kal-action-buttons">
                                <button class="kal-btn-action-sm kal-btn-edit" onclick="editPost(<?php echo $post['id']; ?>)">Edit</button>
                                <button class="kal-btn-action-sm kal-btn-preview" onclick="previewPost(<?php echo $post['id']; ?>)">Preview</button>
                                <?php if($post['status'] == 'Published'): ?>
                                    <button class="kal-btn-action-sm kal-btn-unpublish" onclick="togglePostStatus(<?php echo $post['id']; ?>, 'unpublish')">Unpublish</button>
                                <?php elseif($post['status'] == 'Draft'): ?>
                                    <button class="kal-btn-action-sm kal-btn-publish" onclick="togglePostStatus(<?php echo $post['id']; ?>, 'publish')">Publish</button>
                                <?php endif; ?>
                                <button class="kal-btn-action-sm kal-btn-delete" onclick="deletePost(<?php echo $post['id']; ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Blog Activity</h3>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="kal-activity-icon published">📝</div>
                <div class="activity-details">
                    <p><strong>"Top 10 Cricket Grounds in Colombo"</strong> was published</p>
                    <small>2 hours ago by Admin</small>
                </div>
                <div class="kal-activity-views">+150 views</div>
            </div>
            <div class="activity-item">
                <div class="kal-activity-icon draft">✏️</div>
                <div class="activity-details">
                    <p><strong>"Football Training Tips"</strong> saved as draft</p>
                    <small>5 hours ago by Coach Mike</small>
                </div>
                <div class="kal-activity-views">Draft</div>
            </div>
            <div class="activity-item">
                <div class="kal-activity-icon popular">🔥</div>
                <div class="activity-details">
                    <p><strong>"Benefits of Playing Tennis"</strong> trending</p>
                    <small>1 day ago</small>
                </div>
                <div class="kal-activity-views">980 views</div>
            </div>
        </div>
    </div>
</div>

<!-- New/Edit Post Modal -->
<div id="postModal" class="kal-modal">
    <div class="kal-modal-content extra-large">
        <div class="kal-modal-header">
            <h3 id="postModalTitle">Create New Blog Post</h3>
            <span class="kal-close" onclick="closePostModal()">&times;</span>
        </div>
        <div class="kal-modal-body">
            <form class="kal-blog-form">
                <div class="kal-form-group">
                    <label>Post Title</label>
                    <input type="text" id="postTitle" name="title" class="kal-form-control" required placeholder="Enter an engaging blog post title">
                </div>
                
                <div class="kal-form-row">
                    <div class="kal-form-group">
                        <label>Category</label>
                        <select id="postCategory" name="category" class="kal-form-control" required>
                            <option value="">Select Category</option>
                            <option value="cricket">Cricket</option>
                            <option value="football">Football</option>
                            <option value="tennis">Tennis</option>
                            <option value="basketball">Basketball</option>
                            <option value="general">General Sports</option>
                        </select>
                    </div>
                    <div class="kal-form-group">
                        <label>Author</label>
                        <select id="postAuthor" name="author" class="kal-form-control" required>
                            <option value="">Select Author</option>
                            <option value="Admin">Admin</option>
                            <option value="Coach Mike">Coach Mike</option>
                            <option value="Dr. Silva">Dr. Silva</option>
                        </select>
                    </div>
                </div>
                
                <div class="kal-form-group">
                    <label>Featured Image</label>
                    <div class="kal-image-upload">
                        <input type="file" id="postImage" accept="image/*">
                        <div class="kal-upload-preview" id="imagePreview"></div>
                        <div class="kal-upload-text">
                            <p>Upload featured image (1200x600px recommended)</p>
                        </div>
                    </div>
                </div>
                
                <div class="kal-form-group">
                    <label>Post Excerpt</label>
                    <textarea id="postExcerpt" name="excerpt" class="kal-form-control" rows="3" placeholder="Write a brief excerpt that will appear in post previews..."></textarea>
                </div>
                
                <div class="kal-form-group">
                    <label>Post Content</label>
                    <div class="kal-editor-toolbar">
                        <button type="button" class="kal-editor-btn" onclick="formatText('bold')"><strong>B</strong></button>
                        <button type="button" class="kal-editor-btn" onclick="formatText('italic')"><em>I</em></button>
                        <button type="button" class="kal-editor-btn" onclick="formatText('underline')"><u>U</u></button>
                        <button type="button" class="kal-editor-btn" onclick="insertLink()">🔗</button>
                        <button type="button" class="kal-editor-btn" onclick="insertImage()">📷</button>
                    </div>
                    <textarea id="postContent" name="content" class="kal-form-control" rows="12" required placeholder="Write your blog post content here..."></textarea>
                </div>
                
                <div class="kal-form-row">
                    <div class="kal-form-group">
                        <label>Tags</label>
                        <input type="text" id="postTags" name="tags" class="kal-form-control" placeholder="Enter tags separated by commas (e.g., cricket, tips, sports)">
                    </div>
                    <div class="kal-form-group">
                        <label>Status</label>
                        <select id="postStatus" name="status" class="kal-form-control" required>
                            <option value="draft">Save as Draft</option>
                            <option value="published">Publish Now</option>
                            <option value="scheduled">Schedule for Later</option>
                        </select>
                    </div>
                </div>
                
                <div class="kal-form-group" id="scheduleSection" style="display: none;">
                    <label>Publish Date & Time</label>
                    <input type="datetime-local" id="publishDate" name="publish_date" class="kal-form-control">
                </div>
                
                <div class="kal-modal-actions">
                    <button type="button" class="kal-btn-cancel" onclick="closePostModal()">Cancel</button>
                    <button type="submit" class="kal-btn-save-post">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Your JavaScript code remains the same...
function openPostModal() {
    document.getElementById('postModalTitle').textContent = 'Create New Blog Post';
    document.getElementById('postModal').style.display = 'block';
    document.querySelector('.kal-blog-form').reset();
    document.getElementById('imagePreview').innerHTML = '';
}

function closePostModal() {
    document.getElementById('postModal').style.display = 'none';
}

function editPost(id) {
    document.getElementById('postModalTitle').textContent = 'Edit Blog Post';
    document.getElementById('postModal').style.display = 'block';
    console.log('Editing post ID:', id);
}

function previewPost(id) {
    window.open(`<?php echo URLROOT; ?>/blog/post/${id}?preview=1`, '_blank');
}

function togglePostStatus(id, action) {
    const actionText = action === 'publish' ? 'publish' : 'unpublish';
    if(confirm(`Are you sure you want to ${actionText} this blog post?`)) {
        alert(`Blog post #${id} ${actionText}ed successfully!`);
    }
}

function deletePost(id) {
    if(confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
        alert(`Blog post #${id} deleted successfully!`);
    }
}

// Show/hide schedule section based on status selection
document.getElementById('postStatus').addEventListener('change', function() {
    const scheduleSection = document.getElementById('scheduleSection');
    if(this.value === 'scheduled') {
        scheduleSection.style.display = 'block';
    } else {
        scheduleSection.style.display = 'none';
    }
});

// Simple text formatting functions
function formatText(command) {
    const textarea = document.getElementById('postContent');
    textarea.focus();
    document.execCommand(command, false, null);
}

function insertLink() {
    const url = prompt('Enter URL:');
    if(url) {
        const text = prompt('Enter link text:');
        if(text) {
            const textarea = document.getElementById('postContent');
            const link = `[${text}](${url})`;
            textarea.value += link;
        }
    }
}

function insertImage() {
    const url = prompt('Enter image URL:');
    if(url) {
        const alt = prompt('Enter image alt text:');
        const textarea = document.getElementById('postContent');
        const image = `![${alt || 'Image'}](${url})`;
        textarea.value += image;
    }
}

// Image preview functionality
document.getElementById('postImage').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = 
                `<img src="${e.target.result}" style="max-width: 200px; border-radius: 8px;">`;
        };
        reader.readAsDataURL(file);
    }
});

// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const category = this.value.toLowerCase();
    const rows = document.querySelectorAll('.kal-blog-row');
    
    rows.forEach(row => {
        if(category === '' || row.dataset.category === category) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value.toLowerCase();
    const rows = document.querySelectorAll('.kal-blog-row');
    
    rows.forEach(row => {
        if(status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('blogSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.kal-blog-row');
    
    rows.forEach(row => {
        const title = row.querySelector('.kal-post-details h4').textContent.toLowerCase();
        if(title.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>