(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["organizerprofile"],{

/***/ "./assets/js/organizerprofile.js":
/*!***************************************!*\
  !*** ./assets/js/organizerprofile.js ***!
  \***************************************/
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
/* harmony import */ var marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! marker-clusterer/marker-clusterer.js */ "./node_modules/marker-clusterer/marker-clusterer.js");
/* harmony import */ var marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(marker_clusterer_marker_clusterer_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! handlebars/dist/handlebars.min.js */ "./node_modules/handlebars/dist/handlebars.min.js");
/* harmony import */ var handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_8__);







// js

 // snazzy-info-window must be loader after Google Maps is completely loader

function initMap() {
  if ($('#venues-map').length > 0) {
    $.getScript('https://cdn.jsdelivr.net/npm/snazzy-info-window@1.1.1/dist/snazzy-info-window.min.js', function () {
      drawMap($('#venues-map').data('venues'));
    });
  }
}

global.initMap = initMap; // Initializes Google Maps

function drawMap(venues) {
  var map = new google.maps.Map(document.getElementById('venues-map'), {
    zoom: 7,
    center: {
      lat: parseFloat(venues[0].lat),
      lng: parseFloat(venues[0].lng)
    }
  });
  var markers = venues.map(function (venue, i) {
    var marker = new google.maps.Marker({
      position: {
        lat: parseFloat(venue.lat),
        lng: parseFloat(venue.lng)
      },
      icon: $('#venues-map').data('pin-path')
    });
    var template = handlebars_dist_handlebars_min_js__WEBPACK_IMPORTED_MODULE_8___default.a.compile($('#organizer-info-box').html());
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
        title: venue.name,
        link: venue.link,
        bgImg: venue.image,
        body: '<p class="text-muted"><small>' + venue.address + '</small></p>'
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
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ })

},[["./assets/js/organizerprofile.js","runtime","vendors~app~app.ar~app.es~app.fr~event~events~installer~organizerprofile","vendors~app~event~events~installer~organizerprofile","vendors~app~events~installer~organizerprofile","vendors~app~events~organizerprofile","vendors~events~organizerprofile"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvb3JnYW5pemVycHJvZmlsZS5qcyJdLCJuYW1lcyI6WyJpbml0TWFwIiwiJCIsImxlbmd0aCIsImdldFNjcmlwdCIsImRyYXdNYXAiLCJkYXRhIiwiZ2xvYmFsIiwidmVudWVzIiwibWFwIiwiZ29vZ2xlIiwibWFwcyIsIk1hcCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJ6b29tIiwiY2VudGVyIiwibGF0IiwicGFyc2VGbG9hdCIsImxuZyIsIm1hcmtlcnMiLCJ2ZW51ZSIsImkiLCJtYXJrZXIiLCJNYXJrZXIiLCJwb3NpdGlvbiIsImljb24iLCJ0ZW1wbGF0ZSIsIkhhbmRsZWJhcnMiLCJjb21waWxlIiwiaHRtbCIsImluZm8iLCJjbG9zZURlbGF5ZWQiLCJjbG9zZURlbGF5SGFuZGxlciIsImdldFdyYXBwZXIiLCJyZW1vdmVDbGFzcyIsInNldFRpbWVvdXQiLCJjbG9zZSIsIlNuYXp6eUluZm9XaW5kb3ciLCJ3cmFwcGVyQ2xhc3MiLCJvZmZzZXQiLCJ0b3AiLCJlZGdlT2Zmc2V0IiwicmlnaHQiLCJib3R0b20iLCJib3JkZXIiLCJjbG9zZUJ1dHRvbk1hcmt1cCIsImNvbnRlbnQiLCJ0aXRsZSIsIm5hbWUiLCJsaW5rIiwiYmdJbWciLCJpbWFnZSIsImJvZHkiLCJhZGRyZXNzIiwiY2FsbGJhY2tzIiwib3BlbiIsImFkZENsYXNzIiwiYWZ0ZXJPcGVuIiwid3JhcHBlciIsImZpbmQiLCJvbiIsImJlZm9yZUNsb3NlIiwiYWZ0ZXJDbG9zZSIsIm9mZiIsIk1hcmtlckNsdXN0ZXJlciIsImltYWdlUGF0aCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBQUE7QUFFQTtDQUdBOztBQUNBLFNBQVNBLE9BQVQsR0FBbUI7RUFDZixJQUFJQyxDQUFDLENBQUMsYUFBRCxDQUFELENBQWlCQyxNQUFqQixHQUEwQixDQUE5QixFQUFpQztJQUM3QkQsQ0FBQyxDQUFD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bkIsSUFBSUMsT0FBTyxHQUFHekQsQ0FBQyxDQUFDLEtBQUtnQyxVQUFMLEVBQUQsQ0FBZjtVQUNBeUIsT0FBTyxDQUFDRixRQUFSLENBQWlCLFFBQWpCO1VBQ0FFLE9BQU8sQ0FBQ0MsSUFBUixDQUFhLGVBQWIsRUFBOEJDLEVBQTlCLENBQWlDLE9BQWpDLEVBQTBDNUIsaUJBQTFDO1FBQ0gsQ0FSTTtRQVNQNkIsV0FBVyxFQUFFLHVCQUFZO1VBQ3JCLElBQUksQ0FBQzlCLFlBQUwsRUFBbUI7WUFDZkMsaUJBQWlCO1lBQ2pCLE9BQU8sS0FBUDtVQUNIOztVQUNELE9BQU8sSUFBUDtRQUNILENBZk07UUFnQlA4QixVQUFVLEVBQUUsc0JBQVk7VUFDcEIsSUFBSUosT0FBTyxHQUFHekQsQ0FBQyxDQUFDLEtBQUtnQyxVQUFMLEVBQUQsQ0FBZjtVQUNBeUIsT0FBTyxDQUFDQyxJQUFSLENBQWEsZUFBYixFQUE4QkksR0FBOUI7VUFDQUwsT0FBTyxDQUFDeEIsV0FBUixDQUFvQixNQUFwQjtVQUNBSCxZQUFZLEdBQUcsS0FBZjtRQUNIO01BckJNO0lBcEJhLENBQXJCLENBQVA7SUE2Q0EsT0FBT1QsTUFBUDtFQUNILENBbkVhLENBQWQ7RUFvRUEsSUFBSTBDLGVBQUosQ0FBb0J4RCxHQUFwQixFQUF5QlcsT0FBekIsRUFDUTtJQUFDOEMsU0FBUyxFQUFFO0VBQVosQ0FEUjtBQUVILEMiLCJmaWxlIjoib3JnYW5pemVycHJvZmlsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vIGpzXG5cbmltcG9ydCAnbWFya2VyLWNsdXN0ZXJlci9tYXJrZXItY2x1c3RlcmVyLmpzJztcbmltcG9ydCBIYW5kbGViYXJzIGZyb20gJ2hhbmRsZWJhcnMvZGlzdC9oYW5kbGViYXJzLm1pbi5qcyc7XG5cbi8vIHNuYXp6eS1pbmZvLXdpbmRvdyBtdXN0IGJlIGxvYWRlciBhZnRlciBHb29nbGUgTWFwcyBpcyBjb21wbGV0ZWx5IGxvYWRlclxuZnVuY3Rpb24gaW5pdE1hcCgpIHtcbiAgICBpZiAoJCgnI3ZlbnVlcy1tYXAnKS5sZW5ndGggPiAwKSB7XG4gICAgICAgICQuZ2V0U2NyaXB0KCdodHRwczovL2Nkbi5qc2RlbGl2ci5uZXQvbnBtL3NuYXp6eS1pbmZvLXdpbmRvd0AxLjEuMS9kaXN0L3NuYXp6eS1pbmZvLXdpbmRvdy5taW4uanMnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBkcmF3TWFwKCQoJyN2ZW51ZXMtbWFwJykuZGF0YSgndmVudWVzJykpO1xuICAgICAgICB9KTtcbiAgICB9XG59XG5nbG9iYWwuaW5pdE1hcCA9IGluaXRNYXA7XG5cbi8vIEluaXRpYWxpemVzIEdvb2dsZSBNYXBzXG5cbmZ1bmN0aW9uIGRyYXdNYXAodmVudWVzKSB7XG5cbiAgICB2YXIgbWFwID0gbmV3IGdvb2dsZS5tYXBzLk1hcChkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndmVudWVzLW1hcCcpLCB7XG4gICAgICAgIHpvb206IDcsXG4gICAgICAgIGNlbnRlcjoge1xuICAgICAgICAgICAgbGF0OiBwYXJzZUZsb2F0KHZlbnVlc1swXS5sYXQpLFxuICAgICAgICAgICAgbG5nOiBwYXJzZUZsb2F0KHZlbnVlc1swXS5sbmcpXG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIHZhciBtYXJrZXJzID0gdmVudWVzLm1hcChmdW5jdGlvbiAodmVudWUsIGkpIHtcbiAgICAgICAgdmFyIG1hcmtlciA9IG5ldyBnb29nbGUubWFwcy5NYXJrZXIoe1xuICAgICAgICAgICAgcG9zaXRpb246IHtcbiAgICAgICAgICAgICAgICBsYXQ6IHBhcnNlRmxvYXQodmVudWUubGF0KSxcbiAgICAgICAgICAgICAgICBsbmc6IHBhcnNlRmxvYXQodmVudWUubG5nKVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGljb246ICQoJyN2ZW51ZXMtbWFwJykuZGF0YSgncGluLXBhdGgnKVxuICAgICAgICB9KTtcblxuICAgICAgICB2YXIgdGVtcGxhdGUgPSBIYW5kbGViYXJzLmNvbXBpbGUoJCgnI29yZ2FuaXplci1pbmZvLWJveCcpLmh0bWwoKSk7XG5cbiAgICAgICAgdmFyIGluZm8gPSBudWxsO1xuICAgICAgICB2YXIgY2xvc2VEZWxheWVkID0gZmFsc2U7XG4gICAgICAgIHZhciBjbG9zZURlbGF5SGFuZGxlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQoaW5mby5nZXRXcmFwcGVyKCkpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGNsb3NlRGVsYXllZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgaW5mby5jbG9zZSgpO1xuICAgICAgICAgICAgfSwgMzAwKTtcbiAgICAgICAgfTtcbiAgICAgICAgLy8gQWRkIGEgU25henp5IEluZm8gV2luZG93IHRvIHRoZSBtYXJrZXJcbiAgICAgICAgaW5mbyA9IG5ldyBTbmF6enlJbmZvV2luZG93KHtcbiAgICAgICAgICAgIG1hcmtlcjogbWFya2VyLFxuICAgICAgICAgICAgd3JhcHBlckNsYXNzOiAnY3VzdG9tLXdpbmRvdycsXG4gICAgICAgICAgICBvZmZzZXQ6IHtcbiAgICAgICAgICAgICAgICB0b3A6ICctNzJweCdcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBlZGdlT2Zmc2V0OiB7XG4gICAgICAgICAgICAgICAgdG9wOiA1MCxcbiAgICAgICAgICAgICAgICByaWdodDogNjAsXG4gICAgICAgICAgICAgICAgYm90dG9tOiA1MFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGJvcmRlcjogZmFsc2UsXG4gICAgICAgICAgICBjbG9zZUJ1dHRvbk1hcmt1cDogJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiY3VzdG9tLWNsb3NlXCI+JiMyMTU7PC9idXR0b24+JyxcbiAgICAgICAgICAgIGNvbnRlbnQ6IHRlbXBsYXRlKHtcbiAgICAgICAgICAgICAgICB0aXRsZTogdmVudWUubmFtZSxcbiAgICAgICAgICAgICAgICBsaW5rOiB2ZW51ZS5saW5rLFxuICAgICAgICAgICAgICAgIGJnSW1nOiB2ZW51ZS5pbWFnZSxcbiAgICAgICAgICAgICAgICBib2R5OlxuICAgICAgICAgICAgICAgICAgICAgICAgJzxwIGNsYXNzPVwidGV4dC1tdXRlZFwiPjxzbWFsbD4nICsgdmVudWUuYWRkcmVzcyArICc8L3NtYWxsPjwvcD4nXG4gICAgICAgICAgICB9KSxcbiAgICAgICAgICAgIGNhbGxiYWNrczoge1xuICAgICAgICAgICAgICAgIG9wZW46IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzLmdldFdyYXBwZXIoKSkuYWRkQ2xhc3MoJ29wZW4nKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGFmdGVyT3BlbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgd3JhcHBlciA9ICQodGhpcy5nZXRXcmFwcGVyKCkpO1xuICAgICAgICAgICAgICAgICAgICB3cmFwcGVyLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgd3JhcHBlci5maW5kKCcuY3VzdG9tLWNsb3NlJykub24oJ2NsaWNrJywgY2xvc2VEZWxheUhhbmRsZXIpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgYmVmb3JlQ2xvc2U6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFjbG9zZURlbGF5ZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsb3NlRGVsYXlIYW5kbGVyKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBhZnRlckNsb3NlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB3cmFwcGVyID0gJCh0aGlzLmdldFdyYXBwZXIoKSk7XG4gICAgICAgICAgICAgICAgICAgIHdyYXBwZXIuZmluZCgnLmN1c3RvbS1jbG9zZScpLm9mZigpO1xuICAgICAgICAgICAgICAgICAgICB3cmFwcGVyLnJlbW92ZUNsYXNzKCdvcGVuJyk7XG4gICAgICAgICAgICAgICAgICAgIGNsb3NlRGVsYXllZCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIG1hcmtlcjtcbiAgICB9KTtcbiAgICBuZXcgTWFya2VyQ2x1c3RlcmVyKG1hcCwgbWFya2VycyxcbiAgICAgICAgICAgIHtpbWFnZVBhdGg6ICdodHRwczovL2RldmVsb3BlcnMuZ29vZ2xlLmNvbS9tYXBzL2RvY3VtZW50YXRpb24vamF2YXNjcmlwdC9leGFtcGxlcy9tYXJrZXJjbHVzdGVyZXIvbSd9KTtcbn0iXSwic291cmNlUm9vdCI6IiJ9