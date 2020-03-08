import VueRouter from 'vue-router'

import Compose from './emails/Compose'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/compose',
            component: Compose,
        },
    ],
});
export default router;
