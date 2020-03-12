<template>
    <div class="container">
        <br/>
        <h4>Search Students by : </h4>
        <input class="btn btn-primary" type="button" @click="displaySearchByPhoneNumberForm" value="Phone Number"/>
        <input class="btn btn-primary" type="button" @click="displaySearchByFullNameForm" value="Full Name"/>
        <br/><br/><br/>
        <div v-show="displayError">
            <h6 style="color:red">No Student exists with this record</h6>
        </div>
        <div v-show="displaySuccess">
            <h6 style="color:green">Student are assigned to classes</h6>
        </div>
        
        <form v-show="showSearchByPhoneNumberForm" @submit.prevent="getStudentByPhoneNumber" enctype="multipart/form-data">
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" maxlength = "100" class="form-control" id="phone_number" placeholder="Phone Number" v-model="student.phone_number" required/>
            </div>   
            <input class="btn btn-primary" type="submit" value="search" />
        </form>
        <form v-show="showSearchByFullNameForm" @submit.prevent="getStudentByFullName" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" maxlength = "100" class="form-control" id="full_name" placeholder="Full Name" v-model="student.full_name" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="search" />
        </form>
        <br/><br/>
         <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Students </h5></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="student in students" v-bind:key="student.student_id">
                    <td><a :href="student.link_to_profile">{{ student.student_name }}</a></td>
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
                
                student_id : '',
                student: { selectedDate: '', student_id: '', phone_number : '', full_name : ''},
                students:[],
                selectedValueOfStudent : '',

                selectedValueOfStudent : '',
           
                displayError : false,
                displaySuccess : false,

                studentData : { selectedStudentId : '', selectedClassId : ''},
            };
        },
        methods:{
            displaySearchByPhoneNumberForm(){
                this.showDataTable = false;
                this.showSearchByPhoneNumberForm = true;
                this.showSearchByFullNameForm = false;
                this.displaySuccess = false;
            },
            displaySearchByFullNameForm(){
                this.showDataTable = false;
                this.showSearchByPhoneNumberForm = false;
                this.showSearchByFullNameForm = true;
                this.displaySuccess = false;
            },
            getStudentByFullName(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/codewithus/get_student_by_fullName',this.student).then(function(response){
                    if(response.data.response_msg === "No Student exists with this information"){
                        _this.displayError = true;
                        _this.showDataTable = false;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = true;
                    }
                    else{
                        _this.displayError = false;
                        _this.student = { student_id: '', phone_number : '', full_name : ''}
                        _this.students = response.data.students;
                        _this.showDataTable = true;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                    }  
                })                  
            },
            getStudentByPhoneNumber(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/codewithus/get_student_by_phoneNumber',this.student).then(function(response){
                    if(response.data.response_msg == "No Student exists with this information"){
                        _this.displayError = true;
                        _this.showDataTable = false;
                        _this.showSearchByPhoneNumberForm = true;
                        _this.showSearchByFullNameForm = false;
                    }
                    else{
                        _this.displayError = false;
                        _this.student = { student_id: '', phone_number : '', full_name : ''}
                         _this.students = response.data.students;
                         debugger;
                        _this.showDataTable = true;
                        _this.showSearchByPhoneNumberForm = false;
                        _this.showSearchByFullNameForm = false;
                    }  
                })                  
            },
        },
        created() {
            console.log('VueJS component for adding students.');
        }
    }
</script>

