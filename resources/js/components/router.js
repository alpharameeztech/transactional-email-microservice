import VueRouter from 'vue-router'

import Compose from './emails/Compose'

import Sent from './emails/Sent'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/compose',
            component: Compose,
        },
        {
            path: '/sent',
            component: Sent,
        },
    ],
});
export default router;
