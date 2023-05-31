(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/communication"],{

/***/ "./resources/js/communication.js":
/*!***************************************!*\
  !*** ./resources/js/communication.js ***!
  \***************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper */ \"./resources/js/helper.js\");\n/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ \"./node_modules/axios/index.js\");\n/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);\n// require('./app');\n\n\nvar communicationJS = {\n  init: function init() {\n    console.log(\"communication\");\n    $('#emailCommunicationsTable').DataTable();\n    this.updateStatus();\n  },\n  updateStatus: function updateStatus() {\n    $(\".btn-status\").click(function (e) {\n      var appUrl = _helper__WEBPACK_IMPORTED_MODULE_0__[\"default\"].getSiteUrl();\n      var token = $(\"[name='token']\").attr('content');\n      var id = e.target.id;\n      var settings = {\n        method: 'POST',\n        url: appUrl + '/communication/patient/delete',\n        data: {\n          token: token,\n          id: id\n        },\n        headers: {\n          Accept: 'application/json',\n          'Content-Type': 'application/json',\n          'X-CSRF-TOKEN': document.querySelector('meta[name=\"token\"]').getAttribute('content')\n        },\n        xsrfHeaderName: 'X-XSRF-TOKEN'\n      };\n      axios__WEBPACK_IMPORTED_MODULE_1___default()(settings).then(function (resp) {\n        if (resp.status === 200) {\n          if (resp.data) {\n            location.reload();\n          }\n        }\n      })[\"catch\"](function (err) {\n        console.error(err);\n      });\n    });\n  }\n};\ncommunicationJS.init();//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY29tbXVuaWNhdGlvbi5qcz9jMTY3Il0sIm5hbWVzIjpbImNvbW11bmljYXRpb25KUyIsImluaXQiLCJjb25zb2xlIiwibG9nIiwiJCIsIkRhdGFUYWJsZSIsInVwZGF0ZVN0YXR1cyIsImNsaWNrIiwiZSIsImFwcFVybCIsImhlbHBlciIsImdldFNpdGVVcmwiLCJ0b2tlbiIsImF0dHIiLCJpZCIsInRhcmdldCIsInNldHRpbmdzIiwibWV0aG9kIiwidXJsIiwiZGF0YSIsImhlYWRlcnMiLCJBY2NlcHQiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3IiLCJnZXRBdHRyaWJ1dGUiLCJ4c3JmSGVhZGVyTmFtZSIsImF4aW9zIiwidGhlbiIsInJlc3AiLCJzdGF0dXMiLCJsb2NhdGlvbiIsInJlbG9hZCIsImVyciIsImVycm9yIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUVBLElBQUlBLGVBQWUsR0FBRztBQUVwQkMsTUFBSSxFQUFFLGdCQUFVO0FBRVZDLFdBQU8sQ0FBQ0MsR0FBUixDQUFZLGVBQVo7QUFDQUMsS0FBQyxDQUFDLDJCQUFELENBQUQsQ0FBK0JDLFNBQS9CO0FBQ0EsU0FBS0MsWUFBTDtBQUNILEdBUGlCO0FBU2xCQSxjQUFZLEVBQUUsd0JBQVU7QUFDcEJGLEtBQUMsQ0FBQyxhQUFELENBQUQsQ0FBaUJHLEtBQWpCLENBQXVCLFVBQUFDLENBQUMsRUFBRTtBQUN0QixVQUFNQyxNQUFNLEdBQUdDLCtDQUFNLENBQUNDLFVBQVAsRUFBZjtBQUNBLFVBQUlDLEtBQUssR0FBR1IsQ0FBQyxDQUFDLGdCQUFELENBQUQsQ0FBb0JTLElBQXBCLENBQXlCLFNBQXpCLENBQVo7QUFDQSxVQUFJQyxFQUFFLEdBQUdOLENBQUMsQ0FBQ08sTUFBRixDQUFTRCxFQUFsQjtBQUVBLFVBQUlFLFFBQVEsR0FBRztBQUNYQyxjQUFNLEVBQUUsTUFERztBQUVYQyxXQUFHLEVBQUVULE1BQU0sR0FBRywrQkFGSDtBQUdYVSxZQUFJLEVBQUU7QUFBQ1AsZUFBSyxFQUFMQSxLQUFEO0FBQVFFLFlBQUUsRUFBRkE7QUFBUixTQUhLO0FBSVhNLGVBQU8sRUFBRTtBQUNMQyxnQkFBTSxFQUFFLGtCQURIO0FBRUwsMEJBQWdCLGtCQUZYO0FBR0wsMEJBQWdCQyxRQUFRLENBQUNDLGFBQVQsQ0FBdUIsb0JBQXZCLEVBQTZDQyxZQUE3QyxDQUEwRCxTQUExRDtBQUhYLFNBSkU7QUFTWEMsc0JBQWMsRUFBRTtBQVRMLE9BQWY7QUFZQUMsa0RBQUssQ0FBQ1YsUUFBRCxDQUFMLENBQWdCVyxJQUFoQixDQUFxQixVQUFBQyxJQUFJLEVBQUk7QUFDekIsWUFBR0EsSUFBSSxDQUFDQyxNQUFMLEtBQWdCLEdBQW5CLEVBQXdCO0FBQ3JCLGNBQUdELElBQUksQ0FBQ1QsSUFBUixFQUFhO0FBQ1pXLG9CQUFRLENBQUNDLE1BQVQ7QUFDQTtBQUNIO0FBQ0osT0FORCxXQU1TLFVBQUFDLEdBQUcsRUFBSTtBQUNaOUIsZUFBTyxDQUFDK0IsS0FBUixDQUFjRCxHQUFkO0FBQ0gsT0FSRDtBQVNILEtBMUJEO0FBMkJIO0FBckNpQixDQUF0QjtBQXdDQWhDLGVBQWUsQ0FBQ0MsSUFBaEIiLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY29tbXVuaWNhdGlvbi5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vIHJlcXVpcmUoJy4vYXBwJyk7XG5pbXBvcnQgaGVscGVyIGZyb20gJy4vaGVscGVyJztcbmltcG9ydCBheGlvcyBmcm9tICdheGlvcyc7XG5cbmxldCBjb21tdW5pY2F0aW9uSlMgPSB7XG5cbiAgaW5pdDogZnVuY3Rpb24oKXtcblxuICAgICAgICBjb25zb2xlLmxvZyhcImNvbW11bmljYXRpb25cIik7XG4gICAgICAgICQoJyNlbWFpbENvbW11bmljYXRpb25zVGFibGUnKS5EYXRhVGFibGUoKTtcbiAgICAgICAgdGhpcy51cGRhdGVTdGF0dXMoKTtcbiAgICB9LFxuXG4gICAgdXBkYXRlU3RhdHVzOiBmdW5jdGlvbigpe1xuICAgICAgICAkKFwiLmJ0bi1zdGF0dXNcIikuY2xpY2soZT0+e1xuICAgICAgICAgICAgY29uc3QgYXBwVXJsID0gaGVscGVyLmdldFNpdGVVcmwoKTtcbiAgICAgICAgICAgIGxldCB0b2tlbiA9ICQoXCJbbmFtZT0ndG9rZW4nXVwiKS5hdHRyKCdjb250ZW50Jyk7XG4gICAgICAgICAgICB2YXIgaWQgPSBlLnRhcmdldC5pZFxuXG4gICAgICAgICAgICBsZXQgc2V0dGluZ3MgPSB7XG4gICAgICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXG4gICAgICAgICAgICAgICAgdXJsOiBhcHBVcmwgKyAnL2NvbW11bmljYXRpb24vcGF0aWVudC9kZWxldGUnLFxuICAgICAgICAgICAgICAgIGRhdGE6IHt0b2tlbiwgaWR9LFxuICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcbiAgICAgICAgICAgICAgICAgICAgQWNjZXB0OiAnYXBwbGljYXRpb24vanNvbicsXG4gICAgICAgICAgICAgICAgICAgICdDb250ZW50LVR5cGUnOiAnYXBwbGljYXRpb24vanNvbicsXG4gICAgICAgICAgICAgICAgICAgICdYLUNTUkYtVE9LRU4nOiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdtZXRhW25hbWU9XCJ0b2tlblwiXScpLmdldEF0dHJpYnV0ZSgnY29udGVudCcpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB4c3JmSGVhZGVyTmFtZTogJ1gtWFNSRi1UT0tFTicsXG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICBheGlvcyhzZXR0aW5ncykudGhlbihyZXNwID0+IHtcbiAgICAgICAgICAgICAgICBpZihyZXNwLnN0YXR1cyA9PT0gMjAwKSB7XG4gICAgICAgICAgICAgICAgICAgaWYocmVzcC5kYXRhKXtcbiAgICAgICAgICAgICAgICAgICAgbG9jYXRpb24ucmVsb2FkKCk7XG4gICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pLmNhdGNoKGVyciA9PiB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5lcnJvcihlcnIpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH1cbn1cblxuY29tbXVuaWNhdGlvbkpTLmluaXQoKTtcblxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/communication.js\n");

/***/ }),

/***/ 15:
/*!*********************************************!*\
  !*** multi ./resources/js/communication.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\GithubRepository\pryapus\resources\js\communication.js */"./resources/js/communication.js");


/***/ })

},[[15,"/js/manifest","/js/vendor"]]]);