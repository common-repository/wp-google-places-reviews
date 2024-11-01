/**
 * WP Google Places Reviews
 * Version 1.0.0
 */

jQuery(document).ready(function() {
	if (document.getElementById('copyButton')) {
		enqueueCopyToClipboard();
	}
});

function enqueueCopyToClipboard() {
	document.getElementById('copyButton').addEventListener('click', function() {
		copyToClipboardMsg(document.getElementById('copyTarget'), 'msg');
	});

	document.getElementById('copyButton2').addEventListener('click', function() {
		copyToClipboardMsg(document.getElementById('copyTarget2'), 'msg');
	});

	document.getElementById('pasteTarget').addEventListener('mousedown', function() {
		this.value = '';
	});
};

function copyToClipboardMsg(elem, msgElem) {
	var succeed = copyToClipboard(elem);
	var msg;
	if (!succeed) {
		msg = 'Copy not supported or blocked. Please select text and press Ctrl+c to copy.';
	} else {
		msg = '<div class="reviewsuccess">Your review has been copied. You can now paste it on Google.</div>';
	}
	if (typeof msgElem === 'string') {
		msgElem = document.getElementById(msgElem);
	}
	msgElem.innerHTML = msg;
	setTimeout(function() {
		msgElem.innerHTML = '';
	}, 10000);
};

function copyToClipboard(elem) {
	// create hidden text element, if it doesn't already exist
	var targetId = '_hiddenCopyText_';
	var isInput = elem.tagName === 'INPUT' || elem.tagName === 'TEXTAREA';
	var origSelectionStart, origSelectionEnd;
	if (isInput) {
		// can just use the original source element for the selection and copy
		target = elem;
		origSelectionStart = elem.selectionStart;
		origSelectionEnd = elem.selectionEnd;
	} else {
		// must use a temporary form element for the selection and copy
		target = document.getElementById(targetId);
		if (!target) {
			var target = document.createElement('textarea');
			target.style.position = 'absolute';
			target.style.left = '-9999px';
			target.style.top = '0';
			target.id = targetId;
			document.body.appendChild(target);
		}
		target.textContent = elem.textContent;
	}
	// select the content
	var currentFocus = document.activeElement;
	target.focus();
	target.setSelectionRange(0, target.value.length);

	// copy the selection
	var succeed;
	try {
		succeed = document.execCommand('copy');
	} catch(e) {
		succeed = false;
	}
	// restore original focus
	if (currentFocus && typeof currentFocus.focus === 'function') {
		currentFocus.focus();
	}

	if (isInput) {
		// restore prior selection
		elem.setSelectionRange(origSelectionStart, origSelectionEnd);
	} else {
		// clear temporary content
		target.textContent = '';
	}
	return succeed;
};