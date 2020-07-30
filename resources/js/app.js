require('./bootstrap');
import Echo from "laravel-echo";

// resources/assets/js/bootstrap.js

import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '30cfc6c3e71955413265',
    cluster: 'eu',
    encrypted: true
});
