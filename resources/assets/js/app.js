
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
import axios from 'axios';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
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

// Training components with categories and sub categories
Vue.component('lesson-category', require('./components/LessonCategories.vue'));
Vue.component('lesson-sub-category', require('./components/LessonSubCategories.vue'));
Vue.component('lesson', require('./components/Lessons.vue'));
Vue.component('student-updates', require('./components/StudentUpdates'));

const app = new Vue({
    el: '#app_vue',
});
