<template>
    <div class="container">
        <h3>Student Profile</h3>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="editStudentProfile" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="studentProfile.phone_number" />
                    </div>    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="studentProfile.email" />
                    </div>
                    <div class="form-group">
                        <label>Student's Main Topic : <b style="color:black">{{studentProfile.topic}}</b></label>
                        <select  class="option form-control" @change="onChangeOfTopic($event)" v-model="selectedValueOfTopic" >
                            <option v-for="topic in topics" v-bind:key="topic.topic_id" :value="topic.topic_id"> {{ topic.topic_name }}</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="studentProfile.notes" />
                    </div>    
                    <input class="btn btn-primary" type="submit" value="Update Profile" />
                </form>
            </div>
        </div>
        <br/><br/>
        <h3>Assign new class</h3>
        <div v-show="displayNoClassError">
            <h6 style="color:red">No Classes exist on this date</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="addStudentToClasses" enctype="multipart/form-data" >
                     <div class="form-group">
                        <label>Date/Day</label>    
                        <input type="date" class="form-control" id="class_date" name="class_date" v-model="studentData.selectedDate" @change="dateChanged($event)" />
                    </div>
                    <div class="form-group">
                        <select class="option form-control" @change="onChangeOfTaskClass($event)" v-model="selectedValueOfTaskClass" required>
                            <option v-for="taskclass in allTaskClasses" v-bind:key="taskclass.taskclass_id" :value="taskclass.taskclass_id"> {{ taskclass.taskclass_name }}</option>
                        </select>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Assign" />
                </form>
            </div>
        </div>
        <br/><br/>

        <h3>Assigned Classes</h3>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                   
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Date</td> 
                                <td>Time</td> 
                                <td></td>      
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="taskClass in taskClasses" v-bind:key="taskClass.taskclass_id">
                                <td>{{ taskClass.taskclass_name }}</td>
                                <td>{{ taskClass.taskclass_date }}</td>
                                <td>{{ taskClass.taskclass_time }}</td>
                                <td><button @click="unAssignStudent(taskClass)" class="btn btn-danger">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
         <br/><br/>
        <h3>Add new Location</h3>
        <div v-show="displayLocationAttachmentError">
            <h6 style="color:red">Student already has this location</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="addLocationToStudent" enctype="multipart/form-data" >
                    <div class="form-group">
                        <select class="option form-control" @change="onChangeOfLocationSelection($event)" v-model="selectedValueOfLocation" required>
                            <option v-for="location in allLocations" v-bind:key="location.location_id" :value="location.location_id"> {{ location.location_name }}</option>
                        </select>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Assign" />
                </form>
            </div>
        </div>
        <br/><br/>
        <h3>Assigned Locations</h3>
        <div v-show="displayLocationRemoveError">
            <h6 style="color:red">You cannot delete this location. As Students must be attached with atleast one location.</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                   
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <td>Student's Locations</td> 
                                <td></td>      
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="location in studentLocations" v-bind:key="location.location_id">
                                <td>{{ location.location_name }}</td>
                                <td><button @click="removeLocation(location.location_id)" class="btn btn-danger">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                studentData : {student_id : '', selectedDate: '', selectedStudentId : '', selectedClassId : '', selectedLocationId : ''},
                studentRemoveLocation : {student_id: '', selectedLocationId : ''},
                locations : [],
                allLocations : [],
                selectedValueOfLocation : '',
                studentLocations : [],
                taskClasses : [],
                allTaskClasses : [],
                selectedValueOfTaskClass : '',
                selectedValueOfTopic : '',
                displayNoClassError : false,
                displayLocationAttachmentError : false,
                displayLocationRemoveError : false,
                topics : [],
                studentProfile : {},
            }
        },
        props: ['student'],
        methods:{
            getTopics(){
                var _this = this;
                axios.get('/codewithus/get_topics').then(function(response){             
                    _this.topics = response.data.topics; 
                })
            },
            getAllLocations(){
                var _this = this;
                axios.get('/codewithus/get_all_locations').then(function(response){ 
                    _this.allLocations = response.data.locations; 
                })
            },
            getStudentProfile(){
                var _this = this; 
                axios.post('/codewithus/get_student_profile',this.studentData).then(function(response){
                    _this.studentProfile = response.data.profile;
                })   
            },
            getAssignedClasses(){
                var _this = this; 
                axios.post('/codewithus/get_assigned_classes',this.studentData).then(function(response){
                    _this.taskClasses = response.data.taskClasses;
                }) 
            },
            getAssignedLocations(){
                var _this = this; 
                axios.post('/codewithus/get_student_location',this.studentData).then(function(response){
                    _this.studentLocations = response.data.student_locations;
                }) 
            },
            onChangeOfTopic(e){
                this.studentProfile.topic_id = event.target.value;
            },
            editStudentProfile(){
                var _this = this;
                axios.post('/codewithus/edit_student_profile',this.studentProfile).then(function(response){
                    _this.selectedValueOfTopic = '';
                    _this.getStudentProfile();
                }) 
            },
            removeLocation(locationId){
                var _this = this;
                if(confirm('Are you sure?')){
                    _this.studentRemoveLocation.selectedLocationId = locationId;
                   debugger;
                    axios.post('/codewithus/remove_student_location',_this.studentRemoveLocation).then(function(response){
                        if(response.data.response_msg == "You can not delete this location."){
                            _this.displayLocationRemoveError = true;
                        }
                        else{
                            _this.displayLocationRemoveError = false;
                            _this.getAssignedLocations(); 
                        }
                        _this.studentRemoveLocation.selectedLocationId = "";
                    })  
                }
            },
            onChangeOfLocationSelection(e){
                this.studentData.selectedLocationId = event.target.value;
            },
            addLocationToStudent(){
                var _this = this;
                _this.displayLocationRemoveError = false;
                axios.post('/codewithus/add_student_location',this.studentData).then(function(response){
                    if(response.data.response_msg == "You cannot add duplicate location."){
                        _this.displayLocationAttachmentError = true;
                    }
                    else{
                        _this.displayLocationAttachmentError = false;
                    }
                    _this.selectedValueOfTopic = '';
                    _this.selectedValueOfLocation = '';
                    _this.studentData.selectedLocationId;
                    _this.getAssignedLocations();
                }) 
            },
            unAssignStudent(data){
               var _this = this;
                axios.post('/codewithus/un_assign_student',data).then(function(response){
                    _this.getAssignedClasses();
                })  
            },
            onChangeOfTaskClass(event){
                this.studentData.selectedClassId  = event.target.value;
            },
            addStudentToClasses(){
                var _this = this; 
                axios.post('/codewithus/add_student_to_class',this.studentData).then(function(response){ 
                   if(response.data.response_msg == "Data saved"){
                        _this.selectedValueOfTaskClass = "";
                        _this.getAssignedClasses();
                    }
                }) 
            },
            getAllAvailableClasses(){
                var _this = this;   
                axios.post('/codewithus/get_classes',this.studentData).then(function(response){
                    if(response.data.response_msg == "No Classes exist for this Information"){
                        _this.allTaskClasses = [];
                        _this.displayNoClassError = true;
                    }
                    else{
                        _this.displayNoClassError = false;
                        _this.allTaskClasses = response.data.classes;
                    }  
                }) 
            },
            dateChanged(event){
                this.studentData.selectedDate = event.target.value;
                this.getAllAvailableClasses();
            },
        },
        created(){
            this.studentData.student_id = this.student;
            this.studentData.selectedStudentId =this.student;
            this.studentRemoveLocation.student_id = this.student;
            this.getTopics();
            this.getStudentProfile();
            this.getAssignedClasses();
            this.getAllAvailableClasses();
            this.getAllLocations();
            this.getAssignedLocations(); 
        }
    }
</script>
