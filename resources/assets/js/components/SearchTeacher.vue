<template>
    <div class="container">
        <br/>
        <h4>Search Teachers by : </h4>
        <input class="btn btn-primary" type="button" @click="displaySearchByPhoneNumberForm" value="Phone Number"/>
        <input class="btn btn-primary" type="button" @click="displaySearchByFullNameForm" value="Full Name"/>
        <input class="btn btn-primary" type="button" @click="displaySearchByEmailForm" value="Email"/>
        <br/><br/><br/>
        <div v-show="displayError">
            <h6 style="color:red">No Teacher exists with this information</h6>
        </div>
    
        <form v-show="showSearchByPhoneNumberForm" @submit.prevent="getTeacherByPhoneNumber" enctype="multipart/form-data">
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" maxlength = "100" class="form-control" id="phone_number" placeholder="Phone Number" v-model="teacher.phone_number" required/>
            </div>   
            <input class="btn btn-primary" type="submit" value="search" />
        </form>
        <form v-show="showSearchByFullNameForm" @submit.prevent="getTeacherByFullName" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" maxlength = "100" class="form-control" id="full_name" placeholder="Full Name" v-model="teacher.full_name" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="search" />
        </form>
        <form v-show="showSearchByEmailForm" @submit.prevent="getTeacherByEmail" enctype="multipart/form-data">
            <div class="form-group">
                <label>Email</label>
                <input type="text" maxlength = "100" class="form-control" id="email" placeholder="Email" v-model="teacher.email" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="search" />
        </form>
        <br/>
         <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Teachers </h5></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="teacher in teachers" v-bind:key="teacher.teacher_id">
                    <td><a :href="teacher.link_to_profile">{{ teacher.teacher_name }}</a></td>
                </tr>      
            </tbody>
        </table>
       
        </div>
</template>

<script>
export default {
        data(){
            return {
                showDataTable : false,
                showSearchByPhoneNumberForm : false,
                showSearchByFullNameForm: false,
                showSearchByEmailForm: false,
                
                teacher_id : '',
                teacher: { teacher_id: '', phone_number : '', full_name : ''},
                teachers:[],
                selectedValueOfTeacher : '',
           
                displayError : false,

                teacherData : { selectedStudentId : ''},
            };
        },
        methods:{
            displaySearchByPhoneNumberForm(){
                this.showDataTable = false;
                this.showSearchByPhoneNumberForm = true;
                this.showSearchByFullNameForm = false;
                this.showSearchByEmailForm = false;
                this.displaySuccess = false;
            },
            displaySearchByFullNameForm(){
                this.showDataTable = false;
                this.showSearchByPhoneNumberForm = false;
                this.showSearchByFullNameForm = true;
                this.showSearchByEmailForm = false;
                this.displaySuccess = false;
            },
            displaySearchByEmailForm(){
                this.showDataTable = false;
                this.showSearchByPhoneNumberForm = false;
                this.showSearchByFullNameForm = false;
                this.showSearchByEmailForm = true;
                this.displaySuccess = false;
            },
            getTeacherByFullName(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/get_teacher_by_fullName',this.teacher).then(function(response){
                    if(response.data.response_msg === "No teacher exists with this information"){
                        _this.displayError = true;
                        _this.showDataTable = false;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = true;
                        _this.showSearchByEmailForm = false;
                    }
                    else{
                        _this.displayError = false;
                        _this.teacher = { teacher_id: '', phone_number : '', full_name : ''}
                        _this.teachers = response.data.teachers;
                        _this.showDataTable = true;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                        _this.showSearchByEmailForm = false;
                    }  
                })                  
            },
            getTeacherByPhoneNumber(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/get_teacher_by_phoneNumber',this.teacher).then(function(response){
                    if(response.data.response_msg == "No Teacher exists with this information"){
                        _this.displayError = true;
                        _this.showDataTable = false;
                        _this.showSearchByPhoneNumberForm = true;
                        _this.showSearchByFullNameForm = false;
                        _this.showSearchByEmailForm = false;
                    }
                    else{
                        _this.displayError = false;
                        _this.teacher = { teacher_id: '', phone_number : '', full_name : ''}
                        _this.teachers = response.data.teachers;
                        _this.showDataTable = true;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                        _this.showSearchByEmailForm = false;
                    }  
                })                  
            },
            getTeacherByEmail(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/get_teacher_by_email',this.teacher).then(function(response){
                    if(response.data.response_msg == "No Teacher exists with this information"){
                        _this.displayError = true;
                        _this.showDataTable = false;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                        _this.showSearchByEmailForm = true;
                    }
                    else{
                        _this.displayError = false;
                        _this.teacher = { teacher_id: '', phone_number : '', full_name : ''}
                        _this.teachers = response.data.teachers;
                        _this.showDataTable = true;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                        _this.showSearchByEmailForm = false;
                    }  
                })                  
            },
        },
        created() {
            console.log('VueJS component for adding students.');
        }
    }
</script>

