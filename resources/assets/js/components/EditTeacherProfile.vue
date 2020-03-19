<template>
    <div class="container">
        <h3>Teacher Profile</h3>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="editTeacherProfile" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="teacherProfile.phone_number" />
                    </div>    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="teacherProfile.email" />
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" maxlength = "100" class="form-control" v-model="teacherProfile.notes" />
                    </div>    
                    <input class="btn btn-primary" type="submit" value="Update Profile" />
                </form>
            </div>
        </div>
        <br/><br/>
        <h3>Add new Topic</h3>
        <div v-show="displayTopicAttachmentError">
            <h6 style="color:red">Teacher already has this Topic</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="addTopicToTeacher" enctype="multipart/form-data" >
                    <div class="form-group">
                        <select  class="option form-control" @change="onChangeOfTopic($event)" v-model="selectedValueOfTopic" >
                            <option v-for="topic in topics" v-bind:key="topic.topic_id" :value="topic.topic_id"> {{ topic.topic_name }}</option>
                        </select>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Assign" />
                </form>
            </div>
        </div>
        <br/><br/>
        <h3>Teacher's Topics</h3>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                   
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <td>Teacher's Topics</td> 
                                <td></td>      
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="topic in teacherTopics" v-bind:key="topic.topic_id">
                                <td>{{ topic.topic_name }}</td>
                                <td><button @click="removeTopic(topic.topic_id)" class="btn btn-danger">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br/><br/>
        <h3>Add new Location</h3>
        <div v-show="displayLocationAttachmentError">
            <h6 style="color:red">Teacher already has this location</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <form @submit.prevent="addLocationToTeacher" enctype="multipart/form-data" >
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
            <h6 style="color:red">You cannot delete this location. As Teachers must be attached with atleast one location.</h6>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                   
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <td>Teacher's Locations</td> 
                                <td></td>      
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="location in teacherLocations" v-bind:key="location.location_id">
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
                teacherData : {teacher_id : '', selectedTeacherId : '', selectedLocationId : '', selectedTopicId :''},
                teacherRemoveLocation : {teacher_id: '', selectedLocationId : ''},
                teacherRemoveTopic : {teacher_id: '', selectedTopicId: ''},
                locations : [],
                allLocations : [],
                selectedValueOfLocation : '',
                selectedValueOfTopic : '',
                teacherLocations : [],
                teacherTopics : [],
                displayLocationAttachmentError : false,
                displayTopicAttachmentError : false,
                displayLocationRemoveError : false,
                topics : [],
                teacherProfile : {},
            }
        },
        props: ['teacher'],
        methods:{
            getTopics(){
                var _this = this;
                axios.get('/get_topics').then(function(response){     
                    _this.topics = response.data.topics; 
                })
            },
            getAllLocations(){
                var _this = this;
                axios.get('/get_all_locations').then(function(response){     
                    _this.allLocations = response.data.locations; 
                })
            },
            getTeacherProfile(){
                var _this = this; 
                axios.post('/get_teacher_profile',this.teacherData).then(function(response){
                    _this.teacherProfile = response.data.profile;
                    _this.displayLocationRemoveError = false;
                })   
            },
            getAssignedTopics(){
                var _this = this; 
                axios.post('/get_teacher_topic',this.teacherData).then(function(response){
                    _this.teacherTopics = response.data.teacher_topics;
                }) 
            },
            getAssignedLocations(){
                var _this = this; 
                axios.post('/get_teacher_location',this.teacherData).then(function(response){
                    _this.teacherLocations = response.data.teacher_locations;
                }) 
            },
            onChangeOfTopic(e){
                this.teacherData.selectedTopicId = event.target.value;
            },
            editTeacherProfile(){
                var _this = this;
                axios.post('/edit_teacher_profile',this.teacherProfile).then(function(response){
                    _this.selectedValueOfTopic = '';
                    _this.getTeacherProfile();
                }) 
            },
            removeTopic(topicId){
                var _this = this;
                if(confirm('Are you sure?')){
                    _this.teacherRemoveTopic.selectedTopicId = topicId;
                   
                    axios.post('/remove_teacher_topic',_this.teacherRemoveTopic).then(function(response){
                        if(response.data.response_msg == "You can not delete this topic."){
                           //
                        }
                        else{
                            _this.getAssignedTopics(); 
                        }
                        _this.teacherRemoveTopic.selectedTopicId = "";
                    })  
                }
            },
            removeLocation(locationId){
                var _this = this;
                if(confirm('Are you sure?')){
                    _this.teacherRemoveLocation.selectedLocationId = locationId;
                   
                    axios.post('/remove_teacher_location',_this.teacherRemoveLocation).then(function(response){
                        if(response.data.response_msg == "You can not delete this location."){
                            _this.displayLocationRemoveError = true;
                        }
                        else{
                            _this.displayLocationRemoveError = false;
                            _this.getAssignedLocations(); 
                        }
                        _this.teacherRemoveLocation.selectedLocationId = "";
                    })  
                }
            },
            onChangeOfLocationSelection(e){
                this.teacherData.selectedLocationId = event.target.value;
            },
            addLocationToTeacher(){
                var _this = this;
                _this.displayLocationRemoveError = false;
                axios.post('/add_teacher_location',this.teacherData).then(function(response){
                    if(response.data.response_msg == "You cannot add duplicate location."){
                        _this.displayLocationAttachmentError = true;
                    }
                    else{
                        _this.displayLocationAttachmentError = false;
                    }
                    _this.selectedValueOfTopic = '';
                    _this.selectedValueOfLocation = '';
                    _this.teacherData.selectedLocationId;
                    _this.getAssignedLocations();
                }) 
            },
            addTopicToTeacher(){
                var _this = this;
                _this.displayLocationRemoveError = false;
                axios.post('/add_teacher_topic',this.teacherData).then(function(response){
                    if(response.data.response_msg == "You cannot add duplicate topic."){
                        _this.displayTopicAttachmentError = true;
                    }
                    else{
                        _this.displayTopicAttachmentError = false;
                    }
                    _this.selectedValueOfTopic = '';
                    _this.teacherData.selectedTopicId;
                    _this.getAssignedTopics();
                }) 
            },
        },
        created(){
            this.teacherData.teacher_id = this.teacher;
            this.teacherData.selectedTeacherId =this.teacher;
            this.teacherRemoveLocation.teacher_id = this.teacher;
            this.getTopics();
            this.getAllLocations();
            this.getTeacherProfile();
            this.getAssignedLocations(); 
            this.getAssignedTopics();
        }
    }
</script>
