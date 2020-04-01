<template>
    <div class="container">
        <h3>Add Student Form</h3><br/>
        <div class="row">
            <div class="col-md-12 ">
                <div v-show="successMessage">
                   <h5 style="color:green">Student has been added.</h5>
                </div>
                <form @submit.prevent="addStudent" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label>Student's Name</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="student.full_name" />
                    </div>
                    <div v-show="isParentPhoneNumberAvailable" class="form-group">
                        <label>Phone Number</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="student.phone_number" value="student.phone_number" readonly/>
                    </div>
                    <div v-show="isParentPhoneNumberNotAvailable" class="form-group">
                        <label>Phone Number</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="student.phone_number" />
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" v-model="student.dob" />
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <select class="option form-control" @change="onChangeOfLocationSelection($event)" v-model="selectedValueOfLocation" required>
                            <option v-for="location in locations" v-bind:key="location.location_id" :value="location.location_id"> {{ location.location_name }}</option>
                        </select>
                    </div>  
                     <input class="btn btn-primary" type="submit" value="Add Student" /> 
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
        data(){
            return {
                isParentPhoneNumberAvailable : false,
                isParentPhoneNumberNotAvailable : false,
                successMessage : false,
                locations : [],
                selectedValueOfLocation : '',
                student : {user_name:'', full_name:'', phone_number:'', dob:'', email:'', location_id:''},
            };
        },
        methods:{
            getParentsPhoneNumber(){
                var _this = this;      
                axios.get('/get_parents_phoneNumber').then(function(response){   
                    if(response.data.response_msg == "Not a Parent"){
                        _this.isParentPhoneNumberAvailable = false;
                        _this.isParentPhoneNumberNotAvailable = true;
                    }
                    else{
                        _this.student.phone_number = response.data.phoneNumber;
                        _this.isParentPhoneNumberAvailable = true;
                        _this.isParentPhoneNumberNotAvailable = false;
                    }  
                })                  
            },
            getLocations(){
                var _this = this;
                axios.get('/get_locations_for_adding_students').then(function(response){     
                    _this.locations = response.data.locations; 
                }) 
            },
            onChangeOfLocationSelection(e){
                this.successMessage = false;
                this.student.location_id = event.target.value;
            },
            addStudent(e){
                var _this = this;
                e.preventDefault();
                axios.post('/add_student_by_user', this.student).then(function(response){     
                    if(response.data.response_msg == "Student Added"){
                        _this.student = {user_name:'', full_name:'', phone_number:'', dob:'', email:'', location_id:''};
                        _this.selectedValueOfLocation = '';
                        _this.successMessage = true;
                    }
                }) 
            }
        },
        created() {
            this.getParentsPhoneNumber();
            this.getLocations();
            console.log('VueJS component created now.');
        }
    }
</script>
