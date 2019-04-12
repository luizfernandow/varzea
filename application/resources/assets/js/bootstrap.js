
window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('material-design-lite');
    require('getmdl-select/src/js/getmdl-select');
    window.dialogPolyfill = require('dialog-polyfill');
    // require('bootstrap');

} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

window.Timer = require('easytimer.js');

if ('serviceWorker' in navigator) {
	function urlBase64ToUint8Array(base64String) {
	    var padding = '='.repeat((4 - base64String.length % 4) % 4);
	    var base64 = (base64String + padding)
	        .replace(/\-/g, '+')
	        .replace(/_/g, '/');
	    var rawData = window.atob(base64);
	    var outputArray = new Uint8Array(rawData.length);
	    for (var i = 0; i < rawData.length; ++i) {
	        outputArray[i] = rawData.charCodeAt(i);
	    }
	    return outputArray;
	}
	function storePushSubscription(pushSubscription) {
		console.log(pushSubscription)
	    window.axios.post('/push', pushSubscription)
        .then((res) => {
            return res;
        })
        .then((res) => {
            console.log(res)
        })
        .catch((err) => {
            console.log(err)
        });
	}
	function subscribeUser() {
	    navigator.serviceWorker.ready
	        .then((registration) => {
	            const subscribeOptions = {
	                userVisibleOnly: true,
	                applicationServerKey: urlBase64ToUint8Array(
	                    process.env.MIX_VAPID_PUBLIC_KEY
	                )
	            };
	            return registration.pushManager.subscribe(subscribeOptions);
	        })
	        .then((pushSubscription) => {
	            console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
	            storePushSubscription(pushSubscription);
	        });
	}
	function initPush() {
	    new Promise(function (resolve, reject) {
	        const permissionResult = Notification.requestPermission(function (result) {
	            resolve(result);
	        });
	        if (permissionResult) {
	            permissionResult.then(resolve, reject);
	        }
	    })
	        .then((permissionResult) => {
	            if (permissionResult !== 'granted') {
	                throw new Error('We weren\'t granted permission.');
	            }
	            subscribeUser();
	        });
	}
	window.addEventListener('load', () => {
		navigator.serviceWorker.register('/sw.js').then(registration => {
			// console.log('SW registered: ', registration);
			initPush();
		}).catch(registrationError => {
			console.log('SW registration failed: ', registrationError);
		});
	});
}


window.axios.post(`{{ url("/languages/translate/{$language->key}") }}`, 
                    {
                        do_translate: true,
                        group: $(this).data('group'),
                        key: $(this).data('key'),
                        value: $(this).val()
                    }).then((response) => {
                    flashMessage(response.data.message, response.data.success ? 'success' : 'danger');
                });