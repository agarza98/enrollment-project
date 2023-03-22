import AllEnrollments from './components/AllEnrollments.vue';
import CreateEnrollment from './components/CreateEnrollment.vue';
import EditEnrollment from './components/EditEnrollment.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllEnrollments
    },
    {
        name: 'create',
        path: '/create',
        component: CreateEnrollment
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditEnrollment
    }
];
