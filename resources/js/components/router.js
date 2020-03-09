import VueRouter from 'vue-router'

import Dashboard from './Dashboard'

import Compose from './emails/Compose'

import Sent from './emails/Sent'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Dashboard,
        },
        {
            path: '/dashboard',
            component: Dashboard,
        },
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
