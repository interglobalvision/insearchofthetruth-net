/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, document, Site, YT, WP, google */

Site = {
  mobileThreshold: 601,
  scrollToSpeed: 300,
  loadingDelay: 1500, // milliseconds
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
      }

      if ($('.paypal-form-holder').length) {
        Site.Paypal.init();
      }

      if ($('#support-form').length) {
        Site.SupportForm.init();
      }

      if ($('.slick-slide').length > 1) {
        $('.slick-container').slick({
          infinite: true,
          speed: 300,
          slidesToShow: 1,
          centerMode: true,
          variableWidth: true,
          dots: true,
          arrows: false,
          focusOnSelect: true
        });
      }

      $('.js-menu-toggle').on('click', function() {
        $('#mobile-menu').toggleClass('open');
      });

    });

  },

  // Remove laoding class fro body after a certain time
  removeLoading: function() {
    var _this = this;

    setTimeout( function() {
      // Remove loading
      $('body').removeClass('loading');
    }, _this.loadingDelay);
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

  getHashVideoId: function() {
    // Get hash
    var hash = location.hash.split('/');

    // Check that its a portrait
    if(hash[1] === 'portrait') {

      // Get youtube ID
      var videoId = hash[hash.length - 1];

      return videoId;

    }

    return false;
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

    // Init youtube whuen youtube api is ready
    // TODO: subscribe to this event with jQuery

    // If WP_DEBUG turn on controls cuz happy Dev :)
    if(WP.wp_debug === true && WP.isAdmin === true) {
      _this.playerOptions.controls = 1;
    }

    // Get the player container element
    _this.$wrapper = $('#player-container');

    // Get the player container element
    _this.$container = $('#portraits');

    // Bind stuff
    _this.bind();

  },

  setVideosList: function(list) {
    var _this = this;

    if(list) {
      _this.list = list;
    }

  },

  bind: function() {
    var _this = this;

    // Listen for updatedyoutubelist
    $(window).on('updatedyoutubelist', function(event, data) {
      // Update list cache
      _this.setVideosList(data.youtubeIds);
    });

  },

  initYoutube: function() {
    var _this = this;

    // Remove Loading
    Site.removeLoading();

    // Init youtube player inside #player-container
    _this.player = new YT.Player('player-iframe', {
      playerVars: _this.playerOptions,
    });

    $(window).resize(_this.onResize.bind(_this));


    _this.player.addEventListener('onReady', _this.handleVideoReady.bind(this));
    _this.player.addEventListener('onStateChange', _this.handleVideoStateChange.bind(this));

    $(window).resize(_this.onResize.bind(_this));

  },

  handleVideoReady: function(event) {
    Site.Portraits.checkHash();
  },

  handleVideoStateChange: function(event) {
    var _this = this;

    console.log('state change');

    switch(event.data) {
      case -1: // Unstarted
        _this.fadeOut();
        console.log('Unstarted');
        break;
      case 0: // Ended
        _this.fadeOut();
        console.log('Ended 0');
        if(_this.list) {
          console.log('Next');
          _this.nextVideo();
        }
        break;
      case 1: // Playing
        console.log('Playing');
        _this.fadeIn();
        break;
      case 3: // Video ended
        _this.fadeOut();
        console.log('Video ended 3');
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
    _this.closeIframe();
    _this.player.stopVideo();

    location.hash = '';

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
    var currentVideo = Site.getHashVideoId();
    currentVideo = _this.list.indexOf(currentVideo);

    // Check if theres more videos to play
    if (_this.list.length > currentVideo + 1) {

      // Get next video ID
      var nextVideo = _this.list[currentVideo + 1];

      // Play next video
      location.hash = '#!/portrait/' + nextVideo;
    } else {
      _this.closeVideo();
    }
  },

  closeIframe: function() {
    var _this = this;

    $('#player-wrapper').css({
      'height': 0
    });
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

    _this.$grid.shuffle = new window.Shuffle(_this.$grid, {
      itemSelector: '.portrait',
      sizer: '#portrait-sizer',
      filterMode: window.Shuffle.FilterMode.ALL,
    });

    // Workaround to make it compatible with lazysizes
    _this.$grid[0].addEventListener('load', (function(){
      var runs;
      var update = function(){
        _this.$grid.shuffle.layout();
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

    // Bind scroll to top on portrait click
    $('.portrait a').on('click', Site.Player.scrollIn);
  },

  checkHash: function() {
    var _this = this;

    var videoId = Site.getHashVideoId();

    if(videoId) {
      if (Site.Map.$wrapper.hasClass('show-map')) {
        Site.Player.playVideo(videoId);
      } else {
        var list = _this.getFilteredYoutubeIds();

        Site.Player.playVideo(videoId, list);
      }
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
    var filteredElements = $('.shuffle-item--visible').toArray();

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
    var filterArray = _this.getFilterArray();

    _this.$grid.shuffle.filter(filterArray);
  },

  // Return an array of filters
  // Eg. ["subject-ants"]
  //     ["subject-ants", "location-pants"]
  getFilterArray: function() {
    var _this = this;

    var filterArray = [];

    // Iterate thru the filters to get it's values
    _this.$filters.each( function() {
      if(this.value) {

        // Build up selector string
        filterArray.push(this.dataset.filter + '-' + this.value);
      }
    });

    return filterArray;
  },

};

// Declared ASAP
window.onYouTubePlayerAPIReady = Site.Player.initYoutube.bind(Site.Player);

Site.Map = {
  options: {
    mapTypeControl: false,
    fullscreenControl: false,
    streetViewControl: false,
    zoomControl: false,
    // TODO: Set coordinates
    center: {
      lat: -34.397,
      lng: 150.644
    },
    minZoom: 2,
    maxZoom: 15,
    icon: {
      // requires Maps API and set on init()
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
            "visibility": "on"
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

    // Setup options that depend on Maps API library
    _this.options.icon = {
      url: WP.themeUrl + '/dist/img/truth-bubble.png',
      scaledSize: new google.maps.Size(75.5,55),
      anchor: new google.maps.Point(0,55)
    };

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

    // Get values for view toggle scrollTo
    _this.headerHeight = $('#header').outerHeight(true);

    $(window).resize(_this.onResize.bind(_this));

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

        if(WP.wp_debug === true && WP.isAdmin === true) {
          // Add marker
          _this.markers[index] = new google.maps.Marker({
            map: _this.map,
            title: item.name,
            position: {
              lat: parseInt(item.lat),
              lng: parseInt(item.lng),
            }
          });
        }

        // Add slug to the marker
        _this.markers[index].slug = item.slug;

        // Add click listener
        _this.markers[index].addListener('click', function() {
          _this.playPortraitsByLocation(this.slug);
        });

        // Add item location to the bounds list
        bounds.extend(_this.markers[index].getPosition());

      });

      // Make map fit all locations

      //center the map to the geometric center of all markers
      _this.map.setCenter(bounds.getCenter());

      // Listen for bounds change (only once)
      google.maps.event.addListenerOnce(_this.map, 'bounds_changed', function(event) {
        //remove one zoom level to ensure no marker is on the edge.
        _this.map.setZoom(_this.map.getZoom()-1);

        // set a minimum zoom
        // if you got only 1 marker or all markers are too close to each other map will be zoomed too much.
        if (_this.map.getZoom() > 13) {
          _this.map.setZoom(4);
        }

      }.bind(this));

      _this.map.fitBounds(bounds);

    }

    _this.bindToggleMap();

    _this.limitBounds();

    _this.zoomButtons();
  },

  // prevent map dragging into North/South grey areas
  limitBounds: function() {
    var _this = this;

    google.maps.event.addListener(_this.map, 'drag', function() {
      var sLat = _this.map.getBounds().getSouthWest().lat();
      var nLat = _this.map.getBounds().getNorthEast().lat();

      if (sLat < -85 || nLat > 85) {
        // map out of bounds
        // relcenter map within bounds
        _this.map.setOptions({
          center: new google.maps.LatLng(
            _this.mapCenter.lat(), // set Latitude for center of map here
            _this.mapCenter.lng() // set Langitude for center of map here
          )
        });
      } else {
        // map within bounds
        // save map center
        _this.mapCenter = _this.map.getCenter();
      }
    });

  },

  bindToggleMap: function() {
    var _this = this;

    $('.toggle-map').on('click', function(event) {
      event.preventDefault();
      // Toggle map class
      _this.$wrapper.toggleClass('show-map');

      var wrapperOffsetTop = _this.$wrapper.offset().top;

      // Scroll to where portraits or map is
      $('body').scrollTo(wrapperOffsetTop - _this.headerHeight, Site.scrollToSpeed);
    });
  },

  // Play portraits by location
  playPortraitsByLocation: function(mapLocation) {
    var _this = this;

    // Empty list for youtube IDs
    var list = [];

    // Filter selector string
    var filter = '[data-groups*="location-' + mapLocation + '"]';

    // Filter portraits
    var $filteredPortraits = _this.$portraits.filter(filter);

    // Save youtube IDs
    $filteredPortraits.each(function(index, element) {
      list[index] = element.dataset.youtubeId;
    });

    // Play first video, update the list
    // Site.Player.playVideo(list[0], list);

    var firstVideoId = list[0];

    debugger;

    // Play next video
    location.hash = '#!/portrait/' + firstVideoId;

    // If passed, update list
    if(typeof list !== 'undefined') {
      Site.Player.setVideosList(list);
    }

    // Scroll to top
    $('body').scrollTo(0, Site.scrollToSpeed);

  },

  zoomButtons: function() {
    var _this = this;

    $('.map-zoom-button').on('click', function() {
      var zoomChange = parseInt($(this).attr('data-zoom'));
      var currentZoom = _this.map.getZoom();

      _this.map.setZoom(currentZoom + zoomChange);
    });

    google.maps.event.addListener(_this.map, 'zoom_changed', function(){
      var zoomLevel = _this.map.getZoom();

      if (zoomLevel <= _this.options.minZoom) {
        $('.map-zoom-button[data-zoom="-1"]').addClass('disabled');
      } else {
        $('.map-zoom-button[data-zoom="-1"]').removeClass('disabled');
      }

      if (zoomLevel >= _this.options.maxZoom) {
        $('.map-zoom-button[data-zoom="1"]').addClass('disabled');
      } else {
        $('.map-zoom-button[data-zoom="1"]').removeClass('disabled');
      }
    });
  },

  onResize: function() {
    var _this = this;

    // Refresh values for view toggle scrollTo
    _this.headerHeight = $('#header').outerHeight(true);
  }
};

Site.SupportForm = {
  init: function() {
    var _this = this;

    _this.$form = $('#support-form');

    if (_this.$form.length) {
      _this.bind();
    }

  },

  bind: function() {
    var _this = this;

    _this.$form.on({
      'submit': function(e) {
        e.preventDefault();

        var data = $(this).serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});

        _this.submitForm(this, data);

      }
    })
  },

  submitForm: function(form, data) {
    var _this = this;

    // validate and notify
    if (data.email === '' || data.message === '') {
      _this.warnInvalid(form);
    } else {
      _this.unwarnInvalid(form);
      _this.makeRequest(data, form);
      $('#support-submit').attr('disabled', 'disabled');
    }
  },

  warnInvalid: function(form) {
    $(form).addClass('invalid');
  },

  unwarnInvalid: function(form) {
    $(form).removeClass('invalid');
  },

  makeRequest: function(data, form) {
    var _this = this;
    var requestData = {
      'action': 'send_enquiry',
      'nonce': data.nonce,
      'data': data
    };

    $.ajax({
      url: WP.ajaxUrl,
      type: 'post',
      data: requestData,
      success: function(response, status) {
        _this.handleResponse(response, status, form);
      }
    });
  },

  handleResponse: function(response, status, form) {
    var _this = this;

    if (response.type === 'error') {
      _this.handleError(response.error, form);
    } else if (response.type === 'success') {
      $(form).addClass('thanks');
      $('#support-form-email').val('');
    }

    $('#support-submit').attr('disabled', false);

  },

  handleError: function(error, form) {
    var _this = this;

    console.log('Error!', error);

    $(form).addClass('error');
    $('#support-messages .message-error').text(error.message);
  },
};

Site.init();
