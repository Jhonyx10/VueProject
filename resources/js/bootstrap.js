import axios from 'axios';
window.axios = axios;
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    // Use the standard authorizer logic but force withCredentials
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios
                    .post(
                        "/broadcasting/auth",
                        {
                            socket_id: socketId,
                            channel_name: channel.name,
                        },
                        {
                            withCredentials: true, // <--- THIS IS NON-NEGOTIABLE
                        }
                    )
                    .then((response) => {
                        callback(false, response.data);
                    })
                    .catch((error) => {
                        console.error("Auth Error:", error);
                        callback(true, error);
                    });
            },
        };
    },
});
