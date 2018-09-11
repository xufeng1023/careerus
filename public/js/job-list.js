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
/******/ 	return __webpack_require__(__webpack_require__.s = 249);
/******/ })
/************************************************************************/
/******/ ({

/***/ 156:
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ 174:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(175)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 175:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),

/***/ 249:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(250);


/***/ }),

/***/ 250:
/***/ (function(module, exports, __webpack_require__) {

Vue.component('job-list', __webpack_require__(251));

new Vue({
    el: '#job-list'
});

/***/ }),

/***/ 251:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(4)
/* script */
var __vue_script__ = __webpack_require__(252)
/* template */
var __vue_template__ = __webpack_require__(256)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\loops\\job-list.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-a0ebf59c", Component.options)
  } else {
    hotAPI.reload("data-v-a0ebf59c", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 252:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__timeline2_vue__ = __webpack_require__(264);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__timeline2_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__timeline2_vue__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

window.dates = [];

/* harmony default export */ __webpack_exports__["default"] = ({
    components: { timeline: __WEBPACK_IMPORTED_MODULE_0__timeline2_vue___default.a },
    data: function data() {
        return {
            jobs: [],
            search: '',
            category: '',
            location: '',
            type: '',
            offset: 0,
            stopLoading: false,
            shouldWaitLoading: false,
            categories: [],
            timeout: '',
            locations: [{ en: 'NY', zh: '纽约' }, { en: 'NJ', zh: '新泽西' }, { en: 'CHICAGO', zh: '芝加哥' }, { en: 'LOS ANGELES', zh: '洛杉矶' }, { en: 'MIAMI', zh: '迈阿密' }, { en: 'BOSTON', zh: '波士顿' }, { en: 'SAN JOSE', zh: '圣何塞' }, { en: 'WASHINGTON', zh: '华盛顿' }, { en: 'ATLANTA', zh: '亚特兰大' }, { en: 'SAN FRANCISCO', zh: '圣弗朗西斯科' }, { en: 'SAN DIEGO', zh: '圣地亚哥' }]
        };
    },
    created: function created() {
        var self = this;
        var timeout;
        this.fetch();
        //this.fetchLocations();
        this.fetchCategories();
        $(window).scroll(function () {
            if (self.shouldWaitLoading) return;

            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
                timeout = setTimeout(function () {
                    self.fetch(true);
                }, 100);
                self.shouldWaitLoading = true;
            }
        });
    },

    methods: {
        fetch: function fetch() {
            var push = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

            if (!push) {
                this.offset = 0;
                window.dates = [];
                this.stopLoading = false;
            }
            if (this.stopLoading) return;
            $.ajax('/job-list', {
                data: {
                    s: this.search,
                    c: this.category,
                    l: this.location,
                    o: this.offset,
                    t: this.type
                },
                context: this,
                success: function success(data) {
                    var _this = this;

                    if (!this.offset) this.jobs = [];

                    this.offset += data.length;

                    data.forEach(function (job) {
                        _this.jobs.push(job);
                    });

                    this.shouldWaitLoading = false;
                    if (data.length < 20) this.stopLoading = true;
                }
            });
        },
        fetchCategories: function fetchCategories() {
            if (this.categories = this.tryLocalGet('categories')) return;
            $.ajax('/catagory', {
                context: this,
                success: function success(data) {
                    this.tryLocalSet('categories', data);
                    this.categories = data;
                }
            });
        },
        fetchLocations: function fetchLocations() {
            if (this.locations = this.tryLocalGet('locations')) return;
            $.ajax('/locations', {
                context: this,
                success: function success(data) {
                    this.tryLocalSet('locations', data);
                    this.locations = data;
                }
            });
        },
        tryLocalGet: function tryLocalGet(item) {
            if (typeof Storage !== "undefined") {
                return JSON.parse(localStorage.getItem(item));
            }
        },
        tryLocalSet: function tryLocalSet(item, data) {
            if (typeof Storage !== "undefined") {
                return localStorage.setItem(item, JSON.stringify(data));
            }
        },
        toggleFavorite: function toggleFavorite(e) {
            $.post(e.target.getAttribute('action'), [], function () {
                e.target.classList.toggle('filled');
                var span = $(e.target).siblings('.favorites');
                if ($(e.target).hasClass('filled')) span.text(parseInt(span.text()) + 1);else span.text(parseInt(span.text()) - 1);
            }).fail(function () {
                toastr.error('请先登入');
            });
        },
        getJob: function getJob(job) {
            window.jobToApply = job;
        },
        goTo: function goTo(url) {
            window.open(url);
        }
    },
    watch: {
        category: function category() {
            this.fetch();
        },
        location: function location() {
            this.fetch();
        },
        search: function search() {
            var _this2 = this;

            clearTimeout(this.timeout);
            this.timeout = setTimeout(function () {
                _this2.fetch();
            }, 800);
        },
        type: function type() {
            this.fetch();
        }
    },
    computed: {
        computedJobs: function computedJobs() {
            return this.jobs;
        }
    }
});

/***/ }),

/***/ 256:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-sm-8 col-md-6" }, [
        _c("div", { staticClass: "input-group" }, [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.search,
                expression: "search"
              }
            ],
            staticClass: "form-control mb-3",
            attrs: { type: "text", placeholder: "关键字" },
            domProps: { value: _vm.search },
            on: {
              input: function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.search = $event.target.value
              }
            }
          }),
          _vm._v(" "),
          _c(
            "select",
            {
              staticClass: "form-control mb-3",
              on: {
                change: function($event) {
                  _vm.category = $event.target.value
                }
              }
            },
            [
              _c("option", { attrs: { value: "", selected: "" } }, [
                _vm._v("所有行业")
              ]),
              _vm._v(" "),
              _vm._l(_vm.categories, function(cat) {
                return _c(
                  "option",
                  { key: cat.id, domProps: { value: cat.id } },
                  [_vm._v(_vm._s(cat.name))]
                )
              })
            ],
            2
          ),
          _vm._v(" "),
          _c(
            "select",
            {
              staticClass: "form-control mb-3",
              on: {
                change: function($event) {
                  _vm.location = $event.target.value
                }
              }
            },
            [
              _c("option", { attrs: { value: "", selected: "" } }, [
                _vm._v("所有地区")
              ]),
              _vm._v(" "),
              _vm._l(_vm.locations, function(location) {
                return _c(
                  "option",
                  { key: location.en, domProps: { value: location.en } },
                  [_vm._v(_vm._s(location.zh))]
                )
              })
            ],
            2
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-sm-4 col-md-3 col-lg-3 ml-auto" }, [
        _c(
          "select",
          {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.type,
                expression: "type"
              }
            ],
            staticClass: "form-control mb-3",
            on: {
              change: function($event) {
                var $$selectedVal = Array.prototype.filter
                  .call($event.target.options, function(o) {
                    return o.selected
                  })
                  .map(function(o) {
                    var val = "_value" in o ? o._value : o.value
                    return val
                  })
                _vm.type = $event.target.multiple
                  ? $$selectedVal
                  : $$selectedVal[0]
              }
            }
          },
          [
            _c("option", { attrs: { value: "" } }, [_vm._v("所有工作类型")]),
            _vm._v(" "),
            _c("option", { attrs: { value: "Part-time" } }, [_vm._v("半职")]),
            _vm._v(" "),
            _c("option", { attrs: { value: "Full-time" } }, [_vm._v("全职")]),
            _vm._v(" "),
            _c("option", { attrs: { value: "Internship" } }, [_vm._v("实习")])
          ]
        )
      ])
    ]),
    _vm._v(" "),
    _vm.jobs.length
      ? _c(
          "div",
          { staticClass: "row" },
          [
            _vm._l(_vm.jobs, function(job) {
              return [
                _c("timeline", {
                  key: job.identity,
                  staticClass: "mt-3",
                  attrs: { date: job.created_at }
                }),
                _vm._v(" "),
                _c("div", { key: job.id, staticClass: "col-lg-4" }, [
                  _c("div", { staticClass: "card my-3" }, [
                    _c("div", { staticClass: "card-header" }, [
                      _c(
                        "div",
                        {
                          staticClass:
                            "justify-content-between d-flex align-items-center flex-sm-wrap"
                        },
                        [
                          _c(
                            "h6",
                            {
                              staticClass: "card-title m-0",
                              attrs: { title: job.chinese_title || job.title }
                            },
                            [_vm._v(_vm._s(job.showTitle))]
                          ),
                          _vm._v(" "),
                          job.is_applied
                            ? _c(
                                "button",
                                {
                                  staticClass: "btn btn-secondary btn-sm",
                                  attrs: { disabled: "" }
                                },
                                [
                                  _vm._v(
                                    "HR内推\r\n                            "
                                  ),
                                  _c(
                                    "div",
                                    {
                                      staticClass:
                                        "bg-white d-inline-flex justify-content-center align-items-center text-secondary",
                                      staticStyle: {
                                        width: "15px",
                                        height: "15px",
                                        "border-radius": "50%"
                                      }
                                    },
                                    [_vm._v("✔")]
                                  )
                                ]
                              )
                            : _c(
                                "button",
                                {
                                  staticClass: "btn btn-success btn-sm",
                                  attrs: {
                                    "data-toggle": "modal",
                                    "data-target": "#applyModal"
                                  },
                                  on: {
                                    click: function($event) {
                                      _vm.getJob(job)
                                    }
                                  }
                                },
                                [_vm._v("HR内推")]
                              )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass: "text-muted text-truncate",
                          attrs: { title: job.title }
                        },
                        [_vm._v(_vm._s(job.title))]
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "card-body d-flex flex-column justify-content-between fancy-load-more"
                      },
                      [
                        _c("p", { staticClass: "card-text" }, [
                          _vm._v(_vm._s(job.excerpt))
                        ]),
                        _vm._v(" "),
                        _c("div", [
                          _c(
                            "a",
                            {
                              attrs: {
                                href: job.company.website || "javascript:;",
                                target: "_blank",
                                rel: "nofollow"
                              }
                            },
                            [
                              _c(
                                "div",
                                {
                                  staticClass: "text-truncate text-info",
                                  attrs: { title: job.company.name }
                                },
                                [
                                  _vm._v(
                                    _vm._s(
                                      job.company.short_name || job.company.name
                                    )
                                  )
                                ]
                              )
                            ]
                          ),
                          _vm._v(" "),
                          _c("div", { staticClass: "text-secondary" }, [
                            _vm._v(
                              _vm._s(
                                job.location ||
                                  job.company.city + "," + job.company.state
                              )
                            )
                          ]),
                          _vm._v(" "),
                          _c(
                            "div",
                            _vm._l(job.tags, function(tag) {
                              return _c(
                                "span",
                                {
                                  key: tag.id,
                                  staticClass:
                                    "badge badge-pill badge-secondary"
                                },
                                [_vm._v(_vm._s(tag.name))]
                              )
                            })
                          )
                        ])
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "card-footer d-flex align-items-center justify-content-between"
                      },
                      [
                        _c("small", { staticClass: "text-muted" }, [
                          _vm._v(_vm._s(job.chinese_job_type))
                        ]),
                        _vm._v(" "),
                        _c("small", { staticClass: "text-muted" }, [
                          _vm._v(
                            "近三年H1B人数:" + _vm._s(job.company.totalSponsor)
                          )
                        ])
                      ]
                    )
                  ])
                ])
              ]
            })
          ],
          2
        )
      : _c("div", {}, [
          _vm._v("抱歉，暂时没有找到您要求的工作，请尝试其他搜索吧。")
        ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-a0ebf59c", module.exports)
  }
}

/***/ }),

/***/ 264:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(265)
}
var normalizeComponent = __webpack_require__(4)
/* script */
var __vue_script__ = __webpack_require__(267)
/* template */
var __vue_template__ = __webpack_require__(268)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\loops\\timeline2.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-188f3ebd", Component.options)
  } else {
    hotAPI.reload("data-v-188f3ebd", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 265:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(266);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(174)("c3c9f5cc", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-188f3ebd\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./timeline2.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-188f3ebd\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./timeline2.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 266:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(156)(false);
// imports


// module
exports.push([module.i, "\n.shelf-left {\r\n    height: 30px;\r\n    width: 2px;\r\n    background: #17a2b8;\r\n    -webkit-transform: skewX(-27deg) translateX(-22px);\r\n            transform: skewX(-27deg) translateX(-22px);\n}\n.shelf-right {\r\n    height: 29px;\r\n    width: 3px;\r\n    background: #17a2b8;\r\n    -webkit-transform: skewX(57deg) translate(4px, 1px);\r\n            transform: skewX(57deg) translate(4px, 1px);\n}\n.shelf-date {\r\n    -webkit-transform: skewY(-5deg) translateY(-3px);\r\n            transform: skewY(-5deg) translateY(-3px);\n}\n.rotate:hover {\r\n    cursor: pointer;\r\n    -webkit-animation-name: swing;\r\n            animation-name: swing;\r\n    -webkit-animation-duration: 500ms;\r\n            animation-duration: 500ms;\r\n    -webkit-animation-iteration-count: 1;\r\n            animation-iteration-count: 1;\r\n    -webkit-animation-timing-function: linear;\r\n            animation-timing-function: linear;\n}\n@-webkit-keyframes swing {\nfrom {-webkit-transform: rotateX(0turn);transform: rotateX(0turn);\n}\nto {-webkit-transform: rotateX(1turn);transform: rotateX(1turn);\n}\n}\n@keyframes swing {\nfrom {-webkit-transform: rotateX(0turn);transform: rotateX(0turn);\n}\nto {-webkit-transform: rotateX(1turn);transform: rotateX(1turn);\n}\n}\r\n", ""]);

// exports


/***/ }),

/***/ 267:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['date'],
    data: function data() {
        return {
            w100: false,
            newDate: false
        };
    },
    mounted: function mounted() {
        if (window.dates.indexOf(this.date) === -1) {
            window.dates.push(this.date);
            this.w100 = true;
            this.newDate = true;
        }
    }
});

/***/ }),

/***/ 268:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.newDate
    ? _c(
        "div",
        {
          staticClass: "mx-3 d-flex justify-content-center",
          class: { "w-100": _vm.w100 }
        },
        [
          _c(
            "div",
            {
              staticClass:
                "d-flex justify-content-center align-items-center flex-column shelf-date"
            },
            [
              _vm._m(0),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "px-3 bg-info text-white date-shadow rotate" },
                [_vm._v(_vm._s(_vm.date))]
              )
            ]
          )
        ]
      )
    : _vm._e()
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "d-flex" }, [
      _c("div", { staticClass: "shelf-left" }),
      _vm._v(" "),
      _c("div", { staticClass: "shelf-right" })
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-188f3ebd", module.exports)
  }
}

/***/ }),

/***/ 4:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ })

/******/ });