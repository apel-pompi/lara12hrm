import Echo from 'laravel-echo';

declare global {
    interface Window {
        Echo: Echo;
    }
}


const echo = new Echo({
    broadcaster: 'reverb',

    key:
        import.meta.env.VITE_REVERB_APP_KEY ||
        import.meta.env.VITE_PUSHER_APP_KEY ||
        'local',

    wsHost:
        import.meta.env.VITE_REVERB_HOST ||
        import.meta.env.VITE_PUSHER_HOST ||
        '127.0.0.1',

    wsPort: Number(
        import.meta.env.VITE_REVERB_PORT ||
        import.meta.env.VITE_PUSHER_PORT ||
        8080
    ),

    wssPort: Number(
        import.meta.env.VITE_REVERB_PORT ||
        import.meta.env.VITE_PUSHER_PORT ||
        8080
    ),

    forceTLS:
        (
            import.meta.env.VITE_REVERB_SCHEME ||
            'http'
        ) === 'https',

    enabledTransports: ['ws', 'wss'],

    authEndpoint: '/broadcasting/auth',
});

window.Echo = echo;

export { echo };

export default echo;

