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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/components/action_list.js":
/*!************************************************!*\
  !*** ./resources/js/components/action_list.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.onload = function () {
  var app = new Vue({
    el: '#app'
  });
};

Vue.component('action_list', {
  template: '#action_list',
  data: function data() {
    return {
      pages: 0,
      current_page: 0,
      actions: [],
      consultants: [],
      crmid_selected: '',
      //0 update 1 insert
      //modal_status: 0,
      modal: {},
      crm_data: {},
      action_data: {},
      filter: []
    };
  },
  mounted: function mounted() {
    this.getActionsPages();
    this.getActionsByPage(1);
  },
  watch: {},
  computed: {
    page_start: function page_start() {
      return Math.floor((this.current_page - 1) / 10) * 10;
    }
  },
  methods: {
    test: function test() {
      alert('123');
      console.log('111');
    },
    getActionsPages: function getActionsPages() {
      var _this = this;

      axios({
        method: 'get',
        url: '/action/pages'
      }).then(function (response) {
        _this.pages = response.data;
      })["catch"](function (error) {
        console.log(error);
        alert(error);
      });
    },
    getActionsByPage: function getActionsByPage(page) {
      var _this2 = this;

      this.current_page = page;
      axios({
        method: 'get',
        url: '/action/page/' + page
      }).then(function (response) {
        //console.log(response.data);
        _this2.actions = response.data[0]; //console.log('actions', this.actions);
      })["catch"](function (error) {
        console.log(error);
        alert(error);
      });
    },
    searchActions: function searchActions() {
      var _this3 = this;

      console.log(this.selected); //return;

      var params = new URLSearchParams();
      params.append('ichk', this.$refs.csrf.value);
      params.append('conditions', this.filter);
      params.append('attending_date', this.selected);
      axios({
        method: 'post',
        url: '/chinaseminar/api/activity/searchActions',
        data: params,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function (response) {
        console.log(response.data); //this.consultants = response.data;

        _this3.actions = response.data;
        _this3.pages = 0;
        _this3.current_page = 0;
      });
    },
    updateModal: function updateModal(action) {
      var _this4 = this;

      var crmIds = JSON.parse(action.response_obj);
      var idList = new Array();

      for (var i in crmIds) {
        idList.push(crmIds[i]);
      }

      var data = {};
      data.idList = idList;
      axios({
        method: 'post',
        url: 'http://192.168.20.65:9694/api/v1.0/suite-crm/get-contact-list-by-id-list',
        data: data,
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(function (response) {
        //console.log(response.data.dataList);
        var dataList = response.data.dataList; //console.log(action.data);

        var action_data = JSON.parse(action.data); //console.dir(action_data);

        var crmData = new Array(); //console.dir(dataList);

        for (var i = 0; i < dataList.length; i++) {
          var row = {};

          for (var key in action_data) {
            row[key] = dataList[i]['attributes'][key];
          }

          row['id'] = dataList[i]['attributes']['id'];
          crmData.push(row);
        } //console.log('-----action-----');
        //console.dir(dataList);


        var data = JSON.parse(action.data);
        var display_data = {};

        for (var key in data) {
          //console.log(key'key...'+key+'...'+data[key]);
          //若是表單資料為空或未定義,則不顯示輸入框
          if (data[key] == null || data[key] == 'undefined') {} else {
            //console.log('in');
            display_data[key] = data[key];
          }
        } //console.dir(display_data);
        //console.dir(crmData);


        _this4.action_data = _.cloneDeep(display_data);
        _this4.crm_data = _.cloneDeep(crmData);
      });
    },
    updateDataToCrm: function updateDataToCrm(cust_data) {
      console.dir(cust_data); //console.log(this.crmid_selected);
      //var ele = document.getElementById('actionModal');
      //console.dir(ele);

      if (this.crmid_selected != 0 && typeof this.crmid_selected != 'undefined') {
        //ele.$emit('hide');
        var data = {};
        data = cust_data;
        data['id'] = this.crmid_selected;
        axios({
          method: 'post',
          url: '/action/updateCrmByEmployee',
          data: cust_data,
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(function (response) {
          console.dir(response);
        });
      } else {
        alert('請選取CRM ID');
        return;
      } //if()

    }
  }
});

/***/ }),

/***/ 2:
/*!******************************************************!*\
  !*** multi ./resources/js/components/action_list.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Fu\project\iHala\resources\js\components\action_list.js */"./resources/js/components/action_list.js");


/***/ })

/******/ });