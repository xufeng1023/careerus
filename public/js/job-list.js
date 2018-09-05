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
var __vue_template__ = __webpack_require__(253)
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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__timeline3_vue__ = __webpack_require__(269);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__timeline3_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__timeline3_vue__);
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
    components: { timeline: __WEBPACK_IMPORTED_MODULE_0__timeline3_vue___default.a },
    data: function data() {
        return {
            jobs: [],
            search: '',
            category: '',
            location: '',
            type: '',
            offset: 0,
            stopLoading: false,
            categories: [],
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
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    self.fetch(true);
                }, 60);
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

                    if (data.length < 9) this.stopLoading = true;
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
            this.fetch();
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

/***/ 253:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container" }, [
    _c("div", { staticClass: "row pt-3" }, [
      _c("div", { staticClass: "col-sm-3" }, [
        _c("div", { staticClass: "fixed-filter" }, [
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
          ),
          _vm._v(" "),
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
      _c("div", { staticClass: "col-sm-9" }, [
        _vm.jobs.length
          ? _c(
              "div",
              { staticClass: "row border-left pl-sm-3" },
              [
                _vm._l(_vm.jobs, function(job) {
                  return [
                    _c("timeline", {
                      key: job.identity,
                      attrs: { date: job.created_at }
                    }),
                    _vm._v(" "),
                    _c("div", { key: job.id, staticClass: "col-lg-6" }, [
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
                                "h5",
                                {
                                  staticClass: "card-title m-0",
                                  attrs: {
                                    title: job.chinese_title || job.title
                                  }
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
                                        "HR内推\r\n                                    "
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
                          _c("div", { staticClass: "text-muted" }, [
                            _vm._v(_vm._s(job.chinese_job_type))
                          ])
                        ]),
                        _vm._v(" "),
                        _c(
                          "div",
                          {
                            staticClass:
                              "card-body d-flex flex-column justify-content-between"
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
                                    [_vm._v(_vm._s(job.company.name))]
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "p",
                                {
                                  staticClass:
                                    "small d-flex justify-content-between"
                                },
                                [
                                  _c("span", [
                                    _vm._v("地点"),
                                    _c("br"),
                                    _c(
                                      "span",
                                      { staticClass: "text-secondary" },
                                      [
                                        _vm._v(
                                          _vm._s(
                                            job.location ||
                                              job.company.city +
                                                "," +
                                                job.company.state
                                          )
                                        )
                                      ]
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _c("span", [
                                    _vm._v("规模"),
                                    _c("br"),
                                    _c(
                                      "span",
                                      { staticClass: "text-secondary" },
                                      [_vm._v(_vm._s(job.company.scale))]
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _c("span", [
                                    _vm._v("2017 H1B"),
                                    _c("br"),
                                    _c(
                                      "span",
                                      { staticClass: "text-secondary" },
                                      [
                                        _vm._v(
                                          _vm._s(job.company.totalSponsor) +
                                            "人"
                                        )
                                      ]
                                    )
                                  ])
                                ]
                              ),
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
                            job.copied_from
                              ? _c("small", { staticClass: "text-muted" }, [
                                  _vm._v("信息来源:" + _vm._s(job.copied_from))
                                ])
                              : _vm._e(),
                            _vm._v(" "),
                            _c("div", [
                              _c(
                                "form",
                                {
                                  staticClass: "d-inline",
                                  class: job.is_favorited ? "filled" : "",
                                  attrs: {
                                    action: "/job/favorite/toggle/" + job.id,
                                    method: "post"
                                  },
                                  on: {
                                    submit: function($event) {
                                      $event.preventDefault()
                                      return _vm.toggleFavorite($event)
                                    }
                                  }
                                },
                                [
                                  _c("button", {
                                    staticClass:
                                      "btn btn-sm p-0 btn-light border-0 icon heart",
                                    attrs: { type: "submit" }
                                  })
                                ]
                              ),
                              _vm._v(" "),
                              _c("span", { staticClass: "favorites" }, [
                                _vm._v(_vm._s(job.favorites_count))
                              ])
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

/***/ 269:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(4)
/* script */
var __vue_script__ = __webpack_require__(270)
/* template */
var __vue_template__ = __webpack_require__(271)
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
Component.options.__file = "resources\\assets\\js\\components\\loops\\timeline3.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-189d563e", Component.options)
  } else {
    hotAPI.reload("data-v-189d563e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 270:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
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

/***/ 271:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.newDate
    ? _c(
        "div",
        {
          staticClass: "d-flex align-items-center timeline-date my-1",
          class: { "w-100": _vm.w100 }
        },
        [_c("div", { staticClass: "m-0 h5 date" }, [_vm._v(_vm._s(_vm.date))])]
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-189d563e", module.exports)
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