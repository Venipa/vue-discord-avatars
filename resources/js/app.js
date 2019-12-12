require('./bootstrap');
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import vClipboard from 'v-clipboard';
import AvatarFetchVue from './components/AvatarFetchComponent.vue';

Vue.use(BootstrapVue);
Vue.use(vClipboard);

new Vue({
    components: {
        'app-avatar-fetch': AvatarFetchVue
    }
}).$mount('#app');
