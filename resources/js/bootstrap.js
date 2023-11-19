import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "59570153d0bd814faacb",
    cluster:'eu',
    encrypted: true,
});


