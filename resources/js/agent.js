import {createApp} from "vue";
import Echo from "laravel-echo"
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
import {find as _find} from 'lodash';
window.Pusher = require('pusher-js');

let rtc = {}, rtm = {client: {}, channel: {}}, $ = window.$, localAudioTrack, customer_id;
let app = createApp({
    data(){
        return {
            inCall: false,
            customers: []
        };
    },
    methods: {
        initEcho(){
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: window.k,
                wsHost: window.location.hostname,
                wssPort: 443,
                encrypted: true,
                disableStats: true
            });
            window.Echo.private('agent.' + window.a).notification((notification) => {
                switch(notification.type){
                    case 'calls.incoming': this.customers.push(notification.customer); break;
                    case 'calls.ended':
                        if(notification.call.id == this.inCall)
                            this.hangup();
                }
            });
            window.Echo.join('organization.' + window.d);
        },
        initRtc(){
            rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
            rtm.client = AgoraRTM.createInstance(window.rtc.app_id);
            rtm.channel = rtm.client.createChannel(window.rtc.agent_id);
            rtm.client.login({uid: window.rtc.agent_id, token: window.rtc.token})
                .then(() => rtm.channel.join());

            rtc.on('user-published', async (peer, type) => {
                await rtc.subscribe(peer, type);
                peer.audioTrack.play();
            });
            rtc.on('user-unpublished', async (peer) => await rtc.unsubscribe(peer));
        },
        initCustomers(){
            $.get(window.b + '/customers', (response) => {
                this.customers = response.customers;
                let activeCustomer = _find(response.customers, (call) => call.started_at != null);
                if(activeCustomer)
                    this.pick(activeCustomer);
            });
        },
        pick(customer){
            this.inCall = customer.id;
            $.get(window.b + '/pick/' + this.inCall, (response) => {
                customer_id = response.customer_id;
                $.get(window.b + '/rtc', (response) => {
                    rtc.join(window.c, response.channel, response.rtc_token, response.user_id).then(() => {
                        AgoraRTC.createMicrophoneAudioTrack().then((audioTrack) => {
                            localAudioTrack = audioTrack;
                            rtc.publish([localAudioTrack]);
                        });
                    });
                    rtm.channel.sendMessage({text: JSON.stringify({action: 'pick', customer_id, agent_id: window.rtc.agent_id})});
                });
            });
        },
        popCurrentCustomer(){
            this.customers.splice(0, 1);
            this.inCall = false;
        },
        hangup(notify){
            localAudioTrack.close();
            rtc.leave();
            if(notify){
                rtm.channel.sendMessage({text: JSON.stringify({action: 'hang', customer_id})}).then(() => customer_id = null);
                $.get(window.b + '/hangup/' + this.inCall, this.popCurrentCustomer);
            } else
                this.popCurrentCustomer();
        }
    },
    mounted(){
        this.initEcho();
        this.initRtc();
        this.initCustomers();
    }
});

app.mount('#wcc-app');
