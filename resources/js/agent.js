import {createApp} from "vue";
import Echo from "laravel-echo"
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
window.Pusher = require('pusher-js');

let app = createApp({
    data(){
        return {
            rtc: {},
            rtm: {},
            signaling: {},
            customers: []
        };
    },
    mounted(){
        window.Echo = this.signaling = new Echo({
            broadcaster: 'pusher',
            key: 'wcckey',
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        });
        this.signaling.private('agent.' + window.rtc.agent_id).listen('calls.incoming', (e) => {
            this.customers.push(e.customer);
        });

        this.rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
        this.rtm = AgoraRTM.createInstance(window.rtc.app_id);
        this.rtm.login({uid: 'a-' + window.rtc.agent_id});
    }
});

app.mount('#app');
