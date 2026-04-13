<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $data['title']; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>

  <!-- Blog Title Section -->
  <section class="dashboard-title-section">
    <h1 class="dashboard-main-title">Our <span class="green">Blog</span></h1>
    <p class="dashboard-subtitle">Stay updated with the latest sports news, tips, and insights</p>
  </section>

  <!-- Blog Hero Section -->
  <section class="faq-hero">
    <div class="hero-text">
      <p class="hero-dis">
        <span class="green">SPORTS INSIGHTS</span><br>
        <span class="description">
          Explore articles about stadiums, equipment, coaching, and everything sports!
          <span class="green">Your journey to excellence starts here.</span>
        </span>
      </p>
      <div class="hero-buttons">
        <a href="#latest" class="btn faq-btn">Latest Posts</a>
        <a href="#categories" class="btn faq-btn">Categories</a>
      </div>
    </div>
  </section>

  <!-- Search Section -->
  <section class="search-section">
    <div class="search-container">
      <div class="search-box">
        <input type="text" id="blog-search" placeholder="Search blog posts..." class="search-input">
        <button class="search-btn">🔍</button>
      </div>
      <p class="search-hint">Search for topics like "football", "coaching", "equipment"</p>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="faq-categories" id="categories">
    <div class="categories-container">
      <div class="category-card">
        <div class="category-icon">⚽</div>
        <h3>Football</h3>
        <p>Football stadiums, tips & news</p>
      </div>
      <div class="category-card">
        <div class="category-icon">🏏</div>
        <h3>Cricket</h3>
        <p>Cricket grounds & coaching</p>
      </div>
      <div class="category-card">
        <div class="category-icon">🏀</div>
        <h3>Basketball</h3>
        <p>Basketball courts & training</p>
      </div>
      <div class="category-card">
        <div class="category-icon">🎾</div>
        <h3>Tennis</h3>
        <p>Tennis facilities & equipment</p>
      </div>
      <div class="category-card">
        <div class="category-icon">🏋️</div>
        <h3>Training</h3>
        <p>Fitness & training tips</p>
      </div>
      <div class="category-card">
        <div class="category-icon">📰</div>
        <h3>General</h3>
        <p>Sports news & updates</p>
      </div>
    </div>
  </section>







  <!-- Blog Posts Section -->
  <section class="dashboard-main" id="latest">
    <div class="dashboard-container" style="display: block;">
      <div class="content-section">
        <h2 class="section-heading">Latest Blog Posts</h2>
        
        <div class="blog-posts-grid">
          <?php foreach($data['posts'] as $post): ?>
            <article class="blog-post-card">
              <div class="blog-post-image">
                <img src="<?php echo $post['image']; ?>" 
                     alt="<?php echo $post['title']; ?>"
                     onerror="this.src='<?php echo URLROOT; ?>/images/blog/default/images.png'">
                <div class="blog-post-category"><?php echo $post['category']; ?></div>
              </div>
              
              <div class="blog-post-content">
                <div class="blog-post-meta">
                  <span class="blog-post-author">👤 <?php echo $post['author']; ?></span>
                  <span class="blog-post-date">📅 <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                  <span class="blog-post-views">👁️ <?php echo $post['views']; ?> views</span>
                </div>
                
                <h3 class="blog-post-title"><?php echo $post['title']; ?></h3>
                <p class="blog-post-excerpt"><?php echo $post['excerpt']; ?></p>
                

                <?php if (!empty($post['tags'])):?>
                <div class="blog-post-tags">
                  <?php foreach (explode(',',$post['tags']) as $tag): ?>
                    <span class="blog-tag">
                      #<?php echo trim($tag); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>




                <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post['id']; ?>" class="blog-read-more">
                  Read More →
                </a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>















  <script>
    // Simple search functionality
    document.getElementById('blog-search').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const blogCards = document.querySelectorAll('.blog-post-card');
      
      blogCards.forEach(card => {
        const title = card.querySelector('.blog-post-title').textContent.toLowerCase();
        const excerpt = card.querySelector('.blog-post-excerpt').textContent.toLowerCase();
        const category = card.querySelector('.blog-post-category').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || excerpt.includes(searchTerm) || category.includes(searchTerm)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  </script>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
