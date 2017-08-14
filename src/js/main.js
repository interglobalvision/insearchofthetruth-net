/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, jQuery, document, Site, YT */

Site = {
  mobileThreshold: 601,
  init: function() {
    var _this = this;

    $(window).resize(function(){
      _this.onResize();
    });

    $(document).ready(function () {

      if ($('#portraits').length) {
        // Init player
        Site.Player.init();
        // Init grid
        Site.Portraits.init();
      }

      if ($('.paypal-form-holder').length) {
        Site.Paypal.init();
      }

      if ($('.swiper-container').length) {
        // Init any galleries
        Site.Gallery.init();
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

Site.Gallery = {
  instances: [],
  options: {
    pagination: '.swiper-pagination',
    loop: true,
    slidesPerView: 'auto',
    loopedSlides: 5,
    spaceBetween: 0,
    paginationClickable: true,
    centeredSlides: true,
    onTap: function(swiper) {
      swiper.slideNext();
    },
  },

  init: function() {
    var _this = this;

    // Find and loop all swiper containers
    $('.swiper-container').each(function(index, element) {
      // Create and save instance on instances array
      _this.instances[index] = new Swiper (element, _this.options);
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

Site.Player = {
  playerOptions: {
    // https://developers.google.com/youtube/player_parameters?playerVersion=HTML5#Parameters
    controls: 0,
    modestbranding: 1,
    rel: 0,
  },

  init: function() {
    var _this = this;

    // Get the player container element
    _this.$playerContainer = $('#player-iframe');

    // Bind stuff
    _this.bind();

  },

  bind: function() {
    var _this = this;

    // Init youtube whuen youtube api is ready
    // TODO: subscribe to this event with jQuery
    window.onYouTubePlayerAPIReady = _this.initYoutube.bind(_this);

    // Listen for updatedyoutubelist
    $(window).on('updatedyoutubelist', function(event, data) {

      // Update list cache
      _this.list = data.youtubeIds;
    });
  },

  initYoutube: function() {
    var _this = this;

    // Init youtube player inside #player-container
    _this.$player = new YT.Player('player-iframe', {playerVars: _this.playerOptions});

  },

  playVideo: function(videoId, list) {
    var _this = this;

    // Play video
    _this.$player.loadVideoById(videoId);

    // Update list cache
    if(list) {
      _this.list = list;
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

    // Get grid element
    _this.$grid = $('#portraits-grid');

    // Get portraits
    _this.$portraits = _this.$grid.find('.portrait');

    // Init grid
    _this.initGrid();

    // Bind stuff
    _this.bind();

  },

  initGrid: function() {
    var _this = this;

    // Set grid with isotope
    _this.$grid.isotope({
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

  bind: function() {
    var _this = this;

    // Bind select filters change
    _this.$filters = $('.filter-select').on('change', _this.handleFilterChange.bind(_this));

    // Bind portrait clicks
    _this.$portraits.on('click', function() {
      _this.playVideo(this.dataset.youtubeId);
    });

    // Bind arrange complete grid event
    _this.$grid.on('arrangeComplete',_this.handleArrangeComplete.bind(_this));
  },

  playVideo: function(videoId) {
    var _this = this;

    Site.Player.playVideo(videoId);

    // TODO: scroll to video
  },

  // Get a list of youtube ids (filtered in the list)
  getFilteredYoutubeIds: function() {
    var _this = this;

    // Get the filtered elements
    var filteredElements = _this.$grid.isotope('getFilteredItemElements');

    // Get youtubeIds
    var youtubeIds = filteredElements.map( function(val) {
      return val.dataset.youtubeId;
    });

    return youtubeIds;
  },

  handleArrangeComplete: function(event) {
    var _this = this;

    var youtubeIds = _this.getFilteredYoutubeIds();

    // Trigger an event with the list of youtube Ids
    $(window).trigger('updatedyoutubelist', {
      youtubeIds: youtubeIds,
    });

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

    return selector;
  },

};

Site.init();
