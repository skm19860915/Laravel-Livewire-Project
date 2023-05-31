(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/emails"],{

/***/ "./resources/js/emails.js":
/*!********************************!*\
  !*** ./resources/js/emails.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper */ \"./resources/js/helper.js\");\n/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ \"./node_modules/axios/index.js\");\n/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);\n\n\nvar emailJS = {\n  init: function init() {\n    $('#emailsDatatable').DataTable();\n    console.log('email-journey');\n    this.updateStatus();\n  },\n  updateStatus: function updateStatus() {\n    $(\".btn-status\").click(function (e) {\n      var appUrl = _helper__WEBPACK_IMPORTED_MODULE_0__[\"default\"].getSiteUrl();\n      var token = $(\"[name='token']\").attr('content');\n      var id = e.target.id;\n      var settings = {\n        method: 'POST',\n        url: appUrl + '/settings/emails',\n        data: {\n          token: token,\n          id: id\n        },\n        headers: {\n          Accept: 'application/json',\n          'Content-Type': 'application/json',\n          'X-CSRF-TOKEN': document.querySelector('meta[name=\"token\"]').getAttribute('content')\n        },\n        xsrfHeaderName: 'X-XSRF-TOKEN'\n      };\n      axios__WEBPACK_IMPORTED_MODULE_1___default()(settings).then(function (resp) {\n        if (resp.status === 200) {\n          if (resp.data) {\n            console.log(resp.data);\n\n            if (resp.data == -1) {\n              $('#email_action').removeAttr(\"style\");\n            } else {\n              location.reload();\n            }\n          }\n        }\n      })[\"catch\"](function (err) {\n        console.error(err);\n      });\n    });\n  }\n};\nemailJS.init();//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZW1haWxzLmpzPzgzODYiXSwibmFtZXMiOlsiZW1haWxKUyIsImluaXQiLCIkIiwiRGF0YVRhYmxlIiwiY29uc29sZSIsImxvZyIsInVwZGF0ZVN0YXR1cyIsImNsaWNrIiwiZSIsImFwcFVybCIsImhlbHBlciIsImdldFNpdGVVcmwiLCJ0b2tlbiIsImF0dHIiLCJpZCIsInRhcmdldCIsInNldHRpbmdzIiwibWV0aG9kIiwidXJsIiwiZGF0YSIsImhlYWRlcnMiLCJBY2NlcHQiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3IiLCJnZXRBdHRyaWJ1dGUiLCJ4c3JmSGVhZGVyTmFtZSIsImF4aW9zIiwidGhlbiIsInJlc3AiLCJzdGF0dXMiLCJyZW1vdmVBdHRyIiwibG9jYXRpb24iLCJyZWxvYWQiLCJlcnIiLCJlcnJvciJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBRUEsSUFBSUEsT0FBTyxHQUFHO0FBRVpDLE1BQUksRUFBRSxnQkFBVTtBQUNWQyxLQUFDLENBQUMsa0JBQUQsQ0FBRCxDQUFzQkMsU0FBdEI7QUFDQUMsV0FBTyxDQUFDQyxHQUFSLENBQVksZUFBWjtBQUNBLFNBQUtDLFlBQUw7QUFDSCxHQU5TO0FBT1ZBLGNBQVksRUFBRSx3QkFBVTtBQUNwQkosS0FBQyxDQUFDLGFBQUQsQ0FBRCxDQUFpQkssS0FBakIsQ0FBdUIsVUFBQUMsQ0FBQyxFQUFFO0FBQ3RCLFVBQU1DLE1BQU0sR0FBR0MsK0NBQU0sQ0FBQ0MsVUFBUCxFQUFmO0FBQ0EsVUFBSUMsS0FBSyxHQUFHVixDQUFDLENBQUMsZ0JBQUQsQ0FBRCxDQUFvQlcsSUFBcEIsQ0FBeUIsU0FBekIsQ0FBWjtBQUNBLFVBQUlDLEVBQUUsR0FBR04sQ0FBQyxDQUFDTyxNQUFGLENBQVNELEVBQWxCO0FBRUEsVUFBSUUsUUFBUSxHQUFHO0FBQ1hDLGNBQU0sRUFBRSxNQURHO0FBRVhDLFdBQUcsRUFBRVQsTUFBTSxHQUFHLGtCQUZIO0FBR1hVLFlBQUksRUFBRTtBQUFDUCxlQUFLLEVBQUxBLEtBQUQ7QUFBUUUsWUFBRSxFQUFGQTtBQUFSLFNBSEs7QUFJWE0sZUFBTyxFQUFFO0FBQ0xDLGdCQUFNLEVBQUUsa0JBREg7QUFFTCwwQkFBZ0Isa0JBRlg7QUFHTCwwQkFBZ0JDLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QixvQkFBdkIsRUFBNkNDLFlBQTdDLENBQTBELFNBQTFEO0FBSFgsU0FKRTtBQVNYQyxzQkFBYyxFQUFFO0FBVEwsT0FBZjtBQVlBQyxrREFBSyxDQUFDVixRQUFELENBQUwsQ0FBZ0JXLElBQWhCLENBQXFCLFVBQUFDLElBQUksRUFBSTtBQUN6QixZQUFHQSxJQUFJLENBQUNDLE1BQUwsS0FBZ0IsR0FBbkIsRUFBd0I7QUFDckIsY0FBR0QsSUFBSSxDQUFDVCxJQUFSLEVBQWE7QUFDWmYsbUJBQU8sQ0FBQ0MsR0FBUixDQUFZdUIsSUFBSSxDQUFDVCxJQUFqQjs7QUFDQSxnQkFBR1MsSUFBSSxDQUFDVCxJQUFMLElBQWEsQ0FBQyxDQUFqQixFQUFtQjtBQUNmakIsZUFBQyxDQUFDLGVBQUQsQ0FBRCxDQUFtQjRCLFVBQW5CLENBQThCLE9BQTlCO0FBQ0gsYUFGRCxNQUdJO0FBQ0FDLHNCQUFRLENBQUNDLE1BQVQ7QUFDSDtBQUNEO0FBQ0g7QUFDSixPQVpELFdBWVMsVUFBQUMsR0FBRyxFQUFJO0FBQ1o3QixlQUFPLENBQUM4QixLQUFSLENBQWNELEdBQWQ7QUFDSCxPQWREO0FBZUgsS0FoQ0Q7QUFpQ0g7QUF6Q1MsQ0FBZDtBQTRDQWpDLE9BQU8sQ0FBQ0MsSUFBUiIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9lbWFpbHMuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgaGVscGVyIGZyb20gJy4vaGVscGVyJztcbmltcG9ydCBheGlvcyBmcm9tICdheGlvcyc7XG5cbmxldCBlbWFpbEpTID0ge1xuXG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgICAgICQoJyNlbWFpbHNEYXRhdGFibGUnKS5EYXRhVGFibGUoKTtcbiAgICAgICAgY29uc29sZS5sb2coJ2VtYWlsLWpvdXJuZXknKVxuICAgICAgICB0aGlzLnVwZGF0ZVN0YXR1cygpO1xuICAgIH0sXG4gICAgdXBkYXRlU3RhdHVzOiBmdW5jdGlvbigpe1xuICAgICAgICAkKFwiLmJ0bi1zdGF0dXNcIikuY2xpY2soZT0+e1xuICAgICAgICAgICAgY29uc3QgYXBwVXJsID0gaGVscGVyLmdldFNpdGVVcmwoKTtcbiAgICAgICAgICAgIGxldCB0b2tlbiA9ICQoXCJbbmFtZT0ndG9rZW4nXVwiKS5hdHRyKCdjb250ZW50Jyk7XG4gICAgICAgICAgICB2YXIgaWQgPSBlLnRhcmdldC5pZFxuXG4gICAgICAgICAgICBsZXQgc2V0dGluZ3MgPSB7XG4gICAgICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVcmwgKyAnL3NldHRpbmdzL2VtYWlscycsXG4gICAgICAgICAgICAgICAgZGF0YToge3Rva2VuLCBpZH0sXG4gICAgICAgICAgICAgICAgaGVhZGVyczoge1xuICAgICAgICAgICAgICAgICAgICBBY2NlcHQ6ICdhcHBsaWNhdGlvbi9qc29uJyxcbiAgICAgICAgICAgICAgICAgICAgJ0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJyxcbiAgICAgICAgICAgICAgICAgICAgJ1gtQ1NSRi1UT0tFTic6IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ21ldGFbbmFtZT1cInRva2VuXCJdJykuZ2V0QXR0cmlidXRlKCdjb250ZW50JylcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHhzcmZIZWFkZXJOYW1lOiAnWC1YU1JGLVRPS0VOJyxcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIGF4aW9zKHNldHRpbmdzKS50aGVuKHJlc3AgPT4ge1xuICAgICAgICAgICAgICAgIGlmKHJlc3Auc3RhdHVzID09PSAyMDApIHtcbiAgICAgICAgICAgICAgICAgICBpZihyZXNwLmRhdGEpe1xuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhyZXNwLmRhdGEpXG4gICAgICAgICAgICAgICAgICAgIGlmKHJlc3AuZGF0YSA9PSAtMSl7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcjZW1haWxfYWN0aW9uJykucmVtb3ZlQXR0cihcInN0eWxlXCIpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KS5jYXRjaChlcnIgPT4ge1xuICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoZXJyKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9XG59XG5cbmVtYWlsSlMuaW5pdCgpO1xuXG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/emails.js\n");

/***/ }),

/***/ 3:
/*!**************************************!*\
  !*** multi ./resources/js/emails.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\GithubRepository\pryapus\resources\js\emails.js */"./resources/js/emails.js");


/***/ })

},[[3,"/js/manifest","/js/vendor"]]]);