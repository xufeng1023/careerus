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
/******/ 	return __webpack_require__(__webpack_require__.s = 225);
/******/ })
/************************************************************************/
/******/ ({

/***/ 225:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(226);


/***/ }),

/***/ 226:
/***/ (function(module, exports) {

Vue.component('pie-chart', {
    template: '<canvas id="pie-chart-canvas"></canvas>',
    props: ['dataSet'],
    data: function data() {
        return {
            bars: {
                labels: [],
                numbers: [],
                bgColor: []
            }
        };
    },

    mounted: function mounted() {
        var self = this;

        var other = 0;

        this.dataSet.split(',').forEach(function (val, index) {
            var matches = val.replace(/\r\n/, '').match(/([a-zA-Z ]*\-?[a-zA-Z ]+)\(([\d]+)\)/);

            if (!matches) return;

            if (index > 2) other += Number(matches[2]);else {
                self.bars.labels.push(matches[1]);
                self.bars.numbers.push(matches[2]);
            }
        });

        if (other > 0) {
            self.bars.labels.push('other');
            self.bars.numbers.push(other);
        }

        if (self.bars.numbers.length) {
            var _sum = self.bars.numbers.reduce(function (total, num) {
                return Number(total) + Number(num);
            });
        }

        self.bars.numbers.forEach(function (val) {
            self.bars.bgColor.push('rgba(54, 162, 235, ' + val / sum + ')');
        });

        var myChart = new Chart(document.getElementById("pie-chart-canvas").getContext('2d'), {
            type: 'pie',
            data: {
                labels: self.bars.labels,
                datasets: [{
                    data: self.bars.numbers,
                    backgroundColor: self.bars.bgColor,
                    borderWidth: 1
                }]
            }
        });
    }
});

new Vue({ el: '#jobPageRight' });

/***/ })

/******/ });