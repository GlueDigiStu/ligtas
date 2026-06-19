/**
 * Cart recipient gate.
 *
 * Stops a customer reaching the checkout with empty, invalid, or unsaved
 * recipient details. When "Proceed to checkout" is clicked we validate every
 * recipient box in place; if they're all complete we save them via AJAX (so
 * the customer never has to press "Update cart") and then follow through to
 * the checkout. Otherwise we block, highlight the offending fields, and explain.
 */
(function ($) {
	'use strict';

	if (typeof CourseRecipients === 'undefined') {
		return;
	}

	var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	var ERROR_CLASS = 'course-field-error';

	function clearFeedback() {
		$('.' + ERROR_CLASS).removeClass(ERROR_CLASS);
		$('.course-recipients-error').remove();
	}

	function showError(message, $scrollTarget) {
		$('.course-recipients-error').remove();

		var $notice = $('<ul class="woocommerce-error course-recipients-error" role="alert" tabindex="-1"></ul>');
		$('<li></li>').text(message).appendTo($notice);

		var $anchor = $('.wc-proceed-to-checkout').first();
		if ($anchor.length) {
			$anchor.before($notice);
		} else {
			$('.woocommerce-cart-form').first().before($notice);
		}

		var $target = ($scrollTarget && $scrollTarget.length) ? $scrollTarget : $notice;
		$('html, body').animate({ scrollTop: $target.offset().top - 120 }, 400);
	}

	/**
	 * Validate every recipient box currently rendered in the cart.
	 *
	 * @return {Object} { valid, hasEmpty, hasInvalidEmail, firstInvalid }
	 */
	function validateRecipients() {
		var result = { valid: true, hasEmpty: false, hasInvalidEmail: false, firstInvalid: null };

		$('.course-recipient-form').each(function () {
			$(this).find('input.input-text').each(function () {
				var $input = $(this);
				var value = $.trim($input.val());
				var isEmail = ($input.attr('type') === 'email');

				var bad = false;
				if (!value) {
					bad = true;
					result.hasEmpty = true;
				} else if (isEmail && !EMAIL_RE.test(value)) {
					bad = true;
					result.hasInvalidEmail = true;
				}

				if (bad) {
					result.valid = false;
					$input.addClass(ERROR_CLASS);
					if (!result.firstInvalid) {
						result.firstInvalid = $input;
					}
				}
			});
		});

		return result;
	}

	$(document).on('click', '.wc-proceed-to-checkout a.checkout-button', function (e) {
		var $btn = $(this);

		// No recipient boxes in the cart — nothing to gate, behave normally.
		if (!$('.course-recipient-form').length) {
			return true;
		}

		// Don't double-fire while a save is already in flight.
		if ($btn.data('courseSaving')) {
			e.preventDefault();
			return false;
		}

		clearFeedback();

		var check = validateRecipients();
		if (!check.valid) {
			e.preventDefault();
			var message = check.hasEmpty ? CourseRecipients.messages.incomplete : CourseRecipients.messages.invalidEmail;
			showError(message, check.firstInvalid);
			if (check.firstInvalid) {
				check.firstInvalid.trigger('focus');
			}
			return false;
		}

		// Everything is valid — persist it, then follow the link to checkout.
		e.preventDefault();

		var targetUrl = $btn.attr('href');
		$btn.data('courseSaving', true).addClass('course-saving');

		var payload = $('.woocommerce-cart-form')
			.find('[name^="recipient_first_name"], [name^="recipient_last_name"], [name^="recipient_email"], [name^="recipient_dob"]')
			.serialize();

		payload += '&action=course_save_recipients';
		payload += '&nonce=' + encodeURIComponent(CourseRecipients.nonce);

		$.post(CourseRecipients.ajaxUrl, payload)
			.done(function (response) {
				if (response && response.success) {
					window.location.href = targetUrl;
					return;
				}

				$btn.data('courseSaving', false).removeClass('course-saving');
				var msg = (response && response.data && response.data.errors && response.data.errors.length)
					? response.data.errors.join(' ')
					: CourseRecipients.messages.saveFailed;
				showError(msg);
			})
			.fail(function () {
				$btn.data('courseSaving', false).removeClass('course-saving');
				showError(CourseRecipients.messages.saveFailed);
			});

		return false;
	});

	// Clear a field's error state as soon as the customer starts fixing it.
	$(document).on('input', '.course-recipient-form input.input-text', function () {
		$(this).removeClass(ERROR_CLASS);
	});
})(jQuery);
