window._ = require('lodash');

try {
    require('bootstrap');
    window.$ = window.jQuery = require('jquery');
    window.Toastify = require('toastify-js');
} catch (e) {}

const button_loader = '&nbsp;<span class="spinner-border spinner-border-sm text-light mb-1"></span>';

export const makeToast = (text = "This is a toast", type = "success", url = null, clickFunc = () => {}) => {
    var colorType = {
        success: "linear-gradient(to right, #00b09b, #96c93d)",
        warning: "linear-gradient(to right, #b00000, #f3c21f)",
        info: "",
        danger: "#b00000"
    }
    var json = {
        text: text,
        duration: 5000,
        newWindow: true,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        backgroundColor: colorType[type],
        stopOnFocus: true, // Prevents dismissing of toast on hover
        onClick: clickFunc // Callback after click
    }
    if (url !== null)
        json.destination = url;
    Toastify(json).showToast();
}

window.sendRequest = (form = {}, data = {}, thenFunc = () => { }, catchFunc = () => { }) => {
    try {
        axios({
            method: form.method,
            url: form.action,
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
            .then(thenFunc)
            .catch(catchFunc)
    } catch (e) {
        console.error('Could not send XHR requests');
    }
}

window.submitForm = (thenFunc = null, catchFunc = null) => {
    if (document.getElementsByTagName("form")) {
        $('body').off('submit', 'form');
        $('body').on('submit', 'form', function (e) {
            e.preventDefault();
            var submit_button = $(this).find('button[type="submit"]');
            const original_value = submit_button.html();
            submit_button.html(original_value + button_loader);
            submit_button.prop("disabled", true);

            var form = e.target;
            var data = new FormData(form);

            if (typeof thenFunc !== 'function' || thenFunc === null)
                thenFunc = (response) => {
                    $(this).find('button[type="submit"]').html(original_value);
                    $(this).find('button[type="submit"]').prop("disabled", false);
                    if (response.data.status_code === 200) {
                        $(this)[0].reset();
                        makeToast(response.data.data.message, "success");
                    } else {
                        makeToast(response.data.data.message, "danger");
                    }
                };
            if (typeof catchFunc !== 'function' || catchFunc === null)
                catchFunc = (e) => {
                    $(this).find('button[type="submit"]').html(original_value);
                    $(this).find('button[type="submit"]').prop("disabled", false);
                    makeToast("Oopps, looks like something went wrong. Try again?", "danger");
                };
            sendRequest(form, data, thenFunc, catchFunc);
        });
    }
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
