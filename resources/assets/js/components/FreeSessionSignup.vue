<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <form v-show="displayFreeSessionForm1" @submit.prevent="getAvailableTimeSlots" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label>Pick a Location</label>
                        <select  class="option form-control" @change="onChangeOfLocation($event)" v-model="selectedValueOfLocation" required>
                            <option v-for="location in locations" v-bind:key="location.id" :value="location.id"> {{ location.location_name }}</option>
                        </select>
                    </div> 
                    <div class="form-group">
                        <input type="text" placeholder="Student's Name" maxlength = "100" class="form-control" v-model="studentData.student_name" required/>
                    </div>
                    <div class="form-group">
                        <label>Student's Age</label>
                        <input type="number" min="3" max="20" class="form-control" v-model="studentData.student_age" required/>
                    </div>     
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" placeholder="Phone Number" maxlength = "100" class="form-control" v-model="studentData.phone_number" required/>
                    </div>    
                    <div class="form-group">
                        <input type="text" placeholder="Email" maxlength = "100" class="form-control" v-model="studentData.email" required/>
                    </div>
                    <div class="form-group">
                        <label>Topic of Interest </label>
                        <select  class="option form-control" @change="onChangeOfTopic($event)" v-model="selectedValueOfTopic" required>
                            <option v-for="topic in topics" v-bind:key="topic.id" :value="topic.id"> {{ topic.name }}</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <input type="text" placeholder="How did you hear about us? Google, Friend, etc" maxlength = "100" class="form-control" v-model="studentData.ad_source" required/>
                    </div>    
                    <input class="btn btn-primary" type="submit" value="See Available Time Slots" />
                </form>

                <form v-show="displayFreeSessionForm2" @submit.prevent="addFreeSession" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label>Pick a Time Slot</label>
                        <select  class="option form-control" @change="onChangeOfTimeSlot($event)" v-model="selectedValueOfTimeSlot" required>
                            <option v-for="availableTimeSlot in availableTimeSlots" v-bind:key="availableTimeSlot.timeslot_id" :value="availableTimeSlot.timeslot_id"> {{ availableTimeSlot.timeslot_datetime }}</option>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label>What is your main goal for signing up for a coding class?</label>
                        <textarea rows="4" class="form-control" v-model="studentData.expectations" required/>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Register" />
                </form>
                <div v-show="thankyouForm">
                    <h2>THANKYOU !</h2>
                    <h5>You have successfully subscribed for a Free Session for {{ studentNameForFreeSession }}</h5>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                locations : [],
                selectedLocationId : '',
                selectedValueOfLocation : '',
                topics : [],
                selectedTopicId : '',
                selectedValueOfTopic : '',
                availableTimeSlots : [],
                selectedTimeSlotId : '',
                selectedValueOfTimeSlot : '',
                studentData : {location_id :'', student_name :'', student_age : '', phone_number : '', email :'',
                               topic_id : '', ad_source : '', time_slot_id : '', expectations : ''},
                displayFreeSessionForm1 : true,
                displayFreeSessionForm2 : false,
                thankyouForm : false,

                studentNameForFreeSession : '',
                freeSessionBookedDate : '',
            };
        },
       methods:{
            getLocations(){
                var _this = this;
                axios.get('/codewithus/get_free_session_locations').then(function(response){         
                    if(response.data.response_msg == "No Locations found."){
                        //
                    }
                    else{
                        _this.locations = response.data.locations; 
                    }
                })
            },
            getTopics(){
                var _this = this;
                axios.get('/codewithus/get_free_session_topics').then(function(response){         
                    if(response.data.response_msg == "No Topics found."){
                        //
                    }
                    else{
                        _this.topics = response.data.topics; 
                    }
                })
            },
            onChangeOfLocation(e){
                this.studentData.location_id = event.target.value;
                this.selectedLocationId = event.target.value;
            },
            onChangeOfTopic(e){
                this.studentData.topic_id = event.target.value;
                this.selectedTopicId = event.target.value;
            },
            onChangeOfTimeSlot(e){
                this.studentData.time_slot_id = event.target.value;
                this.selectedValueOfTimeSlot = event.target.value;
            },
            getAvailableTimeSlots(e){
                var _this = this;
                axios.post('/codewithus/get_available_time_slots', _this.studentData).then(function(response){         
                    if(response.data.response_msg == "No Available Slots found."){
                        //
                    }
                    else{
                        _this.displayFreeSessionForm1 = false;
                        _this.displayFreeSessionForm2 = true;
                        _this.availableTimeSlots = response.data.availableTimeSlots; 
                    }
                })
            },
            addFreeSession(){
                var _this = this;
                axios.post('/codewithus/add_free_session', _this.studentData).then(function(response){         
                    if(response.data.response_msg == "Error"){
                        //
                    }
                    else{
                        _this.displayFreeSessionForm1 = false;
                        _this.displayFreeSessionForm2 = false;
                        _this.thankyouForm = true;
                        _this.studentNameForFreeSession = _this.studentData.student_name;
                        
                        _this.studentData = {location_id :'', student_name :'', student_age : '', phone_number : '', email :'',
                                             topic_id : '', ad_source : '', time_slot_id : '', expectations : ''};
                        _this.selectedValueOfLocation = "";
                        _this.selectedValueOfTopic = "";
                        _this.selectedValueOfTimeSlot = "";
                    }
                })
            }
        },
       created() {
           this.getLocations();
           this.getTopics();
           console.log('VueJS component created.');
        } 
    }
</script>
