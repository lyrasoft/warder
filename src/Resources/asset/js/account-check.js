"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */
$(function () {
  var UserAccountChecker =
  /*#__PURE__*/
  function (_mix$with) {
    _inherits(UserAccountChecker, _mix$with);

    function UserAccountChecker($element, options) {
      var _this;

      _classCallCheck(this, UserAccountChecker);

      _this = _possibleConstructorReturn(this, _getPrototypeOf(UserAccountChecker).call(this));
      _this.$element = $element;
      _this.options = $.extend(true, {}, _this.constructor.defaultOptions, options);

      var validate = _this.$element.attr('data-validate');

      _this.$element.attr('data-validate', validate + '|' + _this.options.validate_name);

      if (!_this.$element.attr('data-custom-error-message')) {
        _this.$element.attr('data-custom-error-message', Phoenix.__('warder.message.user.account.exists'));
      }

      _this.validation = _this.$element.closest('form').validation();

      _this.registerEvents();

      _this.registerHandler();

      return _this;
    }

    _createClass(UserAccountChecker, [{
      key: "check",
      value: function check(account) {
        var _this2 = this;

        this.trigger('start', {
          account: account
        });
        return Phoenix.Ajax.get(Phoenix.route('user_account_check'), {
          account: account
        }).done(function (res) {
          _this2.trigger('done', {
            account: account,
            exists: res.data.exists,
            res: res
          });

          return res;
        }).then(function (res) {
          return res.data.exists;
        }).fail(function (xhr) {
          console.error(xhr);

          _this2.trigger('error', {
            account: account,
            xhr: xhr
          });
        }).always(function () {
          _this2.trigger('end', {
            account: account
          });
        });
      }
    }, {
      key: "registerEvents",
      value: function registerEvents() {
        var _this3 = this;

        this.$element.unbind('change').on('change', function (e) {
          _this3.check(_this3.$element.val()).then(function (exists) {
            if (exists) {
              _this3.$element.attr('data-account-exists', true);

              var msg = _this3.$element.attr('data-custom-error-message');

              _this3.$element[0].setCustomValidity('');

              Phoenix.UI.showValidateResponse(_this3.validation, _this3.validation.STATE_FAIL, _this3.$element, msg);
            } else {
              _this3.$element[0].setCustomValidity('');

              _this3.$element.attr('data-account-exists', false);

              Phoenix.UI.showValidateResponse(_this3.validation, _this3.validation.STATE_SUCCESS, _this3.$element);
            }
          });
        });
      }
    }, {
      key: "registerHandler",
      value: function registerHandler() {
        this.validation.addValidator(this.options.validate_name, function (value, element) {
          return element.attr('data-account-exists') === 'false';
        });
      }
    }]);

    return UserAccountChecker;
  }(mix(
  /*#__PURE__*/
  function () {
    function _class() {
      _classCallCheck(this, _class);
    }

    return _class;
  }())["with"](PhoenixEventMixin));

  _defineProperty(UserAccountChecker, "defaultOptions", {
    validate_name: 'user-account'
  });

  Phoenix.plugin('userAccountCheck', UserAccountChecker);
});
//# sourceMappingURL=account-check.js.map
