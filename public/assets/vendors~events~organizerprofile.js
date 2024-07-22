(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["vendors~events~organizerprofile"],{

/***/ "./node_modules/core-js/internals/create-html.js":
/*!*******************************************************!*\
  !*** ./node_modules/core-js/internals/create-html.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var uncurryThis = __webpack_require__(/*! ../internals/function-uncurry-this */ "./node_modules/core-js/internals/function-uncurry-this.js");
var requireObjectCoercible = __webpack_require__(/*! ../internals/require-object-coercible */ "./node_modules/core-js/internals/require-object-coercible.js");
var toString = __webpack_require__(/*! ../internals/to-string */ "./node_modules/core-js/internals/to-string.js");

var quot = /"/g;
var replace = uncurryThis(''.replace);

// `CreateHTML` abstract operation
// https://tc39.es/ecma262/#sec-createhtml
module.exports = function (string, tag, attribute, value) {
  var S = toString(requireObjectCoercible(string));
  var p1 = '<' + tag;
  if (attribute !== '') p1 += ' ' + attribute + '="' + replace(toString(value), quot, '&quot;') + '"';
  return p1 + '>' + S + '</' + tag + '>';
};


/***/ }),

/***/ "./node_modules/core-js/internals/string-html-forced.js":
/*!**************************************************************!*\
  !*** ./node_modules/core-js/internals/string-html-forced.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var fails = __webpack_require__(/*! ../internals/fails */ "./node_modules/core-js/internals/fails.js");

// check the existence of a method, lowercase
// of a tag and escaping quotes in arguments
module.exports = function (METHOD_NAME) {
  return fails(function () {
    var test = ''[METHOD_NAME]('"');
    return test !== test.toLowerCase() || test.split('"').length > 3;
  });
};


/***/ }),

/***/ "./node_modules/core-js/modules/es.string.link.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/es.string.link.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var createHTML = __webpack_require__(/*! ../internals/create-html */ "./node_modules/core-js/internals/create-html.js");
var forcedStringHTMLMethod = __webpack_require__(/*! ../internals/string-html-forced */ "./node_modules/core-js/internals/string-html-forced.js");

// `String.prototype.link` method
// https://tc39.es/ecma262/#sec-string.prototype.link
$({ target: 'String', proto: true, forced: forcedStringHTMLMethod('link') }, {
  link: function link(url) {
    return createHTML(this, 'a', 'href', url);
  }
});


/***/ }),

/***/ "./node_modules/marker-clusterer/marker-clusterer.js":
/*!***********************************************************!*\
  !*** ./node_modules/marker-clusterer/marker-clusterer.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @externs_url https://raw.githubusercontent.com/google/closure-compiler/master/contrib/externs/maps/google_maps_api_v3.js
// ==/ClosureCompiler==

/**
 * @name MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Luke Mahe
 * @fileoverview
 * The library creates and manages per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This is a v3 implementation of the
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * @license
 * Copyright 2010 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>=} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object=} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Whether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'minimumClusterSize': (number) The minimum number of markers to be in a
 *                           cluster before the markers are hidden and a count
 *                           is shown.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 *       'backgroundPosition': (string) The position of the backgound x, y.
 *       'iconAnchor': (Array) The anchor position of the icon x, y.
 * @constructor
 * @extends google.maps.OverlayView
 */
function MarkerClusterer(map, opt_markers, opt_options) {
  // MarkerClusterer implements google.maps.OverlayView interface. We use the
  // extend function to extend MarkerClusterer with google.maps.OverlayView
  // because it might not always be available when the code is defined so we
  // look for it at the last possible moment. If it doesn't exist now then
  // there is no point going ahead :)
  this.extend(MarkerClusterer, google.maps.OverlayView);
  this.map_ = map;

  /**
   * @type {Array.<google.maps.Marker>}
   * @private
   */
  this.markers_ = [];

  /**
   *  @type {Array.<Cluster>}
   */
  this.clusters_ = [];

  this.sizes = [53, 56, 66, 78, 90];

  /**
   * @private
   */
  this.styles_ = [];

  /**
   * @type {boolean}
   * @private
   */
  this.ready_ = false;

  var options = opt_options || {};

  /**
   * @type {number}
   * @private
   */
  this.gridSize_ = options['gridSize'] || 60;

  /**
   * @private
   */
  this.minClusterSize_ = options['minimumClusterSize'] || 2;


  /**
   * @type {?number}
   * @private
   */
  this.maxZoom_ = options['maxZoom'] || null;

  this.styles_ = options['styles'] || [];

  /**
   * @type {string}
   * @private
   */
  this.imagePath_ = options['imagePath'] ||
      this.MARKER_CLUSTER_IMAGE_PATH_;

  /**
   * @type {string}
   * @private
   */
  this.imageExtension_ = options['imageExtension'] ||
      this.MARKER_CLUSTER_IMAGE_EXTENSION_;

  /**
   * @type {boolean}
   * @private
   */
  this.zoomOnClick_ = true;

  if (options['zoomOnClick'] != undefined) {
    this.zoomOnClick_ = options['zoomOnClick'];
  }

  /**
   * @type {boolean}
   * @private
   */
  this.averageCenter_ = false;

  if (options['averageCenter'] != undefined) {
    this.averageCenter_ = options['averageCenter'];
  }

  this.setupStyles_();

  this.setMap(map);

  /**
   * @type {number}
   * @private
   */
  this.prevZoom_ = this.map_.getZoom();

  // Add the map event listeners
  var that = this;
  google.maps.event.addListener(this.map_, 'zoom_changed', function() {
    var zoom = that.map_.getZoom();

    if (that.prevZoom_ != zoom) {
      that.prevZoom_ = zoom;
      that.resetViewport();
    }
  });

  google.maps.event.addListener(this.map_, 'idle', function() {
    that.redraw();
  });

  // Finally, add the markers
  if (opt_markers && opt_markers.length) {
    this.addMarkers(opt_markers, false);
  }
}


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ = '../images/m';


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';


/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */
MarkerClusterer.prototype.extend = function(obj1, obj2) {
  return (function(object) {
    for (var property in object.prototype) {
      this.prototype[property] = object.prototype[property];
    }
    return this;
  }).apply(obj1, [obj2]);
};


/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.onAdd = function() {
  this.setReady_(true);
};

/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.draw = function() {};

/**
 * Sets up the styles object.
 *
 * @private
 */
MarkerClusterer.prototype.setupStyles_ = function() {
  if (this.styles_.length) {
    return;
  }

  for (var i = 0, size; size = this.sizes[i]; i++) {
    this.styles_.push({
      url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
      height: size,
      width: size
    });
  }
};

/**
 *  Fit the map to the bounds of the markers in the clusterer.
 */
MarkerClusterer.prototype.fitMapToMarkers = function() {
  var markers = this.getMarkers();
  var bounds = new google.maps.LatLngBounds();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }

  this.map_.fitBounds(bounds);
};


/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */
MarkerClusterer.prototype.setStyles = function(styles) {
  this.styles_ = styles;
};


/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */
MarkerClusterer.prototype.getStyles = function() {
  return this.styles_;
};


/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */
MarkerClusterer.prototype.isZoomOnClick = function() {
  return this.zoomOnClick_;
};

/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */
MarkerClusterer.prototype.isAverageCenter = function() {
  return this.averageCenter_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */
MarkerClusterer.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 *  Returns the number of markers in the clusterer
 *
 *  @return {Number} The number of markers.
 */
MarkerClusterer.prototype.getTotalMarkers = function() {
  return this.markers_.length;
};


/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */
MarkerClusterer.prototype.setMaxZoom = function(maxZoom) {
  this.maxZoom_ = maxZoom;
};


/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */
MarkerClusterer.prototype.getMaxZoom = function() {
  return this.maxZoom_;
};


/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */
MarkerClusterer.prototype.calculator_ = function(markers, numStyles) {
  var index = 0;
  var count = markers.length;
  var dv = count;
  while (dv !== 0) {
    dv = parseInt(dv / 10, 10);
    index++;
  }

  index = Math.min(index, numStyles);
  return {
    text: count,
    index: index
  };
};


/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */
MarkerClusterer.prototype.setCalculator = function(calculator) {
  this.calculator_ = calculator;
};


/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */
MarkerClusterer.prototype.getCalculator = function() {
  return this.calculator_;
};


/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarkers = function(markers, opt_nodraw) {
  for (var i = 0, marker; marker = markers[i]; i++) {
    this.pushMarkerTo_(marker);
  }
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.pushMarkerTo_ = function(marker) {
  marker.isAdded = false;
  if (marker['draggable']) {
    // If the marker is draggable add a listener so we update the clusters on
    // the drag end.
    var that = this;
    google.maps.event.addListener(marker, 'dragend', function() {
      marker.isAdded = false;
      that.repaint();
    });
  }
  this.markers_.push(marker);
};


/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarker = function(marker, opt_nodraw) {
  this.pushMarkerTo_(marker);
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Removes a marker and returns true if removed, false if not
 *
 * @param {google.maps.Marker} marker The marker to remove
 * @return {boolean} Whether the marker was removed or not
 * @private
 */
MarkerClusterer.prototype.removeMarker_ = function(marker) {
  var index = -1;
  if (this.markers_.indexOf) {
    index = this.markers_.indexOf(marker);
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        index = i;
        break;
      }
    }
  }

  if (index == -1) {
    // Marker is not in our list of markers.
    return false;
  }

  marker.setMap(null);

  this.markers_.splice(index, 1);

  return true;
};


/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 * @return {boolean} True if the marker was removed.
 */
MarkerClusterer.prototype.removeMarker = function(marker, opt_nodraw) {
  var removed = this.removeMarker_(marker);

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  } else {
   return false;
  }
};


/**
 * Removes an array of markers from the cluster.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 */
MarkerClusterer.prototype.removeMarkers = function(markers, opt_nodraw) {
  var removed = false;

  for (var i = 0, marker; marker = markers[i]; i++) {
    var r = this.removeMarker_(marker);
    removed = removed || r;
  }

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  }
};


/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */
MarkerClusterer.prototype.setReady_ = function(ready) {
  if (!this.ready_) {
    this.ready_ = ready;
    this.createClusters_();
  }
};


/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */
MarkerClusterer.prototype.getTotalClusters = function() {
  return this.clusters_.length;
};


/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */
MarkerClusterer.prototype.getMap = function() {
  return this.map_;
};


/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */
MarkerClusterer.prototype.setMap = function(map) {
  this.map_ = map;
};


/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getGridSize = function() {
  return this.gridSize_;
};


/**
 * Sets the size of the grid.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setGridSize = function(size) {
  this.gridSize_ = size;
};


/**
 * Returns the min cluster size.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getMinClusterSize = function() {
  return this.minClusterSize_;
};

/**
 * Sets the min cluster size.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setMinClusterSize = function(size) {
  this.minClusterSize_ = size;
};


/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */
MarkerClusterer.prototype.getExtendedBounds = function(bounds) {
  var projection = this.getProjection();

  // Turn the bounds into latlng.
  var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
      bounds.getNorthEast().lng());
  var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
      bounds.getSouthWest().lng());

  // Convert the points to pixels and the extend out by the grid size.
  var trPix = projection.fromLatLngToDivPixel(tr);
  trPix.x += this.gridSize_;
  trPix.y -= this.gridSize_;

  var blPix = projection.fromLatLngToDivPixel(bl);
  blPix.x -= this.gridSize_;
  blPix.y += this.gridSize_;

  // Convert the pixel points back to LatLng
  var ne = projection.fromDivPixelToLatLng(trPix);
  var sw = projection.fromDivPixelToLatLng(blPix);

  // Extend the bounds to contain the new bounds.
  bounds.extend(ne);
  bounds.extend(sw);

  return bounds;
};


/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */
MarkerClusterer.prototype.isMarkerInBounds_ = function(marker, bounds) {
  return bounds.contains(marker.getPosition());
};


/**
 * Clears all clusters and markers from the clusterer.
 */
MarkerClusterer.prototype.clearMarkers = function() {
  this.resetViewport(true);

  // Set the markers a empty array.
  this.markers_ = [];
};


/**
 * Clears all existing clusters and recreates them.
 * @param {boolean} opt_hide To also hide the marker.
 */
MarkerClusterer.prototype.resetViewport = function(opt_hide) {
  // Remove all the clusters
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    cluster.remove();
  }

  // Reset the markers to not be added and to be invisible.
  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    marker.isAdded = false;
    if (opt_hide) {
      marker.setMap(null);
    }
  }

  this.clusters_ = [];
};

/**
 *
 */
MarkerClusterer.prototype.repaint = function() {
  var oldClusters = this.clusters_.slice();
  this.clusters_.length = 0;
  this.resetViewport();
  this.redraw();

  // Remove the old clusters.
  // Do it in a timeout so the other clusters have been drawn first.
  window.setTimeout(function() {
    for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
      cluster.remove();
    }
  }, 0);
};


/**
 * Redraws the clusters.
 */
MarkerClusterer.prototype.redraw = function() {
  this.createClusters_();
};


/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
*/
MarkerClusterer.prototype.distanceBetweenPoints_ = function(p1, p2) {
  if (!p1 || !p2) {
    return 0;
  }

  var R = 6371; // Radius of the Earth in km
  var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
  var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d;
};


/**
 * Add a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.addToClosestCluster_ = function(marker) {
  var distance = 40000; // Some large number
  var clusterToAddTo = null;
  var pos = marker.getPosition();
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    var center = cluster.getCenter();
    if (center) {
      var d = this.distanceBetweenPoints_(center, marker.getPosition());
      if (d < distance) {
        distance = d;
        clusterToAddTo = cluster;
      }
    }
  }

  if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
    clusterToAddTo.addMarker(marker);
  } else {
    var cluster = new Cluster(this);
    cluster.addMarker(marker);
    this.clusters_.push(cluster);
  }
};


/**
 * Creates the clusters.
 *
 * @private
 */
MarkerClusterer.prototype.createClusters_ = function() {
  if (!this.ready_) {
    return;
  }

  // Get our current map view bounds.
  // Create a new bounds object so we don't affect the map.
  var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
      this.map_.getBounds().getNorthEast());
  var bounds = this.getExtendedBounds(mapBounds);

  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
      this.addToClosestCluster_(marker);
    }
  }
};


/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */
function Cluster(markerClusterer) {
  this.markerClusterer_ = markerClusterer;
  this.map_ = markerClusterer.getMap();
  this.gridSize_ = markerClusterer.getGridSize();
  this.minClusterSize_ = markerClusterer.getMinClusterSize();
  this.averageCenter_ = markerClusterer.isAverageCenter();
  this.center_ = null;
  this.markers_ = [];
  this.bounds_ = null;
  this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(),
      markerClusterer.getGridSize());
}

/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */
Cluster.prototype.isMarkerAlreadyAdded = function(marker) {
  if (this.markers_.indexOf) {
    return this.markers_.indexOf(marker) != -1;
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        return true;
      }
    }
  }
  return false;
};


/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */
Cluster.prototype.addMarker = function(marker) {
  if (this.isMarkerAlreadyAdded(marker)) {
    return false;
  }

  if (!this.center_) {
    this.center_ = marker.getPosition();
    this.calculateBounds_();
  } else {
    if (this.averageCenter_) {
      var l = this.markers_.length + 1;
      var lat = (this.center_.lat() * (l-1) + marker.getPosition().lat()) / l;
      var lng = (this.center_.lng() * (l-1) + marker.getPosition().lng()) / l;
      this.center_ = new google.maps.LatLng(lat, lng);
      this.calculateBounds_();
    }
  }

  marker.isAdded = true;
  this.markers_.push(marker);

  var len = this.markers_.length;
  if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
    // Min cluster size not reached so show the marker.
    marker.setMap(this.map_);
  }

  if (len == this.minClusterSize_) {
    // Hide the markers that were showing.
    for (var i = 0; i < len; i++) {
      this.markers_[i].setMap(null);
    }
  }

  if (len >= this.minClusterSize_) {
    marker.setMap(null);
  }

  this.updateIcon();
  return true;
};


/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */
Cluster.prototype.getMarkerClusterer = function() {
  return this.markerClusterer_;
};


/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */
Cluster.prototype.getBounds = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  var markers = this.getMarkers();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }
  return bounds;
};


/**
 * Removes the cluster
 */
Cluster.prototype.remove = function() {
  this.clusterIcon_.remove();
  this.markers_.length = 0;
  delete this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */
Cluster.prototype.getSize = function() {
  return this.markers_.length;
};


/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */
Cluster.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */
Cluster.prototype.getCenter = function() {
  return this.center_;
};


/**
 * Calculated the extended bounds of the cluster with the grid.
 *
 * @private
 */
Cluster.prototype.calculateBounds_ = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};


/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */
Cluster.prototype.isMarkerInClusterBounds = function(marker) {
  return this.bounds_.contains(marker.getPosition());
};


/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */
Cluster.prototype.getMap = function() {
  return this.map_;
};


/**
 * Updates the cluster icon
 */
Cluster.prototype.updateIcon = function() {
  var zoom = this.map_.getZoom();
  var mz = this.markerClusterer_.getMaxZoom();

  if (mz && zoom > mz) {
    // The zoom is greater than our max zoom so show all the markers in cluster.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
      marker.setMap(this.map_);
    }
    return;
  }

  if (this.markers_.length < this.minClusterSize_) {
    // Min cluster size not yet reached.
    this.clusterIcon_.hide();
    return;
  }

  var numStyles = this.markerClusterer_.getStyles().length;
  var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
  this.clusterIcon_.setCenter(this.center_);
  this.clusterIcon_.setSums(sums);
  this.clusterIcon_.show();
};


/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 *     'backgroundPosition: (string) The background postition x, y.
 * @param {number=} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */
function ClusterIcon(cluster, styles, opt_padding) {
  cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);

  this.styles_ = styles;
  this.padding_ = opt_padding || 0;
  this.cluster_ = cluster;
  this.center_ = null;
  this.map_ = cluster.getMap();
  this.div_ = null;
  this.sums_ = null;
  this.visible_ = false;

  this.setMap(this.map_);
}


/**
 * Triggers the clusterclick event and zoom's if the option is set.
 *
 * @param {google.maps.MouseEvent} event The event to propagate
 */
ClusterIcon.prototype.triggerClusterClick = function(event) {
  var markerClusterer = this.cluster_.getMarkerClusterer();

  // Trigger the clusterclick event.
  google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_, event);

  if (markerClusterer.isZoomOnClick()) {
    // Zoom into the cluster.
    this.map_.fitBounds(this.cluster_.getBounds());
  }
};


/**
 * Adding the cluster icon to the dom.
 * @ignore
 */
ClusterIcon.prototype.onAdd = function() {
  this.div_ = document.createElement('DIV');
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.innerHTML = this.sums_.text;
  }

  var panes = this.getPanes();
  panes.overlayMouseTarget.appendChild(this.div_);

  var that = this;
  var isDragging = false;
  google.maps.event.addDomListener(this.div_, 'click', function(event) {
    // Only perform click when not preceded by a drag
    if (!isDragging) {
      that.triggerClusterClick(event);
    }
  });
  google.maps.event.addDomListener(this.div_, 'mousedown', function() {
    isDragging = false;
  });
  google.maps.event.addDomListener(this.div_, 'mousemove', function() {
    isDragging = true;
  });
};


/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */
ClusterIcon.prototype.getPosFromLatLng_ = function(latlng) {
  var pos = this.getProjection().fromLatLngToDivPixel(latlng);

  if (typeof this.iconAnchor_ === 'object' && this.iconAnchor_.length === 2) {
    pos.x -= this.iconAnchor_[0];
    pos.y -= this.iconAnchor_[1];
  } else {
    pos.x -= parseInt(this.width_ / 2, 10);
    pos.y -= parseInt(this.height_ / 2, 10);
  }
  return pos;
};


/**
 * Draw the icon.
 * @ignore
 */
ClusterIcon.prototype.draw = function() {
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.top = pos.y + 'px';
    this.div_.style.left = pos.x + 'px';
  }
};


/**
 * Hide the icon.
 */
ClusterIcon.prototype.hide = function() {
  if (this.div_) {
    this.div_.style.display = 'none';
  }
  this.visible_ = false;
};


/**
 * Position and show the icon.
 */
ClusterIcon.prototype.show = function() {
  if (this.div_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.style.display = '';
  }
  this.visible_ = true;
};


/**
 * Remove the icon from the map
 */
ClusterIcon.prototype.remove = function() {
  this.setMap(null);
};


/**
 * Implementation of the onRemove interface.
 * @ignore
 */
ClusterIcon.prototype.onRemove = function() {
  if (this.div_ && this.div_.parentNode) {
    this.hide();
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};


/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */
ClusterIcon.prototype.setSums = function(sums) {
  this.sums_ = sums;
  this.text_ = sums.text;
  this.index_ = sums.index;
  if (this.div_) {
    this.div_.innerHTML = sums.text;
  }

  this.useStyle();
};


/**
 * Sets the icon to the the styles.
 */
ClusterIcon.prototype.useStyle = function() {
  var index = Math.max(0, this.sums_.index - 1);
  index = Math.min(this.styles_.length - 1, index);
  var style = this.styles_[index];
  this.url_ = style['url'];
  this.height_ = style['height'];
  this.width_ = style['width'];
  this.textColor_ = style['textColor'];
  this.anchor_ = style['anchor'];
  this.textSize_ = style['textSize'];
  this.backgroundPosition_ = style['backgroundPosition'];
  this.iconAnchor_ = style['iconAnchor'];
};


/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */
ClusterIcon.prototype.setCenter = function(center) {
  this.center_ = center;
};


/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */
ClusterIcon.prototype.createCss = function(pos) {
  var style = [];
  style.push('background-image:url(' + this.url_ + ');');
  var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
  style.push('background-position:' + backgroundPosition + ';');

  if (typeof this.anchor_ === 'object') {
    if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 &&
        this.anchor_[0] < this.height_) {
      style.push('height:' + (this.height_ - this.anchor_[0]) +
          'px; padding-top:' + this.anchor_[0] + 'px;');
    } else if (typeof this.anchor_[0] === 'number' && this.anchor_[0] < 0 &&
        -this.anchor_[0] < this.height_) {
      style.push('height:' + this.height_ + 'px; line-height:' + (this.height_ + this.anchor_[0]) +
          'px;');
    } else {
      style.push('height:' + this.height_ + 'px; line-height:' + this.height_ +
          'px;');
    }
    if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 &&
        this.anchor_[1] < this.width_) {
      style.push('width:' + (this.width_ - this.anchor_[1]) +
          'px; padding-left:' + this.anchor_[1] + 'px;');
    } else {
      style.push('width:' + this.width_ + 'px; text-align:center;');
    }
  } else {
    style.push('height:' + this.height_ + 'px; line-height:' +
        this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
  }

  var txtColor = this.textColor_ ? this.textColor_ : 'black';
  var txtSize = this.textSize_ ? this.textSize_ : 11;

  style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
      pos.x + 'px; color:' + txtColor + '; position:absolute; font-size:' +
      txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
  return style.join('');
};


// Export Symbols for Closure
// If you are not going to compile with closure then you can remove the
// code below.
window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] =
    MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['fitMapToMarkers'] =
    MarkerClusterer.prototype.fitMapToMarkers;
MarkerClusterer.prototype['getCalculator'] =
    MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] =
    MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getExtendedBounds'] =
    MarkerClusterer.prototype.getExtendedBounds;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getTotalClusters'] =
    MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] =
    MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] =
    MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['removeMarkers'] =
    MarkerClusterer.prototype.removeMarkers;
MarkerClusterer.prototype['resetViewport'] =
    MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['repaint'] =
    MarkerClusterer.prototype.repaint;
MarkerClusterer.prototype['setCalculator'] =
    MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] =
    MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['setMaxZoom'] =
    MarkerClusterer.prototype.setMaxZoom;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;

Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;

ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;

MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ = './images/m';  //changed image path


/***/ })

}]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvY29yZS1qcy9pbnRlcm5hbHMvY3JlYXRlLWh0bWwuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2NvcmUtanMvaW50ZXJuYWxzL3N0cmluZy1odG1sLWZvcmNlZC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvY29yZS1qcy9tb2R1bGVzL2VzLnN0cmluZy5saW5rLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9tYXJrZXItY2x1c3RlcmVyL21hcmtlci1jbHVzdGVyZXIuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7O0FBQUEsa0JBQWtCLG1CQUFPLENBQUMscUdBQW9DO0FBQzlELDZCQUE2QixtQkFBTyxDQUFDLDJHQUF1QztBQUM1RSxlQUFlLG1CQUFPLENBQUMsNkVBQXdCOztBQUUvQztBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2RkFBNkY7QUFDN0Y7QUFDQTs7Ozs7Ozs7Ozs7O0FDZEEsWUFBWSxtQkFBTyxDQUFDLHFFQUFvQjs7QUFFeEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIOzs7Ozs7Ozs7Ozs7O0FDVGE7QUFDYixRQUFRLG1CQUFPLENBQUMsdUVBQXFCO0FBQ3JDLGlCQUFpQixtQkFBTyxDQUFDLGlGQUEwQjtBQUNuRCw2QkFBNkIsbUJBQU8sQ0FBQywrRkFBaUM7O0FBRXRFO0FBQ0E7QUFDQSxHQUFHLHdFQUF3RTtBQUMzRTtBQUNBO0FBQ0E7QUFDQSxDQUFDOzs7Ozs7Ozs7Ozs7QUNYRDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFD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Q0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBLFdBQVcsdUJBQXVCO0FBQ2xDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7OztBQUdBO0FBQ0E7QUFDQTtBQUNBLFdBQVcsbUJBQW1CO0FBQzlCLFlBQVksa0JBQWtCO0FBQzlCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0EsV0FBVyxPQUFPO0FBQ2xCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQSxXQUFXLG1CQUFtQjtBQUM5QjtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0EsV0FBVyxrQkFBa0I7QUFDN0IsWUFBWSxPQUFPO0FBQ25CO0FBQ0E7QUFDQTtBQUNBLHNEQUFzRDtBQUN0RDtBQUNBLDZEQUE2RDs7QUFFN0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxjQUFjLHVDQUF1QztBQUNyRCxLQUFLO0FBQ0w7QUFDQSxnREFBZ0Q7QUFDaEQsY0FBYztBQUNkLEtBQUs7QUFDTCxnREFBZ0Q7QUFDaEQsY0FBYztBQUNkO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyx3Q0FBd0M7QUFDdEQsS0FBSztBQUNMLDhDQUE4QyxtQkFBbUI7QUFDakU7QUFDQSxHQUFHO0FBQ0gsOENBQThDO0FBQzlDLDJCQUEyQiw2QkFBNkIsbUJBQW1CO0FBQzNFOztBQUVBO0FBQ0E7O0FBRUEsNkJBQTZCLHFCQUFxQjtBQUNsRCxrQkFBa0Isd0JBQXdCLG1CQUFtQjtBQUM3RCxvQkFBb0IsOEJBQThCO0FBQ2xEO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSxvRUFBb0UiLCJmaWxlIjoidmVuZG9yc35ldmVudHN+b3JnYW5pemVycHJvZmlsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbInZhciB1bmN1cnJ5VGhpcyA9IHJlcXVpcmUoJy4uL2ludGVybmFscy9mdW5jdGlvbi11bmN1cnJ5LXRoaXMnKTtcbnZhciByZXF1aXJlT2JqZWN0Q29lcmNpYmxlID0gcmVxdWlyZSgnLi4vaW50ZXJuYWxzL3JlcXVpcmUtb2JqZWN0LWNvZXJjaWJsZScpO1xudmFyIHRvU3RyaW5nID0gcmVxdWlyZSgnLi4vaW50ZXJuYWxzL3RvLXN0cmluZycpO1xuXG52YXIgcXVvdCA9IC9cIi9nO1xudmFyIHJlcGxhY2UgPSB1bmN1cnJ5VGhpcygnJy5yZXBsYWNlKTtcblxuLy8gYENyZWF0ZUhUTUxgIGFic3RyYWN0IG9wZXJhdGlvblxuLy8gaHR0cHM6Ly90YzM5LmVzL2VjbWEyNjIvI3NlYy1jcmVhdGVodG1sXG5tb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uIChzdHJpbmcsIHRhZywgYXR0cmlidXRlLCB2YWx1ZSkge1xuICB2YXIgUyA9IHRvU3RyaW5nKHJlcXVpcmVPYmplY3RDb2VyY2libGUoc3RyaW5nKSk7XG4gIHZhciBwMSA9ICc8JyArIHRhZztcbiAgaWYgKGF0dHJpYnV0ZSAhPT0gJycpIHAxICs9ICcgJyArIGF0dHJpYnV0ZSArICc9XCInICsgcmVwbGFjZSh0b1N0cmluZyh2YWx1ZSksIHF1b3QsICcmcXVvdDsnKSArICdcIic7XG4gIHJldHVybiBwMSArICc+JyArIFMgKyAnPC8nICsgdGFnICsgJz4nO1xufTtcbiIsInZhciBmYWlscyA9IHJlcXVpcmUoJy4uL2ludGVybmFscy9mYWlscycpO1xuXG4vLyBjaGVjayB0aGUgZXhpc3RlbmNlIG9mIGEgbWV0aG9kLCBsb3dlcmNhc2Vcbi8vIG9mIGEgdGFnIGFuZCBlc2NhcGluZyBxdW90ZXMgaW4gYXJndW1lbnRzXG5tb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uIChNRVRIT0RfTkFNRSkge1xuICByZXR1cm4gZmFpbHMoZnVuY3Rpb24gKCkge1xuICAgIHZhciB0ZXN0ID0gJydbTUVUSE9EX05BTUVdKCdcIicpO1xuICAgIHJldHVybiB0ZXN0ICE9PSB0ZXN0LnRvTG93ZXJDYXNlKCkgfHwgdGVzdC5zcGxpdCgnXCInKS5sZW5ndGggPiAzO1xuICB9KTtcbn07XG4iLCIndXNlIHN0cmljdCc7XG52YXIgJCA9IHJlcXVpcmUoJy4uL2ludGVybmFscy9leHBvcnQnKTtcbnZhciBjcmVhdGVIVE1MID0gcmVxdWlyZSgnLi4vaW50ZXJuYWxzL2NyZWF0ZS1odG1sJyk7XG52YXIgZm9yY2VkU3RyaW5nSFRNTE1ldGhvZCA9IHJlcXVpcmUoJy4uL2ludGVybmFscy9zdHJpbmctaHRtbC1mb3JjZWQnKTtcblxuLy8gYFN0cmluZy5wcm90b3R5cGUubGlua2AgbWV0aG9kXG4vLyBodHRwczovL3RjMzkuZXMvZWNtYTI2Mi8jc2VjLXN0cmluZy5wcm90b3R5cGUubGlua1xuJCh7IHRhcmdldDogJ1N0cmluZycsIHByb3RvOiB0cnVlLCBmb3JjZWQ6IGZvcmNlZFN0cmluZ0hUTUxNZXRob2QoJ2xpbmsnKSB9LCB7XG4gIGxpbms6IGZ1bmN0aW9uIGxpbmsodXJsKSB7XG4gICAgcmV0dXJuIGNyZWF0ZUhUTUwodGhpcywgJ2EnLCAnaHJlZicsIHVybCk7XG4gIH1cbn0pO1xuIiwiLy8gPT1DbG9zdXJlQ29tcGlsZXI9PVxyXG4vLyBAY29tcGlsYXRpb25fbGV2ZWwgQURWQU5DRURfT1BUSU1JWkFUSU9OU1xyXG4vLyBAZXh0ZXJuc191cmwgaHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL2dvb2dsZS9jbG9zdXJlLWNvbXBpbGVyL21hc3Rlci9jb250cmliL2V4dGVybnMvbWFwcy9nb29nbGVfbWFwc19hcGlfdjMuanNcclxuLy8gPT0vQ2xvc3VyZUNvbXBpbGVyPT1cclxuXHJcbi8qKlxyXG4gKiBAbmFtZSBNYXJrZXJDbHVzdGVyZXIgZm9yIEdvb2dsZSBNYXBzIHYzXHJcbiAqIEB2ZXJzaW9uIHZlcnNpb24gMS4wXHJcbiAqIEBhdXRob3IgTHVrZSBNYWhlXHJcbiAqIEBmaWxlb3ZlcnZpZXdcclxuICogVGhlIGxpYnJhcnkgY3JlYXRlcyBhbmQgbWFuYWdlcyBwZXItem9vbS1sZXZlbCBjbHVzdGVycyBmb3IgbGFyZ2UgYW1vdW50cyBvZlxyXG4gKiBtYXJrZXJzLlxyXG4gKiA8YnIvPlxyXG4gKiBUaGlzIGlzIGEgdjMgaW1wbGVtZW50YXRpb24gb2YgdGhlXHJcbiAqIDxhIGhyZWY9XCJodHRwOi8vZ21hcHMtdXRpbGl0eS1saWJyYXJ5LWRldi5nb29nbGVjb2RlLmNvbS9zdm4vdGFncy9tYXJrZXJjbHVzdGVyZXIvXCJcclxuICogPnYyIE1hcmtlckNsdXN0ZXJlcjwvYT4uXHJcbiAqL1xyXG5cclxuLyoqXHJcbiAqIEBsaWNlbnNlXHJcbiAqIENvcHlyaWdodCAyMDEwIEdvb2dsZSBJbmMuIEFsbCBSaWdodHMgUmVzZXJ2ZWQuXHJcbiAqXHJcbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XHJcbiAqIHlvdSBtYXkgbm90IHVzZSB0aGlzIGZpbGUgZXhjZXB0IGluIGNvbXBsaWFuY2Ugd2l0aCB0aGUgTGljZW5zZS5cclxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XHJcbiAqXHJcbiAqICAgICBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcclxuICpcclxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxyXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXHJcbiAqIFdJVEhPVVQgV0FSUkFOVElFUyBPUiBDT05ESVRJT05TIE9GIEFOWSBLSU5ELCBlaXRoZXIgZXhwcmVzcyBvciBpbXBsaWVkLlxyXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXHJcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxyXG4gKi9cclxuXHJcblxyXG4vKipcclxuICogQSBNYXJrZXIgQ2x1c3RlcmVyIHRoYXQgY2x1c3RlcnMgbWFya2Vycy5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5NYXB9IG1hcCBUaGUgR29vZ2xlIG1hcCB0byBhdHRhY2ggdG8uXHJcbiAqIEBwYXJhbSB7QXJyYXkuPGdvb2dsZS5tYXBzLk1hcmtlcj49fSBvcHRfbWFya2VycyBPcHRpb25hbCBtYXJrZXJzIHRvIGFkZCB0b1xyXG4gKiAgIHRoZSBjbHVzdGVyLlxyXG4gKiBAcGFyYW0ge09iamVjdD19IG9wdF9vcHRpb25zIHN1cHBvcnQgdGhlIGZvbGxvd2luZyBvcHRpb25zOlxyXG4gKiAgICAgJ2dyaWRTaXplJzogKG51bWJlcikgVGhlIGdyaWQgc2l6ZSBvZiBhIGNsdXN0ZXIgaW4gcGl4ZWxzLlxyXG4gKiAgICAgJ21heFpvb20nOiAobnVtYmVyKSBUaGUgbWF4aW11bSB6b29tIGxldmVsIHRoYXQgYSBtYXJrZXIgY2FuIGJlIHBhcnQgb2YgYVxyXG4gKiAgICAgICAgICAgICAgICBjbHVzdGVyLlxyXG4gKiAgICAgJ3pvb21PbkNsaWNrJzogKGJvb2xlYW4pIFdoZXRoZXIgdGhlIGRlZmF1bHQgYmVoYXZpb3VyIG9mIGNsaWNraW5nIG9uIGFcclxuICogICAgICAgICAgICAgICAgICAgIGNsdXN0ZXIgaXMgdG8gem9vbSBpbnRvIGl0LlxyXG4gKiAgICAgJ2F2ZXJhZ2VDZW50ZXInOiAoYm9vbGVhbikgV2hldGhlciB0aGUgY2VudGVyIG9mIGVhY2ggY2x1c3RlciBzaG91bGQgYmVcclxuICogICAgICAgICAgICAgICAgICAgICAgdGhlIGF2ZXJhZ2Ugb2YgYWxsIG1hcmtlcnMgaW4gdGhlIGNsdXN0ZXIuXHJcbiAqICAgICAnbWluaW11bUNsdXN0ZXJTaXplJzogKG51bWJlcikgVGhlIG1pbmltdW0gbnVtYmVyIG9mIG1hcmtlcnMgdG8gYmUgaW4gYVxyXG4gKiAgICAgICAgICAgICAgICAgICAgICAgICAgIGNsdXN0ZXIgYmVmb3JlIHRoZSBtYXJrZXJzIGFyZSBoaWRkZW4gYW5kIGEgY291bnRcclxuICogICAgICAgICAgICAgICAgICAgICAgICAgICBpcyBzaG93bi5cclxuICogICAgICdzdHlsZXMnOiAob2JqZWN0KSBBbiBvYmplY3QgdGhhdCBoYXMgc3R5bGUgcHJvcGVydGllczpcclxuICogICAgICAgJ3VybCc6IChzdHJpbmcpIFRoZSBpbWFnZSB1cmwuXHJcbiAqICAgICAgICdoZWlnaHQnOiAobnVtYmVyKSBUaGUgaW1hZ2UgaGVpZ2h0LlxyXG4gKiAgICAgICAnd2lkdGgnOiAobnVtYmVyKSBUaGUgaW1hZ2Ugd2lkdGguXHJcbiAqICAgICAgICdhbmNob3InOiAoQXJyYXkpIFRoZSBhbmNob3IgcG9zaXRpb24gb2YgdGhlIGxhYmVsIHRleHQuXHJcbiAqICAgICAgICd0ZXh0Q29sb3InOiAoc3RyaW5nKSBUaGUgdGV4dCBjb2xvci5cclxuICogICAgICAgJ3RleHRTaXplJzogKG51bWJlcikgVGhlIHRleHQgc2l6ZS5cclxuICogICAgICAgJ2JhY2tncm91bmRQb3NpdGlvbic6IChzdHJpbmcpIFRoZSBwb3NpdGlvbiBvZiB0aGUgYmFja2dvdW5kIHgsIHkuXHJcbiAqICAgICAgICdpY29uQW5jaG9yJzogKEFycmF5KSBUaGUgYW5jaG9yIHBvc2l0aW9uIG9mIHRoZSBpY29uIHgsIHkuXHJcbiAqIEBjb25zdHJ1Y3RvclxyXG4gKiBAZXh0ZW5kcyBnb29nbGUubWFwcy5PdmVybGF5Vmlld1xyXG4gKi9cclxuZnVuY3Rpb24gTWFya2VyQ2x1c3RlcmVyKG1hcCwgb3B0X21hcmtlcnMsIG9wdF9vcHRpb25zKSB7XHJcbiAgLy8gTWFya2VyQ2x1c3RlcmVyIGltcGxlbWVudHMgZ29vZ2xlLm1hcHMuT3ZlcmxheVZpZXcgaW50ZXJmYWNlLiBXZSB1c2UgdGhlXHJcbiAgLy8gZXh0ZW5kIGZ1bmN0aW9uIHRvIGV4dGVuZCBNYXJrZXJDbHVzdGVyZXIgd2l0aCBnb29nbGUubWFwcy5PdmVybGF5Vmlld1xyXG4gIC8vIGJlY2F1c2UgaXQgbWlnaHQgbm90IGFsd2F5cyBiZSBhdmFpbGFibGUgd2hlbiB0aGUgY29kZSBpcyBkZWZpbmVkIHNvIHdlXHJcbiAgLy8gbG9vayBmb3IgaXQgYXQgdGhlIGxhc3QgcG9zc2libGUgbW9tZW50LiBJZiBpdCBkb2Vzbid0IGV4aXN0IG5vdyB0aGVuXHJcbiAgLy8gdGhlcmUgaXMgbm8gcG9pbnQgZ29pbmcgYWhlYWQgOilcclxuICB0aGlzLmV4dGVuZChNYXJrZXJDbHVzdGVyZXIsIGdvb2dsZS5tYXBzLk92ZXJsYXlWaWV3KTtcclxuICB0aGlzLm1hcF8gPSBtYXA7XHJcblxyXG4gIC8qKlxyXG4gICAqIEB0eXBlIHtBcnJheS48Z29vZ2xlLm1hcHMuTWFya2VyPn1cclxuICAgKiBAcHJpdmF0ZVxyXG4gICAqL1xyXG4gIHRoaXMubWFya2Vyc18gPSBbXTtcclxuXHJcbiAgLyoqXHJcbiAgICogIEB0eXBlIHtBcnJheS48Q2x1c3Rlcj59XHJcbiAgICovXHJcbiAgdGhpcy5jbHVzdGVyc18gPSBbXTtcclxuXHJcbiAgdGhpcy5zaXplcyA9IFs1MywgNTYsIDY2LCA3OCwgOTBdO1xyXG5cclxuICAvKipcclxuICAgKiBAcHJpdmF0ZVxyXG4gICAqL1xyXG4gIHRoaXMuc3R5bGVzXyA9IFtdO1xyXG5cclxuICAvKipcclxuICAgKiBAdHlwZSB7Ym9vbGVhbn1cclxuICAgKiBAcHJpdmF0ZVxyXG4gICAqL1xyXG4gIHRoaXMucmVhZHlfID0gZmFsc2U7XHJcblxyXG4gIHZhciBvcHRpb25zID0gb3B0X29wdGlvbnMgfHwge307XHJcblxyXG4gIC8qKlxyXG4gICAqIEB0eXBlIHtudW1iZXJ9XHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICB0aGlzLmdyaWRTaXplXyA9IG9wdGlvbnNbJ2dyaWRTaXplJ10gfHwgNjA7XHJcblxyXG4gIC8qKlxyXG4gICAqIEBwcml2YXRlXHJcbiAgICovXHJcbiAgdGhpcy5taW5DbHVzdGVyU2l6ZV8gPSBvcHRpb25zWydtaW5pbXVtQ2x1c3RlclNpemUnXSB8fCAyO1xyXG5cclxuXHJcbiAgLyoqXHJcbiAgICogQHR5cGUgez9udW1iZXJ9XHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICB0aGlzLm1heFpvb21fID0gb3B0aW9uc1snbWF4Wm9vbSddIHx8IG51bGw7XHJcblxyXG4gIHRoaXMuc3R5bGVzXyA9IG9wdGlvbnNbJ3N0eWxlcyddIHx8IFtdO1xyXG5cclxuICAvKipcclxuICAgKiBAdHlwZSB7c3RyaW5nfVxyXG4gICAqIEBwcml2YXRlXHJcbiAgICovXHJcbiAgdGhpcy5pbWFnZVBhdGhfID0gb3B0aW9uc1snaW1hZ2VQYXRoJ10gfHxcclxuICAgICAgdGhpcy5NQVJLRVJfQ0xVU1RFUl9JTUFHRV9QQVRIXztcclxuXHJcbiAgLyoqXHJcbiAgICogQHR5cGUge3N0cmluZ31cclxuICAgKiBAcHJpdmF0ZVxyXG4gICAqL1xyXG4gIHRoaXMuaW1hZ2VFeHRlbnNpb25fID0gb3B0aW9uc1snaW1hZ2VFeHRlbnNpb24nXSB8fFxyXG4gICAgICB0aGlzLk1BUktFUl9DTFVTVEVSX0lNQUdFX0VYVEVOU0lPTl87XHJcblxyXG4gIC8qKlxyXG4gICAqIEB0eXBlIHtib29sZWFufVxyXG4gICAqIEBwcml2YXRlXHJcbiAgICovXHJcbiAgdGhpcy56b29tT25DbGlja18gPSB0cnVlO1xyXG5cclxuICBpZiAob3B0aW9uc1snem9vbU9uQ2xpY2snXSAhPSB1bmRlZmluZWQpIHtcclxuICAgIHRoaXMuem9vbU9uQ2xpY2tfID0gb3B0aW9uc1snem9vbU9uQ2xpY2snXTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIEB0eXBlIHtib29sZWFufVxyXG4gICAqIEBwcml2YXRlXHJcbiAgICovXHJcbiAgdGhpcy5hdmVyYWdlQ2VudGVyXyA9IGZhbHNlO1xyXG5cclxuICBpZiAob3B0aW9uc1snYXZlcmFnZUNlbnRlciddICE9IHVuZGVmaW5lZCkge1xyXG4gICAgdGhpcy5hdmVyYWdlQ2VudGVyXyA9IG9wdGlvbnNbJ2F2ZXJhZ2VDZW50ZXInXTtcclxuICB9XHJcblxyXG4gIHRoaXMuc2V0dXBTdHlsZXNfKCk7XHJcblxyXG4gIHRoaXMuc2V0TWFwKG1hcCk7XHJcblxyXG4gIC8qKlxyXG4gICAqIEB0eXBlIHtudW1iZXJ9XHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICB0aGlzLnByZXZab29tXyA9IHRoaXMubWFwXy5nZXRab29tKCk7XHJcblxyXG4gIC8vIEFkZCB0aGUgbWFwIGV2ZW50IGxpc3RlbmVyc1xyXG4gIHZhciB0aGF0ID0gdGhpcztcclxuICBnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcih0aGlzLm1hcF8sICd6b29tX2NoYW5nZWQnLCBmdW5jdGlvbigpIHtcclxuICAgIHZhciB6b29tID0gdGhhdC5tYXBfLmdldFpvb20oKTtcclxuXHJcbiAgICBpZiAodGhhdC5wcmV2Wm9vbV8gIT0gem9vbSkge1xyXG4gICAgICB0aGF0LnByZXZab29tXyA9IHpvb207XHJcbiAgICAgIHRoYXQucmVzZXRWaWV3cG9ydCgpO1xyXG4gICAgfVxyXG4gIH0pO1xyXG5cclxuICBnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcih0aGlzLm1hcF8sICdpZGxlJywgZnVuY3Rpb24oKSB7XHJcbiAgICB0aGF0LnJlZHJhdygpO1xyXG4gIH0pO1xyXG5cclxuICAvLyBGaW5hbGx5LCBhZGQgdGhlIG1hcmtlcnNcclxuICBpZiAob3B0X21hcmtlcnMgJiYgb3B0X21hcmtlcnMubGVuZ3RoKSB7XHJcbiAgICB0aGlzLmFkZE1hcmtlcnMob3B0X21hcmtlcnMsIGZhbHNlKTtcclxuICB9XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogVGhlIG1hcmtlciBjbHVzdGVyIGltYWdlIHBhdGguXHJcbiAqXHJcbiAqIEB0eXBlIHtzdHJpbmd9XHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLk1BUktFUl9DTFVTVEVSX0lNQUdFX1BBVEhfID0gJy4uL2ltYWdlcy9tJztcclxuXHJcblxyXG4vKipcclxuICogVGhlIG1hcmtlciBjbHVzdGVyIGltYWdlIHBhdGguXHJcbiAqXHJcbiAqIEB0eXBlIHtzdHJpbmd9XHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLk1BUktFUl9DTFVTVEVSX0lNQUdFX0VYVEVOU0lPTl8gPSAncG5nJztcclxuXHJcblxyXG4vKipcclxuICogRXh0ZW5kcyBhIG9iamVjdHMgcHJvdG90eXBlIGJ5IGFub3RoZXJzLlxyXG4gKlxyXG4gKiBAcGFyYW0ge09iamVjdH0gb2JqMSBUaGUgb2JqZWN0IHRvIGJlIGV4dGVuZGVkLlxyXG4gKiBAcGFyYW0ge09iamVjdH0gb2JqMiBUaGUgb2JqZWN0IHRvIGV4dGVuZCB3aXRoLlxyXG4gKiBAcmV0dXJuIHtPYmplY3R9IFRoZSBuZXcgZXh0ZW5kZWQgb2JqZWN0LlxyXG4gKiBAaWdub3JlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmV4dGVuZCA9IGZ1bmN0aW9uKG9iajEsIG9iajIpIHtcclxuICByZXR1cm4gKGZ1bmN0aW9uKG9iamVjdCkge1xyXG4gICAgZm9yICh2YXIgcHJvcGVydHkgaW4gb2JqZWN0LnByb3RvdHlwZSkge1xyXG4gICAgICB0aGlzLnByb3RvdHlwZVtwcm9wZXJ0eV0gPSBvYmplY3QucHJvdG90eXBlW3Byb3BlcnR5XTtcclxuICAgIH1cclxuICAgIHJldHVybiB0aGlzO1xyXG4gIH0pLmFwcGx5KG9iajEsIFtvYmoyXSk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIEltcGxlbWVudGFpb24gb2YgdGhlIGludGVyZmFjZSBtZXRob2QuXHJcbiAqIEBpZ25vcmVcclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUub25BZGQgPSBmdW5jdGlvbigpIHtcclxuICB0aGlzLnNldFJlYWR5Xyh0cnVlKTtcclxufTtcclxuXHJcbi8qKlxyXG4gKiBJbXBsZW1lbnRhaW9uIG9mIHRoZSBpbnRlcmZhY2UgbWV0aG9kLlxyXG4gKiBAaWdub3JlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmRyYXcgPSBmdW5jdGlvbigpIHt9O1xyXG5cclxuLyoqXHJcbiAqIFNldHMgdXAgdGhlIHN0eWxlcyBvYmplY3QuXHJcbiAqXHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnNldHVwU3R5bGVzXyA9IGZ1bmN0aW9uKCkge1xyXG4gIGlmICh0aGlzLnN0eWxlc18ubGVuZ3RoKSB7XHJcbiAgICByZXR1cm47XHJcbiAgfVxyXG5cclxuICBmb3IgKHZhciBpID0gMCwgc2l6ZTsgc2l6ZSA9IHRoaXMuc2l6ZXNbaV07IGkrKykge1xyXG4gICAgdGhpcy5zdHlsZXNfLnB1c2goe1xyXG4gICAgICB1cmw6IHRoaXMuaW1hZ2VQYXRoXyArIChpICsgMSkgKyAnLicgKyB0aGlzLmltYWdlRXh0ZW5zaW9uXyxcclxuICAgICAgaGVpZ2h0OiBzaXplLFxyXG4gICAgICB3aWR0aDogc2l6ZVxyXG4gICAgfSk7XHJcbiAgfVxyXG59O1xyXG5cclxuLyoqXHJcbiAqICBGaXQgdGhlIG1hcCB0byB0aGUgYm91bmRzIG9mIHRoZSBtYXJrZXJzIGluIHRoZSBjbHVzdGVyZXIuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmZpdE1hcFRvTWFya2VycyA9IGZ1bmN0aW9uKCkge1xyXG4gIHZhciBtYXJrZXJzID0gdGhpcy5nZXRNYXJrZXJzKCk7XHJcbiAgdmFyIGJvdW5kcyA9IG5ldyBnb29nbGUubWFwcy5MYXRMbmdCb3VuZHMoKTtcclxuICBmb3IgKHZhciBpID0gMCwgbWFya2VyOyBtYXJrZXIgPSBtYXJrZXJzW2ldOyBpKyspIHtcclxuICAgIGJvdW5kcy5leHRlbmQobWFya2VyLmdldFBvc2l0aW9uKCkpO1xyXG4gIH1cclxuXHJcbiAgdGhpcy5tYXBfLmZpdEJvdW5kcyhib3VuZHMpO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiAgU2V0cyB0aGUgc3R5bGVzLlxyXG4gKlxyXG4gKiAgQHBhcmFtIHtPYmplY3R9IHN0eWxlcyBUaGUgc3R5bGUgdG8gc2V0LlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRTdHlsZXMgPSBmdW5jdGlvbihzdHlsZXMpIHtcclxuICB0aGlzLnN0eWxlc18gPSBzdHlsZXM7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqICBHZXRzIHRoZSBzdHlsZXMuXHJcbiAqXHJcbiAqICBAcmV0dXJuIHtPYmplY3R9IFRoZSBzdHlsZXMgb2JqZWN0LlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRTdHlsZXMgPSBmdW5jdGlvbigpIHtcclxuICByZXR1cm4gdGhpcy5zdHlsZXNfO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBXaGV0aGVyIHpvb20gb24gY2xpY2sgaXMgc2V0LlxyXG4gKlxyXG4gKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIGlmIHpvb21PbkNsaWNrXyBpcyBzZXQuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmlzWm9vbU9uQ2xpY2sgPSBmdW5jdGlvbigpIHtcclxuICByZXR1cm4gdGhpcy56b29tT25DbGlja187XHJcbn07XHJcblxyXG4vKipcclxuICogV2hldGhlciBhdmVyYWdlIGNlbnRlciBpcyBzZXQuXHJcbiAqXHJcbiAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgaWYgYXZlcmFnZUNlbnRlcl8gaXMgc2V0LlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5pc0F2ZXJhZ2VDZW50ZXIgPSBmdW5jdGlvbigpIHtcclxuICByZXR1cm4gdGhpcy5hdmVyYWdlQ2VudGVyXztcclxufTtcclxuXHJcblxyXG4vKipcclxuICogIFJldHVybnMgdGhlIGFycmF5IG9mIG1hcmtlcnMgaW4gdGhlIGNsdXN0ZXJlci5cclxuICpcclxuICogIEByZXR1cm4ge0FycmF5Ljxnb29nbGUubWFwcy5NYXJrZXI+fSBUaGUgbWFya2Vycy5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0TWFya2VycyA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1hcmtlcnNfO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiAgUmV0dXJucyB0aGUgbnVtYmVyIG9mIG1hcmtlcnMgaW4gdGhlIGNsdXN0ZXJlclxyXG4gKlxyXG4gKiAgQHJldHVybiB7TnVtYmVyfSBUaGUgbnVtYmVyIG9mIG1hcmtlcnMuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmdldFRvdGFsTWFya2VycyA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1hcmtlcnNfLmxlbmd0aDtcclxufTtcclxuXHJcblxyXG4vKipcclxuICogIFNldHMgdGhlIG1heCB6b29tIGZvciB0aGUgY2x1c3RlcmVyLlxyXG4gKlxyXG4gKiAgQHBhcmFtIHtudW1iZXJ9IG1heFpvb20gVGhlIG1heCB6b29tIGxldmVsLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRNYXhab29tID0gZnVuY3Rpb24obWF4Wm9vbSkge1xyXG4gIHRoaXMubWF4Wm9vbV8gPSBtYXhab29tO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiAgR2V0cyB0aGUgbWF4IHpvb20gZm9yIHRoZSBjbHVzdGVyZXIuXHJcbiAqXHJcbiAqICBAcmV0dXJuIHtudW1iZXJ9IFRoZSBtYXggem9vbSBsZXZlbC5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0TWF4Wm9vbSA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1heFpvb21fO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiAgVGhlIGZ1bmN0aW9uIGZvciBjYWxjdWxhdGluZyB0aGUgY2x1c3RlciBpY29uIGltYWdlLlxyXG4gKlxyXG4gKiAgQHBhcmFtIHtBcnJheS48Z29vZ2xlLm1hcHMuTWFya2VyPn0gbWFya2VycyBUaGUgbWFya2VycyBpbiB0aGUgY2x1c3RlcmVyLlxyXG4gKiAgQHBhcmFtIHtudW1iZXJ9IG51bVN0eWxlcyBUaGUgbnVtYmVyIG9mIHN0eWxlcyBhdmFpbGFibGUuXHJcbiAqICBAcmV0dXJuIHtPYmplY3R9IEEgb2JqZWN0IHByb3BlcnRpZXM6ICd0ZXh0JyAoc3RyaW5nKSBhbmQgJ2luZGV4JyAobnVtYmVyKS5cclxuICogIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmNhbGN1bGF0b3JfID0gZnVuY3Rpb24obWFya2VycywgbnVtU3R5bGVzKSB7XHJcbiAgdmFyIGluZGV4ID0gMDtcclxuICB2YXIgY291bnQgPSBtYXJrZXJzLmxlbmd0aDtcclxuICB2YXIgZHYgPSBjb3VudDtcclxuICB3aGlsZSAoZHYgIT09IDApIHtcclxuICAgIGR2ID0gcGFyc2VJbnQoZHYgLyAxMCwgMTApO1xyXG4gICAgaW5kZXgrKztcclxuICB9XHJcblxyXG4gIGluZGV4ID0gTWF0aC5taW4oaW5kZXgsIG51bVN0eWxlcyk7XHJcbiAgcmV0dXJuIHtcclxuICAgIHRleHQ6IGNvdW50LFxyXG4gICAgaW5kZXg6IGluZGV4XHJcbiAgfTtcclxufTtcclxuXHJcblxyXG4vKipcclxuICogU2V0IHRoZSBjYWxjdWxhdG9yIGZ1bmN0aW9uLlxyXG4gKlxyXG4gKiBAcGFyYW0ge2Z1bmN0aW9uKEFycmF5LCBudW1iZXIpfSBjYWxjdWxhdG9yIFRoZSBmdW5jdGlvbiB0byBzZXQgYXMgdGhlXHJcbiAqICAgICBjYWxjdWxhdG9yLiBUaGUgZnVuY3Rpb24gc2hvdWxkIHJldHVybiBhIG9iamVjdCBwcm9wZXJ0aWVzOlxyXG4gKiAgICAgJ3RleHQnIChzdHJpbmcpIGFuZCAnaW5kZXgnIChudW1iZXIpLlxyXG4gKlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRDYWxjdWxhdG9yID0gZnVuY3Rpb24oY2FsY3VsYXRvcikge1xyXG4gIHRoaXMuY2FsY3VsYXRvcl8gPSBjYWxjdWxhdG9yO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBHZXQgdGhlIGNhbGN1bGF0b3IgZnVuY3Rpb24uXHJcbiAqXHJcbiAqIEByZXR1cm4ge2Z1bmN0aW9uKEFycmF5LCBudW1iZXIpfSB0aGUgY2FsY3VsYXRvciBmdW5jdGlvbi5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0Q2FsY3VsYXRvciA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLmNhbGN1bGF0b3JfO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBBZGQgYW4gYXJyYXkgb2YgbWFya2VycyB0byB0aGUgY2x1c3RlcmVyLlxyXG4gKlxyXG4gKiBAcGFyYW0ge0FycmF5Ljxnb29nbGUubWFwcy5NYXJrZXI+fSBtYXJrZXJzIFRoZSBtYXJrZXJzIHRvIGFkZC5cclxuICogQHBhcmFtIHtib29sZWFuPX0gb3B0X25vZHJhdyBXaGV0aGVyIHRvIHJlZHJhdyB0aGUgY2x1c3RlcnMuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmFkZE1hcmtlcnMgPSBmdW5jdGlvbihtYXJrZXJzLCBvcHRfbm9kcmF3KSB7XHJcbiAgZm9yICh2YXIgaSA9IDAsIG1hcmtlcjsgbWFya2VyID0gbWFya2Vyc1tpXTsgaSsrKSB7XHJcbiAgICB0aGlzLnB1c2hNYXJrZXJUb18obWFya2VyKTtcclxuICB9XHJcbiAgaWYgKCFvcHRfbm9kcmF3KSB7XHJcbiAgICB0aGlzLnJlZHJhdygpO1xyXG4gIH1cclxufTtcclxuXHJcblxyXG4vKipcclxuICogUHVzaGVzIGEgbWFya2VyIHRvIHRoZSBjbHVzdGVyZXIuXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTWFya2VyfSBtYXJrZXIgVGhlIG1hcmtlciB0byBhZGQuXHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnB1c2hNYXJrZXJUb18gPSBmdW5jdGlvbihtYXJrZXIpIHtcclxuICBtYXJrZXIuaXNBZGRlZCA9IGZhbHNlO1xyXG4gIGlmIChtYXJrZXJbJ2RyYWdnYWJsZSddKSB7XHJcbiAgICAvLyBJZiB0aGUgbWFya2VyIGlzIGRyYWdnYWJsZSBhZGQgYSBsaXN0ZW5lciBzbyB3ZSB1cGRhdGUgdGhlIGNsdXN0ZXJzIG9uXHJcbiAgICAvLyB0aGUgZHJhZyBlbmQuXHJcbiAgICB2YXIgdGhhdCA9IHRoaXM7XHJcbiAgICBnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcihtYXJrZXIsICdkcmFnZW5kJywgZnVuY3Rpb24oKSB7XHJcbiAgICAgIG1hcmtlci5pc0FkZGVkID0gZmFsc2U7XHJcbiAgICAgIHRoYXQucmVwYWludCgpO1xyXG4gICAgfSk7XHJcbiAgfVxyXG4gIHRoaXMubWFya2Vyc18ucHVzaChtYXJrZXIpO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBBZGRzIGEgbWFya2VyIHRvIHRoZSBjbHVzdGVyZXIgYW5kIHJlZHJhd3MgaWYgbmVlZGVkLlxyXG4gKlxyXG4gKiBAcGFyYW0ge2dvb2dsZS5tYXBzLk1hcmtlcn0gbWFya2VyIFRoZSBtYXJrZXIgdG8gYWRkLlxyXG4gKiBAcGFyYW0ge2Jvb2xlYW49fSBvcHRfbm9kcmF3IFdoZXRoZXIgdG8gcmVkcmF3IHRoZSBjbHVzdGVycy5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuYWRkTWFya2VyID0gZnVuY3Rpb24obWFya2VyLCBvcHRfbm9kcmF3KSB7XHJcbiAgdGhpcy5wdXNoTWFya2VyVG9fKG1hcmtlcik7XHJcbiAgaWYgKCFvcHRfbm9kcmF3KSB7XHJcbiAgICB0aGlzLnJlZHJhdygpO1xyXG4gIH1cclxufTtcclxuXHJcblxyXG4vKipcclxuICogUmVtb3ZlcyBhIG1hcmtlciBhbmQgcmV0dXJucyB0cnVlIGlmIHJlbW92ZWQsIGZhbHNlIGlmIG5vdFxyXG4gKlxyXG4gKiBAcGFyYW0ge2dvb2dsZS5tYXBzLk1hcmtlcn0gbWFya2VyIFRoZSBtYXJrZXIgdG8gcmVtb3ZlXHJcbiAqIEByZXR1cm4ge2Jvb2xlYW59IFdoZXRoZXIgdGhlIG1hcmtlciB3YXMgcmVtb3ZlZCBvciBub3RcclxuICogQHByaXZhdGVcclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUucmVtb3ZlTWFya2VyXyA9IGZ1bmN0aW9uKG1hcmtlcikge1xyXG4gIHZhciBpbmRleCA9IC0xO1xyXG4gIGlmICh0aGlzLm1hcmtlcnNfLmluZGV4T2YpIHtcclxuICAgIGluZGV4ID0gdGhpcy5tYXJrZXJzXy5pbmRleE9mKG1hcmtlcik7XHJcbiAgfSBlbHNlIHtcclxuICAgIGZvciAodmFyIGkgPSAwLCBtOyBtID0gdGhpcy5tYXJrZXJzX1tpXTsgaSsrKSB7XHJcbiAgICAgIGlmIChtID09IG1hcmtlcikge1xyXG4gICAgICAgIGluZGV4ID0gaTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgfVxyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgaWYgKGluZGV4ID09IC0xKSB7XHJcbiAgICAvLyBNYXJrZXIgaXMgbm90IGluIG91ciBsaXN0IG9mIG1hcmtlcnMuXHJcbiAgICByZXR1cm4gZmFsc2U7XHJcbiAgfVxyXG5cclxuICBtYXJrZXIuc2V0TWFwKG51bGwpO1xyXG5cclxuICB0aGlzLm1hcmtlcnNfLnNwbGljZShpbmRleCwgMSk7XHJcblxyXG4gIHJldHVybiB0cnVlO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZW1vdmUgYSBtYXJrZXIgZnJvbSB0aGUgY2x1c3Rlci5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5NYXJrZXJ9IG1hcmtlciBUaGUgbWFya2VyIHRvIHJlbW92ZS5cclxuICogQHBhcmFtIHtib29sZWFuPX0gb3B0X25vZHJhdyBPcHRpb25hbCBib29sZWFuIHRvIGZvcmNlIG5vIHJlZHJhdy5cclxuICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSBpZiB0aGUgbWFya2VyIHdhcyByZW1vdmVkLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5yZW1vdmVNYXJrZXIgPSBmdW5jdGlvbihtYXJrZXIsIG9wdF9ub2RyYXcpIHtcclxuICB2YXIgcmVtb3ZlZCA9IHRoaXMucmVtb3ZlTWFya2VyXyhtYXJrZXIpO1xyXG5cclxuICBpZiAoIW9wdF9ub2RyYXcgJiYgcmVtb3ZlZCkge1xyXG4gICAgdGhpcy5yZXNldFZpZXdwb3J0KCk7XHJcbiAgICB0aGlzLnJlZHJhdygpO1xyXG4gICAgcmV0dXJuIHRydWU7XHJcbiAgfSBlbHNlIHtcclxuICAgcmV0dXJuIGZhbHNlO1xyXG4gIH1cclxufTtcclxuXHJcblxyXG4vKipcclxuICogUmVtb3ZlcyBhbiBhcnJheSBvZiBtYXJrZXJzIGZyb20gdGhlIGNsdXN0ZXIuXHJcbiAqXHJcbiAqIEBwYXJhbSB7QXJyYXkuPGdvb2dsZS5tYXBzLk1hcmtlcj59IG1hcmtlcnMgVGhlIG1hcmtlcnMgdG8gcmVtb3ZlLlxyXG4gKiBAcGFyYW0ge2Jvb2xlYW49fSBvcHRfbm9kcmF3IE9wdGlvbmFsIGJvb2xlYW4gdG8gZm9yY2Ugbm8gcmVkcmF3LlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5yZW1vdmVNYXJrZXJzID0gZnVuY3Rpb24obWFya2Vycywgb3B0X25vZHJhdykge1xyXG4gIHZhciByZW1vdmVkID0gZmFsc2U7XHJcblxyXG4gIGZvciAodmFyIGkgPSAwLCBtYXJrZXI7IG1hcmtlciA9IG1hcmtlcnNbaV07IGkrKykge1xyXG4gICAgdmFyIHIgPSB0aGlzLnJlbW92ZU1hcmtlcl8obWFya2VyKTtcclxuICAgIHJlbW92ZWQgPSByZW1vdmVkIHx8IHI7XHJcbiAgfVxyXG5cclxuICBpZiAoIW9wdF9ub2RyYXcgJiYgcmVtb3ZlZCkge1xyXG4gICAgdGhpcy5yZXNldFZpZXdwb3J0KCk7XHJcbiAgICB0aGlzLnJlZHJhdygpO1xyXG4gICAgcmV0dXJuIHRydWU7XHJcbiAgfVxyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBTZXRzIHRoZSBjbHVzdGVyZXIncyByZWFkeSBzdGF0ZS5cclxuICpcclxuICogQHBhcmFtIHtib29sZWFufSByZWFkeSBUaGUgc3RhdGUuXHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnNldFJlYWR5XyA9IGZ1bmN0aW9uKHJlYWR5KSB7XHJcbiAgaWYgKCF0aGlzLnJlYWR5Xykge1xyXG4gICAgdGhpcy5yZWFkeV8gPSByZWFkeTtcclxuICAgIHRoaXMuY3JlYXRlQ2x1c3RlcnNfKCk7XHJcbiAgfVxyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBudW1iZXIgb2YgY2x1c3RlcnMgaW4gdGhlIGNsdXN0ZXJlci5cclxuICpcclxuICogQHJldHVybiB7bnVtYmVyfSBUaGUgbnVtYmVyIG9mIGNsdXN0ZXJzLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRUb3RhbENsdXN0ZXJzID0gZnVuY3Rpb24oKSB7XHJcbiAgcmV0dXJuIHRoaXMuY2x1c3RlcnNfLmxlbmd0aDtcclxufTtcclxuXHJcblxyXG4vKipcclxuICogUmV0dXJucyB0aGUgZ29vZ2xlIG1hcCB0aGF0IHRoZSBjbHVzdGVyZXIgaXMgYXNzb2NpYXRlZCB3aXRoLlxyXG4gKlxyXG4gKiBAcmV0dXJuIHtnb29nbGUubWFwcy5NYXB9IFRoZSBtYXAuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmdldE1hcCA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1hcF87XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFNldHMgdGhlIGdvb2dsZSBtYXAgdGhhdCB0aGUgY2x1c3RlcmVyIGlzIGFzc29jaWF0ZWQgd2l0aC5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5NYXB9IG1hcCBUaGUgbWFwLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRNYXAgPSBmdW5jdGlvbihtYXApIHtcclxuICB0aGlzLm1hcF8gPSBtYXA7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJldHVybnMgdGhlIHNpemUgb2YgdGhlIGdyaWQuXHJcbiAqXHJcbiAqIEByZXR1cm4ge251bWJlcn0gVGhlIGdyaWQgc2l6ZS5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0R3JpZFNpemUgPSBmdW5jdGlvbigpIHtcclxuICByZXR1cm4gdGhpcy5ncmlkU2l6ZV87XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFNldHMgdGhlIHNpemUgb2YgdGhlIGdyaWQuXHJcbiAqXHJcbiAqIEBwYXJhbSB7bnVtYmVyfSBzaXplIFRoZSBncmlkIHNpemUuXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnNldEdyaWRTaXplID0gZnVuY3Rpb24oc2l6ZSkge1xyXG4gIHRoaXMuZ3JpZFNpemVfID0gc2l6ZTtcclxufTtcclxuXHJcblxyXG4vKipcclxuICogUmV0dXJucyB0aGUgbWluIGNsdXN0ZXIgc2l6ZS5cclxuICpcclxuICogQHJldHVybiB7bnVtYmVyfSBUaGUgZ3JpZCBzaXplLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRNaW5DbHVzdGVyU2l6ZSA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1pbkNsdXN0ZXJTaXplXztcclxufTtcclxuXHJcbi8qKlxyXG4gKiBTZXRzIHRoZSBtaW4gY2x1c3RlciBzaXplLlxyXG4gKlxyXG4gKiBAcGFyYW0ge251bWJlcn0gc2l6ZSBUaGUgZ3JpZCBzaXplLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRNaW5DbHVzdGVyU2l6ZSA9IGZ1bmN0aW9uKHNpemUpIHtcclxuICB0aGlzLm1pbkNsdXN0ZXJTaXplXyA9IHNpemU7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIEV4dGVuZHMgYSBib3VuZHMgb2JqZWN0IGJ5IHRoZSBncmlkIHNpemUuXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTGF0TG5nQm91bmRzfSBib3VuZHMgVGhlIGJvdW5kcyB0byBleHRlbmQuXHJcbiAqIEByZXR1cm4ge2dvb2dsZS5tYXBzLkxhdExuZ0JvdW5kc30gVGhlIGV4dGVuZGVkIGJvdW5kcy5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0RXh0ZW5kZWRCb3VuZHMgPSBmdW5jdGlvbihib3VuZHMpIHtcclxuICB2YXIgcHJvamVjdGlvbiA9IHRoaXMuZ2V0UHJvamVjdGlvbigpO1xyXG5cclxuICAvLyBUdXJuIHRoZSBib3VuZHMgaW50byBsYXRsbmcuXHJcbiAgdmFyIHRyID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhib3VuZHMuZ2V0Tm9ydGhFYXN0KCkubGF0KCksXHJcbiAgICAgIGJvdW5kcy5nZXROb3J0aEVhc3QoKS5sbmcoKSk7XHJcbiAgdmFyIGJsID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhib3VuZHMuZ2V0U291dGhXZXN0KCkubGF0KCksXHJcbiAgICAgIGJvdW5kcy5nZXRTb3V0aFdlc3QoKS5sbmcoKSk7XHJcblxyXG4gIC8vIENvbnZlcnQgdGhlIHBvaW50cyB0byBwaXhlbHMgYW5kIHRoZSBleHRlbmQgb3V0IGJ5IHRoZSBncmlkIHNpemUuXHJcbiAgdmFyIHRyUGl4ID0gcHJvamVjdGlvbi5mcm9tTGF0TG5nVG9EaXZQaXhlbCh0cik7XHJcbiAgdHJQaXgueCArPSB0aGlzLmdyaWRTaXplXztcclxuICB0clBpeC55IC09IHRoaXMuZ3JpZFNpemVfO1xyXG5cclxuICB2YXIgYmxQaXggPSBwcm9qZWN0aW9uLmZyb21MYXRMbmdUb0RpdlBpeGVsKGJsKTtcclxuICBibFBpeC54IC09IHRoaXMuZ3JpZFNpemVfO1xyXG4gIGJsUGl4LnkgKz0gdGhpcy5ncmlkU2l6ZV87XHJcblxyXG4gIC8vIENvbnZlcnQgdGhlIHBpeGVsIHBvaW50cyBiYWNrIHRvIExhdExuZ1xyXG4gIHZhciBuZSA9IHByb2plY3Rpb24uZnJvbURpdlBpeGVsVG9MYXRMbmcodHJQaXgpO1xyXG4gIHZhciBzdyA9IHByb2plY3Rpb24uZnJvbURpdlBpeGVsVG9MYXRMbmcoYmxQaXgpO1xyXG5cclxuICAvLyBFeHRlbmQgdGhlIGJvdW5kcyB0byBjb250YWluIHRoZSBuZXcgYm91bmRzLlxyXG4gIGJvdW5kcy5leHRlbmQobmUpO1xyXG4gIGJvdW5kcy5leHRlbmQoc3cpO1xyXG5cclxuICByZXR1cm4gYm91bmRzO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBEZXRlcm1pbnMgaWYgYSBtYXJrZXIgaXMgY29udGFpbmVkIGluIGEgYm91bmRzLlxyXG4gKlxyXG4gKiBAcGFyYW0ge2dvb2dsZS5tYXBzLk1hcmtlcn0gbWFya2VyIFRoZSBtYXJrZXIgdG8gY2hlY2suXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTGF0TG5nQm91bmRzfSBib3VuZHMgVGhlIGJvdW5kcyB0byBjaGVjayBhZ2FpbnN0LlxyXG4gKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIGlmIHRoZSBtYXJrZXIgaXMgaW4gdGhlIGJvdW5kcy5cclxuICogQHByaXZhdGVcclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuaXNNYXJrZXJJbkJvdW5kc18gPSBmdW5jdGlvbihtYXJrZXIsIGJvdW5kcykge1xyXG4gIHJldHVybiBib3VuZHMuY29udGFpbnMobWFya2VyLmdldFBvc2l0aW9uKCkpO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBDbGVhcnMgYWxsIGNsdXN0ZXJzIGFuZCBtYXJrZXJzIGZyb20gdGhlIGNsdXN0ZXJlci5cclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuY2xlYXJNYXJrZXJzID0gZnVuY3Rpb24oKSB7XHJcbiAgdGhpcy5yZXNldFZpZXdwb3J0KHRydWUpO1xyXG5cclxuICAvLyBTZXQgdGhlIG1hcmtlcnMgYSBlbXB0eSBhcnJheS5cclxuICB0aGlzLm1hcmtlcnNfID0gW107XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIENsZWFycyBhbGwgZXhpc3RpbmcgY2x1c3RlcnMgYW5kIHJlY3JlYXRlcyB0aGVtLlxyXG4gKiBAcGFyYW0ge2Jvb2xlYW59IG9wdF9oaWRlIFRvIGFsc28gaGlkZSB0aGUgbWFya2VyLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5yZXNldFZpZXdwb3J0ID0gZnVuY3Rpb24ob3B0X2hpZGUpIHtcclxuICAvLyBSZW1vdmUgYWxsIHRoZSBjbHVzdGVyc1xyXG4gIGZvciAodmFyIGkgPSAwLCBjbHVzdGVyOyBjbHVzdGVyID0gdGhpcy5jbHVzdGVyc19baV07IGkrKykge1xyXG4gICAgY2x1c3Rlci5yZW1vdmUoKTtcclxuICB9XHJcblxyXG4gIC8vIFJlc2V0IHRoZSBtYXJrZXJzIHRvIG5vdCBiZSBhZGRlZCBhbmQgdG8gYmUgaW52aXNpYmxlLlxyXG4gIGZvciAodmFyIGkgPSAwLCBtYXJrZXI7IG1hcmtlciA9IHRoaXMubWFya2Vyc19baV07IGkrKykge1xyXG4gICAgbWFya2VyLmlzQWRkZWQgPSBmYWxzZTtcclxuICAgIGlmIChvcHRfaGlkZSkge1xyXG4gICAgICBtYXJrZXIuc2V0TWFwKG51bGwpO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgdGhpcy5jbHVzdGVyc18gPSBbXTtcclxufTtcclxuXHJcbi8qKlxyXG4gKlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5yZXBhaW50ID0gZnVuY3Rpb24oKSB7XHJcbiAgdmFyIG9sZENsdXN0ZXJzID0gdGhpcy5jbHVzdGVyc18uc2xpY2UoKTtcclxuICB0aGlzLmNsdXN0ZXJzXy5sZW5ndGggPSAwO1xyXG4gIHRoaXMucmVzZXRWaWV3cG9ydCgpO1xyXG4gIHRoaXMucmVkcmF3KCk7XHJcblxyXG4gIC8vIFJlbW92ZSB0aGUgb2xkIGNsdXN0ZXJzLlxyXG4gIC8vIERvIGl0IGluIGEgdGltZW91dCBzbyB0aGUgb3RoZXIgY2x1c3RlcnMgaGF2ZSBiZWVuIGRyYXduIGZpcnN0LlxyXG4gIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xyXG4gICAgZm9yICh2YXIgaSA9IDAsIGNsdXN0ZXI7IGNsdXN0ZXIgPSBvbGRDbHVzdGVyc1tpXTsgaSsrKSB7XHJcbiAgICAgIGNsdXN0ZXIucmVtb3ZlKCk7XHJcbiAgICB9XHJcbiAgfSwgMCk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJlZHJhd3MgdGhlIGNsdXN0ZXJzLlxyXG4gKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5yZWRyYXcgPSBmdW5jdGlvbigpIHtcclxuICB0aGlzLmNyZWF0ZUNsdXN0ZXJzXygpO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBDYWxjdWxhdGVzIHRoZSBkaXN0YW5jZSBiZXR3ZWVuIHR3byBsYXRsbmcgbG9jYXRpb25zIGluIGttLlxyXG4gKiBAc2VlIGh0dHA6Ly93d3cubW92YWJsZS10eXBlLmNvLnVrL3NjcmlwdHMvbGF0bG9uZy5odG1sXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTGF0TG5nfSBwMSBUaGUgZmlyc3QgbGF0IGxuZyBwb2ludC5cclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5MYXRMbmd9IHAyIFRoZSBzZWNvbmQgbGF0IGxuZyBwb2ludC5cclxuICogQHJldHVybiB7bnVtYmVyfSBUaGUgZGlzdGFuY2UgYmV0d2VlbiB0aGUgdHdvIHBvaW50cyBpbiBrbS5cclxuICogQHByaXZhdGVcclxuKi9cclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5kaXN0YW5jZUJldHdlZW5Qb2ludHNfID0gZnVuY3Rpb24ocDEsIHAyKSB7XHJcbiAgaWYgKCFwMSB8fCAhcDIpIHtcclxuICAgIHJldHVybiAwO1xyXG4gIH1cclxuXHJcbiAgdmFyIFIgPSA2MzcxOyAvLyBSYWRpdXMgb2YgdGhlIEVhcnRoIGluIGttXHJcbiAgdmFyIGRMYXQgPSAocDIubGF0KCkgLSBwMS5sYXQoKSkgKiBNYXRoLlBJIC8gMTgwO1xyXG4gIHZhciBkTG9uID0gKHAyLmxuZygpIC0gcDEubG5nKCkpICogTWF0aC5QSSAvIDE4MDtcclxuICB2YXIgYSA9IE1hdGguc2luKGRMYXQgLyAyKSAqIE1hdGguc2luKGRMYXQgLyAyKSArXHJcbiAgICBNYXRoLmNvcyhwMS5sYXQoKSAqIE1hdGguUEkgLyAxODApICogTWF0aC5jb3MocDIubGF0KCkgKiBNYXRoLlBJIC8gMTgwKSAqXHJcbiAgICBNYXRoLnNpbihkTG9uIC8gMikgKiBNYXRoLnNpbihkTG9uIC8gMik7XHJcbiAgdmFyIGMgPSAyICogTWF0aC5hdGFuMihNYXRoLnNxcnQoYSksIE1hdGguc3FydCgxIC0gYSkpO1xyXG4gIHZhciBkID0gUiAqIGM7XHJcbiAgcmV0dXJuIGQ7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIEFkZCBhIG1hcmtlciB0byBhIGNsdXN0ZXIsIG9yIGNyZWF0ZXMgYSBuZXcgY2x1c3Rlci5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5NYXJrZXJ9IG1hcmtlciBUaGUgbWFya2VyIHRvIGFkZC5cclxuICogQHByaXZhdGVcclxuICovXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuYWRkVG9DbG9zZXN0Q2x1c3Rlcl8gPSBmdW5jdGlvbihtYXJrZXIpIHtcclxuICB2YXIgZGlzdGFuY2UgPSA0MDAwMDsgLy8gU29tZSBsYXJnZSBudW1iZXJcclxuICB2YXIgY2x1c3RlclRvQWRkVG8gPSBudWxsO1xyXG4gIHZhciBwb3MgPSBtYXJrZXIuZ2V0UG9zaXRpb24oKTtcclxuICBmb3IgKHZhciBpID0gMCwgY2x1c3RlcjsgY2x1c3RlciA9IHRoaXMuY2x1c3RlcnNfW2ldOyBpKyspIHtcclxuICAgIHZhciBjZW50ZXIgPSBjbHVzdGVyLmdldENlbnRlcigpO1xyXG4gICAgaWYgKGNlbnRlcikge1xyXG4gICAgICB2YXIgZCA9IHRoaXMuZGlzdGFuY2VCZXR3ZWVuUG9pbnRzXyhjZW50ZXIsIG1hcmtlci5nZXRQb3NpdGlvbigpKTtcclxuICAgICAgaWYgKGQgPCBkaXN0YW5jZSkge1xyXG4gICAgICAgIGRpc3RhbmNlID0gZDtcclxuICAgICAgICBjbHVzdGVyVG9BZGRUbyA9IGNsdXN0ZXI7XHJcbiAgICAgIH1cclxuICAgIH1cclxuICB9XHJcblxyXG4gIGlmIChjbHVzdGVyVG9BZGRUbyAmJiBjbHVzdGVyVG9BZGRUby5pc01hcmtlckluQ2x1c3RlckJvdW5kcyhtYXJrZXIpKSB7XHJcbiAgICBjbHVzdGVyVG9BZGRUby5hZGRNYXJrZXIobWFya2VyKTtcclxuICB9IGVsc2Uge1xyXG4gICAgdmFyIGNsdXN0ZXIgPSBuZXcgQ2x1c3Rlcih0aGlzKTtcclxuICAgIGNsdXN0ZXIuYWRkTWFya2VyKG1hcmtlcik7XHJcbiAgICB0aGlzLmNsdXN0ZXJzXy5wdXNoKGNsdXN0ZXIpO1xyXG4gIH1cclxufTtcclxuXHJcblxyXG4vKipcclxuICogQ3JlYXRlcyB0aGUgY2x1c3RlcnMuXHJcbiAqXHJcbiAqIEBwcml2YXRlXHJcbiAqL1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmNyZWF0ZUNsdXN0ZXJzXyA9IGZ1bmN0aW9uKCkge1xyXG4gIGlmICghdGhpcy5yZWFkeV8pIHtcclxuICAgIHJldHVybjtcclxuICB9XHJcblxyXG4gIC8vIEdldCBvdXIgY3VycmVudCBtYXAgdmlldyBib3VuZHMuXHJcbiAgLy8gQ3JlYXRlIGEgbmV3IGJvdW5kcyBvYmplY3Qgc28gd2UgZG9uJ3QgYWZmZWN0IHRoZSBtYXAuXHJcbiAgdmFyIG1hcEJvdW5kcyA9IG5ldyBnb29nbGUubWFwcy5MYXRMbmdCb3VuZHModGhpcy5tYXBfLmdldEJvdW5kcygpLmdldFNvdXRoV2VzdCgpLFxyXG4gICAgICB0aGlzLm1hcF8uZ2V0Qm91bmRzKCkuZ2V0Tm9ydGhFYXN0KCkpO1xyXG4gIHZhciBib3VuZHMgPSB0aGlzLmdldEV4dGVuZGVkQm91bmRzKG1hcEJvdW5kcyk7XHJcblxyXG4gIGZvciAodmFyIGkgPSAwLCBtYXJrZXI7IG1hcmtlciA9IHRoaXMubWFya2Vyc19baV07IGkrKykge1xyXG4gICAgaWYgKCFtYXJrZXIuaXNBZGRlZCAmJiB0aGlzLmlzTWFya2VySW5Cb3VuZHNfKG1hcmtlciwgYm91bmRzKSkge1xyXG4gICAgICB0aGlzLmFkZFRvQ2xvc2VzdENsdXN0ZXJfKG1hcmtlcik7XHJcbiAgICB9XHJcbiAgfVxyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBBIGNsdXN0ZXIgdGhhdCBjb250YWlucyBtYXJrZXJzLlxyXG4gKlxyXG4gKiBAcGFyYW0ge01hcmtlckNsdXN0ZXJlcn0gbWFya2VyQ2x1c3RlcmVyIFRoZSBtYXJrZXJjbHVzdGVyZXIgdGhhdCB0aGlzXHJcbiAqICAgICBjbHVzdGVyIGlzIGFzc29jaWF0ZWQgd2l0aC5cclxuICogQGNvbnN0cnVjdG9yXHJcbiAqIEBpZ25vcmVcclxuICovXHJcbmZ1bmN0aW9uIENsdXN0ZXIobWFya2VyQ2x1c3RlcmVyKSB7XHJcbiAgdGhpcy5tYXJrZXJDbHVzdGVyZXJfID0gbWFya2VyQ2x1c3RlcmVyO1xyXG4gIHRoaXMubWFwXyA9IG1hcmtlckNsdXN0ZXJlci5nZXRNYXAoKTtcclxuICB0aGlzLmdyaWRTaXplXyA9IG1hcmtlckNsdXN0ZXJlci5nZXRHcmlkU2l6ZSgpO1xyXG4gIHRoaXMubWluQ2x1c3RlclNpemVfID0gbWFya2VyQ2x1c3RlcmVyLmdldE1pbkNsdXN0ZXJTaXplKCk7XHJcbiAgdGhpcy5hdmVyYWdlQ2VudGVyXyA9IG1hcmtlckNsdXN0ZXJlci5pc0F2ZXJhZ2VDZW50ZXIoKTtcclxuICB0aGlzLmNlbnRlcl8gPSBudWxsO1xyXG4gIHRoaXMubWFya2Vyc18gPSBbXTtcclxuICB0aGlzLmJvdW5kc18gPSBudWxsO1xyXG4gIHRoaXMuY2x1c3Rlckljb25fID0gbmV3IENsdXN0ZXJJY29uKHRoaXMsIG1hcmtlckNsdXN0ZXJlci5nZXRTdHlsZXMoKSxcclxuICAgICAgbWFya2VyQ2x1c3RlcmVyLmdldEdyaWRTaXplKCkpO1xyXG59XHJcblxyXG4vKipcclxuICogRGV0ZXJtaW5zIGlmIGEgbWFya2VyIGlzIGFscmVhZHkgYWRkZWQgdG8gdGhlIGNsdXN0ZXIuXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTWFya2VyfSBtYXJrZXIgVGhlIG1hcmtlciB0byBjaGVjay5cclxuICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSBpZiB0aGUgbWFya2VyIGlzIGFscmVhZHkgYWRkZWQuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5pc01hcmtlckFscmVhZHlBZGRlZCA9IGZ1bmN0aW9uKG1hcmtlcikge1xyXG4gIGlmICh0aGlzLm1hcmtlcnNfLmluZGV4T2YpIHtcclxuICAgIHJldHVybiB0aGlzLm1hcmtlcnNfLmluZGV4T2YobWFya2VyKSAhPSAtMTtcclxuICB9IGVsc2Uge1xyXG4gICAgZm9yICh2YXIgaSA9IDAsIG07IG0gPSB0aGlzLm1hcmtlcnNfW2ldOyBpKyspIHtcclxuICAgICAgaWYgKG0gPT0gbWFya2VyKSB7XHJcbiAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgIH1cclxuICAgIH1cclxuICB9XHJcbiAgcmV0dXJuIGZhbHNlO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBBZGQgYSBtYXJrZXIgdGhlIGNsdXN0ZXIuXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTWFya2VyfSBtYXJrZXIgVGhlIG1hcmtlciB0byBhZGQuXHJcbiAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgaWYgdGhlIG1hcmtlciB3YXMgYWRkZWQuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5hZGRNYXJrZXIgPSBmdW5jdGlvbihtYXJrZXIpIHtcclxuICBpZiAodGhpcy5pc01hcmtlckFscmVhZHlBZGRlZChtYXJrZXIpKSB7XHJcbiAgICByZXR1cm4gZmFsc2U7XHJcbiAgfVxyXG5cclxuICBpZiAoIXRoaXMuY2VudGVyXykge1xyXG4gICAgdGhpcy5jZW50ZXJfID0gbWFya2VyLmdldFBvc2l0aW9uKCk7XHJcbiAgICB0aGlzLmNhbGN1bGF0ZUJvdW5kc18oKTtcclxuICB9IGVsc2Uge1xyXG4gICAgaWYgKHRoaXMuYXZlcmFnZUNlbnRlcl8pIHtcclxuICAgICAgdmFyIGwgPSB0aGlzLm1hcmtlcnNfLmxlbmd0aCArIDE7XHJcbiAgICAgIHZhciBsYXQgPSAodGhpcy5jZW50ZXJfLmxhdCgpICogKGwtMSkgKyBtYXJrZXIuZ2V0UG9zaXRpb24oKS5sYXQoKSkgLyBsO1xyXG4gICAgICB2YXIgbG5nID0gKHRoaXMuY2VudGVyXy5sbmcoKSAqIChsLTEpICsgbWFya2VyLmdldFBvc2l0aW9uKCkubG5nKCkpIC8gbDtcclxuICAgICAgdGhpcy5jZW50ZXJfID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhsYXQsIGxuZyk7XHJcbiAgICAgIHRoaXMuY2FsY3VsYXRlQm91bmRzXygpO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgbWFya2VyLmlzQWRkZWQgPSB0cnVlO1xyXG4gIHRoaXMubWFya2Vyc18ucHVzaChtYXJrZXIpO1xyXG5cclxuICB2YXIgbGVuID0gdGhpcy5tYXJrZXJzXy5sZW5ndGg7XHJcbiAgaWYgKGxlbiA8IHRoaXMubWluQ2x1c3RlclNpemVfICYmIG1hcmtlci5nZXRNYXAoKSAhPSB0aGlzLm1hcF8pIHtcclxuICAgIC8vIE1pbiBjbHVzdGVyIHNpemUgbm90IHJlYWNoZWQgc28gc2hvdyB0aGUgbWFya2VyLlxyXG4gICAgbWFya2VyLnNldE1hcCh0aGlzLm1hcF8pO1xyXG4gIH1cclxuXHJcbiAgaWYgKGxlbiA9PSB0aGlzLm1pbkNsdXN0ZXJTaXplXykge1xyXG4gICAgLy8gSGlkZSB0aGUgbWFya2VycyB0aGF0IHdlcmUgc2hvd2luZy5cclxuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbGVuOyBpKyspIHtcclxuICAgICAgdGhpcy5tYXJrZXJzX1tpXS5zZXRNYXAobnVsbCk7XHJcbiAgICB9XHJcbiAgfVxyXG5cclxuICBpZiAobGVuID49IHRoaXMubWluQ2x1c3RlclNpemVfKSB7XHJcbiAgICBtYXJrZXIuc2V0TWFwKG51bGwpO1xyXG4gIH1cclxuXHJcbiAgdGhpcy51cGRhdGVJY29uKCk7XHJcbiAgcmV0dXJuIHRydWU7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJldHVybnMgdGhlIG1hcmtlciBjbHVzdGVyZXIgdGhhdCB0aGUgY2x1c3RlciBpcyBhc3NvY2lhdGVkIHdpdGguXHJcbiAqXHJcbiAqIEByZXR1cm4ge01hcmtlckNsdXN0ZXJlcn0gVGhlIGFzc29jaWF0ZWQgbWFya2VyIGNsdXN0ZXJlci5cclxuICovXHJcbkNsdXN0ZXIucHJvdG90eXBlLmdldE1hcmtlckNsdXN0ZXJlciA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1hcmtlckNsdXN0ZXJlcl87XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJldHVybnMgdGhlIGJvdW5kcyBvZiB0aGUgY2x1c3Rlci5cclxuICpcclxuICogQHJldHVybiB7Z29vZ2xlLm1hcHMuTGF0TG5nQm91bmRzfSB0aGUgY2x1c3RlciBib3VuZHMuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5nZXRCb3VuZHMgPSBmdW5jdGlvbigpIHtcclxuICB2YXIgYm91bmRzID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZ0JvdW5kcyh0aGlzLmNlbnRlcl8sIHRoaXMuY2VudGVyXyk7XHJcbiAgdmFyIG1hcmtlcnMgPSB0aGlzLmdldE1hcmtlcnMoKTtcclxuICBmb3IgKHZhciBpID0gMCwgbWFya2VyOyBtYXJrZXIgPSBtYXJrZXJzW2ldOyBpKyspIHtcclxuICAgIGJvdW5kcy5leHRlbmQobWFya2VyLmdldFBvc2l0aW9uKCkpO1xyXG4gIH1cclxuICByZXR1cm4gYm91bmRzO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZW1vdmVzIHRoZSBjbHVzdGVyXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5yZW1vdmUgPSBmdW5jdGlvbigpIHtcclxuICB0aGlzLmNsdXN0ZXJJY29uXy5yZW1vdmUoKTtcclxuICB0aGlzLm1hcmtlcnNfLmxlbmd0aCA9IDA7XHJcbiAgZGVsZXRlIHRoaXMubWFya2Vyc187XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJldHVybnMgdGhlIGNlbnRlciBvZiB0aGUgY2x1c3Rlci5cclxuICpcclxuICogQHJldHVybiB7bnVtYmVyfSBUaGUgY2x1c3RlciBjZW50ZXIuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5nZXRTaXplID0gZnVuY3Rpb24oKSB7XHJcbiAgcmV0dXJuIHRoaXMubWFya2Vyc18ubGVuZ3RoO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBjZW50ZXIgb2YgdGhlIGNsdXN0ZXIuXHJcbiAqXHJcbiAqIEByZXR1cm4ge0FycmF5Ljxnb29nbGUubWFwcy5NYXJrZXI+fSBUaGUgY2x1c3RlciBjZW50ZXIuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5nZXRNYXJrZXJzID0gZnVuY3Rpb24oKSB7XHJcbiAgcmV0dXJuIHRoaXMubWFya2Vyc187XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFJldHVybnMgdGhlIGNlbnRlciBvZiB0aGUgY2x1c3Rlci5cclxuICpcclxuICogQHJldHVybiB7Z29vZ2xlLm1hcHMuTGF0TG5nfSBUaGUgY2x1c3RlciBjZW50ZXIuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5nZXRDZW50ZXIgPSBmdW5jdGlvbigpIHtcclxuICByZXR1cm4gdGhpcy5jZW50ZXJfO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBDYWxjdWxhdGVkIHRoZSBleHRlbmRlZCBib3VuZHMgb2YgdGhlIGNsdXN0ZXIgd2l0aCB0aGUgZ3JpZC5cclxuICpcclxuICogQHByaXZhdGVcclxuICovXHJcbkNsdXN0ZXIucHJvdG90eXBlLmNhbGN1bGF0ZUJvdW5kc18gPSBmdW5jdGlvbigpIHtcclxuICB2YXIgYm91bmRzID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZ0JvdW5kcyh0aGlzLmNlbnRlcl8sIHRoaXMuY2VudGVyXyk7XHJcbiAgdGhpcy5ib3VuZHNfID0gdGhpcy5tYXJrZXJDbHVzdGVyZXJfLmdldEV4dGVuZGVkQm91bmRzKGJvdW5kcyk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIERldGVybWluZXMgaWYgYSBtYXJrZXIgbGllcyBpbiB0aGUgY2x1c3RlcnMgYm91bmRzLlxyXG4gKlxyXG4gKiBAcGFyYW0ge2dvb2dsZS5tYXBzLk1hcmtlcn0gbWFya2VyIFRoZSBtYXJrZXIgdG8gY2hlY2suXHJcbiAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgaWYgdGhlIG1hcmtlciBsaWVzIGluIHRoZSBib3VuZHMuXHJcbiAqL1xyXG5DbHVzdGVyLnByb3RvdHlwZS5pc01hcmtlckluQ2x1c3RlckJvdW5kcyA9IGZ1bmN0aW9uKG1hcmtlcikge1xyXG4gIHJldHVybiB0aGlzLmJvdW5kc18uY29udGFpbnMobWFya2VyLmdldFBvc2l0aW9uKCkpO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBtYXAgdGhhdCB0aGUgY2x1c3RlciBpcyBhc3NvY2lhdGVkIHdpdGguXHJcbiAqXHJcbiAqIEByZXR1cm4ge2dvb2dsZS5tYXBzLk1hcH0gVGhlIG1hcC5cclxuICovXHJcbkNsdXN0ZXIucHJvdG90eXBlLmdldE1hcCA9IGZ1bmN0aW9uKCkge1xyXG4gIHJldHVybiB0aGlzLm1hcF87XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFVwZGF0ZXMgdGhlIGNsdXN0ZXIgaWNvblxyXG4gKi9cclxuQ2x1c3Rlci5wcm90b3R5cGUudXBkYXRlSWNvbiA9IGZ1bmN0aW9uKCkge1xyXG4gIHZhciB6b29tID0gdGhpcy5tYXBfLmdldFpvb20oKTtcclxuICB2YXIgbXogPSB0aGlzLm1hcmtlckNsdXN0ZXJlcl8uZ2V0TWF4Wm9vbSgpO1xyXG5cclxuICBpZiAobXogJiYgem9vbSA+IG16KSB7XHJcbiAgICAvLyBUaGUgem9vbSBpcyBncmVhdGVyIHRoYW4gb3VyIG1heCB6b29tIHNvIHNob3cgYWxsIHRoZSBtYXJrZXJzIGluIGNsdXN0ZXIuXHJcbiAgICBmb3IgKHZhciBpID0gMCwgbWFya2VyOyBtYXJrZXIgPSB0aGlzLm1hcmtlcnNfW2ldOyBpKyspIHtcclxuICAgICAgbWFya2VyLnNldE1hcCh0aGlzLm1hcF8pO1xyXG4gICAgfVxyXG4gICAgcmV0dXJuO1xyXG4gIH1cclxuXHJcbiAgaWYgKHRoaXMubWFya2Vyc18ubGVuZ3RoIDwgdGhpcy5taW5DbHVzdGVyU2l6ZV8pIHtcclxuICAgIC8vIE1pbiBjbHVzdGVyIHNpemUgbm90IHlldCByZWFjaGVkLlxyXG4gICAgdGhpcy5jbHVzdGVySWNvbl8uaGlkZSgpO1xyXG4gICAgcmV0dXJuO1xyXG4gIH1cclxuXHJcbiAgdmFyIG51bVN0eWxlcyA9IHRoaXMubWFya2VyQ2x1c3RlcmVyXy5nZXRTdHlsZXMoKS5sZW5ndGg7XHJcbiAgdmFyIHN1bXMgPSB0aGlzLm1hcmtlckNsdXN0ZXJlcl8uZ2V0Q2FsY3VsYXRvcigpKHRoaXMubWFya2Vyc18sIG51bVN0eWxlcyk7XHJcbiAgdGhpcy5jbHVzdGVySWNvbl8uc2V0Q2VudGVyKHRoaXMuY2VudGVyXyk7XHJcbiAgdGhpcy5jbHVzdGVySWNvbl8uc2V0U3VtcyhzdW1zKTtcclxuICB0aGlzLmNsdXN0ZXJJY29uXy5zaG93KCk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIEEgY2x1c3RlciBpY29uXHJcbiAqXHJcbiAqIEBwYXJhbSB7Q2x1c3Rlcn0gY2x1c3RlciBUaGUgY2x1c3RlciB0byBiZSBhc3NvY2lhdGVkIHdpdGguXHJcbiAqIEBwYXJhbSB7T2JqZWN0fSBzdHlsZXMgQW4gb2JqZWN0IHRoYXQgaGFzIHN0eWxlIHByb3BlcnRpZXM6XHJcbiAqICAgICAndXJsJzogKHN0cmluZykgVGhlIGltYWdlIHVybC5cclxuICogICAgICdoZWlnaHQnOiAobnVtYmVyKSBUaGUgaW1hZ2UgaGVpZ2h0LlxyXG4gKiAgICAgJ3dpZHRoJzogKG51bWJlcikgVGhlIGltYWdlIHdpZHRoLlxyXG4gKiAgICAgJ2FuY2hvcic6IChBcnJheSkgVGhlIGFuY2hvciBwb3NpdGlvbiBvZiB0aGUgbGFiZWwgdGV4dC5cclxuICogICAgICd0ZXh0Q29sb3InOiAoc3RyaW5nKSBUaGUgdGV4dCBjb2xvci5cclxuICogICAgICd0ZXh0U2l6ZSc6IChudW1iZXIpIFRoZSB0ZXh0IHNpemUuXHJcbiAqICAgICAnYmFja2dyb3VuZFBvc2l0aW9uOiAoc3RyaW5nKSBUaGUgYmFja2dyb3VuZCBwb3N0aXRpb24geCwgeS5cclxuICogQHBhcmFtIHtudW1iZXI9fSBvcHRfcGFkZGluZyBPcHRpb25hbCBwYWRkaW5nIHRvIGFwcGx5IHRvIHRoZSBjbHVzdGVyIGljb24uXHJcbiAqIEBjb25zdHJ1Y3RvclxyXG4gKiBAZXh0ZW5kcyBnb29nbGUubWFwcy5PdmVybGF5Vmlld1xyXG4gKiBAaWdub3JlXHJcbiAqL1xyXG5mdW5jdGlvbiBDbHVzdGVySWNvbihjbHVzdGVyLCBzdHlsZXMsIG9wdF9wYWRkaW5nKSB7XHJcbiAgY2x1c3Rlci5nZXRNYXJrZXJDbHVzdGVyZXIoKS5leHRlbmQoQ2x1c3Rlckljb24sIGdvb2dsZS5tYXBzLk92ZXJsYXlWaWV3KTtcclxuXHJcbiAgdGhpcy5zdHlsZXNfID0gc3R5bGVzO1xyXG4gIHRoaXMucGFkZGluZ18gPSBvcHRfcGFkZGluZyB8fCAwO1xyXG4gIHRoaXMuY2x1c3Rlcl8gPSBjbHVzdGVyO1xyXG4gIHRoaXMuY2VudGVyXyA9IG51bGw7XHJcbiAgdGhpcy5tYXBfID0gY2x1c3Rlci5nZXRNYXAoKTtcclxuICB0aGlzLmRpdl8gPSBudWxsO1xyXG4gIHRoaXMuc3Vtc18gPSBudWxsO1xyXG4gIHRoaXMudmlzaWJsZV8gPSBmYWxzZTtcclxuXHJcbiAgdGhpcy5zZXRNYXAodGhpcy5tYXBfKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiBUcmlnZ2VycyB0aGUgY2x1c3RlcmNsaWNrIGV2ZW50IGFuZCB6b29tJ3MgaWYgdGhlIG9wdGlvbiBpcyBzZXQuXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuTW91c2VFdmVudH0gZXZlbnQgVGhlIGV2ZW50IHRvIHByb3BhZ2F0ZVxyXG4gKi9cclxuQ2x1c3Rlckljb24ucHJvdG90eXBlLnRyaWdnZXJDbHVzdGVyQ2xpY2sgPSBmdW5jdGlvbihldmVudCkge1xyXG4gIHZhciBtYXJrZXJDbHVzdGVyZXIgPSB0aGlzLmNsdXN0ZXJfLmdldE1hcmtlckNsdXN0ZXJlcigpO1xyXG5cclxuICAvLyBUcmlnZ2VyIHRoZSBjbHVzdGVyY2xpY2sgZXZlbnQuXHJcbiAgZ29vZ2xlLm1hcHMuZXZlbnQudHJpZ2dlcihtYXJrZXJDbHVzdGVyZXIsICdjbHVzdGVyY2xpY2snLCB0aGlzLmNsdXN0ZXJfLCBldmVudCk7XHJcblxyXG4gIGlmIChtYXJrZXJDbHVzdGVyZXIuaXNab29tT25DbGljaygpKSB7XHJcbiAgICAvLyBab29tIGludG8gdGhlIGNsdXN0ZXIuXHJcbiAgICB0aGlzLm1hcF8uZml0Qm91bmRzKHRoaXMuY2x1c3Rlcl8uZ2V0Qm91bmRzKCkpO1xyXG4gIH1cclxufTtcclxuXHJcblxyXG4vKipcclxuICogQWRkaW5nIHRoZSBjbHVzdGVyIGljb24gdG8gdGhlIGRvbS5cclxuICogQGlnbm9yZVxyXG4gKi9cclxuQ2x1c3Rlckljb24ucHJvdG90eXBlLm9uQWRkID0gZnVuY3Rpb24oKSB7XHJcbiAgdGhpcy5kaXZfID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnRElWJyk7XHJcbiAgaWYgKHRoaXMudmlzaWJsZV8pIHtcclxuICAgIHZhciBwb3MgPSB0aGlzLmdldFBvc0Zyb21MYXRMbmdfKHRoaXMuY2VudGVyXyk7XHJcbiAgICB0aGlzLmRpdl8uc3R5bGUuY3NzVGV4dCA9IHRoaXMuY3JlYXRlQ3NzKHBvcyk7XHJcbiAgICB0aGlzLmRpdl8uaW5uZXJIVE1MID0gdGhpcy5zdW1zXy50ZXh0O1xyXG4gIH1cclxuXHJcbiAgdmFyIHBhbmVzID0gdGhpcy5nZXRQYW5lcygpO1xyXG4gIHBhbmVzLm92ZXJsYXlNb3VzZVRhcmdldC5hcHBlbmRDaGlsZCh0aGlzLmRpdl8pO1xyXG5cclxuICB2YXIgdGhhdCA9IHRoaXM7XHJcbiAgdmFyIGlzRHJhZ2dpbmcgPSBmYWxzZTtcclxuICBnb29nbGUubWFwcy5ldmVudC5hZGREb21MaXN0ZW5lcih0aGlzLmRpdl8sICdjbGljaycsIGZ1bmN0aW9uKGV2ZW50KSB7XHJcbiAgICAvLyBPbmx5IHBlcmZvcm0gY2xpY2sgd2hlbiBub3QgcHJlY2VkZWQgYnkgYSBkcmFnXHJcbiAgICBpZiAoIWlzRHJhZ2dpbmcpIHtcclxuICAgICAgdGhhdC50cmlnZ2VyQ2x1c3RlckNsaWNrKGV2ZW50KTtcclxuICAgIH1cclxuICB9KTtcclxuICBnb29nbGUubWFwcy5ldmVudC5hZGREb21MaXN0ZW5lcih0aGlzLmRpdl8sICdtb3VzZWRvd24nLCBmdW5jdGlvbigpIHtcclxuICAgIGlzRHJhZ2dpbmcgPSBmYWxzZTtcclxuICB9KTtcclxuICBnb29nbGUubWFwcy5ldmVudC5hZGREb21MaXN0ZW5lcih0aGlzLmRpdl8sICdtb3VzZW1vdmUnLCBmdW5jdGlvbigpIHtcclxuICAgIGlzRHJhZ2dpbmcgPSB0cnVlO1xyXG4gIH0pO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBwb3NpdGlvbiB0byBwbGFjZSB0aGUgZGl2IGRlbmRpbmcgb24gdGhlIGxhdGxuZy5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5MYXRMbmd9IGxhdGxuZyBUaGUgcG9zaXRpb24gaW4gbGF0bG5nLlxyXG4gKiBAcmV0dXJuIHtnb29nbGUubWFwcy5Qb2ludH0gVGhlIHBvc2l0aW9uIGluIHBpeGVscy5cclxuICogQHByaXZhdGVcclxuICovXHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZS5nZXRQb3NGcm9tTGF0TG5nXyA9IGZ1bmN0aW9uKGxhdGxuZykge1xyXG4gIHZhciBwb3MgPSB0aGlzLmdldFByb2plY3Rpb24oKS5mcm9tTGF0TG5nVG9EaXZQaXhlbChsYXRsbmcpO1xyXG5cclxuICBpZiAodHlwZW9mIHRoaXMuaWNvbkFuY2hvcl8gPT09ICdvYmplY3QnICYmIHRoaXMuaWNvbkFuY2hvcl8ubGVuZ3RoID09PSAyKSB7XHJcbiAgICBwb3MueCAtPSB0aGlzLmljb25BbmNob3JfWzBdO1xyXG4gICAgcG9zLnkgLT0gdGhpcy5pY29uQW5jaG9yX1sxXTtcclxuICB9IGVsc2Uge1xyXG4gICAgcG9zLnggLT0gcGFyc2VJbnQodGhpcy53aWR0aF8gLyAyLCAxMCk7XHJcbiAgICBwb3MueSAtPSBwYXJzZUludCh0aGlzLmhlaWdodF8gLyAyLCAxMCk7XHJcbiAgfVxyXG4gIHJldHVybiBwb3M7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIERyYXcgdGhlIGljb24uXHJcbiAqIEBpZ25vcmVcclxuICovXHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZS5kcmF3ID0gZnVuY3Rpb24oKSB7XHJcbiAgaWYgKHRoaXMudmlzaWJsZV8pIHtcclxuICAgIHZhciBwb3MgPSB0aGlzLmdldFBvc0Zyb21MYXRMbmdfKHRoaXMuY2VudGVyXyk7XHJcbiAgICB0aGlzLmRpdl8uc3R5bGUudG9wID0gcG9zLnkgKyAncHgnO1xyXG4gICAgdGhpcy5kaXZfLnN0eWxlLmxlZnQgPSBwb3MueCArICdweCc7XHJcbiAgfVxyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBIaWRlIHRoZSBpY29uLlxyXG4gKi9cclxuQ2x1c3Rlckljb24ucHJvdG90eXBlLmhpZGUgPSBmdW5jdGlvbigpIHtcclxuICBpZiAodGhpcy5kaXZfKSB7XHJcbiAgICB0aGlzLmRpdl8uc3R5bGUuZGlzcGxheSA9ICdub25lJztcclxuICB9XHJcbiAgdGhpcy52aXNpYmxlXyA9IGZhbHNlO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBQb3NpdGlvbiBhbmQgc2hvdyB0aGUgaWNvbi5cclxuICovXHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZS5zaG93ID0gZnVuY3Rpb24oKSB7XHJcbiAgaWYgKHRoaXMuZGl2Xykge1xyXG4gICAgdmFyIHBvcyA9IHRoaXMuZ2V0UG9zRnJvbUxhdExuZ18odGhpcy5jZW50ZXJfKTtcclxuICAgIHRoaXMuZGl2Xy5zdHlsZS5jc3NUZXh0ID0gdGhpcy5jcmVhdGVDc3MocG9zKTtcclxuICAgIHRoaXMuZGl2Xy5zdHlsZS5kaXNwbGF5ID0gJyc7XHJcbiAgfVxyXG4gIHRoaXMudmlzaWJsZV8gPSB0cnVlO1xyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBSZW1vdmUgdGhlIGljb24gZnJvbSB0aGUgbWFwXHJcbiAqL1xyXG5DbHVzdGVySWNvbi5wcm90b3R5cGUucmVtb3ZlID0gZnVuY3Rpb24oKSB7XHJcbiAgdGhpcy5zZXRNYXAobnVsbCk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIEltcGxlbWVudGF0aW9uIG9mIHRoZSBvblJlbW92ZSBpbnRlcmZhY2UuXHJcbiAqIEBpZ25vcmVcclxuICovXHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZS5vblJlbW92ZSA9IGZ1bmN0aW9uKCkge1xyXG4gIGlmICh0aGlzLmRpdl8gJiYgdGhpcy5kaXZfLnBhcmVudE5vZGUpIHtcclxuICAgIHRoaXMuaGlkZSgpO1xyXG4gICAgdGhpcy5kaXZfLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5kaXZfKTtcclxuICAgIHRoaXMuZGl2XyA9IG51bGw7XHJcbiAgfVxyXG59O1xyXG5cclxuXHJcbi8qKlxyXG4gKiBTZXQgdGhlIHN1bXMgb2YgdGhlIGljb24uXHJcbiAqXHJcbiAqIEBwYXJhbSB7T2JqZWN0fSBzdW1zIFRoZSBzdW1zIGNvbnRhaW5pbmc6XHJcbiAqICAgJ3RleHQnOiAoc3RyaW5nKSBUaGUgdGV4dCB0byBkaXNwbGF5IGluIHRoZSBpY29uLlxyXG4gKiAgICdpbmRleCc6IChudW1iZXIpIFRoZSBzdHlsZSBpbmRleCBvZiB0aGUgaWNvbi5cclxuICovXHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZS5zZXRTdW1zID0gZnVuY3Rpb24oc3Vtcykge1xyXG4gIHRoaXMuc3Vtc18gPSBzdW1zO1xyXG4gIHRoaXMudGV4dF8gPSBzdW1zLnRleHQ7XHJcbiAgdGhpcy5pbmRleF8gPSBzdW1zLmluZGV4O1xyXG4gIGlmICh0aGlzLmRpdl8pIHtcclxuICAgIHRoaXMuZGl2Xy5pbm5lckhUTUwgPSBzdW1zLnRleHQ7XHJcbiAgfVxyXG5cclxuICB0aGlzLnVzZVN0eWxlKCk7XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFNldHMgdGhlIGljb24gdG8gdGhlIHRoZSBzdHlsZXMuXHJcbiAqL1xyXG5DbHVzdGVySWNvbi5wcm90b3R5cGUudXNlU3R5bGUgPSBmdW5jdGlvbigpIHtcclxuICB2YXIgaW5kZXggPSBNYXRoLm1heCgwLCB0aGlzLnN1bXNfLmluZGV4IC0gMSk7XHJcbiAgaW5kZXggPSBNYXRoLm1pbih0aGlzLnN0eWxlc18ubGVuZ3RoIC0gMSwgaW5kZXgpO1xyXG4gIHZhciBzdHlsZSA9IHRoaXMuc3R5bGVzX1tpbmRleF07XHJcbiAgdGhpcy51cmxfID0gc3R5bGVbJ3VybCddO1xyXG4gIHRoaXMuaGVpZ2h0XyA9IHN0eWxlWydoZWlnaHQnXTtcclxuICB0aGlzLndpZHRoXyA9IHN0eWxlWyd3aWR0aCddO1xyXG4gIHRoaXMudGV4dENvbG9yXyA9IHN0eWxlWyd0ZXh0Q29sb3InXTtcclxuICB0aGlzLmFuY2hvcl8gPSBzdHlsZVsnYW5jaG9yJ107XHJcbiAgdGhpcy50ZXh0U2l6ZV8gPSBzdHlsZVsndGV4dFNpemUnXTtcclxuICB0aGlzLmJhY2tncm91bmRQb3NpdGlvbl8gPSBzdHlsZVsnYmFja2dyb3VuZFBvc2l0aW9uJ107XHJcbiAgdGhpcy5pY29uQW5jaG9yXyA9IHN0eWxlWydpY29uQW5jaG9yJ107XHJcbn07XHJcblxyXG5cclxuLyoqXHJcbiAqIFNldHMgdGhlIGNlbnRlciBvZiB0aGUgaWNvbi5cclxuICpcclxuICogQHBhcmFtIHtnb29nbGUubWFwcy5MYXRMbmd9IGNlbnRlciBUaGUgbGF0bG5nIHRvIHNldCBhcyB0aGUgY2VudGVyLlxyXG4gKi9cclxuQ2x1c3Rlckljb24ucHJvdG90eXBlLnNldENlbnRlciA9IGZ1bmN0aW9uKGNlbnRlcikge1xyXG4gIHRoaXMuY2VudGVyXyA9IGNlbnRlcjtcclxufTtcclxuXHJcblxyXG4vKipcclxuICogQ3JlYXRlIHRoZSBjc3MgdGV4dCBiYXNlZCBvbiB0aGUgcG9zaXRpb24gb2YgdGhlIGljb24uXHJcbiAqXHJcbiAqIEBwYXJhbSB7Z29vZ2xlLm1hcHMuUG9pbnR9IHBvcyBUaGUgcG9zaXRpb24uXHJcbiAqIEByZXR1cm4ge3N0cmluZ30gVGhlIGNzcyBzdHlsZSB0ZXh0LlxyXG4gKi9cclxuQ2x1c3Rlckljb24ucHJvdG90eXBlLmNyZWF0ZUNzcyA9IGZ1bmN0aW9uKHBvcykge1xyXG4gIHZhciBzdHlsZSA9IFtdO1xyXG4gIHN0eWxlLnB1c2goJ2JhY2tncm91bmQtaW1hZ2U6dXJsKCcgKyB0aGlzLnVybF8gKyAnKTsnKTtcclxuICB2YXIgYmFja2dyb3VuZFBvc2l0aW9uID0gdGhpcy5iYWNrZ3JvdW5kUG9zaXRpb25fID8gdGhpcy5iYWNrZ3JvdW5kUG9zaXRpb25fIDogJzAgMCc7XHJcbiAgc3R5bGUucHVzaCgnYmFja2dyb3VuZC1wb3NpdGlvbjonICsgYmFja2dyb3VuZFBvc2l0aW9uICsgJzsnKTtcclxuXHJcbiAgaWYgKHR5cGVvZiB0aGlzLmFuY2hvcl8gPT09ICdvYmplY3QnKSB7XHJcbiAgICBpZiAodHlwZW9mIHRoaXMuYW5jaG9yX1swXSA9PT0gJ251bWJlcicgJiYgdGhpcy5hbmNob3JfWzBdID4gMCAmJlxyXG4gICAgICAgIHRoaXMuYW5jaG9yX1swXSA8IHRoaXMuaGVpZ2h0Xykge1xyXG4gICAgICBzdHlsZS5wdXNoKCdoZWlnaHQ6JyArICh0aGlzLmhlaWdodF8gLSB0aGlzLmFuY2hvcl9bMF0pICtcclxuICAgICAgICAgICdweDsgcGFkZGluZy10b3A6JyArIHRoaXMuYW5jaG9yX1swXSArICdweDsnKTtcclxuICAgIH0gZWxzZSBpZiAodHlwZW9mIHRoaXMuYW5jaG9yX1swXSA9PT0gJ251bWJlcicgJiYgdGhpcy5hbmNob3JfWzBdIDwgMCAmJlxyXG4gICAgICAgIC10aGlzLmFuY2hvcl9bMF0gPCB0aGlzLmhlaWdodF8pIHtcclxuICAgICAgc3R5bGUucHVzaCgnaGVpZ2h0OicgKyB0aGlzLmhlaWdodF8gKyAncHg7IGxpbmUtaGVpZ2h0OicgKyAodGhpcy5oZWlnaHRfICsgdGhpcy5hbmNob3JfWzBdKSArXHJcbiAgICAgICAgICAncHg7Jyk7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICBzdHlsZS5wdXNoKCdoZWlnaHQ6JyArIHRoaXMuaGVpZ2h0XyArICdweDsgbGluZS1oZWlnaHQ6JyArIHRoaXMuaGVpZ2h0XyArXHJcbiAgICAgICAgICAncHg7Jyk7XHJcbiAgICB9XHJcbiAgICBpZiAodHlwZW9mIHRoaXMuYW5jaG9yX1sxXSA9PT0gJ251bWJlcicgJiYgdGhpcy5hbmNob3JfWzFdID4gMCAmJlxyXG4gICAgICAgIHRoaXMuYW5jaG9yX1sxXSA8IHRoaXMud2lkdGhfKSB7XHJcbiAgICAgIHN0eWxlLnB1c2goJ3dpZHRoOicgKyAodGhpcy53aWR0aF8gLSB0aGlzLmFuY2hvcl9bMV0pICtcclxuICAgICAgICAgICdweDsgcGFkZGluZy1sZWZ0OicgKyB0aGlzLmFuY2hvcl9bMV0gKyAncHg7Jyk7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICBzdHlsZS5wdXNoKCd3aWR0aDonICsgdGhpcy53aWR0aF8gKyAncHg7IHRleHQtYWxpZ246Y2VudGVyOycpO1xyXG4gICAgfVxyXG4gIH0gZWxzZSB7XHJcbiAgICBzdHlsZS5wdXNoKCdoZWlnaHQ6JyArIHRoaXMuaGVpZ2h0XyArICdweDsgbGluZS1oZWlnaHQ6JyArXHJcbiAgICAgICAgdGhpcy5oZWlnaHRfICsgJ3B4OyB3aWR0aDonICsgdGhpcy53aWR0aF8gKyAncHg7IHRleHQtYWxpZ246Y2VudGVyOycpO1xyXG4gIH1cclxuXHJcbiAgdmFyIHR4dENvbG9yID0gdGhpcy50ZXh0Q29sb3JfID8gdGhpcy50ZXh0Q29sb3JfIDogJ2JsYWNrJztcclxuICB2YXIgdHh0U2l6ZSA9IHRoaXMudGV4dFNpemVfID8gdGhpcy50ZXh0U2l6ZV8gOiAxMTtcclxuXHJcbiAgc3R5bGUucHVzaCgnY3Vyc29yOnBvaW50ZXI7IHRvcDonICsgcG9zLnkgKyAncHg7IGxlZnQ6JyArXHJcbiAgICAgIHBvcy54ICsgJ3B4OyBjb2xvcjonICsgdHh0Q29sb3IgKyAnOyBwb3NpdGlvbjphYnNvbHV0ZTsgZm9udC1zaXplOicgK1xyXG4gICAgICB0eHRTaXplICsgJ3B4OyBmb250LWZhbWlseTpBcmlhbCxzYW5zLXNlcmlmOyBmb250LXdlaWdodDpib2xkJyk7XHJcbiAgcmV0dXJuIHN0eWxlLmpvaW4oJycpO1xyXG59O1xyXG5cclxuXHJcbi8vIEV4cG9ydCBTeW1ib2xzIGZvciBDbG9zdXJlXHJcbi8vIElmIHlvdSBhcmUgbm90IGdvaW5nIHRvIGNvbXBpbGUgd2l0aCBjbG9zdXJlIHRoZW4geW91IGNhbiByZW1vdmUgdGhlXHJcbi8vIGNvZGUgYmVsb3cuXHJcbndpbmRvd1snTWFya2VyQ2x1c3RlcmVyJ10gPSBNYXJrZXJDbHVzdGVyZXI7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2FkZE1hcmtlciddID0gTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5hZGRNYXJrZXI7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2FkZE1hcmtlcnMnXSA9IE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuYWRkTWFya2VycztcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnY2xlYXJNYXJrZXJzJ10gPVxyXG4gICAgTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5jbGVhck1hcmtlcnM7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2ZpdE1hcFRvTWFya2VycyddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZml0TWFwVG9NYXJrZXJzO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydnZXRDYWxjdWxhdG9yJ10gPVxyXG4gICAgTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRDYWxjdWxhdG9yO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydnZXRHcmlkU2l6ZSddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0R3JpZFNpemU7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2dldEV4dGVuZGVkQm91bmRzJ10gPVxyXG4gICAgTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRFeHRlbmRlZEJvdW5kcztcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnZ2V0TWFwJ10gPSBNYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmdldE1hcDtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnZ2V0TWFya2VycyddID0gTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRNYXJrZXJzO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydnZXRNYXhab29tJ10gPSBNYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmdldE1heFpvb207XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2dldFN0eWxlcyddID0gTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5nZXRTdHlsZXM7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2dldFRvdGFsQ2x1c3RlcnMnXSA9XHJcbiAgICBNYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLmdldFRvdGFsQ2x1c3RlcnM7XHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGVbJ2dldFRvdGFsTWFya2VycyddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuZ2V0VG90YWxNYXJrZXJzO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydyZWRyYXcnXSA9IE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUucmVkcmF3O1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydyZW1vdmVNYXJrZXInXSA9XHJcbiAgICBNYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnJlbW92ZU1hcmtlcjtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsncmVtb3ZlTWFya2VycyddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUucmVtb3ZlTWFya2VycztcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsncmVzZXRWaWV3cG9ydCddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUucmVzZXRWaWV3cG9ydDtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsncmVwYWludCddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUucmVwYWludDtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnc2V0Q2FsY3VsYXRvciddID1cclxuICAgIE1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuc2V0Q2FsY3VsYXRvcjtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnc2V0R3JpZFNpemUnXSA9XHJcbiAgICBNYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlLnNldEdyaWRTaXplO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydzZXRNYXhab29tJ10gPVxyXG4gICAgTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5zZXRNYXhab29tO1xyXG5NYXJrZXJDbHVzdGVyZXIucHJvdG90eXBlWydvbkFkZCddID0gTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5vbkFkZDtcclxuTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZVsnZHJhdyddID0gTWFya2VyQ2x1c3RlcmVyLnByb3RvdHlwZS5kcmF3O1xyXG5cclxuQ2x1c3Rlci5wcm90b3R5cGVbJ2dldENlbnRlciddID0gQ2x1c3Rlci5wcm90b3R5cGUuZ2V0Q2VudGVyO1xyXG5DbHVzdGVyLnByb3RvdHlwZVsnZ2V0U2l6ZSddID0gQ2x1c3Rlci5wcm90b3R5cGUuZ2V0U2l6ZTtcclxuQ2x1c3Rlci5wcm90b3R5cGVbJ2dldE1hcmtlcnMnXSA9IENsdXN0ZXIucHJvdG90eXBlLmdldE1hcmtlcnM7XHJcblxyXG5DbHVzdGVySWNvbi5wcm90b3R5cGVbJ29uQWRkJ10gPSBDbHVzdGVySWNvbi5wcm90b3R5cGUub25BZGQ7XHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZVsnZHJhdyddID0gQ2x1c3Rlckljb24ucHJvdG90eXBlLmRyYXc7XHJcbkNsdXN0ZXJJY29uLnByb3RvdHlwZVsnb25SZW1vdmUnXSA9IENsdXN0ZXJJY29uLnByb3RvdHlwZS5vblJlbW92ZTtcclxuXHJcbk1hcmtlckNsdXN0ZXJlci5wcm90b3R5cGUuTUFSS0VSX0NMVVNURVJfSU1BR0VfUEFUSF8gPSAnLi9pbWFnZXMvbSc7ICAvL2NoYW5nZWQgaW1hZ2UgcGF0aFxyXG4iXSwic291cmNlUm9vdCI6IiJ9