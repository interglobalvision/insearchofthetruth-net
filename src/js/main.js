/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, jQuery, document, Site, Modernizr */

Site = {
  mobileThreshold: 601,
  init: function() {
    var _this = this;

    $(window).resize(function(){
      _this.onResize();
    });

    $(document).ready(function () {

      var mySwiper = new Swiper ('.swiper-container', {
        // Optional parameters
        pagination: '.swiper-pagination',
        loop: true,
        slidesPerView: 'auto',
        loopedSlides: 5,
        spaceBetween: 0,
        paginationClickable: true,
        centeredSlides: true,
        slideToClickedSlide: true,
      });

      if ($('.paypal-form-holder').length) {
        Site.Paypal.init();
      }

    });

  },

  onResize: function() {
    var _this = this;

  },

  fixWidows: function() {
    // utility class mainly for use on headines to avoid widows [single words on a new line]
    $('.js-fix-widows').each(function(){
      var string = $(this).html();
      string = string.replace(/ ([^ ]*)$/,'&nbsp;$1');
      $(this).html(string);
    });
  },
};

Site.Paypal = {
  init: function() {
    var _this = this;

    $form = $('.paypal-form-holder form')

    $form.each(function() {
      _this.styleForm($(this));
    });

  },

  styleBuy: function($buy) {

  },

  styleForm: function($form) {
    $buy = $form.find('input[name="submit"]');
    $select = $form.find('select');

    // Style Buy button
    $buy.attr('type', 'submit');
    $buy.attr('value', 'Buy');
    $buy.wrap('<div class="grid-item item-s-6"></div>');

    // Style select
    $form.prepend($select);
    $form.find('table').remove();
    $select.wrap('<div class="grid-item item-s-6"></div>');
  },

};

Site.init();
