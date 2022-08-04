import {createApp} from "vue";
import AgoraRTC from "agora-rtc-sdk-ng"
import AgoraRTM from "agora-rtm-sdk";
window.Pusher = require('pusher-js');

let rtc = {}, rtm = {client: {}, channel: {}}, $ = window.$;
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
        initRtc(){
            rtc = AgoraRTC.createClient({codec: 'vp8', mode: 'rtc'});
        },
        initRtm(customerId, agentId, token){
            rtm.channel = rtm.client.createChannel(agentId);
            rtm.client.login({uid: customerId, token})
                .then(() => rtm.channel.join());
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
            $.post(window.b + '/call', {id: organization.id}, (response) => {
                this.initRtm(response.customer_id, response.agent_id, response.rtm_token);
            });
        }
    },
    mounted(){
        this.fetchOrganizations();
        rtm.client = AgoraRTM.createInstance(window.rtc.app_id);
    }
});

app.mount('#wcc-app');
