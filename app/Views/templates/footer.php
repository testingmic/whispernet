<footer>
    <div class="footer-content">
      <div class="footer-links">
        <div class="footer-section">
          <h4>Company</h4>
          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Press</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Support</h4>
          <ul>
            <li><a href="#">Help Center</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">API Documentation</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Legal</h4>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Cookie Policy</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-social">
        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> XE Currency Converter. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- jQuery (required for Select2) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.currency-select').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
      });

      // Mobile menu toggle
      $('.mobile-menu-btn').click(function() {
        $('.nav-content').toggleClass('active');
        $(this).toggleClass('active');
      });
    });
    var baseUrl = '<?php echo $baseUrl; ?>';
  </script>
  <script src="<?php echo $baseUrl; ?>/js/app.js"></script>
  <script src="<?php echo $baseUrl; ?>/js/rates.js"></script>
</body>
</html> 