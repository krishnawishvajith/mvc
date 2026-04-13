<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FAQ | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>

  <!-- Title Section -->
  <section class="faq-title-section">
    <div class="title-container">
      <h1 class="faq-main-title">Frequently Asked Questions</h1>
      <p class="faq-subtitle">Find answers to common questions about BookMyGround.com</p>
    </div>
  </section>

  <!-- Hero Section -->
  <section class="faq-hero">
    <div class="hero-text">
      <p class="hero-dis">
        <span class="green">GOT QUESTIONS?</span><br>
        <span class="description">
          Find answers to common questions about booking stadiums, renting equipment, 
          coaching sessions, and more. Your sports journey <span class="green">starts with clarity!</span>
        </span>
      </p>
      <div class="hero-buttons">
        <a href="#general" class="btn faq-btn">General Questions</a>
        <a href="#booking" class="btn faq-btn">Booking Help</a>
      </div>
    </div>
  </section>

  <!-- Search Section -->
  <section class="search-section">
    <div class="search-container">
      <div class="search-box">
        <input type="text" id="faq-search" placeholder="Search for questions..." class="search-input">
        <button class="search-btn">🔍</button>
      </div>
      <p class="search-hint">Type keywords like "booking", "payment", "cancellation" to find relevant answers</p>
    </div>
  </section>

  <!-- FAQ Categories -->
  <section class="faq-categories">
    <div class="categories-container">
      <?php foreach ($data['categories'] as $key => $category): ?>
        <div class="category-card" data-category="<?php echo $key; ?>">
          <div class="category-icon"><?php echo $category['icon']; ?></div>
          <h3><?php echo htmlspecialchars($category['name']); ?></h3>
          <p><?php echo htmlspecialchars($category['description']); ?></p>
          <span class="question-count"><?php echo $category['count']; ?> questions</span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FAQ Content -->
  <section class="faq-content">
    <div class="faq-container">
      
      <?php foreach ($data['faq_data'] as $categoryKey => $faqs): ?>
        <div class="faq-section" id="<?php echo $categoryKey; ?>">
          <h2 class="section-heading"><?php echo htmlspecialchars($data['categories'][$categoryKey]['name']); ?></h2>
          
          <?php foreach ($faqs as $faq): ?>
            <div class="faq-item">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                <span class="toggle-icon">+</span>
              </div>
              <div class="faq-answer">
                <p><?php echo htmlspecialchars($faq['answer']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
          
        </div>
      <?php endforeach; ?>

    </div>
  </section>

  <!-- Contact Support Section -->
  <section class="support-section">
    <div class="support-container">
      <div class="support-content">
        <h2>Still Have Questions?</h2>
        <p>Can't find what you're looking for? Our support team is here to help!</p>
        <div class="support-actions">
          <a href="mailto:support@bookmyground.com" class="support-btn email-btn">📧 Email Support</a>
          <a href="tel:+94111111111" class="support-btn phone-btn">📞 Call Us</a>
        </div>
        <div class="support-info">
          <p><strong>Response Time:</strong> Within 24 hours</p>
          <p><strong>Business Hours:</strong> 24/7, all year round (365 days)</p>
        </div>
      </div>
    </div>
  </section>

  <script>
    function toggleFAQ(element) {
      const faqItem = element.parentElement;
      const answer = faqItem.querySelector('.faq-answer');
      const icon = element.querySelector('.toggle-icon');
      
      if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
        icon.textContent = '+';
        faqItem.classList.remove('active');
      } else {
        answer.style.maxHeight = answer.scrollHeight + "px";
        icon.textContent = '−';
        faqItem.classList.add('active');
      }
    }

    // Search functionality
    document.getElementById('faq-search').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const faqItems = document.querySelectorAll('.faq-item');
      
      faqItems.forEach(item => {
        const question = item.querySelector('h3').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });

    // Category filtering
    document.querySelectorAll('.category-card').forEach(card => {
      card.addEventListener('click', function() {
        const category = this.dataset.category;
        const faqSections = document.querySelectorAll('.faq-section');
        
        faqSections.forEach(section => {
          if (section.id === category) {
            section.scrollIntoView({ behavior: 'smooth' });
          }
        });
      });
    });
  </script>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
