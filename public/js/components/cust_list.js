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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/components/cust_list.js":
/*!**********************************************!*\
  !*** ./resources/js/components/cust_list.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.onload = function () {
  var app = new Vue({
    el: '#app'
  });
};

Vue.component('cust_list', {
  template: '#cust_list',
  data: function data() {
    return {
      pages: 0,
      current_page: 0,
      users: [],
      consultants: [],
      //0 update 1 insert
      modal_status: 0,
      modal: {},
      filter: []
    };
  },
  mounted: function mounted() {
    //this.selected;
    //alert('1234');
    //console.log('999');
    this.getUsersPages();
    this.getUsersByPage(1); //this.getReplyedList();
    //this.getConsultants();		
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
    getUsersPages: function getUsersPages() {
      var _this = this;

      axios({
        method: 'get',
        url: '/customers/pages'
      }).then(function (response) {
        _this.pages = response.data;
      })["catch"](function (error) {
        console.log(error);
        alert(error);
      });
    },
    getUsersByPage: function getUsersByPage(page) {
      var _this2 = this;

      this.current_page = page; //axios.get('/customers/page/' + page).then(function (response) {
      //    console.log(response.data);
      //    this.users = response.data[0]; //console.log('users', this.users);
      //})

      axios({
        method: 'get',
        url: '/customers/page/' + page
      }).then(function (response) {
        //console.log(response.data);
        _this2.users = response.data[0]; //console.log('users', this.users);
      })["catch"](function (error) {
        console.log(error);
        alert(error);
      });
    },
    getConsultants: function getConsultants() {
      var _this3 = this;

      var params = new URLSearchParams();
      params.append('ichk', this.$refs.csrf.value);
      axios({
        method: 'post',
        url: '/chinaseminar/api/activity/getConsultants',
        data: params,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function (response) {
        console.log(response.data);
        _this3.consultants = response.data;
      });
    },
    searchUsers: function searchUsers() {
      var _this4 = this;

      console.log(this.selected); //return;

      var params = new URLSearchParams();
      params.append('ichk', this.$refs.csrf.value);
      params.append('conditions', this.filter);
      params.append('attending_date', this.selected);
      axios({
        method: 'post',
        url: '/chinaseminar/api/activity/searchUsers',
        data: params,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function (response) {
        console.log(response.data); //this.consultants = response.data;

        _this4.users = response.data;
        _this4.pages = 0;
        _this4.current_page = 0;
      });
    },
    downloadUsersList: function downloadUsersList() {
      //location = '/chinaseminar/api/activity/exportUsers';
      location = '/chinaseminar/api/activity/exportUsers/' + this.selected;
    },
    updateUserModal: function updateUserModal(user) {
      //console.log(user);
      this.modal = _.cloneDeep(user);
      this.modal_status = 0;
    },
    createUserModal: function createUserModal() {
      this.modal = {};
      this.modal.attending_date = this.selected;
      this.modal_status = 1;
    },
    updateUserData: function updateUserData(data) {
      var _this5 = this;

      var params = new URLSearchParams();
      console.log('edit..');
      console.log(data); //params.append('ichk', this.$refs.csrf.value);

      params.append('s_id', data.s_id);
      params.append('comm_id', data.comm_id);
      params.append('obj_id', data.obj_id);
      params.append('c_id', data.c_id);
      params.append('cNumber', data.cNumber);
      params.append('name', data.name);
      params.append('origin_name', data.origin_name);
      params.append('sex', data.sex);
      params.append('birth', data.birth);
      params.append('phone', data.phone);
      params.append('email', data.email);
      params.append('about', data.about);
      params.append('treatment', data.treatment); //console.log(data.consultant);

      axios({
        method: 'post',
        url: '/customers/edit/' + data.id,
        data: params,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function (response) {
        console.log(response.data);

        _this5.getUsersByPage(_this5.current_page);
      });
    },
    insertUserData: function insertUserData(data) {
      var _this6 = this;

      var params = new URLSearchParams();
      params.append('ichk', this.$refs.csrf.value);
      params.append('s_id', data.s_id);
      params.append('comm_id', data.comm_id);
      params.append('obj_id', data.obj_id);
      params.append('c_id', data.c_id);
      params.append('cNumber', data.cNumber);
      params.append('name', data.name);
      params.append('origin_name', data.origin_name);
      params.append('sex', data.sex);
      params.append('birth', data.birth);
      params.append('phone', data.phone);
      params.append('email', data.email);
      params.append('about', data.about);
      params.append('treatment', data.treatment);
      axios({
        method: 'post',
        url: '/chinaseminar/api/activity/insert',
        data: params,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function (response) {
        //console.log(response.data);
        _this6.getUsersByPage(_this6.current_page);
      });
    },
    deleteUserData: function deleteUserData(data) {
      var _this7 = this;

      if (confirm("確定刪除?")) {
        var params = new URLSearchParams();
        params.append('ichk', this.$refs.csrf.value);
        axios({
          method: 'post',
          url: '/chinaseminar/api/activity/delete/' + data.id,
          data: params,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).then(function (response) {
          //console.log(response.data);
          _this7.getUsersByPage(_this7.current_page);

          _this7.getReplyedList();
        });
      }
    }
  }
});

/***/ }),

/***/ 1:
/*!****************************************************!*\
  !*** multi ./resources/js/components/cust_list.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Fu\project\iHala\code\resources\js\components\cust_list.js */"./resources/js/components/cust_list.js");


/***/ })

/******/ });