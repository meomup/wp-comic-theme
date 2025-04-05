/**
 * Comic Theme JavaScript
 */
(function($) {
    'use strict';
    
    // Document ready function
    $(document).ready(function() {
        // Initialize tooltips
        initTooltips();
        
        // Comic Image Navigation
        initComicNavigation();
        
        // Back to Top button
        initBackToTop();
        
        // Image Preloading for smoother reading
        preloadComicImages();
        
        // Fullscreen reading mode
        initFullscreenMode();
        
        // Page turning with keyboard arrows
        initKeyboardNavigation();
        
        // Hover effects on comic cards
        initHoverEffects();
        
        // Lazy load images
        initLazyLoad();
        
        // Filter and search functionality
        initFilters();
        
        // Theme switcher
        initThemeSwitcher();
        
        // View counter
        initViewCounter();
    });

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        if (tooltipTriggerList.length > 0) {
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }
    
    /**
     * Initialize comic navigation controls
     */
    function initComicNavigation() {
        var $comicContainer = $('.comic-container');
        
        if ($comicContainer.length) {
            var $comicImages = $comicContainer.find('.comic-image');
            var totalPages = $comicImages.length;
            var currentPage = 1;
            
            // Update pagination text
            $('.total-pages').text(totalPages);
            
            // Show only first image initially
            $comicImages.hide();
            $comicImages.first().show();
            
            // Next page button
            $('.comic-next').on('click', function() {
                if (currentPage < totalPages) {
                    $comicImages.eq(currentPage - 1).hide();
                    $comicImages.eq(currentPage).show();
                    currentPage++;
                    updateNavigationState();
                    
                    // Scroll to top of image
                    scrollToElement($comicContainer);
                    
                    // Save progress
                    saveReadingProgress(currentPage);
                }
            });
            
            // Previous page button
            $('.comic-prev').on('click', function() {
                if (currentPage > 1) {
                    $comicImages.eq(currentPage - 1).hide();
                    $comicImages.eq(currentPage - 2).show();
                    currentPage--;
                    updateNavigationState();
                    
                    // Scroll to top of image
                    scrollToElement($comicContainer);
                    
                    // Save progress
                    saveReadingProgress(currentPage);
                }
            });
            
            // Update navigation buttons state
            function updateNavigationState() {
                $('.current-page').text(currentPage);
                
                if (currentPage === 1) {
                    $('.comic-prev').prop('disabled', true);
                } else {
                    $('.comic-prev').prop('disabled', false);
                }
                
                if (currentPage === totalPages) {
                    $('.comic-next').prop('disabled', true);
                } else {
                    $('.comic-next').prop('disabled', false);
                }
            }
            
            // Restore reading progress if saved
            restoreReadingProgress();
        }
    }
    
    /**
     * Initialize back to top button
     */
    function initBackToTop() {
        var $backToTop = $('#back-to-top');
        
        if ($backToTop.length) {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.fadeIn();
                } else {
                    $backToTop.fadeOut();
                }
            });
            
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        }
    }
    
    /**
     * Preload comic images for smoother reading
     */
    function preloadComicImages() {
        var $comicContainer = $('.comic-container');
        
        if ($comicContainer.length) {
            var $comicImages = $comicContainer.find('.comic-image img');
            
            // Create array of image sources
            var imageSources = [];
            $comicImages.each(function() {
                imageSources.push($(this).attr('src'));
            });
            
            // Preload images
            $(imageSources).each(function() {
                var img = new Image();
                img.src = this;
            });
        }
    }
    
    /**
     * Initialize fullscreen reading mode
     */
    function initFullscreenMode() {
        // Create fullscreen button
        var $comicContainer = $('.comic-container');
        
        if ($comicContainer.length) {
            var $fullscreenBtn = $('<button>', {
                'class': 'btn btn-sm btn-secondary fullscreen-btn',
                'html': '<i class="fas fa-expand"></i>',
                'title': 'Chế độ toàn màn hình'
            });
            
            // Add tooltip
            $fullscreenBtn.attr('data-bs-toggle', 'tooltip');
            $fullscreenBtn.attr('data-bs-placement', 'top');
            
            // Add button to comic navigation
            $('.comic-navigation').prepend($fullscreenBtn);
            
            // Initialize tooltip
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                new bootstrap.Tooltip($fullscreenBtn[0]);
            }
            
            // Toggle fullscreen
            $fullscreenBtn.on('click', function() {
                toggleFullscreen($comicContainer[0]);
                
                // Update button icon
                var isFullscreen = document.fullscreenElement !== null;
                $(this).html(isFullscreen ? '<i class="fas fa-compress"></i>' : '<i class="fas fa-expand"></i>');
            });
        }
    }
    
    /**
     * Toggle fullscreen mode
     */
    function toggleFullscreen(element) {
        if (!document.fullscreenElement) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) { // Firefox
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) { // IE/Edge
                element.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
    
    /**
     * Initialize keyboard navigation
     */
    function initKeyboardNavigation() {
        var $comicContainer = $('.comic-container');
        
        if ($comicContainer.length) {
            $(document).keydown(function(e) {
                // Right arrow key
                if (e.keyCode === 39) {
                    $('.comic-next').trigger('click');
                }
                
                // Left arrow key
                if (e.keyCode === 37) {
                    $('.comic-prev').trigger('click');
                }
            });
        }
    }
    
    /**
     * Initialize hover effects on comic cards
     */
    function initHoverEffects() {
        $('.comic-card').each(function() {
            $(this).hover(
                function() {
                    $(this).addClass('shadow-lg');
                    $(this).find('.comic-cover').css('transform', 'scale(1.05)');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                    $(this).find('.comic-cover').css('transform', 'scale(1)');
                }
            );
        });
    }
    
    /**
     * Save reading progress
     */
    function saveReadingProgress(currentPage) {
        if (typeof(Storage) !== "undefined") {
            var postId = $('article').attr('id');
            
            // Check if we have a post ID
            if (postId) {
                postId = postId.replace('post-', '');
                localStorage.setItem('comic_progress_' + postId, currentPage);
            }
        }
    }
    
    /**
     * Restore reading progress
     */
    function restoreReadingProgress() {
        if (typeof(Storage) !== "undefined") {
            var $comicContainer = $('.comic-container');
            
            if ($comicContainer.length) {
                var $comicImages = $comicContainer.find('.comic-image');
                var postId = $('article').attr('id');
                
                // Check if we have a post ID
                if (postId) {
                    postId = postId.replace('post-', '');
                    var savedPage = localStorage.getItem('comic_progress_' + postId);
                    
                    if (savedPage !== null) {
                        var page = parseInt(savedPage);
                        var totalPages = $comicImages.length;
                        
                        // Validate page number
                        if (page > 0 && page <= totalPages) {
                            // Ask user if they want to continue from saved position
                            if (page > 1 && confirm('Bạn muốn tiếp tục đọc từ trang ' + page + '?')) {
                                // Show the saved page
                                $comicImages.hide();
                                $comicImages.eq(page - 1).show();
                                
                                // Update current page and navigation
                                $('.current-page').text(page);
                                
                                if (page === 1) {
                                    $('.comic-prev').prop('disabled', true);
                                } else {
                                    $('.comic-prev').prop('disabled', false);
                                }
                                
                                if (page === totalPages) {
                                    $('.comic-next').prop('disabled', true);
                                } else {
                                    $('.comic-next').prop('disabled', false);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Initialize lazy loading for images
     */
    function initLazyLoad() {
        // Only run if browser supports Intersection Observer
        if ('IntersectionObserver' in window) {
            var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
            
            if (lazyImages.length > 0) {
                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            if (lazyImage.dataset.src) {
                                lazyImage.src = lazyImage.dataset.src;
                                if (lazyImage.dataset.srcset) {
                                    lazyImage.srcset = lazyImage.dataset.srcset;
                                }
                                lazyImage.classList.remove("lazy");
                                lazyImageObserver.unobserve(lazyImage);
                            }
                        }
                    });
                });
                
                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            }
        } else {
            // Fallback for browsers that don't support Intersection Observer
            // Load all images at once
            var lazyImages = document.querySelectorAll('img.lazy');
            if (lazyImages.length > 0) {
                lazyImages.forEach(function(img) {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                        img.classList.remove('lazy');
                    }
                });
            }
        }
    }
    
    /**
     * Initialize filter and search functionality
     */
    function initFilters() {
        // Comic filter by genre
        $('.genre-filter').on('click', function(e) {
            e.preventDefault();
            var genre = $(this).data('genre');
            
            if (genre === 'all') {
                $('.comic-card').show();
            } else {
                $('.comic-card').hide();
                $('.comic-card[data-genres*="' + genre + '"]').show();
            }
            
            // Update active class
            $('.genre-filter').removeClass('active');
            $(this).addClass('active');
        });
        
        // Search within page
        $('#comic-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.comic-card').filter(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });
        });
    }
    
    /**
     * Initialize theme switcher
     */
    function initThemeSwitcher() {
        const themeToggle = document.getElementById('theme-toggle');
        
        if (themeToggle) {
            const currentTheme = localStorage.getItem('theme') || 'light';
            
            // Set initial theme from localStorage
            if (currentTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                themeToggle.checked = true;
            }
            
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    
                    // Dispatch event for plugins that might need to know about theme change
                    document.dispatchEvent(new CustomEvent('themeChanged', {
                        detail: { theme: 'dark' }
                    }));
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    
                    // Dispatch event for plugins that might need to know about theme change
                    document.dispatchEvent(new CustomEvent('themeChanged', {
                        detail: { theme: 'light' }
                    }));
                }
            });
        }
    }
    
    /**
     * Scroll to element
     */
    function scrollToElement($element) {
        if ($element.length) {
            $('html, body').animate({
                scrollTop: $element.offset().top - 100
            }, 500);
        }
    }
    
    /**
     * AJAX function for view counting
     */
    function initViewCounter() {
        if ($('.comic-chapter').length) {
            var postId = $('article').attr('id');
            
            if (postId) {
                postId = postId.replace('post-', '');
                
                if (typeof comic_ajax !== 'undefined') {
                    $.ajax({
                        url: comic_ajax.ajax_url,
                        type: 'post',
                        data: {
                            action: 'increment_comic_views',
                            post_id: postId,
                            nonce: comic_ajax.nonce
                        }
                    });
                }
            }
        }
    }
    
    // Load more comics button handler
    $(document).on('click', '.load-more-comics', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $grid = $('.comics-grid');
        var page = parseInt($button.data('page'));
        var maxPages = parseInt($button.data('max-pages'));
        
        $button.html('<i class="fas fa-spinner fa-spin"></i> Đang tải...');
        
        if (typeof comic_ajax !== 'undefined') {
            $.ajax({
                url: comic_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'load_more_comics',
                    page: page,
                    nonce: comic_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $grid.append(response.data.html);
                        
                        // Update page number
                        $button.data('page', page + 1);
                        
                        // Hide button if no more pages
                        if (page + 1 > maxPages) {
                            $button.hide();
                        } else {
                            $button.html('Tải thêm truyện');
                        }
                        
                        // Reinitialize hover effects
                        initHoverEffects();
                    }
                }
            });
        }
    });
    
})(jQuery);
