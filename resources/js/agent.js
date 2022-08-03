import {createApp} from "vue";
import Echo from "laravel-echo"
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
window.Pusher = require('pusher-js');

let rtc = {}, rtm = {client: {}, channel: {}}, signaling = {}, $ = window.$;
let app = createApp({
    data(){
        return {
            inCall: false,
            customers: []
        };
    },
    mounted(){
        window.Echo = signaling = new Echo({
            broadcaster: 'pusher',
            key: 'wcckey',
            wsHost: window.location.hostname,
            wssPort: 443,
            encrypted: true,
            disableStats: true
        });
        signaling.private('agent.' + window.a).listen('calls.incoming', (e) => {
            this.customers.push(e.customer);
        });

        rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
        rtm.client = AgoraRTM.createInstance(window.rtc.app_id);
        rtm.channel = rtm.client.createChannel(window.rtc.agent_id);
        rtm.client.login({uid: window.rtc.agent_id, token: window.rtc.token});
    }
});

app.mount('#wcc-app');
