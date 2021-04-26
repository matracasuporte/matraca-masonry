(function ($) {
  var itemOverlay = $('.matraca-container .grid-item');
  var iconClose = $('#iconClose');
  var matracaOverlay = $('.matraca-overlay');

  var $grid = $('.grid').isotope({
    itemSelector: '.grid-item',
    percentPosition: true,
    masonry: {
      columnWidth: '.grid-sizer',
    },
  });
  // layout Isotope after each image loads
  $grid.imagesLoaded().progress(function () {
    $grid.isotope('layout');
  });

  $('.item-overlay-content').directionalHover({
    overlay: 'item-overlay',
    easing: 'swing',
    speed: 400,
  });

  // itemOverlay.click(function () {
  //   var _this = $(this);
  //   matracaOverlay.toggleClass('show');
  //   matracaOverlay.find('.image-view').attr('src', _this.find('.input-view').val());
  //   $('body').css('overflow', 'hidden');
  // });

  // iconClose.click(function () {
  //   matracaOverlay.toggleClass('show');
  //   $('body').css('overflow', 'auto');
  // });
})(jQuery);
