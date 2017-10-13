/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, document, Site, YT, Swiper, WP, google */

Site = {
  mobileThreshold: 601,
  scrollToSpeed: 300,
  init: function() {
    var _this = this;

    $(window).resize(function(){
      _this.onResize();
    });

    $(document).ready(function () {

      if ($('#portraits').length) {
        // Init grid
        Site.Portraits.init();
        // Init player
        Site.Player.init();
        // Init Map
        Site.Map.init();
      }

      if ($('.paypal-form-holder').length) {
        Site.Paypal.init();
      }

      if ($('.swiper-container').length) {
        // Init any galleries
        Site.Gallery.init();
      }

      $('.js-menu-toggle').on('click', function() {
        $('#mobile-menu').toggleClass('open');
      });

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
      _this.instances[index] = new Swiper(element, _this.options);
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
    title: 0,
    showinfo: 0,
    fs: 0, // Fullscreen
  },

  init: function() {
    var _this = this;

    // If WP_DEBUG turn on controls cuz happy Dev :)
    if(WP.wp_debug == true && WP.isAdmin == true) {
      _this.playerOptions.controls = 1;
    }

    // Get the player container element
    _this.$wrapper = $('#player-container');

    // Get the player container element
    _this.$container = $('#portraits');

    // Bind stuff
    _this.bind();

    // Remove loading
    // TODO: This shouldn't go here, we must dev a feat to check Google Maps and
    // Yotube loading, and then remove this class
    $( window ).on( "load", function() {
      $('body').removeClass('loading');
      Site.Portraits.checkHash();
    });

  },

  setVideosList: function(list) {
    var _this = this;

    if(list) {
      _this.list = list;
    }

  },

  bind: function() {
    var _this = this;

    // Init youtube whuen youtube api is ready
    // TODO: subscribe to this event with jQuery
    window.onYouTubePlayerAPIReady = _this.initYoutube.bind(_this);

    // Listen for updatedyoutubelist
    $(window).on('updatedyoutubelist', function(event, data) {
      // Update list cache
      _this.setVideosList(data.youtubeIds);
    });

  },

  initYoutube: function() {
    var _this = this;

    // Init youtube player inside #player-container
    _this.player = new YT.Player('player-iframe', {
      playerVars: _this.playerOptions,
    });

    $(window).resize(_this.onResize.bind(_this));

    _this.player.addEventListener('onStateChange', _this.handleVideoStateChange.bind(this));

  },

  handleVideoStateChange: function(event) {
    var _this = this;

    switch(event.data) {
      case -1: // Unstarted
        _this.fadeOut();
        break;
      case 0: // Ended
        _this.fadeOut();

        if(_this.list) {
          _this.nextVideo();
        }
        break;
      case 1: // Playing
        _this.fadeIn();
        break;
      case 3: // Video ended
        _this.fadeOut();
        break;
      // Check if there's list
    }
  },

  fadeOut: function() {
    var _this = this;

    _this.$container.removeClass('show');
  },

  fadeIn: function() {
    var _this = this;

    _this.$container.addClass('show');
  },

  openVideo: function() {
    var _this = this;

    _this.$container.addClass('video');

    _this.sizeIframe();
  },

  closeVideo: function() {
    var _this = this;

    _this.$container.removeClass('video');

  },

  scrollIn: function() {
    var _this = this;

    // Scroll to top
    $('body').scrollTo(0, Site.scrollToSpeed);
  },

  playVideo: function(videoId, list) {
    var _this = this;

    _this.openVideo();

    // Play video
    _this.player.loadVideoById(videoId);


    // If passed, update list
    if(typeof list !== 'undefined') {
      _this.setVideosList(list);
    }

  },

  nextVideo: function() {
    var _this = this;

    // Get current video ID
    var currentVideo =  _this.player.getVideoData();
    currentVideo = _this.list.indexOf(currentVideo.video_id);

    // Check if theres more videos to play
    if (_this.list.length > currentVideo + 1) {

      // Get next video ID
      var nextVideo = _this.list[currentVideo + 1];

      // Play next video
      _this.playVideo(nextVideo);
    } else {
      _this.closeVideo();
    }
  },

  sizeIframe: function() {
    var _this = this;

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var headerHeight = $('#header').outerHeight();
    var filterHeight = $('#portraits-filters-container').outerHeight();

    var landscapeIframeHeight = windowHeight - (headerHeight + filterHeight);
    var portraitIframeHeight = (windowWidth / 16) * 9;

    if (_this.$container.hasClass('video')) {
      if (windowWidth <= (windowHeight / 9 * 16) && portraitIframeHeight <= landscapeIframeHeight) {
        // Portrait

        $('#player-wrapper').css({
          'height': portraitIframeHeight
        });

        $('#player-iframe').css({
          'width': '100%'
        });

      } else {
        // Landscape

        $('#player-wrapper').css({
          'height': landscapeIframeHeight
        });

        $('#player-iframe').css({
          'width': (landscapeIframeHeight / 9) * 16
        });
      }
    }
  },

  onResize: function() {
    var _this = this;

    _this.sizeIframe();
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

    // Bind hash change aka clicking on a portrait
    $(window).on('hashchange', _this.handleHashChange.bind(_this));

    // Bind arrange complete grid event
    _this.$grid.on('arrangeComplete',_this.handleArrangeComplete.bind(_this));
  },

  checkHash: function() {
    var _this = this;

    // Get hash
    var hash = location.hash.split('/');

    // Check that its a portrait
    if(hash[1] === 'portrait') {

      // Get youtube ID
      var videoId = hash[hash.length - 1];

      var list = _this.getFilteredYoutubeIds();

      Site.Player.playVideo(videoId, list);

      Site.Player.scrollIn();
    }
  },

  handleHashChange: function(event) {
    var _this = this;

    _this.checkHash();

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

Site.Map = {
  options: {
    // TODO: Set coordinates
    center: {
      lat: -34.397,
      lng: 150.644
    },
    zoom: 2,
    icon: {
      url: WP.themeUrl + '/dist/img/truth-bubble.png',
      scaledSize: new google.maps.Size(75.5,55),
      anchor: new google.maps.Point(0,55)
    },
    styles: [
      {
        "elementType": "geometry.fill",
        "stylers": [
          {
            "color": "#ffffff"
          },
          {
            "visibility": "on"
          }
        ]
      },
      {
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "color": "#000000"
          },
          {
            "visibility": "off"
          },
          {
            "weight": 0.5
          }
        ]
      },
      {
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#000000"
          }
        ]
      },
      {
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }
    ],
  },
  dataStyle: {
    fillColor: 'white',
    strokeWeight: 1
  },
  markers: [],
  geoJSONURL: WP.themeUrl + '/dist/static/countries.geo.json',

  init: function() {
    var _this = this;

    // Get grid element
    // TODO: find a way of not repeating this data here and in Site.Portraits
    _this.$grid = $('#portraits-grid');

    // Get portraits
    // TODO: find a way of not repeating this data here and in Site.Portraits
    _this.$portraits = _this.$grid.find('.portrait');

    // Get shared wrapper
    _this.$wrapper = $('#map-portraits-wrapper');

    // Get map container
    _this.$container = $('#map-container');

    // Init google maps
    _this.map = new google.maps.Map(_this.$container[0], _this.options);

    // Load GeoJSON with countries' borders
    _this.map.data.loadGeoJson(_this.geoJSONURL);

    // Set the stroke width, and fill color for each polygon
    _this.map.data.setStyle(_this.dataStyle);

    // Init a bounds object
    var bounds = new google.maps.LatLngBounds();

    // Add a marker for each location
    if (WP.locations.length) {
      $(WP.locations).each(function(index, item) {

        // Add marker
        _this.markers[index] = new google.maps.Marker({
          map: _this.map,
          title: item.name,
          icon: _this.options.icon,
          position: {
            lat: parseInt(item.lat),
            lng: parseInt(item.lng),
          }
        });

        // Add slug to the marker
        _this.markers[index].slug = item.slug;

        // Add click listener
        _this.markers[index].addListener('click', function() {
          _this.playPortraitsByLocation(this.slug);
        });

        // Add item location to the bounds list
        bounds.extend(new google.maps.LatLng(item.lat, item.lng));

      });

      // Make map fit all locations
      _this.map.fitBounds(bounds);

    }

    _this.bind();

  },

  bind: function() {
    var _this = this;

    $('.js-toggle-map').on('click', function(event) {
      event.preventDefault();
      _this.$wrapper.toggleClass('show-map');
    });
  },

  // Play portraits by location
  playPortraitsByLocation: function(location) {
    var _this = this;

    // Empty list for youtube IDs
    var list = [];

    // Filter selector string
    var filter = '[data-filters*=location-' + location + ']';

    // Filter portraits
    var $filteredPortraits = _this.$portraits.filter(filter);

    // Save youtube IDs
    $filteredPortraits.each(function(index, element) {
      list[index] = element.dataset.youtubeId;
    });

    // Play first video, update the list
    Site.Player.playVideo(list[0], list);

    // Scroll to top
    $('body').scrollTo(0, Site.scrollToSpeed);

  },
};

Site.init();
