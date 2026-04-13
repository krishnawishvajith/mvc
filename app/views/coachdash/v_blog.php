<?php require APPROOT.'/views/coachdash/inc/header.php'; ?>

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

                                    <?php $image = !empty($post['featured_image']) ? $post['featured_image']  : URLROOT . '/images/blog/default/images.png';?> <img src="<?php echo $image; ?>"  alt="Post thumbnail" onerror="this.src='<?php echo URLROOT; ?>/images/blog/default/images.png'"
>
                                
                                
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
                            <span class="kal-category-badge">
                                    <?php echo ucwords(str_replace('_', ' ', $post['category'])); ?>
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
            <form class="kal-blog-form" method="post" action="<?php echo URLROOT; ?>/coachdash/createPost" enctype="multipart/form-data">



                <input type="hidden" id="postIdInput" name="post_id" value="">
                <input type="hidden" id="postSlugInput" name="slug" value="">
                
                
                
                <div class="kal-form-group">
                    <label>Post Title</label>
                    <input type="text" id="postTitle" name="title" class="kal-form-control" required placeholder="Enter an engaging blog post title">
                </div>
                
                <div class="kal-form-group">
                    <label> Sport Category</label>
                    <select id="postCategory" name="category" class="kal-form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = $data['categories'] ?? [];
                        foreach ($categories as $key => $label) :
                        ?>
                            <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="kal-form-group">
                    <label>Featured Image</label>
                    <div class="kal-image-upload">
                        <input type="file" id="postImage" name="featured_image" accept="image/*">
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
                            
                        </select>
                    </div>
                </div>
                
                <div class="kal-form-group" id="scheduleSection" style="display: none;">
                    <label>Publish Date & Time</label>
                    <input type="datetime-local" id="publishDate" name="publish_date" class="kal-form-control">
                </div>
                
                <div class="kal-modal-actions">
                    <button type="button" class="kal-btn-cancel" onclick="closePostModal()">Cancel</button>
                    <button type="submit" id="postSaveBtn" class="kal-btn-save-post">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>







































<!-- Post Preview Modal -->
<div id="postPreviewModal" class="kal-modal">
    <div class="kal-modal-content extra-large">
        <div class="kal-modal-header">
            <h3>Blog Post Preview</h3>
            <span id="previewCloseBtn" class="kal-close">&times;</span>
        </div>
        <div class="kal-modal-body">
            <div class="kal-preview-header" style="margin-bottom: 20px;">
                <h2 id="previewTitle"></h2>
                <div style="display:flex; flex-wrap:wrap; gap:12px; color:#555; font-size:0.95rem;">
                    <span>By <strong id="previewAuthor"></strong></span>
                    <span>Category: <strong id="previewCategory"></strong></span>
                    <span>Status: <strong id="previewStatus"></strong></span>
                    <span>Posted: <strong id="previewDate"></strong></span>
                </div>
            </div>
            <div class="kal-preview-image" style="margin-bottom:20px;">
                <img id="previewImage" src="<?php echo URLROOT; ?>/images/blog/default/images.png" alt="Preview image" style="width:100%; max-height:360px; object-fit:cover; border-radius:14px;">
            </div>
            <div id="previewContent" class="kal-blog-preview-content" style="line-height:1.8; color:rgba(255, 255, 255, 0.7);"></div>
        </div>
        <div class="kal-modal-actions">
            <button type="button" class="kal-btn-cancel" onclick="closePreviewModal()">Close Preview</button>
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




    // ensure form is in create mode
    document.querySelector('.kal-blog-form').action = '<?php echo URLROOT; ?>/coachdash/createPost';
    document.getElementById('postIdInput').value = '';
    document.getElementById('postSlugInput').value = '';
    document.getElementById('postSaveBtn').textContent = 'Save Post';





}

function closePostModal() {
    document.getElementById('postModal').style.display = 'none';
}















function editPost(id) {
    document.getElementById('postModalTitle').textContent = 'Edit Blog Post';
    document.getElementById('postModal').style.display = 'block';
    console.log('Editing post ID:', id);



    // fetch post data and populate form
    fetch(`<?php echo URLROOT; ?>/coachdash/getPost?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Could not load post data');
                return;
            }
            const post = data.post;
            document.getElementById('postIdInput').value = post.id;
            document.getElementById('postTitle').value = post.title || '';
            document.getElementById('postSlugInput').value = post.slug || '';
            document.getElementById('postExcerpt').value = post.excerpt || '';
            document.getElementById('postContent').value = post.content || '';
            document.getElementById('postTags').value = post.tags || '';
            // set category if exists
            try { document.getElementById('postCategory').value = post.category || ''; } catch(e) {}
            // set status
            document.getElementById('postStatus').value = (post.status || 'draft');

            // set publish date if available
            if (post.published) {
                try {
                    const d = new Date(post.published);
                    // format as yyyy-mm-ddThh:mm
                    const pad = n => n.toString().padStart(2,'0');
                    const dt = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
                    document.getElementById('publishDate').value = dt;
                } catch(e) {}
            }

            // preview featured image if exists
            if (post.featured_image) {
                document.getElementById('imagePreview').innerHTML = `<img src="${post.featured_image}" style="max-width:200px;border-radius:8px;">`;
            } else {
                document.getElementById('imagePreview').innerHTML = '';
            }

            // change form action to update endpoint
            document.querySelector('.kal-blog-form').action = '<?php echo URLROOT; ?>/coachdash/updatePost';
            document.getElementById('postSaveBtn').textContent = 'Update Post';
        })
        .catch(err => {
            console.error(err);
            alert('Network error while loading post data');
        });




        
  
}


function previewPost(id) {
    fetch(`<?php echo URLROOT; ?>/coachdash/getPost?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Could not load post data for preview');
                return;
            }
            const post = data.post;
            document.getElementById('previewTitle').textContent = post.title || 'Untitled Post';
            document.getElementById('previewAuthor').textContent = post.author || 'Coach';
            document.getElementById('previewCategory').textContent = post.category || 'General';
            document.getElementById('previewStatus').textContent = post.status ? post.status.charAt(0).toUpperCase() + post.status.slice(1) : 'Draft';
            document.getElementById('previewDate').textContent = post.created_at ? new Date(post.created_at).toLocaleDateString() : '-';
            document.getElementById('previewImage').src = post.featured_image || '<?php echo URLROOT; ?>/images/blog/default/images.png';
            document.getElementById('previewImage').alt = post.title || 'Blog preview image';
            document.getElementById('previewContent').innerHTML = post.content || '<p>No content available.</p>';
            document.getElementById('postPreviewModal').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Unable to load preview.');
        });
}

function closePreviewModal() {
    document.getElementById('postPreviewModal').style.display = 'none';
}












function togglePostStatus(id, action) {
    const actionText = action === 'publish' ? 'publish' : 'unpublish';
    if (!confirm(`Are you sure you want to ${actionText} this blog post?`)) return;

    // send AJAX request to toggle status
    fetch('<?php echo URLROOT; ?>/coachdash/togglePostStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, action: action })
    })
    
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // reload to show updated list
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Could not update post status'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Network error while updating post status');
    });
}














function deletePost(id) {
    if(confirm('Are you sure you want to delete this blog post?')) {

        fetch('<?php echo URLROOT; ?>/coachdash/deletePost', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Delete failed');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Network error');
        });
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
const postImageInput = document.getElementById('postImage');
if (postImageInput) {
    postImageInput.addEventListener('change', function() {
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
}


// Preview modal close button
const previewCloseBtn = document.getElementById('previewCloseBtn');
if (previewCloseBtn) {
    previewCloseBtn.addEventListener('click', closePreviewModal);
}

// Click outside to close preview modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('postPreviewModal');
    if (modal && event.target === modal) {
        closePreviewModal();
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

<?php require APPROOT.'/views/coachdash/inc/footer.php'; ?>