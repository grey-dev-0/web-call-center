import {createApp} from "vue";
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
window.Pusher = require('pusher-js');

let rtc = {}, rtm = {client: {}, channel: {}}, $ = window.$, localAudioTrack;
let app = createApp({
    data(){
        return {
            inCall: false,
            organizations: [],
            currentPage: 1,
            lastPage: 1
        };
    },
    methods: {
        initRtc(agentId){
            $.get(window.b + '/rtc?a=' + agentId, (response) => {
                rtc.join(window.c, response.channel, response.rtc_token, response.user_id).then(() => {
                    AgoraRTC.createMicrophoneAudioTrack().then((audioTrack) => {
                        localAudioTrack = audioTrack;
                        rtc.publish([localAudioTrack]);
                    });
                });
            });
        },
        initRtm(customerId, agentId, token){
            rtm.channel = rtm.client.createChannel(agentId);
            rtm.client.login({uid: customerId, token})
                .then(() => rtm.channel.join());

            rtm.channel.on('ChannelMessage', (message, peerId) => {
                message = JSON.parse(message.text);
                if(message.customer_id != window.rtc.customer_id)
                    return;
                switch(message.action){
                    case 'pick': this.initRtc(message.agent_id); break;
                    case 'hang': this.hangup(); break;
                }
            });
        },
        fetchOrganizations(){
            $.get(window.b + '/organizations?p=' + this.currentPage, (response) => {
                this.currentPage = response.organizations.current_page;
                this.lastPage = response.organizations.last_page;
                this.organizations = response.organizations.data;
            });
        },
        first(){
            this.currentPage = 1;
            this.$nextTick(() => this.fetchOrganizations());
        },
        previous(){
            this.currentPage -= 1;
            this.$nextTick(() => this.fetchOrganizations());
        },
        next(){
            this.currentPage += 1;
            this.$nextTick(() => this.fetchOrganizations());
        },
        last(){
            this.currentPage = this.lastPage;
            this.$nextTick(() => this.fetchOrganizations());
        },
        call(organization){
            this.inCall = organization.id;
            $.post(window.b + '/call', {id: this.inCall}, (response) => {
                this.initRtm(response.customer_id, response.agent_id, response.rtm_token);
            });
        },
        hangup(notify){
            localAudioTrack.close();
            rtc.leave();
            rtm.channel.leave();
            if(notify)
                $.get(window.b + '/hangup/' + this.inCall, () => {
                    this.inCall = false;
                });
            else
                this.inCall = false;
        }
    },
    mounted(){
        this.fetchOrganizations();
        rtm.client = AgoraRTM.createInstance(window.rtc.app_id);
        rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
        rtc.on('user-published', async(peer, type) => {
            await rtc.subscribe(peer, type);
            peer.audioTrack.play();
        });
        rtc.on('user-unpublished', async(peer) => await rtc.unsubscribe(peer));
    }
});

app.mount('#wcc-app');
