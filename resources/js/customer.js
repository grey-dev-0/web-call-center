import {createApp} from "vue";
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
import {find as _find} from 'lodash';
window.Pusher = require('pusher-js');

let rtc = {}, rtm = {client: {}, channel: {}}, $ = window.$, localAudioTrack;
let app = createApp({
    data(){
        return {
            inCall: false,
            reconnecting: false,
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
        fetchOrganizations(initial){
            $.get(window.b + '/organizations?p=' + this.currentPage, (response) => {
                this.currentPage = response.organizations.current_page;
                this.lastPage = response.organizations.last_page;
                this.organizations = response.organizations.data;
                if(initial){
                    let activeOrganization = _find(response.organizations.data, (organization) => organization.calls.length > 0);
                    if(activeOrganization)
                        this.call(activeOrganization);
                }
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
            $.post(window.b + '/call', {id: organization.id}, (response) => {
                this.inCall = organization.id;
                this.initRtm(response.customer_id, response.agent_id, response.rtm_token);
            }).fail((xhr) => {
                if(xhr.status == 404)
                    alert(xhr.responseJSON.message);
                console.error(xhr.responseJSON);
            });
        },
        hangup(notify){
            localAudioTrack.close();
            rtc.leave();
            rtm.channel.leave();
            rtm.client.logout();
            if(notify)
                $.get(window.b + '/hangup/' + this.inCall, () => {
                    this.inCall = this.reconnecting = false;
                });
            else
                this.inCall = this.reconnecting = false;
        }
    },
    mounted(){
        this.fetchOrganizations(true);
        rtm.client = AgoraRTM.createInstance(window.rtc.app_id);
        rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
        rtc.on('user-published', async(peer, type) => {
            if(this.reconnecting)
                this.reconnecting = false;
            await rtc.subscribe(peer, type);
            peer.audioTrack.play();
        });
        rtc.on('user-unpublished', async(peer) => {
            this.reconnecting = true;
            if(peer.audioTrack)
                peer.audioTrack.stop();
            await rtc.unsubscribe(peer);
        });
    }
});

app.mount('#wcc-app');
