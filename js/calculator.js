/**
 * SnoBlo Inc. — Estimate Calculator
 * Author: Tony
 *
 * Formula:  estimate = width x length x rate
 * Minimum charge: $35.00 CAD
 *
 * Rates (CAD per sq ft) — update here if pricing changes:
 */
var RATES = {
    driveway: 0.18,
    walkway: 0.22,
    parking: 0.14
};

var MINIMUM_CHARGE = 35.00;

function formatCAD(amount) {
    return '$' + amount.toFixed(2) + ' CAD';
}

function calculateEstimate() {
    var env = document.getElementById('calc-env').value;
    var width = parseFloat(document.getElementById('calc-width').value);
    var length = parseFloat(document.getElementById('calc-length').value);
    var errorEl = document.getElementById('calc-error');
    var resultEl = document.getElementById('calc-result');

    errorEl.textContent = '';
    resultEl.classList.remove('visible');

    if (isNaN(width) || isNaN(length) || !width || !length) {
        errorEl.textContent = 'Please enter valid width and length values.';
        return;
    }

    if (width <= 0 || length <= 0) {
        errorEl.textContent = 'Width and length must be greater than zero.';
        return;
    }

    if (width > 9999 || length > 9999) {
        errorEl.textContent = 'Please enter realistic dimensions (max 9,999 ft).';
        return;
    }

    var area = width * length;
    var rate = RATES[env];
    var subtotal = area * rate;
    var total = subtotal < MINIMUM_CHARGE ? MINIMUM_CHARGE : subtotal;

    document.getElementById('res-area').textContent = area.toLocaleString('en-CA') + ' sq ft';
    document.getElementById('res-rate').textContent = formatCAD(rate) + ' / sq ft';
    document.getElementById('res-subtotal').textContent = formatCAD(subtotal);
    document.getElementById('res-total').textContent = formatCAD(total);

    var noteEl = document.getElementById('res-note');
    if (subtotal < MINIMUM_CHARGE) {
        noteEl.textContent = 'Minimum charge of $35.00 CAD applied. This is an estimate only.';
    } else {
        noteEl.textContent = 'This is an estimate only. Final price confirmed at booking.';
    }

    resultEl.classList.add('visible');
}

/* Allow Enter key to trigger calculation */
document.addEventListener('DOMContentLoaded', function () {
    ['calc-width', 'calc-length'].forEach(function (id) {
        document.getElementById(id).addEventListener('keydown', function (e) {
            if (e.key === 'Enter') calculateEstimate();
        });
    });
});