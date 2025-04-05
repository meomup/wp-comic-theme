<?php
/**
 * The template for displaying the footer
 *
 * @package Comic_Theme
 */

?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="footer-heading">Về chúng tôi</h3>
                    <p>Trang web đọc truyện tranh với nhiều thể loại hấp dẫn, cập nhật nhanh chóng.</p>
                </div>
                
                <div class="col-md-4">
                    <h3 class="footer-heading">Liên kết nhanh</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'menu_class'     => 'list-unstyled footer-links',
                    ));
                    ?>
                </div>
                
                <div class="col-md-4">
                    <h3 class="footer-heading">Theo dõi chúng tôi</h3>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="site-info">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
    
    <!-- Back to top button -->
    <button id="back-to-top" class="btn btn-primary back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
