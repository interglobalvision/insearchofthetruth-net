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

      // Init grid
      if ($('#portraits').length) {
        Site.Portraits.init();
      }

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

    var $form = $('.paypal-form-holder form');

    $form.each(function() {
      _this.styleForm($(this));
    });

    $('.paypal-form-holder').removeClass('u-hidden');

  },

  styleBuy: function($buy) {

  },

  styleForm: function($form) {
    var $buy = $form.find('input[name="submit"]');
    var $select = $form.find('select');

    // Style Buy button
    $buy.attr('type', 'submit');
    $buy.attr('value', 'Buy');
    $buy.wrap('<div class="grid-item item-s-6"></div>');

    // Style select
    if ($select.length) {
      $form.prepend($select);
      $form.find('table').remove();
      $select.wrap('<div class="grid-item item-s-6"></div>');
    }
  },

};

Site.Portraits = {
  init: function() {
    var _this = this;

    // Get filters form element
    _this.$form = $('#portraits-filters');

    // Get filters select elements
    _this.$filters = _this.$form.find('select');

    // Init grid
    _this.initGrid();

    // Bind select filters
    _this.$filters = $('.filter-select').on('change', _this.handleFilterChange.bind(_this));

  },

  initGrid: function() {
    var _this = this;

    // Set grid with isotope
    _this.$grid = $('#portraits-grid').isotope({
      // options
      itemSelector: '.grid-item',
      layoutMode: 'fitRows'
    });

    // Workaround to make it compatible with lazysizes
    _this.$grid[0].addEventListener('load', (function(){
      var runs;
      var update = function(){
        _this.$grid.isotope('layout');
        runs = false;
      };
      return function(){
        if(!runs){
          runs = true;
          setTimeout(update, 33);
        }
      };
    }()), true);

  },

  handleFilterChange: function(event) {
    var _this = this;

    // Get the selector text to be used for filtering
    var filterSelector = _this.getFilterSelector();

    // Filter using the selector on Isotope
    _this.$grid.isotope({
      filter: filterSelector,
    });
  },

  // Return a string selector based on the filter values
  // Eg. '[data-filters*=age-20]'
  //     '[data-filters*=age-20][data-filters*=subject-subject-1]'
  getFilterSelector: function() {
    var _this = this;

    var selector = '';

    // Iterate thru the filters to get it's values
    _this.$filters.each( function(index) {
      if(this.value) {

        // Build up selector string
        selector += '[data-filters*=' + this.dataset.filter + '-' + this.value + ']';
      }
    });

    return selector
  },

};

Site.init();
