import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const isHttps = (import.meta.env.VITE_REVERB_SCHEME ?? window.location.protocol.replace(':', '')) === 'https';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: isHttps ? (import.meta.env.VITE_REVERB_PORT == 8080 || import.meta.env.VITE_REVERB_PORT == 8081 ? 443 : (import.meta.env.VITE_REVERB_PORT ?? 443)) : 443,
    forceTLS: isHttps,
    enabledTransports: ['ws', 'wss'],
});
