<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div v-show="missingTimeSlotSelectionError">
                    <p style="color:red">*You have to pick a tiem slot.</p>
                </div>
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
                        <input placeholder="Student's Age" type="number" min="3" max="20" class="form-control" v-model="studentData.student_age" required/>
                    </div>     
                    <div class="form-group">
                        <input type="text" placeholder="Phone Number" maxlength = "100" class="form-control" v-model="studentData.phone_number" required/>
                    </div>    
                    <div class="form-group">
                        <input type="text" placeholder="Email" maxlength = "100" class="form-control" v-model="studentData.email" required/>
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Postal Address" rows="4" class="form-control" v-model="studentData.address" required/>
                    </div>
                    <div class="form-group">
                        <label>Topic of Interest </label>
                        <select class="option form-control" @change="onChangeOfTopic($event)" v-model="selectedValueOfTopic" required>
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
                        <div class="card-header bg-white">
                            <h5 class="card-title text-black">Pick a suitable time:</h5>
                        </div>
                        <div >
                            <div class="accordion" id="accordionExample">
                            <div class="card mb-2" v-show="ifMondayIsAvailable">
                                <div class="card-header p-1" id="headingOne">
                                <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="icon-question text-primary mr-1"></i>
                                        Monday | Next Availability {{ MondayAvailabilityTitle }}
                                    </button>
                                </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisMondayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisMondayWeekDate }}</b></label>
                                            <div v-for="mondayAvailableTimeSlot in thisMondayAvailableTimeSlots" v-bind:key="mondayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="mondayAvailableTimeSlot.timeslot_datetime" /> {{ mondayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextMondayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextMondayWeekDate }}</b></label>
                                            <div v-for="mondayAvailableTimeSlot in nextMondayAvailableTimeSlots" v-bind:key="mondayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="mondayAvailableTimeSlot.timeslot_datetime" /> {{ mondayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifTuesdayIsAvailable">
                                <div class="card-header p-1" id="headingTwo">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="icon-question text-primary mr-1"></i>
                                        Tuesday | Next Availability {{ TuesdayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisTuesdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisTuesdayWeekDate }}</b></label>
                                            <div v-for="tuesdayAvailableTimeSlot in thisTuesdayAvailableTimeSlots" v-bind:key="tuesdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="tuesdayAvailableTimeSlot.timeslot_datetime" /> {{ tuesdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextTuesdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextTuesdayWeekDate }}</b></label>
                                            <div v-for="tuesdayAvailableTimeSlot in nextTuesdayAvailableTimeSlots" v-bind:key="tuesdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="tuesdayAvailableTimeSlot.timeslot_datetime" /> {{ tuesdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifWednesdayIsAvailable">
                                <div class="card-header p-1" id="headingThree">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="icon-question text-primary mr-1"></i>
                                        Wednesday | Next Availability {{ WednesdayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisWednesdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisWednesdayWeekDate }}</b></label>
                                            <div v-for="wednesdayAvailableTimeSlot in thisWednesdayAvailableTimeSlots" v-bind:key="wednesdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="wednesdayAvailableTimeSlot.timeslot_datetime" /> {{ wednesdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextWednesdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextWednesdayWeekDate }}</b></label>
                                            <div v-for="wednesdayAvailableTimeSlot in nextWednesdayAvailableTimeSlots" v-bind:key="wednesdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="wednesdayAvailableTimeSlot.timeslot_datetime" /> {{ wednesdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifThursdayIsAvailable">
                                <div class="card-header p-1" id="headingFour">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="icon-question text-primary mr-1"></i>
                                        Thursday | Next Availability {{ ThursdayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                <div class="card-body">
                                        <div v-show="thisThursdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisThursdayWeekDate }}</b></label>
                                            <div v-for="thursdayAvailableTimeSlot in thisThursdayAvailableTimeSlots" v-bind:key="thursdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="thursdayAvailableTimeSlot.timeslot_datetime" /> {{ thursdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextThursdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextThursdayWeekDate }}</b></label>
                                            <div v-for="thursdayAvailableTimeSlot in nextThursdayAvailableTimeSlots" v-bind:key="thursdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="thursdayAvailableTimeSlot.timeslot_datetime" /> {{ thursdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifFridayIsAvailable">
                                <div class="card-header p-1" id="headingFive">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"><i class="icon-question text-primary mr-1"></i>
                                        Friday | Next Availability {{ FridayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisFridayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisFridayWeekDate }}</b></label>
                                            <div v-for="fridayAvailableTimeSlot in thisFridayAvailableTimeSlots" v-bind:key="fridayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="fridayAvailableTimeSlot.timeslot_datetime" /> {{ fridayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextFridayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextFridayWeekDate }}</b></label>
                                            <div v-for="fridayAvailableTimeSlot in nextFridayAvailableTimeSlots" v-bind:key="fridayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="fridayAvailableTimeSlot.timeslot_datetime" /> {{ fridayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifSaturdayIsAvailable">
                                <div class="card-header p-1" id="headingSix">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix"><i class="icon-question text-primary mr-1"></i>
                                        Saturday | Next Availability {{ SaturdayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisSaturdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisSaturdayWeekDate }}</b></label>
                                            <div v-for="saturdayAvailableTimeSlot in thisSaturdayAvailableTimeSlots" v-bind:key="saturdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="saturdayAvailableTimeSlot.timeslot_datetime" /> {{ saturdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextSaturdayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextSaturdayWeekDate }}</b></label>
                                            <div v-for="saturdayAvailableTimeSlot in nextSaturdayAvailableTimeSlots" v-bind:key="saturdayAvailableTimeSlot.timeslot_datetime">
                                                <input name="radioInput" type="radio" v-model="selectedDateTime" v-bind:value="saturdayAvailableTimeSlot.timeslot_datetime" /> {{ saturdayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2" v-show="ifSundayIsAvailable">
                                <div class="card-header p-1" id="headingSeven">
                                    <h5 class="mb-0 text-black">
                                    <button class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"><i class="icon-question text-primary mr-1"></i>
                                        Sunday | Next Availability {{ SundayAvailabilityTitle }}
                                    </button>
                                    </h5>
                                </div>
                                <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div v-show="thisSundayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ thisSundayWeekDate }}</b></label>
                                            <div v-for="sundayAvailableTimeSlot in thisSundayAvailableTimeSlots" v-bind:key="sundayAvailableTimeSlot.timeslot_datetime">
                                                <input type="radio" v-model="selectedDateTime" v-bind:value="sundayAvailableTimeSlot.timeslot_datetime" /> {{ sundayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>
                                        <div v-show="nextSundayTimeSlotsAvailable">
                                            <label style="color:black"><b>{{ nextSundayWeekDate }}</b></label>
                                            <div v-for="sundayAvailableTimeSlot in nextSundayAvailableTimeSlots" v-bind:key="sundayAvailableTimeSlot.timeslot_datetime">
                                                <input type="radio" v-model="selectedDateTime" v-bind:value="sundayAvailableTimeSlot.timeslot_datetime"/> {{ sundayAvailableTimeSlot.timeslot_time }}
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    </div> 
                    <div class="form-group">
                        <label>What is your main goal for signing up for a coding class?</label>
                        <textarea rows="4" class="form-control" v-model="studentData.expectations" required/>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Register" />
                </form>
                <div v-show="thankyouForm">
                    <h4 style="color:black">THANKYOU !</h4>
                    <h6>You have successfully subscribed for a Free Session for {{ studentNameForBookedFreeSession }} at {{ timeslotForBookedFreeSession }}.</h6>
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
                               address : '', topic_id : '', ad_source : '', time_slot: '', expectations : ''},
                displayFreeSessionForm1 : true,
                displayFreeSessionForm2 : false,
                thankyouForm : false,

                studentNameForBookedFreeSession : '',
                timeslotForBookedFreeSession : '',
                freeSessionBookedDate : '',
                missingTimeSlotSelectionError : false,

                thisMondayWeekDate : '',
                thisTuesdayWeekDate : '',
                thisWednesdayWeekDate : '',
                thisThursdayWeekDate : '',
                thisFridayWeekDate : '',
                thisSaturdayWeekDate : '',
                thisSundayWeekDate : '',

                nextMondayWeekDate : '',
                nextTuesdayWeekDate : '',
                nextWednesdayWeekDate : '',
                nextThursdayWeekDate : '',
                nextFridayWeekDate : '',
                nextSaturdayWeekDate : '',
                nextSundayWeekDate : '',

                MondayAvailabilityTitle : 'Monday',
                TuesdayAvailabilityTitle : 'Tuesday',
                WednesdayAvailabilityTitle : 'Wednesday',
                ThursdayAvailabilityTitle : 'Thursday',
                FridayAvailabilityTitle : 'Friday',
                SaturdayAvailabilityTitle : 'Saturday',
                SundayAvailabilityTitle : 'Sunday',

                ifMondayIsAvailable : false,
                thisMondayTimeSlotsAvailable: false,
                nextMondayTimeSlotsAvailable: false,
                ifTuesdayIsAvailable : false,
                thisTuesdayTimeSlotsAvailable: false,
                nextTuesdayTimeSlotsAvailable: false,
                ifWednesdayIsAvailable : false,
                thisWednesdayTimeSlotsAvailable: false,
                nextWednesdayTimeSlotsAvailable: false,
                ifThursdayIsAvailable : false,
                thisThursdayTimeSlotsAvailable: false,
                nextThursdayTimeSlotsAvailable: false,
                ifFridayIsAvailable : false,
                thisFridayTimeSlotsAvailable: false,
                nextFridayTimeSlotsAvailable: false,
                ifSaturdayIsAvailable : false,
                thisSaturdayTimeSlotsAvailable: false,
                nextSaturdayTimeSlotsAvailable: false,
                ifSundayIsAvailable : false,
                thisSundayTimeSlotsAvailable: false,
                nextSundayTimeSlotsAvailable: false,
            
                thisMondayAvailableTimeSlots : [],
                thisTuesdayAvailableTimeSlots : [],
                thisWednesdayAvailableTimeSlots : [],
                thisThursdayAvailableTimeSlots : [],
                thisFridayAvailableTimeSlots : [],
                thisSaturdayAvailableTimeSlots : [],
                thisSundayAvailableTimeSlots : [],

                nextMondayAvailableTimeSlots : [],
                nextTuesdayAvailableTimeSlots : [],
                nextWednesdayAvailableTimeSlots : [],
                nextThursdayAvailableTimeSlots : [],
                nextFridayAvailableTimeSlots : [],
                nextSaturdayAvailableTimeSlots : [],
                nextSundayAvailableTimeSlots : [],

                studentData : {location_id :'1'},
                selectedDateTime : '',
            };
        },
       methods:{
            getLocations(){
                var _this = this;
                axios.get('/get_free_session_locations').then(function(response){         
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
                axios.get('/get_free_session_topics').then(function(response){         
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
                axios.post('/get_available_time_slots', _this.studentData).then(function(response){         
                    if(response.data.response_msg == "No Available Slots found."){
                        //
                    }
                    else{
                        _this.displayFreeSessionForm1 = false;
                        _this.displayFreeSessionForm2 = true;
                        _this.thisMondayAvailableTimeSlots = response.data.thisMondayAvailableTimeSlots; 
                        _this.thisTuesdayAvailableTimeSlots = response.data.thisTuesdayAvailableTimeSlots; 
                        _this.thisWednesdayAvailableTimeSlots = response.data.thisWednesdayAvailableTimeSlots; 
                        _this.thisThursdayAvailableTimeSlots = response.data.thisThursdayAvailableTimeSlots; 
                        _this.thisFridayAvailableTimeSlots = response.data.thisFridayAvailableTimeSlots; 
                        _this.thisSaturdayAvailableTimeSlots = response.data.thisSaturdayAvailableTimeSlots; 
                        _this.thisSundayAvailableTimeSlots = response.data.thisSundayAvailableTimeSlots;
                        
                        _this.nextMondayAvailableTimeSlots = response.data.nextMondayAvailableTimeSlots; 
                        _this.nextTuesdayAvailableTimeSlots = response.data.nextTuesdayAvailableTimeSlots; 
                        _this.nextWednesdayAvailableTimeSlots = response.data.nextWednesdayAvailableTimeSlots; 
                        _this.nextThursdayAvailableTimeSlots = response.data.nextThursdayAvailableTimeSlots; 
                        _this.nextFridayAvailableTimeSlots = response.data.nextFridayAvailableTimeSlots; 
                        _this.nextSaturdayAvailableTimeSlots = response.data.nextSaturdayAvailableTimeSlots; 
                        _this.nextSundayAvailableTimeSlots = response.data.nextSundayAvailableTimeSlots;
                    
                        if(_this.thisMondayAvailableTimeSlots.length > 0){
                           _this.ifMondayIsAvailable = true;
                           _this.thisMondayTimeSlotsAvailable = true;
                           _this.thisMondayWeekDate = response.data.thisMondayWeekDate;
                        }
                        if(_this.thisTuesdayAvailableTimeSlots.length > 0){
                            _this.ifTuesdayIsAvailable = true;
                            _this.thisTuesdayTimeSlotsAvailable = true;
                            _this.thisTuesdayWeekDate = response.data.thisTuesdayWeekDate;
                        }
                        if(_this.thisWednesdayAvailableTimeSlots.length > 0){
                           _this.ifWednesdayIsAvailable = true;
                           _this.thisWednesdayTimeSlotsAvailable = true;
                           _this.thisWednesdayWeekDate = response.data.thisWednesdayWeekDate;
                        }
                        if(_this.thisThursdayAvailableTimeSlots.length > 0){
                            _this.ifThursdayIsAvailable = true;
                            _this.thisThursdayTimeSlotsAvailable = true;
                            _this.thisThursdayWeekDate = response.data.thisThursdayWeekDate;
                        }
                        if(_this.thisFridayAvailableTimeSlots.length > 0){
                           _this.ifFridayIsAvailable = true;
                           _this.thisFridayTimeSlotsAvailable = true;
                           _this.thisFridayWeekDate = response.data.thisFridayWeekDate;
                        }
                        if(_this.thisSaturdayAvailableTimeSlots.length > 0){
                            _this.ifSaturdayIsAvailable = true;
                            _this.thisSaturdayTimeSlotsAvailable = true;
                            _this.thisSaturdayWeekDate = response.data.thisSaturdayWeekDate;
                        }
                        if(_this.thisSundayAvailableTimeSlots.length > 0){
                           _this.ifSundayIsAvailable = true;
                           _this.thisSundayTimeSlotsAvailable = true;
                           _this.thisSundayWeekDate = response.data.thisSundayWeekDate;
                        }


                        if(_this.nextMondayAvailableTimeSlots.length > 0){
                           _this.MondayAvailabilityTitle = response.data.mondayAvailabilityTitle;
                           _this.ifMondayIsAvailable = true;
                           _this.nextMondayTimeSlotsAvailable = true;
                           _this.nextMondayWeekDate = response.data.nextMondayWeekDate;
                        }
                        if(_this.nextTuesdayAvailableTimeSlots.length > 0){
                            _this.TuesdayAvailabilityTitle = response.data.tuesdayAvailabilityTitle;
                            _this.ifTuesdayIsAvailable = true;
                            _this.nextTuesdayTimeSlotsAvailable = true;
                            _this.nextTuesdayWeekDate = response.data.nextTuesdayWeekDate;
                        }
                        if(_this.nextWednesdayAvailableTimeSlots.length > 0){
                           _this.WednesdayAvailabilityTitle = response.data.wednesdayAvailabilityTitle;
                           _this.ifWednesdayIsAvailable = true;
                           _this.nextWednesdayTimeSlotsAvailable = true;
                           _this.nextWednesdayWeekDate = response.data.nextWednesdayWeekDate;
                        }
                        if(_this.nextThursdayAvailableTimeSlots.length > 0){
                            _this.ThursdayAvailabilityTitle = response.data.thursdayAvailabilityTitle;
                            _this.ifThursdayIsAvailable = true;
                            _this.nextThursdayTimeSlotsAvailable = true;
                            _this.nextThursdayWeekDate = response.data.nextThursdayWeekDate;
                        }
                        if(_this.nextFridayAvailableTimeSlots.length > 0){
                           _this.FridayAvailabilityTitle = response.data.fridayAvailabilityTitle;
                           _this.ifFridayIsAvailable = true;
                           _this.nextFridayTimeSlotsAvailable = true;
                           _this.nextFridayWeekDate = response.data.nextFridayWeekDate;
                        }
                        if(_this.nextSaturdayAvailableTimeSlots.length > 0){
                            _this.SaturdayAvailabilityTitle = response.data.saturdayAvailabilityTitle;
                            _this.ifSaturdayIsAvailable = true;
                            _this.nextSaturdayTimeSlotsAvailable = true;
                            _this.nextSaturdayWeekDate = response.data.nextSaturdayWeekDate;
                        }
                        if(_this.nextSundayAvailableTimeSlots.length > 0){
                           _this.SundayAvailabilityTitle = response.data.sundayAvailabilityTitle;
                           _this.ifSundayIsAvailable = true;
                           _this.nextSundayTimeSlotsAvailable = true;
                           _this.nextSundayWeekDate = response.data.nextSundayWeekDate;
                        } 
                    }
                })
            },
            addFreeSession(){   
                var _this = this;
                if(_this.selectedDateTime == "" || _this.selectedDateTime == null){
                    _this.missingTimeSlotSelectionError = true;
                }
                else{
                    _this.missingTimeSlotSelectionError = false;
                    _this.studentData.time_slot = _this.selectedDateTime;
                axios.post('/add_free_session', _this.studentData).then(function(response){         
                    if(response.data.response_msg == "Error"){
                        //
                    }
                    else{
                        _this.displayFreeSessionForm1 = false;
                        _this.displayFreeSessionForm2 = false;
                        _this.thankyouForm = true;
                        _this.studentNameForBookedFreeSession = _this.studentData.student_name;
                        _this.timeslotForBookedFreeSession = _this.studentData.time_slot;
                        _this.studentData = {location_id :'', student_name :'', student_age : '', phone_number : '', email :'',
                                             time_slot:'',topic_id : '', ad_source : '', time_slot_id : '', expectations : ''};
                        _this.selectedValueOfLocation = "";
                        _this.selectedValueOfTopic = "";
                        _this.selectedValueOfTimeSlot = "";
                    }
                })
                }
            }
        },
       created() {
           this.getLocations();
           this.getTopics();
           console.log('VueJS component created.');
        } 
    }
</script>
