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

  <!-- Blog Post Header -->
  <section class="dashboard-title-section">
    <h1 class="dashboard-main-title"><?php echo $data['post']['title']; ?></h1>
    <div class="blog-post-meta" style="display: flex; justify-content: center; gap: 20px; margin-top: 15px;">
      <span style="color: #ccc;">👤 <?php echo $data['post']['author']; ?></span>
      <span style="color: #ccc;">📅 <?php echo date('M d, Y', strtotime($data['post']['created_at'])); ?></span>
      <span style="color: #ccc;">👁️ <?php echo $data['post']['views']; ?> views</span>
      <span style="color: #03B200; font-weight: 600;">🏷️ <?php echo $data['post']['category']; ?></span>
    </div>
  </section>

  <!-- Blog Post Content -->
  <section class="dashboard-main">
    <div class="dashboard-container" style="display: block; max-width: 900px; margin: 0 auto;">
      
      <!-- Featured Image -->
      <div class="content-section" style="padding: 0; overflow: hidden;">
        <img src="<?php echo $data['post']['image']; ?>" 
             alt="<?php echo $data['post']['title']; ?>"
             style="width: 100%; height: 400px; object-fit: cover; border-radius: 20px;"
             onerror="this.src='<?php echo URLROOT; ?>/images/blog/default/images.png'">
      </div>

      <!-- Post Content -->
      <div class="content-section" style="margin-top: 30px;">
        <div class="blog-single-content">
          <?php echo nl2br(htmlspecialchars($data['post']['content'])); ?>
        </div>
      </div>

      <!-- Back Button -->
      <div class="content-section" style="margin-top: 30px; text-align: center;">
        <a href="<?php echo URLROOT; ?>/posts" class="btn faq-btn">← Back to Blog</a>
      </div>































      


    </div>
  </section>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
