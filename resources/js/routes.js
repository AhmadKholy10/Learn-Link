import Dashboard from './components/Dashboard.vue';
import ViewStudents from './pages/students/ViewStudents.vue';
import ViewCourses from './pages/courses/ViewCourses.vue';

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },

    
    {
        path: '/admin/student',
        name: 'admin.student',
        component: ViewStudents,
    },

    {
        path: '/admin/course',
        name: 'admin.course',
        component: ViewCourses,
    },
]