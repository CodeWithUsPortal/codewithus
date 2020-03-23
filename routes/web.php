<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$this->get('registeration', function () {
    return view('register_options');
})->name('register-options');
$this->get('/', 'HomeController@index')->name('home');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->get('user_login', 'Auth\LoginController@loginFormForUsersExceptParents')->name('except-parents-login-form');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('/parentsLogin', 'Auth\LoginController@parentsPhoneNumberForm')->name('parent-phone-number-form');
$this->post('/token', 'Auth\LoginController@parentsLoginTokenForm')->name('login-token-form');
$this->post('/parentlogin', 'Auth\LoginController@parentsLogin')->name('parent-login');

//Custom Registration Routes...
$this->post('register', 'Auth\RegisterController@registrationChecks')->name('register');
$this->get('/admins/register', 'Auth\RegisterController@showRegistrationFormForAdmins')->name('admins-register');
$this->get('/teachers/register', 'Auth\RegisterController@showRegistrationFormForTeachers')->name('teachers-register');
$this->get('/students/register', 'Auth\RegisterController@showRegistrationFormForStudentsPage1');
$this->post('/students/register','Auth\RegisterController@studentsRegistrationPage1')->name('students-register-page1');
$this->get('parents/register', 'Auth\RegisterController@showRegistrationForm')->name('parents-register');

// Password Reset Routes ...
$this->post('/usernamesUsingPhoneNumber','UsernameResetController@getUserNamesUsingPhoneNumbers');
$this->post('/setPassword','UsernameResetController@setUserPassword');
$this->get('/username/reset','UsernameResetController@forgotUserNameForm')->name('reset-username');
$this->post('/usernames','UsernameResetController@getUserNames');
$this->get('/password/reset','PasswordResetController@showPasswordResetForm')->name('reset-password');
$this->post('/password/reset','PasswordResetController@validateUsername');
$this->post('/password/update','PasswordResetController@validateSecretTokenAndUpdatePassword')->name('validate-security-code');

// Routes for Dashboards
$this->get('/home', 'HomeController@index')->name('home');
$this->get('/admin/dashboard', 'AdminController@index')->name('admin.dashboard');
$this->get('/teacher/dashboard', 'TeacherController@index')->name('teacher.dashboard');
$this->get('/parent/dashboard', 'ParentController@index')->name('parent.dashboard');
$this->get('/student/dashboard', 'StudentController@index')->name('student.dashboard');

// Parent Routes ...
$this->get('/parent/updates','ParentController@updates');
$this->get('/parent/update/{phoneNumber}/{updateId}','ViewUpdateController@viewUpdate');
$this->get('/parent/students','ParentController@studentsListPage');
$this->get('/parent/getStudents','ParentController@getStudentsList');

// Student Routes ...
$this->get('/student/updates','StudentController@updates');
$this->get('/student/update','StudentController@newUpdateForm');
$this->post('/student/update','StudentController@writeAnUpdate')->name('write-an-update');

// Teacher Routes ...
$this->get('/teacher/updates','TeacherController@updates');
$this->get('/teacher/update/{taskId?}','TeacherController@newUpdateForm');
$this->post('/teacher/update','TeacherController@writeAnUpdate')->name('teacher-write-an-update');
$this->get('/teacher/calendar','TeacherController@calendarView');

// Admin Routes ...
$this->get('/admin/updates','AdminController@updates');
$this->get('/admin/update/{taskId?}','AdminController@newUpdateForm');
$this->post('/admin/update','AdminController@writeAnUpdate')->name('admin-write-an-update');
$this->get('/admin/calendar','AdminController@calendarView');

// Calendar Routes
$this->post('/calendar/get_calendar_events','CalendarController@getCalendarEvents');
$this->get('/calendar/get_locations','CalendarController@getLocations');

// Student&Class Routes
$this->post('/get_student_by_fullName','StudentAndClassController@getStudentByFullName')->name('get-student-by-fullname');
$this->post('/get_student_by_phoneNumber','StudentAndClassController@getStudentByPhoneNumber')->name('get-student-by-phonenumber');
$this->get('/edit_student_profile/{studentId?}','StudentAndClassController@editStudentAssignmentToClasses');
$this->post('/edit_student_profile','StudentAndClassController@editStudentProfile');
$this->post('/get_student_profile','StudentAndClassController@getStudentProfile');
$this->post('/get_assigned_classes','StudentAndClassController@getStudentsClasses');
$this->post('/un_assign_student','StudentAndClassController@unAssignStudent');
$this->post('/get_classes','StudentAndClassController@getClassesForStudentLocationAndDate');
$this->post('/getTeachers', 'StudentAndClassController@getTeachersForTheLocation');
$this->get('/task_class','StudentAndClassController@addTaskClassForm');
$this->post('/add_task_class','StudentAndClassController@addTaskClass')->name('add-task-class');
$this->get('/add_student_form','StudentAndClassController@addStudentForm')->name('add-student-form');
$this->post('/add_student_to_class','StudentAndClassController@addStudentToClass')->name('add-student-to-class');

$this->post('/add_student_location','StudentAndClassController@addLocationToStudent');
$this->post('/get_student_location','StudentAndClassController@getStudentsLocations');
$this->post('/remove_student_location','StudentAndClassController@removeStudentLocation');

// Category/SubCategory and Lectures Routes ...
$this->get('/category','CategoryController@indexLectureCategories');
$this->get('/getAllLectureCategories','CategoryController@allLectureCategories');
$this->post('/addLectureCategory','CategoryController@storeLectureCategory');
$this->put('/category/edit/{id}','CategoryController@updateLectureCategory');
$this->delete('/category/delete/{id}','CategoryController@destroyLectureCategory');
$this->put('/updateCategoryPriority','CategoryController@updateCategoryPriority');
$this->get('/subcategory','SubCategoryController@indexLectureSubCategories');
$this->get('/getAllLectureSubCategories','SubCategoryController@allLectureSubCategories');
$this->post('/addLectureSubCategory','SubCategoryController@storeLectureSubCategory');
$this->put('/subcategory/edit/{id}','SubCategoryController@updateLectureSubCategory');
$this->delete('/subcategory/delete/{id}','SubCategoryController@destroyLectureSubCategory');
$this->put('/updateSubCategoryPriority','SubCategoryController@updateSubCategoryPriority');
$this->get('/lecture','LectureController@indexLectures');
$this->get('/getAllLectures','LectureController@allLectures');
$this->post('/addLecture','LectureController@storeLecture');
$this->put('/lecture/edit/{id}','LectureController@updateLecture');
$this->delete('/lecture/delete/{id}','LectureController@destroyLecture');
$this->put('/updateLecturePriority','LectureController@updateLecturePriority');

// Topic Routes ...
$this->get('/topics','TopicController@topicsIndexPage');
$this->get('/get_topics','TopicController@getTopics');
$this->post('/add_topic','TopicController@addTopic');
$this->put('/topic/edit/{id}','TopicController@editTopic');
$this->delete('/topic/delete/{id}','TopicController@deleteTopic');

// ViewLecture Routes 
$this->get('/categories', 'ViewLectureController@getLectureCategories');
$this->get('/subCategories/{id?}', 'ViewLectureController@getLectureSubCategories');
$this->get('/lectures/{id?}', 'ViewLectureController@getLectures');
$this->get('/teacher/categories', 'ViewLectureController@getLectureCategories');
$this->get('/teacher/subCategories/{id?}', 'ViewLectureController@getLectureSubCategoriesForTeachers');
$this->get('/teacher/lectures/{id?}', 'ViewLectureController@getLecturesForTeachers');

// Teacher Profile Routes ...
$this->get('/teacher_profile','TeacherProfileController@searchTeachers');
$this->post('/get_teacher_by_fullName','TeacherProfileController@getTeacherByFullName')->name('get-teacher-by-fullname');
$this->post('/get_teacher_by_phoneNumber','TeacherProfileController@getTeacherByPhoneNumber')->name('get-teacher-by-phonenumber');
$this->post('/get_teacher_by_email','TeacherProfileController@getTeacherByEmail')->name('get-teacher-by-email');

$this->get('/get_all_locations','TeacherProfileController@getAllLocations');
$this->post('/get_teacher_location','TeacherProfileController@getTeachersLocations');
$this->post('/add_teacher_location','TeacherProfileController@addLocationToTeacher');
$this->post('/remove_teacher_location','TeacherProfileController@removeTeacherLocation');

$this->post('/get_teacher_topic','TeacherProfileController@getTeachersTopics');
$this->post('/add_teacher_topic','TeacherProfileController@addTopicToTeacher');
$this->post('/remove_teacher_topic','TeacherProfileController@removeTeacherTopic');

$this->post('/get_teacher_profile','TeacherProfileController@getTeacherProfile');
$this->get('/edit_teacher_profile/{teacherId?}','TeacherProfileController@editTeacherProfileForm');
$this->post('/edit_teacher_profile','TeacherProfileController@editTeacherProfile');

// Free Session Routes ...
$this->get('/form_options','FreeSessionController@formOptions')->name('free-session-form-options');
$this->get('/signup_form','FreeSessionController@signUpForm')->name('free-session-signup');
$this->get('/signin_form','FreeSessionController@signInForm')->name('free-session-signin');
$this->post('/find_user_record_for_free_session','FreeSessionController@findStudentRecordForFreeSession');
$this->get('/get_free_session_locations','FreeSessionController@allLocationsForFreeSession');
$this->get('/get_free_session_topics','FreeSessionController@allTopics');
$this->post('/get_available_time_slots','FreeSessionController@getAvailableTimeSlotsForALocation');
$this->post('/add_free_session','FreeSessionController@addFreeSession');


// Add Student Routes ...
$this->get('/add_student_form_by_user','AddStudentController@addStudentForm');
$this->get('/get_parents_phoneNumber','AddStudentController@getParentsPhoneNumber');
$this->get('/get_locations_for_adding_students','AddStudentController@getLocations');
$this->post('/add_student_by_user','AddStudentController@addStudent');

//Online Meeting Routes ...
$this->get('/online_meeting_room', 'OnlineMeetingController@onlineMeetingRoom');
$this->get('/get_username_for_online_meeting','OnlineMeetingController@getUsernameForOnlineMeeting');
$this->get('/join_online_meeting_room/{meetingId?}/{userName?}', 'OnlineMeetingController@joinOnlineMeetingRoom');



$this->get('/sendSMS', 'FreeSessionController@sendSMS');

//Training routes with categories and sub categories
Route::get('/training', 'LessonController@index')->name('lessons.index');
Route::get('/training/categories', 'LessonCategoryController@index')->name('lessons.categories');
Route::get('/training/sub-categories', 'LessonSubCategoryController@index')->name('lessons.subcategories');

//Training section - lesson components api routes
Route::get('/training/getAllLessons','LessonController@allLessons');
Route::post('/training/addLesson','LessonController@storeLesson');
Route::put('/training/lesson/edit/{id}','LessonController@updateLesson');
Route::delete('/training/lesson/delete/{id}','LessonController@destroyLesson');
Route::put('/training/updateLessonPriority','LessonController@updateLessonPriority');

//Training section - lesson categories components api routes
Route::get('/training/getAllLessonCategories','LessonCategoryController@allLessonCategories');
Route::post('/training/addLessonCategory','LessonCategoryController@storeLessonCategory');
Route::put('/training/category/edit/{id}','LessonCategoryController@updateLessonCategory');
Route::delete('/training/category/delete/{id}','LessonCategoryController@destroyLessonCategory');
Route::put('/training/updateCategoryPriority','LessonCategoryController@updateCategoryPriority');

//Training section - lesson sub-categories components api routes
Route::get('/training/getAllLessonSubCategories','LessonSubCategoryController@allLessonSubCategories');
Route::post('/training/addLessonSubCategory','LessonSubCategoryController@storeLessonSubCategory');
Route::put('/training/subcategory/edit/{id}','LessonSubCategoryController@updateLessonSubCategory');
Route::delete('/training/subcategory/delete/{id}','LessonSubCategoryController@destroyLessonSubCategory');
Route::put('/training/updateSubCategoryPriority','LessonSubCategoryController@updateSubCategoryPriority');

//update teacher
Route::post('/teacher/store-students-update', 'TeacherController@storeStudentUpdates');
//Teachers update
Route::get('/parent/teachers/update/{phoneNumber}/{updateId}','ViewUpdateController@viewStudentUpdate')->name('teachers-update');

//Add permanent schedules
Route::get('/add-permanent-class-schedule', 'PermanentClassScheduleController@index');
Route::post('/add-permanent-class-schedule', 'PermanentClassScheduleController@store');
Route::delete('/add-permanent-class-schedule/{permanentClassSchedule}', 'PermanentClassScheduleController@destroy');

Route::get('/teacher/completed-task-classes/{id}', 'TeacherController@completedClasses')->name('teachers-completed-classes');

//Teacher route for marking task class as completed
Route::post('/teacher/mark-task-class-competed', 'TeacherController@markClassAsCompleted');
Route::post('/teacher/mark-task-class-incomplete', 'TeacherController@markClassAsInCompleted');


Route::post('/get-incomplete-assigned-classes','StudentAndClassController@getIncompleteStudentsClasses');
Route::post('/get-completed-assigned-classes','StudentAndClassController@getCompletedClasses');
