/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

$(() => {
  class UserAccountChecker extends mix(class {}).with(PhoenixEventMixin) {
    static defaultOptions = {
      validate_name: 'user-account'
    };

    constructor($element, options) {
      super();

      this.$element = $element;
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);

      const validate = this.$element.attr('data-validate');

      this.$element.attr('data-validate', validate + '|' + this.options.validate_name);

      if (!this.$element.attr('data-custom-error-message')) {
        this.$element.attr(
          'data-custom-error-message',
          Phoenix.__('warder.message.user.account.exists')
        );
      }

      this.validation = this.$element.closest('form').validation();

      this.registerEvents();
      this.registerHandler();
    }

    check(account) {
      this.trigger('start', { account });

      return Phoenix.Ajax.get(Phoenix.route('user_account_check'), { account })
        .done((res) => {
          this.trigger('done', { account, exists: res.data.exists, res });

          return res;
        })
        .then((res) => {
          return res.data.exists;
        })
        .fail((xhr) => {
          console.error(xhr);
          this.trigger('error', { account, xhr });
        })
        .always(() => {
          this.trigger('end', { account });
        });
    }

    registerEvents() {
      this.$element.unbind('change').on('change', (e) => {
        this.check(this.$element.val())
          .then((exists) => {
            if (exists) {
              this.$element.attr('data-account-exists', true);
              const msg = this.$element.attr('data-custom-error-message');

              this.$element[0].setCustomValidity('');
              Phoenix.UI.showValidateResponse(
                this.validation,
                this.validation.STATE_FAIL,
                this.$element,
                msg
              );
            } else {
              this.$element[0].setCustomValidity('');

              this.$element.attr('data-account-exists', false);
              Phoenix.UI.showValidateResponse(
                this.validation,
                this.validation.STATE_SUCCESS,
                this.$element
              );
            }
          });
      });
    }

    registerHandler() {
      this.validation.addValidator(this.options.validate_name, (value, element) => {
        return element.attr('data-account-exists') === 'false';
      });
    }
  }

  Phoenix.plugin('userAccountCheck', UserAccountChecker);
});
