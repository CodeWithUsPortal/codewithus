require('./bootstrap');

window.Vue = require('vue');
import axios from 'axios';

//reusable components
Vue.component('pagination', require('./components/Pagination.vue'));

Vue.component('example', require('./components/Example.vue'));
Vue.component('login-component', require('./components/Login.vue'));
Vue.component('username-reset', require('./components/ForgotUsername.vue'));
Vue.component('lecture-category', require('./components/LectureCategories.vue'));
Vue.component('lecture-sub-category', require('./components/LectureSubCategories.vue'));
Vue.component('lecture', require('./components/Lectures.vue'));
Vue.component('calendar-component', require('./components/Calendar.vue'));
Vue.component('search-students', require('./components/SearchStudent.vue'));
Vue.component('edit-student-profile', require('./components/EditStudentProfile.vue'));
Vue.component('add-class', require('./components/AddClass.vue'));
Vue.component('topic', require('./components/Topic.vue'));

Vue.component('search-teachers', require('./components/SearchTeacher.vue'));
Vue.component('edit-teacher-profile', require('./components/EditTeacherProfile.vue'));
Vue.component('free-session-signup', require('./components/FreeSessionSignup.vue'));
Vue.component('free-session-signin', require('./components/FreeSessionSignin.vue'));
Vue.component('parents-students', require('./components/ParentsStudents.vue'));
Vue.component('add-student-by-user', require('./components/AddStudentByUser.vue'));
Vue.component('online-meeting', require('./components/OnlineMeeting.vue'));
Vue.component('free-session-time-slots', require('./components/FreeSessionTimeSlots.vue'));

// Training components with categories and sub categories
Vue.component('lesson-category', require('./components/LessonCategories.vue'));
Vue.component('lesson-sub-category', require('./components/LessonSubCategories.vue'));
Vue.component('lesson', require('./components/Lessons.vue'));
Vue.component('student-updates', require('./components/StudentUpdates'));
Vue.component('student-completed-classes', require('./components/CompletedClasses'));

//Dashboards
Vue.component('teacher-dashboard', require('./components/TeacherDashboard'));

//Bulk Messages Component
Vue.component('bulk-messages', require('./components/BulkMessages'));
//locations
Vue.component('locations', require('./components/Locations'));



const app = new Vue({
    el: '#app_vue',
});
