(function () {
	'use strict';

	const settings = window.GFMailcheckSettings || {};
	const domains = Array.isArray(settings.domains) ? settings.domains : [];
	const availableMessages = settings.messages || {};
	const defaultLanguage = settings.language || 'en';

	function getLanguage() {
		const htmlLanguage = document.documentElement.lang
			? document.documentElement.lang.substring(0, 2).toLowerCase()
			: '';

		if (availableMessages[htmlLanguage]) {
			return htmlLanguage;
		}

		if (availableMessages[defaultLanguage]) {
			return defaultLanguage;
		}

		return 'en';
	}

	function getMessage(key) {
		const language = getLanguage();

		if (availableMessages[language] && availableMessages[language][key]) {
			return availableMessages[language][key];
		}

		if (availableMessages.en && availableMessages.en[key]) {
			return availableMessages.en[key];
		}

		return '';
	}

	function getWrapper(input) {
		return input.closest('.gfield') || input.parentElement;
	}

	function removeSuggestion(input) {
		const wrapper = getWrapper(input);
		if (!wrapper) {
			return;
		}

		const suggestion = wrapper.querySelector('.gf-mailcheck-suggestion');
		if (suggestion) {
			suggestion.remove();
		}

		input.removeAttribute('aria-describedby');
		input.dataset.mailcheckInvalid = '0';
	}

	function showSuggestion(input, suggestion) {
		const wrapper = getWrapper(input);
		if (!wrapper) {
			return;
		}

		removeSuggestion(input);

		const box = document.createElement('div');
		const boxId = 'gf-mailcheck-' + Math.random().toString(36).slice(2);

		box.id = boxId;
		box.className = 'gf-mailcheck-suggestion';
		box.setAttribute('role', 'alert');

		const message = getMessage('suggestion');
		const parts = message.split('%s');
		const prefix = document.createTextNode(parts[0] || '');

		const button = document.createElement('button');
		button.type = 'button';
		button.className = 'gf-mailcheck-suggestion__button';
		button.textContent = suggestion.full;

		const suffix = document.createTextNode(parts.slice(1).join('%s') || '');

		button.addEventListener('click', function () {
			input.value = suggestion.full;
			input.dataset.mailcheckInvalid = '0';
			removeSuggestion(input);

			input.dispatchEvent(new Event('input', { bubbles: true }));
			input.dispatchEvent(new Event('change', { bubbles: true }));
			input.focus();
		});

		box.append(prefix, button, suffix);
		input.insertAdjacentElement('afterend', box);

		input.setAttribute('aria-describedby', boxId);
		input.dataset.mailcheckInvalid = '1';
	}

	function checkInput(input) {
		if (
			!window.Mailcheck ||
			!input ||
			!input.matches('.ginput_container_email input[type="email"], .ginput_container_email input')
		) {
			return;
		}

		const value = input.value.trim();

		if (!value || !value.includes('@')) {
			removeSuggestion(input);
			return;
		}

		window.Mailcheck.run({
			email: value,
			domains: domains,
			suggested: function (suggestion) {
				showSuggestion(input, suggestion);
			},
			empty: function () {
				removeSuggestion(input);
			}
		});
	}

	function initialize(root) {
		const scope = root || document;

		scope.querySelectorAll('.ginput_container_email input').forEach(function (input) {
			if (input.dataset.mailcheckReady === '1') {
				return;
			}

			input.dataset.mailcheckReady = '1';

			input.addEventListener('blur', function () {
				checkInput(input);
			});

			input.addEventListener('input', function () {
				removeSuggestion(input);
			});
		});

		scope.querySelectorAll('form[id^="gform_"]').forEach(function (form) {
			if (form.dataset.mailcheckSubmitReady === '1') {
				return;
			}

			form.dataset.mailcheckSubmitReady = '1';

			form.addEventListener('submit', function (event) {
				const invalidInput = form.querySelector(
					'.ginput_container_email input[data-mailcheck-invalid="1"]'
				);

				if (!invalidInput) {
					return;
				}

				event.preventDefault();
				event.stopImmediatePropagation();

				checkInput(invalidInput);
				invalidInput.focus();
			}, true);
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initialize(document);
	});

	/*
	 * Gravity Forms 2.9+.
	 * Supports AJAX, multi-page forms, and error re-rendering.
	 */
	document.addEventListener('gform/post_render', function (event) {
		const formId = event.detail && event.detail.formId;
		const form = formId ? document.getElementById('gform_' + formId) : document;
		initialize(form || document);
	});

	/*
	 * Compatibility with older Gravity Forms event.
	 */
	if (window.jQuery) {
		window.jQuery(document).on('gform_post_render', function (event, formId) {
			const form = document.getElementById('gform_' + formId);
			initialize(form || document);
		});
	}
})();
