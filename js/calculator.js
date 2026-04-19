/**
 * SnoBlo Inc. — Estimate Calculator + Testimonials
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

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function renderTestimonials(testimonials) {
    var container = document.getElementById('testimonials-container');

    if (!container) {
        return;
    }

    if (!testimonials || !testimonials.length) {
        container.innerHTML = '<div class="testimonial-card"><p>There are no testimonials yet. Be the first to share your experience.</p></div>';
        return;
    }

    container.innerHTML = testimonials.map(function (item) {
        var stars = '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating);
        var initials = item.name.trim().split(' ').map(function (part) {
            return part.charAt(0).toUpperCase();
        }).join('').slice(0, 2);

        return '<div class="testimonial-card">' +
            '<div class="stars">' + stars + '</div>' +
            '<p class="testimonial-text">' + escapeHtml(item.review_text) + '</p>' +
            '<div class="testimonial-author">' +
            '<span class="author-avatar">' + escapeHtml(initials) + '</span>' +
            '<div>' +
            '<p class="author-name">' + escapeHtml(item.name) + '</p>' +
            '<p class="author-location">Verified customer</p>' +
            '</div>' +
            '</div>' +
            '</div>';
    }).join('');
}

function parseJsonResponse(response) {
    return response.text().then(function (text) {
        if (!text) {
            throw new Error('Server returned an empty response.');
        }

        try {
            return JSON.parse(text);
        } catch (error) {
            throw new Error('Invalid server response: ' + text);
        }
    });
}

function fetchTestimonials() {
    fetch('get_reviews.php')
        .then(function (response) {
            return parseJsonResponse(response).then(function (data) {
                if (!response.ok) {
                    throw new Error(data.error || 'Unable to load testimonials.');
                }
                return data;
            });
        })
        .then(function (data) {
            renderTestimonials(data.testimonials || []);
        })
        .catch(function () {
            renderTestimonials([]);
        });
}

function handleTestimonialForm(event) {
    event.preventDefault();

    var nameInput = document.getElementById('t-name');
    var ratingInput = document.getElementById('t-rating');
    var textInput = document.getElementById('t-text');
    var statusEl = document.getElementById('testimonial-status');
    var testimonialForm = document.getElementById('testimonial-form');

    var name = nameInput.value.trim();
    var rating = ratingInput.value;
    var text = textInput.value.trim();

    statusEl.textContent = '';
    statusEl.style.color = '#11632d';

    if (!name || !rating || !text) {
        statusEl.textContent = 'Please complete all fields before submitting your review.';
        statusEl.style.color = '#aa1111';
        return;
    }

    var formData = new FormData(testimonialForm);

    fetch('submit_review.php', {
        method: 'POST',
        body: formData
    })
        .then(function (response) {
            return parseJsonResponse(response).then(function (data) {
                if (!response.ok || data.error) {
                    throw new Error(data.error || 'Submission failed.');
                }
                return data;
            });
        })
        .then(function () {
            nameInput.value = '';
            ratingInput.value = '';
            textInput.value = '';
            statusEl.textContent = 'Thank you! Your review has been saved.';
            statusEl.style.color = '#11632d';
            fetchTestimonials();
        })
        .catch(function (error) {
            statusEl.textContent = error.message || 'Unable to submit feedback.';
            statusEl.style.color = '#aa1111';
        });
}

window.addEventListener('DOMContentLoaded', function () {
    ['calc-width', 'calc-length'].forEach(function (id) {
        var field = document.getElementById(id);
        if (field) {
            field.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') calculateEstimate();
            });
        }
    });

    fetchTestimonials();

    var testimonialForm = document.getElementById('testimonial-form');
    if (testimonialForm) {
        testimonialForm.addEventListener('submit', handleTestimonialForm);
    }
});
