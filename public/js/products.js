(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/products"],{

/***/ "./resources/js/products.js":
/*!**********************************!*\
  !*** ./resources/js/products.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var dropzone__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! dropzone */ \"./node_modules/dropzone/dist/dropzone.js\");\n/* harmony import */ var dropzone__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(dropzone__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./helper */ \"./resources/js/helper.js\");\n/* harmony import */ var alertifyjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! alertifyjs */ \"./node_modules/alertifyjs/build/alertify.js\");\n/* harmony import */ var alertifyjs__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(alertifyjs__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _form_table__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./form_table */ \"./resources/js/form_table.js\");\n\n\n\n\nvar products = {\n  init: function init() {\n    console.log('products');\n    var params = {\n      trigger: document.getElementById('addNewFormRowForProductsTable'),\n      tbody: document.getElementById('products-rows'),\n      cols: {\n        name: 'text',\n        amount: 'number',\n        price: 'number',\n        description: 'text',\n        note: \"text\"\n      },\n      saveLink: true,\n      action: true\n    };\n    new _form_table__WEBPACK_IMPORTED_MODULE_3__[\"default\"](params);\n  }\n};\nproducts.init();//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvcHJvZHVjdHMuanM/YWZlMiJdLCJuYW1lcyI6WyJwcm9kdWN0cyIsImluaXQiLCJjb25zb2xlIiwibG9nIiwicGFyYW1zIiwidHJpZ2dlciIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJ0Ym9keSIsImNvbHMiLCJuYW1lIiwiYW1vdW50IiwicHJpY2UiLCJkZXNjcmlwdGlvbiIsIm5vdGUiLCJzYXZlTGluayIsImFjdGlvbiIsImZvcm1UYWJsZSJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUlBLFFBQVEsR0FBRztBQUViQyxNQUFJLEVBQUUsZ0JBQVU7QUFFVkMsV0FBTyxDQUFDQyxHQUFSLENBQVksVUFBWjtBQUVBLFFBQU1DLE1BQU0sR0FBRztBQUNYQyxhQUFPLEVBQUVDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QiwrQkFBeEIsQ0FERTtBQUVYQyxXQUFLLEVBQUVGLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixlQUF4QixDQUZJO0FBR1hFLFVBQUksRUFBRTtBQUFFQyxZQUFJLEVBQUUsTUFBUjtBQUFnQkMsY0FBTSxFQUFFLFFBQXhCO0FBQWtDQyxhQUFLLEVBQUUsUUFBekM7QUFBa0RDLG1CQUFXLEVBQUMsTUFBOUQ7QUFBcUVDLFlBQUksRUFBQztBQUExRSxPQUhLO0FBSVhDLGNBQVEsRUFBRSxJQUpDO0FBS1hDLFlBQU0sRUFBQztBQUxJLEtBQWY7QUFRQSxRQUFJQyxtREFBSixDQUFjYixNQUFkO0FBRUg7QUFoQlUsQ0FBZjtBQXVCQUosUUFBUSxDQUFDQyxJQUFUIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3Byb2R1Y3RzLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgb3B0aW9ucyB9IGZyb20gJ2Ryb3B6b25lJztcclxuaW1wb3J0IGhlbHBlciBmcm9tICcuL2hlbHBlcic7XHJcbmltcG9ydCBhbGVydGlmeSBmcm9tICdhbGVydGlmeWpzJztcclxuaW1wb3J0IGZvcm1UYWJsZSBmcm9tICcuL2Zvcm1fdGFibGUnO1xyXG5sZXQgcHJvZHVjdHMgPSB7XHJcblxyXG4gIGluaXQ6IGZ1bmN0aW9uKCl7XHJcblxyXG4gICAgICAgIGNvbnNvbGUubG9nKCdwcm9kdWN0cycpO1xyXG5cclxuICAgICAgICBjb25zdCBwYXJhbXMgPSB7XHJcbiAgICAgICAgICAgIHRyaWdnZXI6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhZGROZXdGb3JtUm93Rm9yUHJvZHVjdHNUYWJsZScpLFxyXG4gICAgICAgICAgICB0Ym9keTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Byb2R1Y3RzLXJvd3MnKSxcclxuICAgICAgICAgICAgY29sczogeyBuYW1lOiAndGV4dCcsIGFtb3VudDogJ251bWJlcicsIHByaWNlOiAnbnVtYmVyJyxkZXNjcmlwdGlvbjondGV4dCcsbm90ZTpcInRleHRcIiB9LFxyXG4gICAgICAgICAgICBzYXZlTGluazogdHJ1ZSxcclxuICAgICAgICAgICAgYWN0aW9uOnRydWVcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIG5ldyBmb3JtVGFibGUocGFyYW1zKTtcclxuXHJcbiAgICB9LFxyXG5cclxuXHJcblxyXG5cclxufVxyXG5cclxucHJvZHVjdHMuaW5pdCgpO1xyXG5cclxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/products.js\n");

/***/ }),

/***/ 6:
/*!****************************************!*\
  !*** multi ./resources/js/products.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\GithubRepository\pryapus\resources\js\products.js */"./resources/js/products.js");


/***/ })

},[[6,"/js/manifest","/js/vendor"]]]);