import Dashboard from '../js/pages/Dashboard.vue';
import Chart from '../js/pages/Chart.vue';
import Account from '../js/pages/Account.vue';
import NewTest from '../js/pages/NewTest.vue';
import EditTest from '../js/pages/EditTest.vue';
import TestTaker from '../js/pages/TestTaker.vue';
import SubmitResponse from '../js/pages/SubmitResponse.vue'
import Response from '../js/pages/Response.vue';
import Signin from '../js/pages/Signin.vue';
import Signup from '../js/pages/Signup.vue';
import IndividualTestResponders from '../js/pages/IndividualTestResponders.vue';
import ForgotPassword from '../js/pages/ForgotPassword.vue';
import UpdatePassword from '../js/pages/UpdatePassword.vue';
import Success from '../js/pages/Success.vue';
import LandingPage from '../js/pages/LandingPage.vue';

export default[
    {
        path: '/',
        name: 'landingPage',
        component: LandingPage,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/chart',
        name: 'chart',
        component: Chart,
    },
    {
        path: '/my/account',
        name: 'account',
        component: Account,
    },
    {
        path: '/new/test',
        name: 'test',
        component: NewTest,
    },
    {
        path: '/edit/:id',
        name: 'editTest',
        component: EditTest,
        props:true,
    },
    {
        path: '/test/taker',
        name: 'testTaker',
        component: TestTaker,
    },
    {
        path: '/api/test/number/:id',
        name: 'submitResponse',
        component: SubmitResponse,
    },
    {
        path: '/api/reponse/:id',
        name: 'response',
        component: Response,
    },
    {
        path: '/user/signin',
        name: 'signin',
        component: Signin,
    },
    {
        path: '/user/signup',
        name: 'signup',
        component: Signup,
    },
    {
        path: '/individual/test/responders/:id', 
        name: 'individualTestResponders',
        component: IndividualTestResponders, 
    },
    {
        path: '/user/forgot/password',
        name: 'forgotPassword',
        component: ForgotPassword
    },
    {
        path: '/user/update/password/:token',
        name: 'updatePassword',
        component: UpdatePassword
    },
    {
        path: '/api/user/verify/email/:id',
        name: 'success',
        component: Success
    },
    
    {
        path: '/:catchAll(.*)', 
        name: 'notFound',
        component: Dashboard, 
    },
    
]