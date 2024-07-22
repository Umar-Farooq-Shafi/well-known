(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["events"],{

/***/ "./assets/js/events.js":
/*!*****************************!*\
  !*** ./assets/js/events.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function($, global) {/* harmony import */ var core_js_modules_es_parse_float_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.parse-float.js */ "./node_modules/core-js/modules/es.parse-float.js");
/* harmony import */ var core_js_modules_es_parse_float_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_parse_float_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.map.js */ "./node_modules/core-js/modules/es.array.map.js");
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_web_timers_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/web.timers.js */ "./node_modules/core-js/modules/web.timers.js");
/* harmony import */ var core_js_modules_web_timers_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_timers_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_string_link_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.string.link.js */ "./node_modules/core-js/modules/es.string.link.js");
/* harmony import */ var core_js_modules_es_string_link_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_link_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_array_find_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.array.find.js */ "./node_modules/core-js/modules/es.array.find.js");
/* harmony import */ var core_js_modules_es_array_find_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_find_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _fullcalendar_core__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @fullcalendar/core */ "./node_modules/@fullcalendar/core/main.esm.js");
/* harmony import */ var _fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @fullcalendar/daygrid */ "./node_modules/@fullcalendar/daygrid/main.esm.js");
/* harmony import */ var _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @fullcalendar/timegrid */ "./node_modules/@fullcalendar/timegrid/main.esm.js");
/* harmony import */ var marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! marker-clusterer/marker-clusterer.js */ "./node_modules/marker-clusterer/marker-clusterer.js");
/* harmony import */ var marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! handlebars/dist/handlebars.min.js */ "./node_modules/handlebars/dist/handlebars.min.js");
/* harmony import */ var handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_11__);








// css & scss
__webpack_require__(/*! @fullcalendar/core/main.min.css */ "./node_modules/@fullcalendar/core/main.min.css");

__webpack_require__(/*! @fullcalendar/daygrid/main.min.css */ "./node_modules/@fullcalendar/daygrid/main.min.css");

__webpack_require__(/*! @fullcalendar/timegrid/main.min.css */ "./node_modules/@fullcalendar/timegrid/main.min.css"); // js






__webpack_require__(/*! @fullcalendar/core/locales-all.min.js */ "./node_modules/@fullcalendar/core/locales-all.min.js");


 // snazzy-info-window must be loader after Google Maps is completely loader

if ($('#events-map').length && $('#events-map').data('events').length) {
  var initMap = function initMap() {
    $.getScript('https://cdn.jsdelivr.net/npm/snazzy-info-window@1.1.1/dist/snazzy-info-window.min.js', function () {
      drawMap($('#events-map').data('events'));
    });
  };

  global.initMap = initMap;
} // Initializes Google Maps


function drawMap(events) {
  var map = new google.maps.Map(document.getElementById('events-map'), {
    zoom: 7,
    center: {
      lat: parseFloat(events[0].lat),
      lng: parseFloat(events[0].lng)
    }
  });
  var markers = events.map(function (event, i) {
    var marker = new google.maps.Marker({
      position: {
        lat: parseFloat(event.lat),
        lng: parseFloat(event.lng)
      },
      icon: $('#events-map').data('pin-path')
    });
    var template = handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_11___default.a.compile($('#event-info-box').html());
    var info = null;
    var closeDelayed = false;

    var closeDelayHandler = function closeDelayHandler() {
      $(info.getWrapper()).removeClass('active');
      setTimeout(function () {
        closeDelayed = true;
        info.close();
      }, 300);
    }; // Add a Snazzy Info Window to the marker


    info = new SnazzyInfoWindow({
      marker: marker,
      wrapperClass: 'custom-window',
      offset: {
        top: '-72px'
      },
      edgeOffset: {
        top: 50,
        right: 60,
        bottom: 50
      },
      border: false,
      closeButtonMarkup: '<button type="button" class="custom-close">&#215;</button>',
      content: template({
        title: event.name,
        link: event.link,
        bgImg: event.image,
        body: '<p class="text-muted"><small>' + event.address + '</small></p>' + '<p class="text-muted"><small>' + event.date + '</small></p>' + '<p class="text-muted"><small>' + ($('body').data('currency-position') == 'left' ? $('body').data('currency-symbol') : '') + event.price + ($('body').data('currency-position') == 'right' ? $('body').data('currency-symbol') : '') + '</small></p>'
      }),
      callbacks: {
        open: function open() {
          $(this.getWrapper()).addClass('open');
        },
        afterOpen: function afterOpen() {
          var wrapper = $(this.getWrapper());
          wrapper.addClass('active');
          wrapper.find('.custom-close').on('click', closeDelayHandler);
        },
        beforeClose: function beforeClose() {
          if (!closeDelayed) {
            closeDelayHandler();
            return false;
          }

          return true;
        },
        afterClose: function afterClose() {
          var wrapper = $(this.getWrapper());
          wrapper.find('.custom-close').off();
          wrapper.removeClass('open');
          closeDelayed = false;
        }
      }
    });
    return marker;
  });
  new MarkerClusterer(map, markers, {
    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
  });
}

$(document).ready(function () {
  // Search filters
  $('#filter-local-only').on('change', function () {
    if ($(this).is(':checked')) {
      $('#user-country').show();
      $('#filter-country-container').hide();
      $('#country').select2('val', 'all');
      $('.filter-online-container').hide();
      $('#filter-online-only').prop("checked", false);
      $('#filter-location-container').hide();
      $('#location').val('');
    } else {
      $('#user-country').hide();
      $('#filter-country-container').show();
      $('.filter-online-container').show();
    }
  });

  if (getURLParameter('localonly') == "1") {
    $('#filter-local-only').attr('checked', true);
    $('#filter-local-only').trigger('change');
  }

  if (getURLParameter('category')) {
    $('select#category option').each(function () {
      if ($(this).val() == getURLParameter('category')) {
        $(this).prop('selected', true).trigger('change');
      }
    });
  }

  $('#filter-location-container').hide();

  if (getURLParameter('country')) {
    $('#country option').each(function () {
      if ($(this).val() == getURLParameter('country')) {
        $(this).prop('selected', true).trigger('change');
        $('#filter-location-container').show();
      }
    });
    $('#country').trigger('change');
  }

  $('#country').change(function () {
    if ($(this).val() != "all") {
      $('#filter-location-container').show();
    } else {
      $('#filter-location-container').hide();
      $('#location').val('');
    }
  });

  if (getURLParameter('audience')) {
    $('#' + getURLParameter('audience')).attr('checked', true);
    $('#' + getURLParameter('audience')).closest('label').addClass('active');
  }

  if (getURLParameter('startdate')) {
    if (getURLParameter('startdate') != "today" && getURLParameter('startdate') != "tomorrow" && getURLParameter('startdate') != "thisweekend" && getURLParameter('startdate') != "thisweek" && getURLParameter('startdate') != "nextweek" && getURLParameter('startdate') != "thismonth" && getURLParameter('startdate') != "nextmonth") {
      $('#date-pickadate').val(getURLParameter('startdate'));
      $('#date-pickadate').attr('checked', true);
    } else {
      $('input[name="startdate"][value="' + getURLParameter('startdate') + '"]').attr('checked', true);
    }
  }

  $('#free-events-only').on('change', function () {
    if ($(this).is(':checked')) {
      $('.events-price-range-slider-wrapper').hide();
      $('#pricemin').val('0');
      $('#pricemin').trigger('change');
      $('#pricemax').val('10000');
      $('#pricemax').trigger('change');
    } else {
      $('.events-price-range-slider-wrapper').show();
    }
  });

  if (getURLParameter('freeonly') == "1") {
    $('#free-events-only').attr('checked', true);
    $('#free-events-only').trigger('change');
  }

  $('#filter-online-only').on('change', function () {
    if ($(this).is(':checked')) {
      $('.location-based-filters').hide();
      $('#filter-local-only').prop("checked", false);
      $('#country').select2('val', 'all');
      $('#filter-location-container').hide();
      $('#location').val('');
    } else {
      $('.location-based-filters').show();
    }
  });

  if (getURLParameter('onlineonly') == "1") {
    $('#filter-online-only').attr('checked', true);
    $('#filter-online-only').trigger('change');
  } // Initializes events calendar


  if ($('#events-calendar').length) {
    var calendarEl = document.getElementById('events-calendar');
    var calendar = new _fullcalendar_core__WEBPACK_IMPORTED_MODULE_7__["Calendar"](calendarEl, {
      plugins: ['interaction', _fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_8__["default"], _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_9__["default"]],
      defaultView: 'dayGridMonth',
      defaultDate: $('#events-calendar').data('default-date'),
      height: "auto",
      locale: $('html').attr('lang'),

      /*customButtons: {
       alleventscalendarlink: {
       text: 'All events calendar',
       click: function () {
       }
       }
       },*/
      header: {
        left: 'prev,next today alleventscalendarlink',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: $('#events-calendar').data('events')
    });
    calendar.render();
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ })

},[["./assets/js/events.js","runtime","vendors~app~app.ar~app.es~app.fr~event~events~installer~organizerprofile","vendors~app~event~events~installer~organizerprofile","vendors~app~events~installer~organizerprofile","vendors~app~events~organizerprofile","vendors~events~organizerprofile","vendors~events"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZXZlbnRzLmpzIl0sIm5hbWVzIjpbInJlcXVpcmUiLCIkIiwibGVuZ3RoIiwiZGF0YSIsImluaXRNYXAiLCJnZXRTY3JpcHQiLCJkcmF3TWFwIiwiZ2xvYmFsIiwiZXZlbnRzIiwibWFwIiwiZ29vZ2xlIiwibWFwcyIsIk1hcCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJ6b29tIiwiY2VudGVyIiwibGF0IiwicGFyc2VGbG9hdCIsImxuZyIsIm1hcmtlcnMiLCJldmVudCIsImkiLCJtYXJrZXIiLCJNYXJrZXIiLCJwb3NpdGlvbiIsImljb24iLCJ0ZW1wbGF0ZSIsIkhhbmRsZWJhcnMiLCJjb21waWxlIiwiaHRtbCIsImluZm8iLCJjbG9zZURlbGF5ZWQiLCJjbG9zZURlbGF5SGFuZGxlciIsImdldFdyYXBwZXIiLCJyZW1vdmVDbGFzcyIsInNldFRpbWVvdXQiLCJjbG9zZSIsIlNuYXp6eUluZm9XaW5kb3ciLCJ3cmFwcGVyQ2xhc3MiLCJvZmZzZXQiLCJ0b3AiLCJlZGdlT2Zmc2V0IiwicmlnaHQiLCJib3R0b20iLCJib3JkZXIiLCJjbG9zZUJ1dHRvbk1hcmt1cCIsImNvbnRlbnQiLCJ0aXRsZSIsIm5hbWUiLCJsaW5rIiwiYmdJbWciLCJpbWFnZSIsImJvZHkiLCJhZGRyZXNzIiwiZGF0ZSIsInByaWNlIiwiY2FsbGJhY2tzIiwib3BlbiIsImFkZENsYXNzIiwiYWZ0ZXJPcGVuIiwid3JhcHBlciIsImZpbmQiLCJvbiIsImJlZm9yZUNsb3NlIiwiYWZ0ZXJDbG9zZSIsIm9mZiIsIk1hcmtlckNsdXN0ZXJlciIsImltYWdlUGF0aCIsInJlYWR5IiwiaXMiLCJzaG93IiwiaGlkZSIsInNlbGVjdDIiLCJwcm9wIiwidmFsIiwiZ2V0VVJMUGFyYW1ldGVyIiwiYXR0ciIsInRyaWdnZXIiLCJlYWNoIiwiY2hhbmdlIiwiY2xvc2VzdCIsImNhbGVuZGFyRWwiLCJjYWxlbmRhciIsIkNhbGVuZGFyIiwicGx1Z2lucyIsImRheUdyaWRQbHVnaW4iLCJ0aW1lR3JpZFBsdWdpbiIsImRlZmF1bHRWaWV3IiwiZGVmYXVsdERhdGUiLCJoZWlnaHQiLCJsb2NhbGUiLCJoZWFkZXIiLCJsZWZ0IiwicmVuZGVyIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBQUE7QUFFQUEsbUJBQU8sQ0FBQyx1RkFBRCxDQUFQOztBQUNBQSxtQkFBTyxDQUFDLDZGQUFELENBQVA7O0FBQ0FBLG1CQUFPLENBQUMsK0ZBQUQsQ0FBUCxDLENBRUE7OztBQUVBO0FBQ0E7QUFDQTs7QUFDQUEsbUJBQU8sQ0FBQyxtR0FBRCxDQUFQOztBQUVBO0NBR0E7O0FBQ0EsSUFBSUMsQ0FBQyxDQUFDLGFBQUQsQ0FBRCxDQUFpQkMsTUFBakIsSUFBMkJELENBQUMsQ0FBQyxhQUFELENBQUQsQ0FBaUJFLElBQWpCLENBQXNCLFFBQXRCLEVBQWdDRCxNQUEvRCxFQUF1RTtFQUFBLElBQzFERSxPQUQwRCxHQUNuRSxTQUFTQSxPQUFULEdBQW1CO0lBQ2ZILENBQUMsQ0FBQ0ksU0FBRixDQUFZLHNGQUFaLEVBQW9HLFlBQVk7TUFDNUdDLE9BQU8sQ0FBQ0wsQ0FBQyxDQUFDLGFBQUQsQ0FBRCxDQUFpQkUsSUFBakIsQ0FBc0IsUUFBdEIsQ0FBRCxDQUFQO0lBQ0gsQ0FGRDtFQUdILENBTGtFOztFQU1uRUksTUFBTSxDQUFDSCxPQUFQLEdBQWlCQSxPQUFqQjtBQUNILEMsQ0FFRDs7O0FBRUEsU0FBU0UsT0FBVCxDQUFpQkUsTUFBakIsRUFBeUI7RUFFckIsSUFBSUMsR0FBRyxHQUFHLElBQUlDLE1BQU0sQ0FBQ0MsSUFBUCxDQUFZQyxHQUFoQixDQUFvQkMsUUFBUSxDQUFDQyxjQUFULENBQXdCLFlBQXhCLENBQXBCLEVBQTJEO0lBQ2pFQyxJQUFJLEVBQUUsQ0FEMkQ7SUFFakVDLE1BQU0sRUFBRTtNQUNKQyxHQUFHLEVBQUVDLFVBQVUsQ0FBQ1YsTUFBTSxDQUFDLENBQUQsQ0FBTixDQUFVUyxHQUFY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QWlHMUI7OztFQUNBLElBQUk3RSxDQUFDLENBQUMsa0JBQUQsQ0FBRCxDQUFzQkMsTUFBMUIsRUFBa0M7SUFDOUIsSUFBSWdGLFVBQVUsR0FBR3JFLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixpQkFBeEIsQ0FBakI7SUFFQSxJQUFJcUUsUUFBUSxHQUFHLElBQUlDLDJEQUFKLENBQWFGLFVBQWIsRUFBeUI7TUFDcENHLE9BQU8sRUFBRSxDQUFDLGFBQUQsRUFBZ0JDLDZEQUFoQixFQUErQkMsOERBQS9CLENBRDJCO01BRXBDQyxXQUFXLEVBQUUsY0FGdUI7TUFHcENDLFdBQVcsRUFBRXhGLENBQUMsQ0FBQyxrQkFBRCxDQUFELENBQXNCRSxJQUF0QixDQUEyQixjQUEzQixDQUh1QjtNQUlwQ3VGLE1BQU0sRUFBRSxNQUo0QjtNQUtwQ0MsTUFBTSxFQUFFMUYsQ0FBQyxDQUFDLE1BQUQsQ0FBRCxDQUFVNEUsSUFBVixDQUFlLE1BQWYsQ0FMNEI7O01BT3BDO0FBQ1o7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO01BQ1llLE1BQU0sRUFBRTtRQUNKQyxJQUFJLEVBQUUsdUNBREY7UUFFSjdFLE1BQU0sRUFBRSxPQUZKO1FBR0oyQixLQUFLLEVBQUU7TUFISCxDQWQ0QjtNQW1CcENuQyxNQUFNLEVBQUVQLENBQUMsQ0FBQyxrQkFBRCxDQUFELENBQXNCRSxJQUF0QixDQUEyQixRQUEzQjtJQW5CNEIsQ0FBekIsQ0FBZjtJQXNCQWdGLFFBQVEsQ0FBQ1csTUFBVDtFQUVIO0FBRUosQ0EvSEQsRSIsImZpbGUiOiJldmVudHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLyBjc3MgJiBzY3NzXG5cbnJlcXVpcmUoJ0BmdWxsY2FsZW5kYXIvY29yZS9tYWluLm1pbi5jc3MnKTtcbnJlcXVpcmUoJ0BmdWxsY2FsZW5kYXIvZGF5Z3JpZC9tYWluLm1pbi5jc3MnKTtcbnJlcXVpcmUoJ0BmdWxsY2FsZW5kYXIvdGltZWdyaWQvbWFpbi5taW4uY3NzJyk7XG5cbi8vIGpzXG5cbmltcG9ydCB7IENhbGVuZGFyIH0gZnJvbSAnQGZ1bGxjYWxlbmRhci9jb3JlJztcbmltcG9ydCBkYXlHcmlkUGx1Z2luIGZyb20gJ0BmdWxsY2FsZW5kYXIvZGF5Z3JpZCc7XG5pbXBvcnQgdGltZUdyaWRQbHVnaW4gZnJvbSAnQGZ1bGxjYWxlbmRhci90aW1lZ3JpZCc7XG5yZXF1aXJlKCdAZnVsbGNhbGVuZGFyL2NvcmUvbG9jYWxlcy1hbGwubWluLmpzJyk7XG5cbmltcG9ydCAnbWFya2VyLWNsdXN0ZXJlci9tYXJrZXItY2x1c3RlcmVyLmpzJztcbmltcG9ydCBIYW5kbGViYXJzIGZyb20gJ2hhbmRsZWJhcnMvZGlzdC9oYW5kbGViYXJzLm1pbi5qcyc7XG5cbi8vIHNuYXp6eS1pbmZvLXdpbmRvdyBtdXN0IGJlIGxvYWRlciBhZnRlciBHb29nbGUgTWFwcyBpcyBjb21wbGV0ZWx5IGxvYWRlclxuaWYgKCQoJyNldmVudHMtbWFwJykubGVuZ3RoICYmICQoJyNldmVudHMtbWFwJykuZGF0YSgnZXZlbnRzJykubGVuZ3RoKSB7XG4gICAgZnVuY3Rpb24gaW5pdE1hcCgpIHtcbiAgICAgICAgJC5nZXRTY3JpcHQoJ2h0dHBzOi8vY2RuLmpzZGVsaXZyLm5ldC9ucG0vc25henp5LWluZm8td2luZG93QDEuMS4xL2Rpc3Qvc25henp5LWluZm8td2luZG93Lm1pbi5qcycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGRyYXdNYXAoJCgnI2V2ZW50cy1tYXAnKS5kYXRhKCdldmVudHMnKSk7XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICBnbG9iYWwuaW5pdE1hcCA9IGluaXRNYXA7XG59XG5cbi8vIEluaXRpYWxpemVzIEdvb2dsZSBNYXBzXG5cbmZ1bmN0aW9uIGRyYXdNYXAoZXZlbnRzKSB7XG5cbiAgICB2YXIgbWFwID0gbmV3IGdvb2dsZS5tYXBzLk1hcChkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXZlbnRzLW1hcCcpLCB7XG4gICAgICAgIHpvb206IDcsXG4gICAgICAgIGNlbnRlcjoge1xuICAgICAgICAgICAgbGF0OiBwYXJzZUZsb2F0KGV2ZW50c1swXS5sYXQpLFxuICAgICAgICAgICAgbG5nOiBwYXJzZUZsb2F0KGV2ZW50c1swXS5sbmcpXG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIHZhciBtYXJrZXJzID0gZXZlbnRzLm1hcChmdW5jdGlvbiAoZXZlbnQsIGkpIHtcbiAgICAgICAgdmFyIG1hcmtlciA9IG5ldyBnb29nbGUubWFwcy5NYXJrZXIoe1xuICAgICAgICAgICAgcG9zaXRpb246IHtcbiAgICAgICAgICAgICAgICBsYXQ6IHBhcnNlRmxvYXQoZXZlbnQubGF0KSxcbiAgICAgICAgICAgICAgICBsbmc6IHBhcnNlRmxvYXQoZXZlbnQubG5nKVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGljb246ICQoJyNldmVudHMtbWFwJykuZGF0YSgncGluLXBhdGgnKVxuICAgICAgICB9KTtcblxuICAgICAgICB2YXIgdGVtcGxhdGUgPSBIYW5kbGViYXJzLmNvbXBpbGUoJCgnI2V2ZW50LWluZm8tYm94JykuaHRtbCgpKTtcblxuICAgICAgICB2YXIgaW5mbyA9IG51bGw7XG4gICAgICAgIHZhciBjbG9zZURlbGF5ZWQgPSBmYWxzZTtcbiAgICAgICAgdmFyIGNsb3NlRGVsYXlIYW5kbGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJChpbmZvLmdldFdyYXBwZXIoKSkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgY2xvc2VEZWxheWVkID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBpbmZvLmNsb3NlKCk7XG4gICAgICAgICAgICB9LCAzMDApO1xuICAgICAgICB9O1xuICAgICAgICAvLyBBZGQgYSBTbmF6enkgSW5mbyBXaW5kb3cgdG8gdGhlIG1hcmtlclxuICAgICAgICBpbmZvID0gbmV3IFNuYXp6eUluZm9XaW5kb3coe1xuICAgICAgICAgICAgbWFya2VyOiBtYXJrZXIsXG4gICAgICAgICAgICB3cmFwcGVyQ2xhc3M6ICdjdXN0b20td2luZG93JyxcbiAgICAgICAgICAgIG9mZnNldDoge1xuICAgICAgICAgICAgICAgIHRvcDogJy03MnB4J1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGVkZ2VPZmZzZXQ6IHtcbiAgICAgICAgICAgICAgICB0b3A6IDUwLFxuICAgICAgICAgICAgICAgIHJpZ2h0OiA2MCxcbiAgICAgICAgICAgICAgICBib3R0b206IDUwXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgYm9yZGVyOiBmYWxzZSxcbiAgICAgICAgICAgIGNsb3NlQnV0dG9uTWFya3VwOiAnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJjdXN0b20tY2xvc2VcIj4mIzIxNTs8L2J1dHRvbj4nLFxuICAgICAgICAgICAgY29udGVudDogdGVtcGxhdGUoe1xuICAgICAgICAgICAgICAgIHRpdGxlOiBldmVudC5uYW1lLFxuICAgICAgICAgICAgICAgIGxpbms6IGV2ZW50LmxpbmssXG4gICAgICAgICAgICAgICAgYmdJbWc6IGV2ZW50LmltYWdlLFxuICAgICAgICAgICAgICAgIGJvZHk6XG4gICAgICAgICAgICAgICAgICAgICAgICAnPHAgY2xhc3M9XCJ0ZXh0LW11dGVkXCI+PHNtYWxsPicgKyBldmVudC5hZGRyZXNzICsgJzwvc21hbGw+PC9wPicgK1xuICAgICAgICAgICAgICAgICAgICAgICAgJzxwIGNsYXNzPVwidGV4dC1tdXRlZFwiPjxzbWFsbD4nICsgZXZlbnQuZGF0ZSArICc8L3NtYWxsPjwvcD4nICtcbiAgICAgICAgICAgICAgICAgICAgICAgICc8cCBjbGFzcz1cInRleHQtbXV0ZWRcIj48c21hbGw+JyArICgkKCdib2R5JykuZGF0YSgnY3VycmVuY3ktcG9zaXRpb24nKSA9PSAnbGVmdCcgPyAkKCdib2R5JykuZGF0YSgnY3VycmVuY3ktc3ltYm9sJykgOiAnJykgKyBldmVudC5wcmljZSArICgkKCdib2R5JykuZGF0YSgnY3VycmVuY3ktcG9zaXRpb24nKSA9PSAncmlnaHQnID8gJCgnYm9keScpLmRhdGEoJ2N1cnJlbmN5LXN5bWJvbCcpIDogJycpICsgJzwvc21hbGw+PC9wPidcbiAgICAgICAgICAgIH0pLFxuICAgICAgICAgICAgY2FsbGJhY2tzOiB7XG4gICAgICAgICAgICAgICAgb3BlbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMuZ2V0V3JhcHBlcigpKS5hZGRDbGFzcygnb3BlbicpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgYWZ0ZXJPcGVuOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB3cmFwcGVyID0gJCh0aGlzLmdldFdyYXBwZXIoKSk7XG4gICAgICAgICAgICAgICAgICAgIHdyYXBwZXIuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICB3cmFwcGVyLmZpbmQoJy5jdXN0b20tY2xvc2UnKS5vbignY2xpY2snLCBjbG9zZURlbGF5SGFuZGxlcik7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBiZWZvcmVDbG9zZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBpZiAoIWNsb3NlRGVsYXllZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2xvc2VEZWxheUhhbmRsZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGFmdGVyQ2xvc2U6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHdyYXBwZXIgPSAkKHRoaXMuZ2V0V3JhcHBlcigpKTtcbiAgICAgICAgICAgICAgICAgICAgd3JhcHBlci5maW5kKCcuY3VzdG9tLWNsb3NlJykub2ZmKCk7XG4gICAgICAgICAgICAgICAgICAgIHdyYXBwZXIucmVtb3ZlQ2xhc3MoJ29wZW4nKTtcbiAgICAgICAgICAgICAgICAgICAgY2xvc2VEZWxheWVkID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gbWFya2VyO1xuICAgIH0pO1xuICAgIG5ldyBNYXJrZXJDbHVzdGVyZXIobWFwLCBtYXJrZXJzLFxuICAgICAgICAgICAge2ltYWdlUGF0aDogJ2h0dHBzOi8vZGV2ZWxvcGVycy5nb29nbGUuY29tL21hcHMvZG9jdW1lbnRhdGlvbi9qYXZhc2NyaXB0L2V4YW1wbGVzL21hcmtlcmNsdXN0ZXJlci9tJ30pO1xufVxuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG5cbiAgICAvLyBTZWFyY2ggZmlsdGVyc1xuXG4gICAgJCgnI2ZpbHRlci1sb2NhbC1vbmx5Jykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCQodGhpcykuaXMoJzpjaGVja2VkJykpIHtcbiAgICAgICAgICAgICQoJyN1c2VyLWNvdW50cnknKS5zaG93KCk7XG4gICAgICAgICAgICAkKCcjZmlsdGVyLWNvdW50cnktY29udGFpbmVyJykuaGlkZSgpO1xuICAgICAgICAgICAgJCgnI2NvdW50cnknKS5zZWxlY3QyKCd2YWwnLCAnYWxsJyk7XG4gICAgICAgICAgICAkKCcuZmlsdGVyLW9ubGluZS1jb250YWluZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjZmlsdGVyLW9ubGluZS1vbmx5JykucHJvcChcImNoZWNrZWRcIiwgZmFsc2UpO1xuICAgICAgICAgICAgJCgnI2ZpbHRlci1sb2NhdGlvbi1jb250YWluZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjbG9jYXRpb24nKS52YWwoJycpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgJCgnI3VzZXItY291bnRyeScpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJyNmaWx0ZXItY291bnRyeS1jb250YWluZXInKS5zaG93KCk7XG4gICAgICAgICAgICAkKCcuZmlsdGVyLW9ubGluZS1jb250YWluZXInKS5zaG93KCk7XG4gICAgICAgIH1cbiAgICB9KTtcbiAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdsb2NhbG9ubHknKSA9PSBcIjFcIikge1xuICAgICAgICAkKCcjZmlsdGVyLWxvY2FsLW9ubHknKS5hdHRyKCdjaGVja2VkJywgdHJ1ZSk7XG4gICAgICAgICQoJyNmaWx0ZXItbG9jYWwtb25seScpLnRyaWdnZXIoJ2NoYW5nZScpO1xuICAgIH1cbiAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdjYXRlZ29yeScpKSB7XG4gICAgICAgICQoJ3NlbGVjdCNjYXRlZ29yeSBvcHRpb24nKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICgkKHRoaXMpLnZhbCgpID09IGdldFVSTFBhcmFtZXRlcignY2F0ZWdvcnknKSlcbiAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnByb3AoJ3NlbGVjdGVkJywgdHJ1ZSkudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICAkKCcjZmlsdGVyLWxvY2F0aW9uLWNvbnRhaW5lcicpLmhpZGUoKTtcbiAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdjb3VudHJ5JykpIHtcbiAgICAgICAgJCgnI2NvdW50cnkgb3B0aW9uJykuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoJCh0aGlzKS52YWwoKSA9PSBnZXRVUkxQYXJhbWV0ZXIoJ2NvdW50cnknKSlcbiAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLnByb3AoJ3NlbGVjdGVkJywgdHJ1ZSkudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgICAgICAgICAgJCgnI2ZpbHRlci1sb2NhdGlvbi1jb250YWluZXInKS5zaG93KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICAkKCcjY291bnRyeScpLnRyaWdnZXIoJ2NoYW5nZScpO1xuICAgIH1cbiAgICAkKCcjY291bnRyeScpLmNoYW5nZShmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICgkKHRoaXMpLnZhbCgpICE9IFwiYWxsXCIpIHtcbiAgICAgICAgICAgICQoJyNmaWx0ZXItbG9jYXRpb24tY29udGFpbmVyJykuc2hvdygpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgJCgnI2ZpbHRlci1sb2NhdGlvbi1jb250YWluZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjbG9jYXRpb24nKS52YWwoJycpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdhdWRpZW5jZScpKSB7XG4gICAgICAgICQoJyMnICsgZ2V0VVJMUGFyYW1ldGVyKCdhdWRpZW5jZScpKS5hdHRyKCdjaGVja2VkJywgdHJ1ZSk7XG4gICAgICAgICQoJyMnICsgZ2V0VVJMUGFyYW1ldGVyKCdhdWRpZW5jZScpKS5jbG9zZXN0KCdsYWJlbCcpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICB9XG5cbiAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSkge1xuICAgICAgICBpZiAoZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcInRvZGF5XCIgJiYgZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcInRvbW9ycm93XCIgJiYgZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcInRoaXN3ZWVrZW5kXCIgJiYgZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcInRoaXN3ZWVrXCIgJiYgZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcIm5leHR3ZWVrXCIgJiYgZ2V0VVJMUGFyYW1ldGVyKCdzdGFydGRhdGUnKSAhPSBcInRoaXNtb250aFwiICYmIGdldFVSTFBhcmFtZXRlcignc3RhcnRkYXRlJykgIT0gXCJuZXh0bW9udGhcIikge1xuICAgICAgICAgICAgJCgnI2RhdGUtcGlja2FkYXRlJykudmFsKGdldFVSTFBhcmFtZXRlcignc3RhcnRkYXRlJykpO1xuICAgICAgICAgICAgJCgnI2RhdGUtcGlja2FkYXRlJykuYXR0cignY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgJCgnaW5wdXRbbmFtZT1cInN0YXJ0ZGF0ZVwiXVt2YWx1ZT1cIicgKyBnZXRVUkxQYXJhbWV0ZXIoJ3N0YXJ0ZGF0ZScpICsgJ1wiXScpLmF0dHIoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgICQoJyNmcmVlLWV2ZW50cy1vbmx5Jykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCQodGhpcykuaXMoJzpjaGVja2VkJykpIHtcbiAgICAgICAgICAgICQoJy5ldmVudHMtcHJpY2UtcmFuZ2Utc2xpZGVyLXdyYXBwZXInKS5oaWRlKCk7XG4gICAgICAgICAgICAkKCcjcHJpY2VtaW4nKS52YWwoJzAnKTtcbiAgICAgICAgICAgICQoJyNwcmljZW1pbicpLnRyaWdnZXIoJ2NoYW5nZScpO1xuICAgICAgICAgICAgJCgnI3ByaWNlbWF4JykudmFsKCcxMDAwMCcpO1xuICAgICAgICAgICAgJCgnI3ByaWNlbWF4JykudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAkKCcuZXZlbnRzLXByaWNlLXJhbmdlLXNsaWRlci13cmFwcGVyJykuc2hvdygpO1xuICAgICAgICB9XG4gICAgfSk7XG4gICAgaWYgKGdldFVSTFBhcmFtZXRlcignZnJlZW9ubHknKSA9PSBcIjFcIikge1xuICAgICAgICAkKCcjZnJlZS1ldmVudHMtb25seScpLmF0dHIoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgJCgnI2ZyZWUtZXZlbnRzLW9ubHknKS50cmlnZ2VyKCdjaGFuZ2UnKTtcbiAgICB9XG5cbiAgICAkKCcjZmlsdGVyLW9ubGluZS1vbmx5Jykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCQodGhpcykuaXMoJzpjaGVja2VkJykpIHtcbiAgICAgICAgICAgICQoJy5sb2NhdGlvbi1iYXNlZC1maWx0ZXJzJykuaGlkZSgpO1xuICAgICAgICAgICAgJCgnI2ZpbHRlci1sb2NhbC1vbmx5JykucHJvcChcImNoZWNrZWRcIiwgZmFsc2UpO1xuICAgICAgICAgICAgJCgnI2NvdW50cnknKS5zZWxlY3QyKCd2YWwnLCAnYWxsJyk7XG4gICAgICAgICAgICAkKCcjZmlsdGVyLWxvY2F0aW9uLWNvbnRhaW5lcicpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJyNsb2NhdGlvbicpLnZhbCgnJyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAkKCcubG9jYXRpb24tYmFzZWQtZmlsdGVycycpLnNob3coKTtcbiAgICAgICAgfVxuICAgIH0pO1xuICAgIGlmIChnZXRVUkxQYXJhbWV0ZXIoJ29ubGluZW9ubHknKSA9PSBcIjFcIikge1xuICAgICAgICAkKCcjZmlsdGVyLW9ubGluZS1vbmx5JykuYXR0cignY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICAkKCcjZmlsdGVyLW9ubGluZS1vbmx5JykudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgfVxuXG4gICAgLy8gSW5pdGlhbGl6ZXMgZXZlbnRzIGNhbGVuZGFyXG4gICAgaWYgKCQoJyNldmVudHMtY2FsZW5kYXInKS5sZW5ndGgpIHtcbiAgICAgICAgdmFyIGNhbGVuZGFyRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXZlbnRzLWNhbGVuZGFyJyk7XG5cbiAgICAgICAgdmFyIGNhbGVuZGFyID0gbmV3IENhbGVuZGFyKGNhbGVuZGFyRWwsIHtcbiAgICAgICAgICAgIHBsdWdpbnM6IFsnaW50ZXJhY3Rpb24nLCBkYXlHcmlkUGx1Z2luLCB0aW1lR3JpZFBsdWdpbl0sXG4gICAgICAgICAgICBkZWZhdWx0VmlldzogJ2RheUdyaWRNb250aCcsXG4gICAgICAgICAgICBkZWZhdWx0RGF0ZTogJCgnI2V2ZW50cy1jYWxlbmRhcicpLmRhdGEoJ2RlZmF1bHQtZGF0ZScpLFxuICAgICAgICAgICAgaGVpZ2h0OiBcImF1dG9cIixcbiAgICAgICAgICAgIGxvY2FsZTogJCgnaHRtbCcpLmF0dHIoJ2xhbmcnKSxcblxuICAgICAgICAgICAgLypjdXN0b21CdXR0b25zOiB7XG4gICAgICAgICAgICAgYWxsZXZlbnRzY2FsZW5kYXJsaW5rOiB7XG4gICAgICAgICAgICAgdGV4dDogJ0FsbCBldmVudHMgY2FsZW5kYXInLFxuICAgICAgICAgICAgIGNsaWNrOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgfVxuICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICB9LCovXG4gICAgICAgICAgICBoZWFkZXI6IHtcbiAgICAgICAgICAgICAgICBsZWZ0OiAncHJldixuZXh0IHRvZGF5IGFsbGV2ZW50c2NhbGVuZGFybGluaycsXG4gICAgICAgICAgICAgICAgY2VudGVyOiAndGl0bGUnLFxuICAgICAgICAgICAgICAgIHJpZ2h0OiAnZGF5R3JpZE1vbnRoLHRpbWVHcmlkV2Vlayx0aW1lR3JpZERheSdcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBldmVudHM6ICQoJyNldmVudHMtY2FsZW5kYXInKS5kYXRhKCdldmVudHMnKVxuICAgICAgICB9KTtcblxuICAgICAgICBjYWxlbmRhci5yZW5kZXIoKTtcblxuICAgIH1cblxufSk7Il0sInNvdXJjZVJvb3QiOiIifQ==