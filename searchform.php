<?php
/**
 * Custom search form template
 *
 * @package Comic_Theme
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="input-group">
        <input type="search" class="form-control" placeholder="<?php echo esc_attr_x('Tìm kiếm truyện...', 'placeholder', 'comic-theme'); ?>" value="<?php echo get_search_query(); ?>" name="s" aria-label="<?php echo esc_attr_x('Tìm kiếm:', 'label', 'comic-theme'); ?>">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            <span class="d-none d-md-inline-block ms-1"><?php echo esc_html_x('Tìm', 'submit button', 'comic-theme'); ?></span>
        </button>
    </div>
</form>
