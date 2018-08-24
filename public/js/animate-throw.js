/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 236);
/******/ })
/************************************************************************/
/******/ ({

/***/ 236:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(237);


/***/ }),

/***/ 237:
/***/ (function(module, exports) {

{
  "use strict";

  var pipe = function pipe() {
    for (var _len = arguments.length, functions = Array(_len), _key = 0; _key < _len; _key++) {
      functions[_key] = arguments[_key];
    }

    return function (arg) {
      return functions.reduce(function (a, b) {
        return b(a);
      }, arg);
    };
  };

  var define = function define(object, property, value) {
    return Object.defineProperty(object, property, {
      value: value,
      writable: true,
      configurable: true,
      enumerable: true
    });
  };

  var first = function first(list) {
    return list[0];
  };

  var last = function last(list) {
    return list[list.length - 1];
  };

  var getRandomInt = function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
  };

  var interval = function interval(callback, delay) {
    var tick = function tick(now) {
      if (now - start >= delay) {
        start = now;
        callback();
      }
      requestAnimationFrame(tick);
    };
    var start = performance.now();
    requestAnimationFrame(tick);
  };

  var easeOutQuart = function easeOutQuart(t, b, c, d) {
    return -c * ((t = t / d - 1) * Math.pow(t, 3) - 1) + b;
  };

  jQuery(function ($) {
    $.get('/tags', function (data) {
      var programmingLanguages = document.getElementById("programming-languages");

      var languages = data;

      var getRandomLanguage = function getRandomLanguage() {
        return languages[getRandomInt(0, languages.length)];
      };

      var getRandomY = function getRandomY(x, min, max) {
        if (Math.abs(x) > min) return getRandomInt(-max, max);
        var minY = Math.sqrt(Math.pow(min, 2) - Math.pow(x, 2));
        var randomSign = Math.round(Math.random()) * 2 - 1;
        return randomSign * getRandomInt(minY, max);
      };

      var createIcon = function createIcon(language) {
        var icon = document.createElement("div");
        icon.innerText = language;
        icon.classList.add('throw');
        programmingLanguages.appendChild(icon);
        icon.setAttribute('aria-hidden', true);
        return icon;
      };

      var animateIcon = function animateIcon(icon) {
        var time = { total: 12000 };
        var maxDistance = 120;
        var maxRotation = 800;
        var transform = {};
        define(transform, "translateX", getRandomInt(-maxDistance, maxDistance));
        define(transform, "translateY", getRandomY(transform.translateX, 60, maxDistance));
        //define(transform, "rotate", getRandomInt(-maxRotation, maxRotation));

        var tick = function tick(now) {
          if (time.start == null) define(time, "start", now);
          define(time, "elapsed", now - time.start);
          var progress = easeOutQuart(time.elapsed, 0, 1, time.total);

          icon.style.opacity = Math.abs(1 - progress);
          icon.style.transform = Object.keys(transform).map(function (key) {
            var value = transform[key] * progress;
            // const unit = /rotate/.test(key) ? "deg" : "px";
            var unit = "px";
            //console.log(`${key}(${value}${unit})`)
            return key + "(" + value + unit + ")";
          }).join(" ");

          time.elapsed < time.total ? requestAnimationFrame(tick) : programmingLanguages.removeChild(icon);
        };

        requestAnimationFrame(tick);
      };

      interval(pipe(getRandomLanguage, createIcon, animateIcon), 500);
    });
  });
}

/***/ })

/******/ });