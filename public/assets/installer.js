(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["installer"],{

/***/ "./assets/js/installer.js":
/*!********************************!*\
  !*** ./assets/js/installer.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {__webpack_require__(/*! core-js/modules/es.array.find.js */ "./node_modules/core-js/modules/es.array.find.js");

__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");

// css & scss
__webpack_require__(/*! ../vendor/HoldOn.js/HoldOn.min.css */ "./assets/vendor/HoldOn.js/HoldOn.min.css");

__webpack_require__(/*! ../vendor/jquery-steps/jquery-steps.css */ "./assets/vendor/jquery-steps/jquery-steps.css"); // js


__webpack_require__(/*! ../vendor/HoldOn.js/HoldOn.min.js */ "./assets/vendor/HoldOn.js/HoldOn.min.js");

__webpack_require__(/*! ../vendor/jquery-steps/jquery-steps.min.js */ "./assets/vendor/jquery-steps/jquery-steps.min.js");

$(document).ready(function () {
  $('#steps').steps({
    onChange: function onChange(currentIndex, newIndex, stepDirection) {
      $('#next-step').removeClass('d-none');
      $('#finish-step').removeClass('d-none');

      if (newIndex == 1) {
        if ($('#step' + newIndex).data('test-passed') == '0') {
          $('.step-steps > li > a[href="#step' + newIndex + '"]').closest('li').addClass('error');
          $('#next-step').addClass('d-none');
          $('#finish-step').addClass('d-none');
        }
      }

      if (newIndex == 2) {
        if ($('#step' + newIndex).data('test-passed') == '0') {
          $('.step-steps > li > a[href="#step' + newIndex + '"]').closest('li').addClass('error');
          $('#next-step').addClass('d-none');
          $('#finish-step').addClass('d-none');
        }
      }

      if (newIndex == 3) {
        $('#next-step').addClass('d-none');
      }

      if (newIndex == 4) {
        $('#next-step').addClass('d-none');

        if ($('#step1').data('test-passed') == '0' || $('#step2').data('test-passed') == '0' || $('#step3').data('test-passed') == '0') {
          $('#finish-step').addClass('d-none');
        }
      }

      return true;
    },
    onFinish: function onFinish() {
      HoldOn.open({
        theme: "sk-fading-circle",
        content: 'Please wait while saving your configuration...',
        backgroundColor: "#fff",
        textColor: "#f67611"
      });
      window.location = $('#finish-step').data('save-configuration-path') + '?host=' + $('#host').val() + '&username=' + $('#username').val() + '&password=' + $('#password').val() + '&name=' + $('#name').val();
    }
  });
  $('.step-steps a').each(function () {
    $(this).off("click");
    $(this).click(function (e) {
      e.preventDefault();
      return false;
    });
  });
  $('#test-database-connection').click(function () {
    var isFormValid = true;

    if ($('#host').val().length == 0) {
      $('#host').closest('.form-group').find('p').removeClass('d-none');
      $('#host').addClass('is-invalid');
      isFormValid = false;
    } else {
      $('#host').closest('.form-group').find('p').addClass('d-none');
      $('#host').removeClass('is-invalid');
    }

    if ($('#username').val().length == 0) {
      $('#username').closest('.form-group').find('p').removeClass('d-none');
      $('#username').addClass('is-invalid');
      isFormValid = false;
    } else {
      $('#username').closest('.form-group').find('p').addClass('d-none');
      $('#username').removeClass('is-invalid');
    }

    if ($('#name').val().length == 0) {
      $('#name').closest('.form-group').find('p').removeClass('d-none');
      $('#name').addClass('is-invalid');
      isFormValid = false;
    } else {
      $('#name').closest('.form-group').find('p').addClass('d-none');
      $('#name').removeClass('is-invalid');
    }

    if (isFormValid) {
      $('#test-database-connection').addClass('d-none');
      $.ajax({
        type: 'GET',
        url: $('#finish-step').data('test-database-connection-path'),
        data: {
          host: $('#host').val(),
          username: $('#username').val(),
          password: $('#password').val(),
          name: $('#name').val()
        },
        beforeSend: function beforeSend() {
          $('#database-connection-error').addClass('d-none');
          $('#database-connection-success').addClass('d-none');
          $('#testing-database-connection').removeClass('d-none');
        },
        success: function success(response) {
          $('#test-database-connection').removeClass('d-none');
          $('#testing-database-connection').addClass('d-none');

          if (response != '1') {
            $('#database-connection-error').html('<i class="fas fa-exclamation-circle mr-1"></i>' + response);
            $('#database-connection-error').removeClass('d-none');
            $('#step3').attr('data-test-passed', '0');
          } else {
            $('#next-step').removeClass('d-none');
            $('#database-connection-success').removeClass('d-none');
            $('#step3').attr('data-test-passed', '1');
          }
        }
      });
    }
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/vendor/HoldOn.js/HoldOn.min.css":
/*!************************************************!*\
  !*** ./assets/vendor/HoldOn.js/HoldOn.min.css ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/vendor/HoldOn.js/HoldOn.min.js":
/*!***********************************************!*\
  !*** ./assets/vendor/HoldOn.js/HoldOn.min.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery, $) {(function (b) {
  function a() {
    if ("undefined" == typeof jQuery) {
      throw new Error("HoldOn.js requires jQuery");
    }

    var c = {};

    c.open = function (e) {
      $("#holdon-overlay").remove();
      var h = "sk-rect";
      var g = "";
      var f = "";

      if (e) {
        if (e.hasOwnProperty("theme")) {
          h = e.theme;
        }

        if (e.hasOwnProperty("message")) {
          f = e.message;
        }
      }

      switch (h) {
        case "custom":
          g = '<div style="text-align: center;">' + e.content + "</div>";
          break;

        case "sk-dot":
          g = '<div class="sk-dot"> <div class="sk-dot1"></div> <div class="sk-dot2"></div> </div>';
          break;

        case "sk-rect":
          g = '<div class="sk-rect"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>';
          break;

        case "sk-cube":
          g = '<div class="sk-cube"> <div class="sk-cube1"></div> <div class="sk-cube2"></div> </div>';
          break;

        case "sk-bounce":
          g = '<div class="sk-bounce"> <div class="bounce1"></div> <div class="bounce2"></div> <div class="bounce3"></div> </div>';
          break;

        case "sk-circle":
          g = '<div class="sk-circle"> <div class="sk-circle1 sk-child"></div> <div class="sk-circle2 sk-child"></div> <div class="sk-circle3 sk-child"></div> <div class="sk-circle4 sk-child"></div> <div class="sk-circle5 sk-child"></div> <div class="sk-circle6 sk-child"></div> <div class="sk-circle7 sk-child"></div> <div class="sk-circle8 sk-child"></div> <div class="sk-circle9 sk-child"></div> <div class="sk-circle10 sk-child"></div> <div class="sk-circle11 sk-child"></div> <div class="sk-circle12 sk-child"></div> </div>';
          break;

        case "sk-cube-grid":
          g = '<div class="sk-cube-grid"> <div class="sk-cube-child sk-cube-grid1"></div> <div class="sk-cube-child sk-cube-grid2"></div> <div class="sk-cube-child sk-cube-grid3"></div> <div class="sk-cube-child sk-cube-grid4"></div> <div class="sk-cube-child sk-cube-grid5"></div> <div class="sk-cube-child sk-cube-grid6"></div> <div class="sk-cube-child sk-cube-grid7"></div> <div class="sk-cube-child sk-cube-grid8"></div> <div class="sk-cube-child sk-cube-grid9"></div> </div>';
          break;

        case "sk-folding-cube":
          g = '<div class="sk-folding-cube"> <div class="sk-cubechild1 sk-cube-parent"></div> <div class="sk-cubechild2 sk-cube-parent"></div> <div class="sk-cubechild4 sk-cube-parent"></div> <div class="sk-cubechild3 sk-cube-parent"></div> </div>';
          break;

        case "sk-fading-circle":
          g = '<div class="sk-fading-circle"> <div class="sk-fading-circle1 sk-circle-child"></div> <div class="sk-fading-circle2 sk-circle-child"></div> <div class="sk-fading-circle3 sk-circle-child"></div> <div class="sk-fading-circle4 sk-circle-child"></div> <div class="sk-fading-circle5 sk-circle-child"></div> <div class="sk-fading-circle6 sk-circle-child"></div> <div class="sk-fading-circle7 sk-circle-child"></div> <div class="sk-fading-circle8 sk-circle-child"></div> <div class="sk-fading-circle9 sk-circle-child"></div> <div class="sk-fading-circle10 sk-circle-child"></div> <div class="sk-fading-circle11 sk-circle-child"></div> <div class="sk-fading-circle12 sk-circle-child"></div> </div>';
          break;

        default:
          g = '<div class="sk-rect"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>';
          console.warn(h + " doesn't exist for HoldOn.js");
          break;
      }

      var d = '<div id="holdon-overlay" style="display: none;">\n                                    <div id="holdon-content-container">\n                                        <div id="holdon-content">' + g + '</div>\n                                        <div id="holdon-message">' + f + "</div>\n                                    </div>\n                                </div>";
      $(d).appendTo("body").fadeIn(300);

      if (e) {
        if (e.backgroundColor) {
          $("#holdon-overlay").css("backgroundColor", e.backgroundColor);
        }

        if (e.backgroundColor) {
          $("#holdon-message").css("color", e.textColor);
        }
      }
    };

    c.close = function () {
      $("#holdon-overlay").fadeOut(300, function () {
        $(this).remove();
      });
    };

    return c;
  }

  if (typeof HoldOn === "undefined") {
    b.HoldOn = a();
  }
})(window);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/vendor/jquery-steps/jquery-steps.css":
/*!*****************************************************!*\
  !*** ./assets/vendor/jquery-steps/jquery-steps.css ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/vendor/jquery-steps/jquery-steps.min.js":
/*!********************************************************!*\
  !*** ./assets/vendor/jquery-steps/jquery-steps.min.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;__webpack_require__(/*! core-js/modules/esnext.global-this.js */ "./node_modules/core-js/modules/esnext.global-this.js");

__webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");

__webpack_require__(/*! core-js/modules/es.array.find.js */ "./node_modules/core-js/modules/es.array.find.js");

__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");

__webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");

__webpack_require__(/*! core-js/modules/es.array.filter.js */ "./node_modules/core-js/modules/es.array.filter.js");

__webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");

__webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");

__webpack_require__(/*! core-js/modules/es.symbol.iterator.js */ "./node_modules/core-js/modules/es.symbol.iterator.js");

__webpack_require__(/*! core-js/modules/es.array.iterator.js */ "./node_modules/core-js/modules/es.array.iterator.js");

__webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");

__webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

/*!
    * Steps v1.0.3
    * https://github.com/oguzhanoya/jquery-steps
    *
    * Copyright (c) 2020 oguzhanoya
    * Released under the MIT license
    */
!function (t, e) {
  "object" == ( false ? undefined : _typeof(exports)) && "undefined" != typeof module ? e(__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")) :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (e),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function (t) {
  "use strict";

  function e(t) {
    return t && "object" == _typeof(t) && "default" in t ? t : {
      "default": t
    };
  }

  var a = e(t);

  function s(t, e) {
    for (var o = 0; o < e.length; o++) {
      var i = e[o];
      i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i);
    }
  }

  var n = {
    startAt: 0,
    showBackButton: !0,
    showFooterButtons: !0,
    onInit: $.noop,
    onDestroy: $.noop,
    onFinish: $.noop,
    onChange: function onChange() {
      return !0;
    },
    stepSelector: ".step-steps > li",
    contentSelector: ".step-content > .step-tab-panel",
    footerSelector: ".step-footer",
    buttonSelector: "button",
    activeClass: "active",
    doneClass: "done",
    errorClass: "error"
  },
      o = function () {
    function o(t, e) {
      !function (t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function");
      }(this, o), this.options = a["default"].extend({}, n, e), this.el = a["default"](t), this.init();
    }

    var t, e, i;
    return t = o, i = [{
      key: "setDefaults",
      value: function value(t) {
        a["default"].extend(n, a["default"].isPlainObject(t) && t);
      }
    }], (e = [{
      key: "stepClick",
      value: function value(t) {
        t.preventDefault();
        var e = a["default"](this).closest("li").index(),
            o = t.data.self.getStepIndex();
        t.data.self.setActiveStep(o, e);
      }
    }, {
      key: "btnClick",
      value: function value(t) {
        t.preventDefault();
        var e = a["default"](this).data("direction");
        t.data.self.setAction(e);
      }
    }, {
      key: "init",
      value: function value() {
        this.hook("onInit");
        a["default"](this.el).find(this.options.stepSelector).on("click", {
          self: this
        }, this.stepClick), a["default"](this.el).find("".concat(this.options.footerSelector, " ").concat(this.options.buttonSelector)).on("click", {
          self: this
        }, this.btnClick), this.setShowStep(this.options.startAt, "", this.options.activeClass), this.setFooterBtns(), this.options.showFooterButtons || (this.hideFooterBtns(), this.setFooterBtns = a["default"].noop);
      }
    }, {
      key: "hook",
      value: function value(t) {
        void 0 !== this.options[t] && this.options[t].call(this.el);
      }
    }, {
      key: "destroy",
      value: function value() {
        a["default"](this.el).find(this.options.stepSelector).off("click", this.stepClick), a["default"](this.el).find("".concat(this.options.footerSelector, " ").concat(this.options.buttonSelector)).off("click", this.btnClick), this.el.removeData("plugin_Steps"), this.hook("onDestroy");
      }
    }, {
      key: "getStepIndex",
      value: function value() {
        return this.el.find(this.options.stepSelector).filter(".".concat(this.options.activeClass)).index() || 0;
      }
    }, {
      key: "getMaxStepCount",
      value: function value() {
        return this.el.find(this.options.stepSelector).length - 1;
      }
    }, {
      key: "getStepDirection",
      value: function value(t, e) {
        var o = "none";
        return e < t ? o = "backward" : t < e && (o = "forward"), o;
      }
    }, {
      key: "setShowStep",
      value: function value(t, e, o) {
        var i = 2 < arguments.length && void 0 !== o ? o : "";
        this.el.find(this.options.contentSelector).removeClass(this.options.activeClass);
        var s = this.el.find(this.options.stepSelector).eq(t);
        s.removeClass(e).addClass(i);
        var n = s.find("a").attr("href");
        a["default"](n).addClass(this.options.activeClass);
      }
    }, {
      key: "setActiveStep",
      value: function value(t, e) {
        if (e !== t) {
          if (t < e) for (var o = 0; o <= e; o += 1) {
            o === e ? this.setShowStep(o, this.options.doneClass, this.options.activeClass) : this.setShowStep(o, "".concat(this.options.activeClass, " ").concat(this.options.errorClass), this.options.doneClass);
            var i = this.getStepDirection(o, e);

            if (!this.options.onChange(o, e, i)) {
              this.setShowStep(o, this.options.doneClass, "".concat(this.options.activeClass, " ").concat(this.options.errorClass)), this.setFooterBtns();
              break;
            }
          }
          if (e < t) for (var s = t; e <= s; --s) {
            var n = this.getStepDirection(s, e),
                a = this.options.onChange(s, e, n);

            if (this.setShowStep(s, "".concat(this.options.doneClass, " ").concat(this.options.activeClass, " ").concat(this.options.errorClass)), s === e && this.setShowStep(s, "".concat(this.options.doneClass, " ").concat(this.options.errorClass), this.options.activeClass), !a) {
              this.setShowStep(s, this.options.doneClass, "".concat(this.options.activeClass, " ").concat(this.options.errorClass)), this.setFooterBtns();
              break;
            }
          }
          this.setFooterBtns();
        }
      }
    }, {
      key: "setFooterBtns",
      value: function value() {
        var t = this.getStepIndex(),
            e = this.getMaxStepCount(),
            o = this.el.find(this.options.footerSelector);
        0 === t && o.find('button[data-direction="prev"]').hide(), 0 < t && this.options.showBackButton && o.find('button[data-direction="prev"]').show(), e === t ? (o.find('button[data-direction="prev"]').show(), o.find('button[data-direction="next"]').hide(), o.find('button[data-direction="finish"]').show()) : (o.find('button[data-direction="next"]').show(), o.find('button[data-direction="finish"]').hide()), this.options.showBackButton || o.find('button[data-direction="prev"]').hide();
      }
    }, {
      key: "setAction",
      value: function value(t) {
        var e = this.getStepIndex(),
            o = e;
        "prev" === t && --o, "next" === t && (o += 1), "finish" === t && (this.options.onChange(e, o, "forward") ? this.hook("onFinish") : this.setShowStep(e, "", "error")), "finish" !== t && this.setActiveStep(e, o);
      }
    }, {
      key: "next",
      value: function value() {
        var t = this.getStepIndex();
        return this.getMaxStepCount() === t ? this.setAction("finish") : this.setAction("next");
      }
    }, {
      key: "prev",
      value: function value() {
        return 0 !== this.getStepIndex() && this.setAction("prev");
      }
    }, {
      key: "finish",
      value: function value() {
        this.hook("onFinish");
      }
    }, {
      key: "hideFooterBtns",
      value: function value() {
        this.el.find(this.options.footerSelector).hide();
      }
    }]) && s(t.prototype, e), i && s(t, i), o;
  }(),
      i = a["default"].fn.steps;

  a["default"].fn.steps = function (t) {
    return this.each(function () {
      a["default"].data(this, "plugin_Steps") || a["default"].data(this, "plugin_Steps", new o(this, t));
    });
  }, a["default"].fn.steps.version = "1.0.2", a["default"].fn.steps.setDefaults = o.setDefaults, a["default"].fn.steps.noConflict = function () {
    return a["default"].fn.steps = i, this;
  };
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/core-js/modules/es.global-this.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/es.global-this.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var global = __webpack_require__(/*! ../internals/global */ "./node_modules/core-js/internals/global.js");

// `globalThis` object
// https://tc39.es/ecma262/#sec-globalthis
$({ global: true, forced: global.globalThis !== global }, {
  globalThis: global
});


/***/ }),

/***/ "./node_modules/core-js/modules/esnext.global-this.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/esnext.global-this.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// TODO: Remove from `core-js@4`
__webpack_require__(/*! ../modules/es.global-this */ "./node_modules/core-js/modules/es.global-this.js");


/***/ })

},[["./assets/js/installer.js","runtime","vendors~app~app.ar~app.es~app.fr~event~events~installer~organizerprofile","vendors~app~event~events~installer~organizerprofile","vendors~app~events~installer~organizerprofile","vendors~app~installer"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5zdGFsbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy92ZW5kb3IvSG9sZE9uLmpzL0hvbGRPbi5taW4uY3NzIiwid2VicGFjazovLy8uL2Fzc2V0cy92ZW5kb3IvSG9sZE9uLmpzL0hvbGRPbi5taW4uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3ZlbmRvci9qcXVlcnktc3RlcHMvanF1ZXJ5LXN0ZXBzLmNzcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvdmVuZG9yL2pxdWVyeS1zdGVwcy9qcXVlcnktc3RlcHMubWluLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9jb3JlLWpzL21vZHVsZXMvZXMuZ2xvYmFsLXRoaXMuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2NvcmUtanMvbW9kdWxlcy9lc25leHQuZ2xvYmFsLXRoaXMuanMiXSwibmFtZXMiOlsicmVxdWlyZSIsIiQiLCJkb2N1bWVudCIsInJlYWR5Iiwic3RlcHMiLCJvbkNoYW5nZSIsImN1cnJlbnRJbmRleCIsIm5ld0luZGV4Iiwic3RlcERpcmVjdGlvbiIsInJlbW92ZUNsYXNzIiwiZGF0YSIsImNsb3Nlc3QiLCJhZGRDbGFzcyIsIm9uRmluaXNoIiwiSG9sZE9uIiwib3BlbiIsInRoZW1lIiwiY29udGVudCIsImJhY2tncm91bmRDb2xvciIsInRleHRDb2xvciIsIndpbmRvdyIsImxvY2F0aW9uIiwidmFsIiwiZWFjaCIsIm9mZiIsImNsaWNrIiwiZSIsInByZXZlbnREZWZhdWx0IiwiaXNGb3JtVmFsaWQiLCJsZW5ndGgiLCJmaW5kIiwiYWpheCIsInR5cGUiLCJ1cmwiLCJob3N0IiwidXNlcm5hbWUiLCJwYXNzd29yZCIsIm5hbWUiLCJiZWZvcmVTZW5kIiwic3VjY2VzcyIsInJlc3BvbnNlIiwiaHRtbCIsImF0dHIiLCJiIiwiYSIsImpRdWVyeSIsIkVycm9yIiwiYyIsInJlbW92ZSIsImgiLCJnIiwiZiIsImhhc093blByb3BlcnR5IiwibWVzc2FnZSIsImNvbnNvbGUiLCJ3YXJuIiwiZCIsImFwcGVuZFRvIiwiZmFkZUluIiwiY3NzIiwiY2xvc2UiLCJmYWRlT3V0IiwidCIsImV4cG9ydHMiLCJtb2R1bGUiLCJkZWZpbmUiLCJzIiwibyIsImkiLCJlbnVtZXJhYmxlIiwiY29uZmlndXJhYmxlIiwid3JpdGFibGUiLCJPYmplY3QiLCJkZWZpbmVQcm9wZXJ0eSIsImtleSIsIm4iLCJzdGFydEF0Iiwic2hvd0JhY2tCdXR0b24iLCJzaG93Rm9vdGVyQnV0dG9ucyIsIm9uSW5pdCIsIm5vb3AiLCJvbkRlc3Ryb3kiLCJzdGVwU2VsZWN0b3IiLCJjb250ZW50U2VsZWN0b3IiLCJmb290ZXJTZWxlY3RvciIsImJ1dHRvblNlbGVjdG9yIiwiYWN0aXZlQ2xhc3MiLCJkb25lQ2xhc3MiLCJlcnJvckNsYXNzIiwiVHlwZUVycm9yIiwib3B0aW9ucyIsImV4dGVuZCIsImVsIiwiaW5pdCIsInZhbHVlIiwiaXNQbGFpbk9iamVjdCIsImluZGV4Iiwic2VsZiIsImdldFN0ZXBJbmRleCIsInNldEFjdGl2ZVN0ZXAiLCJzZXRBY3Rpb24iLCJob29rIiwib24iLCJzdGVwQ2xpY2siLCJjb25jYXQiLCJidG5DbGljayIsInNldFNob3dTdGVwIiwic2V0Rm9vdGVyQnRucyIsImhpZGVGb290ZXJCdG5zIiwiY2FsbCIsInJlbW92ZURhdGEiLCJmaWx0ZXIiLCJhcmd1bWVudHMiLCJlcSIsImdldFN0ZXBEaXJlY3Rpb24iLCJnZXRNYXhTdGVwQ291bnQiLCJoaWRlIiwic2hvdyIsInByb3RvdHlwZSIsImZuIiwidmVyc2lvbiIsInNldERlZmF1bHRzIiwibm9Db25mbGljdCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7OztBQUFBO0FBRUFBLG1CQUFPLENBQUMsb0ZBQUQsQ0FBUDs7QUFDQUEsbUJBQU8sQ0FBQyw4RkFBRCxDQUFQLEMsQ0FFQTs7O0FBRUFBLG1CQUFPLENBQUMsa0ZBQUQsQ0FBUDs7QUFDQUEsbUJBQU8sQ0FBQyxvR0FBRCxD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LENBQUMsV0FBRCxDQUFVMkUsRUFBVixDQUFhbkgsS0FBYixHQUFtQmdFLENBQW5CLEVBQXFCLElBQTVCO0VBQWlDLENBQS9TO0FBQWdULENBQTczSyxDQUFELEM7Ozs7Ozs7Ozs7OztBQ1BBLFFBQVEsbUJBQU8sQ0FBQyx1RUFBcUI7QUFDckMsYUFBYSxtQkFBTyxDQUFDLHVFQUFxQjs7QUFFMUM7QUFDQTtBQUNBLEdBQUcscURBQXFEO0FBQ3hEO0FBQ0EsQ0FBQzs7Ozs7Ozs7Ozs7O0FDUEQ7QUFDQSxtQkFBTyxDQUFDLG1GQUEyQiIsImZpbGUiOiJpbnN0YWxsZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLyBjc3MgJiBzY3NzXG5cbnJlcXVpcmUoJy4uL3ZlbmRvci9Ib2xkT24uanMvSG9sZE9uLm1pbi5jc3MnKTtcbnJlcXVpcmUoJy4uL3ZlbmRvci9qcXVlcnktc3RlcHMvanF1ZXJ5LXN0ZXBzLmNzcycpO1xuXG4vLyBqc1xuXG5yZXF1aXJlKCcuLi92ZW5kb3IvSG9sZE9uLmpzL0hvbGRPbi5taW4uanMnKTtcbnJlcXVpcmUoJy4uL3ZlbmRvci9qcXVlcnktc3RlcHMvanF1ZXJ5LXN0ZXBzLm1pbi5qcycpO1xuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG5cbiAgICAkKCcjc3RlcHMnKS5zdGVwcyh7XG4gICAgICAgIG9uQ2hhbmdlOiBmdW5jdGlvbiAoY3VycmVudEluZGV4LCBuZXdJbmRleCwgc3RlcERpcmVjdGlvbikge1xuICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICQoJyNmaW5pc2gtc3RlcCcpLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgIGlmIChuZXdJbmRleCA9PSAxKSB7XG4gICAgICAgICAgICAgICAgaWYgKCQoJyNzdGVwJyArIG5ld0luZGV4KS5kYXRhKCd0ZXN0LXBhc3NlZCcpID09ICcwJykge1xuICAgICAgICAgICAgICAgICAgICAkKCcuc3RlcC1zdGVwcyA+IGxpID4gYVtocmVmPVwiI3N0ZXAnICsgbmV3SW5kZXggKyAnXCJdJykuY2xvc2VzdCgnbGknKS5hZGRDbGFzcygnZXJyb3InKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2ZpbmlzaC1zdGVwJykuYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChuZXdJbmRleCA9PSAyKSB7XG4gICAgICAgICAgICAgICAgaWYgKCQoJyNzdGVwJyArIG5ld0luZGV4KS5kYXRhKCd0ZXN0LXBhc3NlZCcpID09ICcwJykge1xuICAgICAgICAgICAgICAgICAgICAkKCcuc3RlcC1zdGVwcyA+IGxpID4gYVtocmVmPVwiI3N0ZXAnICsgbmV3SW5kZXggKyAnXCJdJykuY2xvc2VzdCgnbGknKS5hZGRDbGFzcygnZXJyb3InKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2ZpbmlzaC1zdGVwJykuYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChuZXdJbmRleCA9PSAzKSB7XG4gICAgICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChuZXdJbmRleCA9PSA0KSB7XG4gICAgICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICBpZiAoJCgnI3N0ZXAxJykuZGF0YSgndGVzdC1wYXNzZWQnKSA9PSAnMCcgfHwgJCgnI3N0ZXAyJykuZGF0YSgndGVzdC1wYXNzZWQnKSA9PSAnMCcgfHwgJCgnI3N0ZXAzJykuZGF0YSgndGVzdC1wYXNzZWQnKSA9PSAnMCcpIHtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2ZpbmlzaC1zdGVwJykuYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9LFxuICAgICAgICBvbkZpbmlzaDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgSG9sZE9uLm9wZW4oe1xuICAgICAgICAgICAgICAgIHRoZW1lOiBcInNrLWZhZGluZy1jaXJjbGVcIixcbiAgICAgICAgICAgICAgICBjb250ZW50OiAnUGxlYXNlIHdhaXQgd2hpbGUgc2F2aW5nIHlvdXIgY29uZmlndXJhdGlvbi4uLicsXG4gICAgICAgICAgICAgICAgYmFja2dyb3VuZENvbG9yOiBcIiNmZmZcIixcbiAgICAgICAgICAgICAgICB0ZXh0Q29sb3I6IFwiI2Y2NzYxMVwiXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbiA9ICQoJyNmaW5pc2gtc3RlcCcpLmRhdGEoJ3NhdmUtY29uZmlndXJhdGlvbi1wYXRoJykgKyAnP2hvc3Q9JyArICQoJyNob3N0JykudmFsKCkgKyAnJnVzZXJuYW1lPScgKyAkKCcjdXNlcm5hbWUnKS52YWwoKSArICcmcGFzc3dvcmQ9JyArICQoJyNwYXNzd29yZCcpLnZhbCgpICsgJyZuYW1lPScgKyAkKCcjbmFtZScpLnZhbCgpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICAkKCcuc3RlcC1zdGVwcyBhJykuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICQodGhpcykub2ZmKFwiY2xpY2tcIik7XG4gICAgICAgICQodGhpcykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICAkKCcjdGVzdC1kYXRhYmFzZS1jb25uZWN0aW9uJykuY2xpY2soZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgaXNGb3JtVmFsaWQgPSB0cnVlO1xuICAgICAgICBpZiAoJCgnI2hvc3QnKS52YWwoKS5sZW5ndGggPT0gMCkge1xuICAgICAgICAgICAgJCgnI2hvc3QnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmZpbmQoJ3AnKS5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgICAgICAkKCcjaG9zdCcpLmFkZENsYXNzKCdpcy1pbnZhbGlkJyk7XG4gICAgICAgICAgICBpc0Zvcm1WYWxpZCA9IGZhbHNlO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgJCgnI2hvc3QnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmZpbmQoJ3AnKS5hZGRDbGFzcygnZC1ub25lJyk7XG4gICAgICAgICAgICAkKCcjaG9zdCcpLnJlbW92ZUNsYXNzKCdpcy1pbnZhbGlkJyk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCQoJyN1c2VybmFtZScpLnZhbCgpLmxlbmd0aCA9PSAwKSB7XG4gICAgICAgICAgICAkKCcjdXNlcm5hbWUnKS5jbG9zZXN0KCcuZm9ybS1ncm91cCcpLmZpbmQoJ3AnKS5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgICAgICAkKCcjdXNlcm5hbWUnKS5hZGRDbGFzcygnaXMtaW52YWxpZCcpO1xuICAgICAgICAgICAgaXNGb3JtVmFsaWQgPSBmYWxzZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICQoJyN1c2VybmFtZScpLmNsb3Nlc3QoJy5mb3JtLWdyb3VwJykuZmluZCgncCcpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICQoJyN1c2VybmFtZScpLnJlbW92ZUNsYXNzKCdpcy1pbnZhbGlkJyk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCQoJyNuYW1lJykudmFsKCkubGVuZ3RoID09IDApIHtcbiAgICAgICAgICAgICQoJyNuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5maW5kKCdwJykucmVtb3ZlQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgJCgnI25hbWUnKS5hZGRDbGFzcygnaXMtaW52YWxpZCcpO1xuICAgICAgICAgICAgaXNGb3JtVmFsaWQgPSBmYWxzZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICQoJyNuYW1lJykuY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKS5maW5kKCdwJykuYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgJCgnI25hbWUnKS5yZW1vdmVDbGFzcygnaXMtaW52YWxpZCcpO1xuICAgICAgICB9XG4gICAgICAgIGlmIChpc0Zvcm1WYWxpZCkge1xuICAgICAgICAgICAgJCgnI3Rlc3QtZGF0YWJhc2UtY29ubmVjdGlvbicpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdHlwZTogJ0dFVCcsXG4gICAgICAgICAgICAgICAgdXJsOiAkKCcjZmluaXNoLXN0ZXAnKS5kYXRhKCd0ZXN0LWRhdGFiYXNlLWNvbm5lY3Rpb24tcGF0aCcpLFxuICAgICAgICAgICAgICAgIGRhdGE6IHtob3N0OiAkKCcjaG9zdCcpLnZhbCgpLCB1c2VybmFtZTogJCgnI3VzZXJuYW1lJykudmFsKCksIHBhc3N3b3JkOiAkKCcjcGFzc3dvcmQnKS52YWwoKSwgbmFtZTogJCgnI25hbWUnKS52YWwoKX0sXG4gICAgICAgICAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAkKCcjZGF0YWJhc2UtY29ubmVjdGlvbi1lcnJvcicpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI2RhdGFiYXNlLWNvbm5lY3Rpb24tc3VjY2VzcycpLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgJCgnI3Rlc3RpbmctZGF0YWJhc2UtY29ubmVjdGlvbicpLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChyZXNwb25zZSkge1xuICAgICAgICAgICAgICAgICAgICAkKCcjdGVzdC1kYXRhYmFzZS1jb25uZWN0aW9uJykucmVtb3ZlQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgICAgICAgICAkKCcjdGVzdGluZy1kYXRhYmFzZS1jb25uZWN0aW9uJykuYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICAgICAgICAgICAgICBpZiAocmVzcG9uc2UgIT0gJzEnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZGF0YWJhc2UtY29ubmVjdGlvbi1lcnJvcicpLmh0bWwoJzxpIGNsYXNzPVwiZmFzIGZhLWV4Y2xhbWF0aW9uLWNpcmNsZSBtci0xXCI+PC9pPicgKyByZXNwb25zZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZGF0YWJhc2UtY29ubmVjdGlvbi1lcnJvcicpLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNzdGVwMycpLmF0dHIoJ2RhdGEtdGVzdC1wYXNzZWQnLCAnMCcpO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnI25leHQtc3RlcCcpLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJyNkYXRhYmFzZS1jb25uZWN0aW9uLXN1Y2Nlc3MnKS5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjc3RlcDMnKS5hdHRyKCdkYXRhLXRlc3QtcGFzc2VkJywgJzEnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfSk7XG5cbn0pOyIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpbiIsIlxuKGZ1bmN0aW9uKGIpe2Z1bmN0aW9uIGEoKXtpZihcInVuZGVmaW5lZFwiPT10eXBlb2YgalF1ZXJ5KXt0aHJvdyBuZXcgRXJyb3IoXCJIb2xkT24uanMgcmVxdWlyZXMgalF1ZXJ5XCIpfXZhciBjPXt9O2Mub3Blbj1mdW5jdGlvbihlKXskKFwiI2hvbGRvbi1vdmVybGF5XCIpLnJlbW92ZSgpO3ZhciBoPVwic2stcmVjdFwiO3ZhciBnPVwiXCI7dmFyIGY9XCJcIjtpZihlKXtpZihlLmhhc093blByb3BlcnR5KFwidGhlbWVcIikpe2g9ZS50aGVtZX1pZihlLmhhc093blByb3BlcnR5KFwibWVzc2FnZVwiKSl7Zj1lLm1lc3NhZ2V9fXN3aXRjaChoKXtjYXNlXCJjdXN0b21cIjpnPSc8ZGl2IHN0eWxlPVwidGV4dC1hbGlnbjogY2VudGVyO1wiPicrZS5jb250ZW50K1wiPC9kaXY+XCI7YnJlYWs7Y2FzZVwic2stZG90XCI6Zz0nPGRpdiBjbGFzcz1cInNrLWRvdFwiPiA8ZGl2IGNsYXNzPVwic2stZG90MVwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stZG90MlwiPjwvZGl2PiA8L2Rpdj4nO2JyZWFrO2Nhc2VcInNrLXJlY3RcIjpnPSc8ZGl2IGNsYXNzPVwic2stcmVjdFwiPiA8ZGl2IGNsYXNzPVwicmVjdDFcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInJlY3QyXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJyZWN0M1wiPjwvZGl2PiA8ZGl2IGNsYXNzPVwicmVjdDRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInJlY3Q1XCI+PC9kaXY+IDwvZGl2Pic7YnJlYWs7Y2FzZVwic2stY3ViZVwiOmc9JzxkaXYgY2xhc3M9XCJzay1jdWJlXCI+IDxkaXYgY2xhc3M9XCJzay1jdWJlMVwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY3ViZTJcIj48L2Rpdj4gPC9kaXY+JzticmVhaztjYXNlXCJzay1ib3VuY2VcIjpnPSc8ZGl2IGNsYXNzPVwic2stYm91bmNlXCI+IDxkaXYgY2xhc3M9XCJib3VuY2UxXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJib3VuY2UyXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJib3VuY2UzXCI+PC9kaXY+IDwvZGl2Pic7YnJlYWs7Y2FzZVwic2stY2lyY2xlXCI6Zz0nPGRpdiBjbGFzcz1cInNrLWNpcmNsZVwiPiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlMSBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlMiBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlMyBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlNCBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlNSBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlNiBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlNyBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlOCBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlOSBzay1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY2lyY2xlMTAgc2stY2hpbGRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWNpcmNsZTExIHNrLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jaXJjbGUxMiBzay1jaGlsZFwiPjwvZGl2PiA8L2Rpdj4nO2JyZWFrO2Nhc2VcInNrLWN1YmUtZ3JpZFwiOmc9JzxkaXYgY2xhc3M9XCJzay1jdWJlLWdyaWRcIj4gPGRpdiBjbGFzcz1cInNrLWN1YmUtY2hpbGQgc2stY3ViZS1ncmlkMVwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY3ViZS1jaGlsZCBzay1jdWJlLWdyaWQyXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlLWNoaWxkIHNrLWN1YmUtZ3JpZDNcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWN1YmUtY2hpbGQgc2stY3ViZS1ncmlkNFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY3ViZS1jaGlsZCBzay1jdWJlLWdyaWQ1XCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlLWNoaWxkIHNrLWN1YmUtZ3JpZDZcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWN1YmUtY2hpbGQgc2stY3ViZS1ncmlkN1wiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stY3ViZS1jaGlsZCBzay1jdWJlLWdyaWQ4XCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlLWNoaWxkIHNrLWN1YmUtZ3JpZDlcIj48L2Rpdj4gPC9kaXY+JzticmVhaztjYXNlXCJzay1mb2xkaW5nLWN1YmVcIjpnPSc8ZGl2IGNsYXNzPVwic2stZm9sZGluZy1jdWJlXCI+IDxkaXYgY2xhc3M9XCJzay1jdWJlY2hpbGQxIHNrLWN1YmUtcGFyZW50XCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlY2hpbGQyIHNrLWN1YmUtcGFyZW50XCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlY2hpbGQ0IHNrLWN1YmUtcGFyZW50XCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1jdWJlY2hpbGQzIHNrLWN1YmUtcGFyZW50XCI+PC9kaXY+IDwvZGl2Pic7YnJlYWs7Y2FzZVwic2stZmFkaW5nLWNpcmNsZVwiOmc9JzxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlXCI+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlMSBzay1jaXJjbGUtY2hpbGRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWZhZGluZy1jaXJjbGUyIHNrLWNpcmNsZS1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stZmFkaW5nLWNpcmNsZTMgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlNCBzay1jaXJjbGUtY2hpbGRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWZhZGluZy1jaXJjbGU1IHNrLWNpcmNsZS1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stZmFkaW5nLWNpcmNsZTYgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlNyBzay1jaXJjbGUtY2hpbGRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInNrLWZhZGluZy1jaXJjbGU4IHNrLWNpcmNsZS1jaGlsZFwiPjwvZGl2PiA8ZGl2IGNsYXNzPVwic2stZmFkaW5nLWNpcmNsZTkgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlMTAgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlMTEgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJzay1mYWRpbmctY2lyY2xlMTIgc2stY2lyY2xlLWNoaWxkXCI+PC9kaXY+IDwvZGl2Pic7YnJlYWs7ZGVmYXVsdDpnPSc8ZGl2IGNsYXNzPVwic2stcmVjdFwiPiA8ZGl2IGNsYXNzPVwicmVjdDFcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInJlY3QyXCI+PC9kaXY+IDxkaXYgY2xhc3M9XCJyZWN0M1wiPjwvZGl2PiA8ZGl2IGNsYXNzPVwicmVjdDRcIj48L2Rpdj4gPGRpdiBjbGFzcz1cInJlY3Q1XCI+PC9kaXY+IDwvZGl2Pic7Y29uc29sZS53YXJuKGgrXCIgZG9lc24ndCBleGlzdCBmb3IgSG9sZE9uLmpzXCIpO2JyZWFrfXZhciBkPSc8ZGl2IGlkPVwiaG9sZG9uLW92ZXJsYXlcIiBzdHlsZT1cImRpc3BsYXk6IG5vbmU7XCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBpZD1cImhvbGRvbi1jb250ZW50LWNvbnRhaW5lclwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGlkPVwiaG9sZG9uLWNvbnRlbnRcIj4nK2crJzwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGlkPVwiaG9sZG9uLW1lc3NhZ2VcIj4nK2YrXCI8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlwiOyQoZCkuYXBwZW5kVG8oXCJib2R5XCIpLmZhZGVJbigzMDApO2lmKGUpe2lmKGUuYmFja2dyb3VuZENvbG9yKXskKFwiI2hvbGRvbi1vdmVybGF5XCIpLmNzcyhcImJhY2tncm91bmRDb2xvclwiLGUuYmFja2dyb3VuZENvbG9yKX1pZihlLmJhY2tncm91bmRDb2xvcil7JChcIiNob2xkb24tbWVzc2FnZVwiKS5jc3MoXCJjb2xvclwiLGUudGV4dENvbG9yKX19fTtjLmNsb3NlPWZ1bmN0aW9uKCl7JChcIiNob2xkb24tb3ZlcmxheVwiKS5mYWRlT3V0KDMwMCxmdW5jdGlvbigpeyQodGhpcykucmVtb3ZlKCl9KX07cmV0dXJuIGN9aWYodHlwZW9mKEhvbGRPbik9PT1cInVuZGVmaW5lZFwiKXtiLkhvbGRPbj1hKCl9fSkod2luZG93KTsiLCIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW4iLCIvKiFcbiAgICAqIFN0ZXBzIHYxLjAuM1xuICAgICogaHR0cHM6Ly9naXRodWIuY29tL29ndXpoYW5veWEvanF1ZXJ5LXN0ZXBzXG4gICAgKlxuICAgICogQ29weXJpZ2h0IChjKSAyMDIwIG9ndXpoYW5veWFcbiAgICAqIFJlbGVhc2VkIHVuZGVyIHRoZSBNSVQgbGljZW5zZVxuICAgICovXG4hZnVuY3Rpb24odCxlKXtcIm9iamVjdFwiPT10eXBlb2YgZXhwb3J0cyYmXCJ1bmRlZmluZWRcIiE9dHlwZW9mIG1vZHVsZT9lKHJlcXVpcmUoXCJqcXVlcnlcIikpOlwiZnVuY3Rpb25cIj09dHlwZW9mIGRlZmluZSYmZGVmaW5lLmFtZD9kZWZpbmUoW1wianF1ZXJ5XCJdLGUpOmUoKHQ9XCJ1bmRlZmluZWRcIiE9dHlwZW9mIGdsb2JhbFRoaXM/Z2xvYmFsVGhpczp0fHxzZWxmKS4kKX0odGhpcyxmdW5jdGlvbih0KXtcInVzZSBzdHJpY3RcIjtmdW5jdGlvbiBlKHQpe3JldHVybiB0JiZcIm9iamVjdFwiPT10eXBlb2YgdCYmXCJkZWZhdWx0XCJpbiB0P3Q6e2RlZmF1bHQ6dH19dmFyIGE9ZSh0KTtmdW5jdGlvbiBzKHQsZSl7Zm9yKHZhciBvPTA7bzxlLmxlbmd0aDtvKyspe3ZhciBpPWVbb107aS5lbnVtZXJhYmxlPWkuZW51bWVyYWJsZXx8ITEsaS5jb25maWd1cmFibGU9ITAsXCJ2YWx1ZVwiaW4gaSYmKGkud3JpdGFibGU9ITApLE9iamVjdC5kZWZpbmVQcm9wZXJ0eSh0LGkua2V5LGkpfX12YXIgbj17c3RhcnRBdDowLHNob3dCYWNrQnV0dG9uOiEwLHNob3dGb290ZXJCdXR0b25zOiEwLG9uSW5pdDokLm5vb3Asb25EZXN0cm95OiQubm9vcCxvbkZpbmlzaDokLm5vb3Asb25DaGFuZ2U6ZnVuY3Rpb24oKXtyZXR1cm4hMH0sc3RlcFNlbGVjdG9yOlwiLnN0ZXAtc3RlcHMgPiBsaVwiLGNvbnRlbnRTZWxlY3RvcjpcIi5zdGVwLWNvbnRlbnQgPiAuc3RlcC10YWItcGFuZWxcIixmb290ZXJTZWxlY3RvcjpcIi5zdGVwLWZvb3RlclwiLGJ1dHRvblNlbGVjdG9yOlwiYnV0dG9uXCIsYWN0aXZlQ2xhc3M6XCJhY3RpdmVcIixkb25lQ2xhc3M6XCJkb25lXCIsZXJyb3JDbGFzczpcImVycm9yXCJ9LG89ZnVuY3Rpb24oKXtmdW5jdGlvbiBvKHQsZSl7IWZ1bmN0aW9uKHQsZSl7aWYoISh0IGluc3RhbmNlb2YgZSkpdGhyb3cgbmV3IFR5cGVFcnJvcihcIkNhbm5vdCBjYWxsIGEgY2xhc3MgYXMgYSBmdW5jdGlvblwiKX0odGhpcyxvKSx0aGlzLm9wdGlvbnM9YS5kZWZhdWx0LmV4dGVuZCh7fSxuLGUpLHRoaXMuZWw9YS5kZWZhdWx0KHQpLHRoaXMuaW5pdCgpfXZhciB0LGUsaTtyZXR1cm4gdD1vLGk9W3trZXk6XCJzZXREZWZhdWx0c1wiLHZhbHVlOmZ1bmN0aW9uKHQpe2EuZGVmYXVsdC5leHRlbmQobixhLmRlZmF1bHQuaXNQbGFpbk9iamVjdCh0KSYmdCl9fV0sKGU9W3trZXk6XCJzdGVwQ2xpY2tcIix2YWx1ZTpmdW5jdGlvbih0KXt0LnByZXZlbnREZWZhdWx0KCk7dmFyIGU9YS5kZWZhdWx0KHRoaXMpLmNsb3Nlc3QoXCJsaVwiKS5pbmRleCgpLG89dC5kYXRhLnNlbGYuZ2V0U3RlcEluZGV4KCk7dC5kYXRhLnNlbGYuc2V0QWN0aXZlU3RlcChvLGUpfX0se2tleTpcImJ0bkNsaWNrXCIsdmFsdWU6ZnVuY3Rpb24odCl7dC5wcmV2ZW50RGVmYXVsdCgpO3ZhciBlPWEuZGVmYXVsdCh0aGlzKS5kYXRhKFwiZGlyZWN0aW9uXCIpO3QuZGF0YS5zZWxmLnNldEFjdGlvbihlKX19LHtrZXk6XCJpbml0XCIsdmFsdWU6ZnVuY3Rpb24oKXt0aGlzLmhvb2soXCJvbkluaXRcIik7YS5kZWZhdWx0KHRoaXMuZWwpLmZpbmQodGhpcy5vcHRpb25zLnN0ZXBTZWxlY3Rvcikub24oXCJjbGlja1wiLHtzZWxmOnRoaXN9LHRoaXMuc3RlcENsaWNrKSxhLmRlZmF1bHQodGhpcy5lbCkuZmluZChcIlwiLmNvbmNhdCh0aGlzLm9wdGlvbnMuZm9vdGVyU2VsZWN0b3IsXCIgXCIpLmNvbmNhdCh0aGlzLm9wdGlvbnMuYnV0dG9uU2VsZWN0b3IpKS5vbihcImNsaWNrXCIse3NlbGY6dGhpc30sdGhpcy5idG5DbGljayksdGhpcy5zZXRTaG93U3RlcCh0aGlzLm9wdGlvbnMuc3RhcnRBdCxcIlwiLHRoaXMub3B0aW9ucy5hY3RpdmVDbGFzcyksdGhpcy5zZXRGb290ZXJCdG5zKCksdGhpcy5vcHRpb25zLnNob3dGb290ZXJCdXR0b25zfHwodGhpcy5oaWRlRm9vdGVyQnRucygpLHRoaXMuc2V0Rm9vdGVyQnRucz1hLmRlZmF1bHQubm9vcCl9fSx7a2V5OlwiaG9va1wiLHZhbHVlOmZ1bmN0aW9uKHQpe3ZvaWQgMCE9PXRoaXMub3B0aW9uc1t0XSYmdGhpcy5vcHRpb25zW3RdLmNhbGwodGhpcy5lbCl9fSx7a2V5OlwiZGVzdHJveVwiLHZhbHVlOmZ1bmN0aW9uKCl7YS5kZWZhdWx0KHRoaXMuZWwpLmZpbmQodGhpcy5vcHRpb25zLnN0ZXBTZWxlY3Rvcikub2ZmKFwiY2xpY2tcIix0aGlzLnN0ZXBDbGljayksYS5kZWZhdWx0KHRoaXMuZWwpLmZpbmQoXCJcIi5jb25jYXQodGhpcy5vcHRpb25zLmZvb3RlclNlbGVjdG9yLFwiIFwiKS5jb25jYXQodGhpcy5vcHRpb25zLmJ1dHRvblNlbGVjdG9yKSkub2ZmKFwiY2xpY2tcIix0aGlzLmJ0bkNsaWNrKSx0aGlzLmVsLnJlbW92ZURhdGEoXCJwbHVnaW5fU3RlcHNcIiksdGhpcy5ob29rKFwib25EZXN0cm95XCIpfX0se2tleTpcImdldFN0ZXBJbmRleFwiLHZhbHVlOmZ1bmN0aW9uKCl7cmV0dXJuIHRoaXMuZWwuZmluZCh0aGlzLm9wdGlvbnMuc3RlcFNlbGVjdG9yKS5maWx0ZXIoXCIuXCIuY29uY2F0KHRoaXMub3B0aW9ucy5hY3RpdmVDbGFzcykpLmluZGV4KCl8fDB9fSx7a2V5OlwiZ2V0TWF4U3RlcENvdW50XCIsdmFsdWU6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5lbC5maW5kKHRoaXMub3B0aW9ucy5zdGVwU2VsZWN0b3IpLmxlbmd0aC0xfX0se2tleTpcImdldFN0ZXBEaXJlY3Rpb25cIix2YWx1ZTpmdW5jdGlvbih0LGUpe3ZhciBvPVwibm9uZVwiO3JldHVybiBlPHQ/bz1cImJhY2t3YXJkXCI6dDxlJiYobz1cImZvcndhcmRcIiksb319LHtrZXk6XCJzZXRTaG93U3RlcFwiLHZhbHVlOmZ1bmN0aW9uKHQsZSxvKXt2YXIgaT0yPGFyZ3VtZW50cy5sZW5ndGgmJnZvaWQgMCE9PW8/bzpcIlwiO3RoaXMuZWwuZmluZCh0aGlzLm9wdGlvbnMuY29udGVudFNlbGVjdG9yKS5yZW1vdmVDbGFzcyh0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MpO3ZhciBzPXRoaXMuZWwuZmluZCh0aGlzLm9wdGlvbnMuc3RlcFNlbGVjdG9yKS5lcSh0KTtzLnJlbW92ZUNsYXNzKGUpLmFkZENsYXNzKGkpO3ZhciBuPXMuZmluZChcImFcIikuYXR0cihcImhyZWZcIik7YS5kZWZhdWx0KG4pLmFkZENsYXNzKHRoaXMub3B0aW9ucy5hY3RpdmVDbGFzcyl9fSx7a2V5Olwic2V0QWN0aXZlU3RlcFwiLHZhbHVlOmZ1bmN0aW9uKHQsZSl7aWYoZSE9PXQpe2lmKHQ8ZSlmb3IodmFyIG89MDtvPD1lO28rPTEpe289PT1lP3RoaXMuc2V0U2hvd1N0ZXAobyx0aGlzLm9wdGlvbnMuZG9uZUNsYXNzLHRoaXMub3B0aW9ucy5hY3RpdmVDbGFzcyk6dGhpcy5zZXRTaG93U3RlcChvLFwiXCIuY29uY2F0KHRoaXMub3B0aW9ucy5hY3RpdmVDbGFzcyxcIiBcIikuY29uY2F0KHRoaXMub3B0aW9ucy5lcnJvckNsYXNzKSx0aGlzLm9wdGlvbnMuZG9uZUNsYXNzKTt2YXIgaT10aGlzLmdldFN0ZXBEaXJlY3Rpb24obyxlKTtpZighdGhpcy5vcHRpb25zLm9uQ2hhbmdlKG8sZSxpKSl7dGhpcy5zZXRTaG93U3RlcChvLHRoaXMub3B0aW9ucy5kb25lQ2xhc3MsXCJcIi5jb25jYXQodGhpcy5vcHRpb25zLmFjdGl2ZUNsYXNzLFwiIFwiKS5jb25jYXQodGhpcy5vcHRpb25zLmVycm9yQ2xhc3MpKSx0aGlzLnNldEZvb3RlckJ0bnMoKTticmVha319aWYoZTx0KWZvcih2YXIgcz10O2U8PXM7LS1zKXt2YXIgbj10aGlzLmdldFN0ZXBEaXJlY3Rpb24ocyxlKSxhPXRoaXMub3B0aW9ucy5vbkNoYW5nZShzLGUsbik7aWYodGhpcy5zZXRTaG93U3RlcChzLFwiXCIuY29uY2F0KHRoaXMub3B0aW9ucy5kb25lQ2xhc3MsXCIgXCIpLmNvbmNhdCh0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MsXCIgXCIpLmNvbmNhdCh0aGlzLm9wdGlvbnMuZXJyb3JDbGFzcykpLHM9PT1lJiZ0aGlzLnNldFNob3dTdGVwKHMsXCJcIi5jb25jYXQodGhpcy5vcHRpb25zLmRvbmVDbGFzcyxcIiBcIikuY29uY2F0KHRoaXMub3B0aW9ucy5lcnJvckNsYXNzKSx0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MpLCFhKXt0aGlzLnNldFNob3dTdGVwKHMsdGhpcy5vcHRpb25zLmRvbmVDbGFzcyxcIlwiLmNvbmNhdCh0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MsXCIgXCIpLmNvbmNhdCh0aGlzLm9wdGlvbnMuZXJyb3JDbGFzcykpLHRoaXMuc2V0Rm9vdGVyQnRucygpO2JyZWFrfX10aGlzLnNldEZvb3RlckJ0bnMoKX19fSx7a2V5Olwic2V0Rm9vdGVyQnRuc1wiLHZhbHVlOmZ1bmN0aW9uKCl7dmFyIHQ9dGhpcy5nZXRTdGVwSW5kZXgoKSxlPXRoaXMuZ2V0TWF4U3RlcENvdW50KCksbz10aGlzLmVsLmZpbmQodGhpcy5vcHRpb25zLmZvb3RlclNlbGVjdG9yKTswPT09dCYmby5maW5kKCdidXR0b25bZGF0YS1kaXJlY3Rpb249XCJwcmV2XCJdJykuaGlkZSgpLDA8dCYmdGhpcy5vcHRpb25zLnNob3dCYWNrQnV0dG9uJiZvLmZpbmQoJ2J1dHRvbltkYXRhLWRpcmVjdGlvbj1cInByZXZcIl0nKS5zaG93KCksZT09PXQ/KG8uZmluZCgnYnV0dG9uW2RhdGEtZGlyZWN0aW9uPVwicHJldlwiXScpLnNob3coKSxvLmZpbmQoJ2J1dHRvbltkYXRhLWRpcmVjdGlvbj1cIm5leHRcIl0nKS5oaWRlKCksby5maW5kKCdidXR0b25bZGF0YS1kaXJlY3Rpb249XCJmaW5pc2hcIl0nKS5zaG93KCkpOihvLmZpbmQoJ2J1dHRvbltkYXRhLWRpcmVjdGlvbj1cIm5leHRcIl0nKS5zaG93KCksby5maW5kKCdidXR0b25bZGF0YS1kaXJlY3Rpb249XCJmaW5pc2hcIl0nKS5oaWRlKCkpLHRoaXMub3B0aW9ucy5zaG93QmFja0J1dHRvbnx8by5maW5kKCdidXR0b25bZGF0YS1kaXJlY3Rpb249XCJwcmV2XCJdJykuaGlkZSgpfX0se2tleTpcInNldEFjdGlvblwiLHZhbHVlOmZ1bmN0aW9uKHQpe3ZhciBlPXRoaXMuZ2V0U3RlcEluZGV4KCksbz1lO1wicHJldlwiPT09dCYmLS1vLFwibmV4dFwiPT09dCYmKG8rPTEpLFwiZmluaXNoXCI9PT10JiYodGhpcy5vcHRpb25zLm9uQ2hhbmdlKGUsbyxcImZvcndhcmRcIik/dGhpcy5ob29rKFwib25GaW5pc2hcIik6dGhpcy5zZXRTaG93U3RlcChlLFwiXCIsXCJlcnJvclwiKSksXCJmaW5pc2hcIiE9PXQmJnRoaXMuc2V0QWN0aXZlU3RlcChlLG8pfX0se2tleTpcIm5leHRcIix2YWx1ZTpmdW5jdGlvbigpe3ZhciB0PXRoaXMuZ2V0U3RlcEluZGV4KCk7cmV0dXJuIHRoaXMuZ2V0TWF4U3RlcENvdW50KCk9PT10P3RoaXMuc2V0QWN0aW9uKFwiZmluaXNoXCIpOnRoaXMuc2V0QWN0aW9uKFwibmV4dFwiKX19LHtrZXk6XCJwcmV2XCIsdmFsdWU6ZnVuY3Rpb24oKXtyZXR1cm4gMCE9PXRoaXMuZ2V0U3RlcEluZGV4KCkmJnRoaXMuc2V0QWN0aW9uKFwicHJldlwiKX19LHtrZXk6XCJmaW5pc2hcIix2YWx1ZTpmdW5jdGlvbigpe3RoaXMuaG9vayhcIm9uRmluaXNoXCIpfX0se2tleTpcImhpZGVGb290ZXJCdG5zXCIsdmFsdWU6ZnVuY3Rpb24oKXt0aGlzLmVsLmZpbmQodGhpcy5vcHRpb25zLmZvb3RlclNlbGVjdG9yKS5oaWRlKCl9fV0pJiZzKHQucHJvdG90eXBlLGUpLGkmJnModCxpKSxvfSgpLGk9YS5kZWZhdWx0LmZuLnN0ZXBzO2EuZGVmYXVsdC5mbi5zdGVwcz1mdW5jdGlvbih0KXtyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7YS5kZWZhdWx0LmRhdGEodGhpcyxcInBsdWdpbl9TdGVwc1wiKXx8YS5kZWZhdWx0LmRhdGEodGhpcyxcInBsdWdpbl9TdGVwc1wiLG5ldyBvKHRoaXMsdCkpfSl9LGEuZGVmYXVsdC5mbi5zdGVwcy52ZXJzaW9uPVwiMS4wLjJcIixhLmRlZmF1bHQuZm4uc3RlcHMuc2V0RGVmYXVsdHM9by5zZXREZWZhdWx0cyxhLmRlZmF1bHQuZm4uc3RlcHMubm9Db25mbGljdD1mdW5jdGlvbigpe3JldHVybiBhLmRlZmF1bHQuZm4uc3RlcHM9aSx0aGlzfX0pOyIsInZhciAkID0gcmVxdWlyZSgnLi4vaW50ZXJuYWxzL2V4cG9ydCcpO1xudmFyIGdsb2JhbCA9IHJlcXVpcmUoJy4uL2ludGVybmFscy9nbG9iYWwnKTtcblxuLy8gYGdsb2JhbFRoaXNgIG9iamVjdFxuLy8gaHR0cHM6Ly90YzM5LmVzL2VjbWEyNjIvI3NlYy1nbG9iYWx0aGlzXG4kKHsgZ2xvYmFsOiB0cnVlLCBmb3JjZWQ6IGdsb2JhbC5nbG9iYWxUaGlzICE9PSBnbG9iYWwgfSwge1xuICBnbG9iYWxUaGlzOiBnbG9iYWxcbn0pO1xuIiwiLy8gVE9ETzogUmVtb3ZlIGZyb20gYGNvcmUtanNANGBcbnJlcXVpcmUoJy4uL21vZHVsZXMvZXMuZ2xvYmFsLXRoaXMnKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=