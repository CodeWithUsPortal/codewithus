<template>
    <div class="container">
        <br/>
         <div v-show="displayError">
            <h6 style="color:red">No Teachers exist for this location.</h6>
        </div>
        <div v-show="displaySuccess">
            <h6 style="color:green">A Class has been added.</h6>
        </div>
        <form @submit.prevent="addClass" enctype="multipart/form-data">
                <h6 style="color:black"><b>Note: </b>To add a Class, you must select a location first.</h6>
                <br/>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" >Location<span class="text-danger">*</span></label>
                    <div class="col-lg-3">      
                        <select  class="option form-control" @change="onChangeOfLocation($event)" v-model="selectedValueOfLocation" required>
                            <option v-for="location in locations" v-bind:key="location.location_id" :value="location.location_id"> {{ location.location_name }}</option>
                        </select>
                    </div>
               </div>        
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" >Class Name <span class="text-danger">*</span></label>
                    <div class="col-lg-3">
                        <input class="form-control" type="text" v-model="taskClassData.taskclass_name"/>
                    </div>
                </div>
               <div class="form-group row">
                    <label class="col-lg-3 col-form-label" >Teacher<span class="text-danger">*</span></label>
                    <div class="col-lg-3">      
                        <select  class="option form-control" @change="onChangeOfTeacher($event)" v-model="selectedValueOfTeacher" required>
                            <option v-for="teacher in teachers" v-bind:key="teacher.teacher_id" :value="teacher.teacher_id"> {{ teacher.teacher_name }}</option>
                        </select>
                    </div>
               </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" >Starts at: <span class="text-danger">*</span></label>
                    <div class="col-lg-3">
                        <input class="form-control" type="datetime-local" v-model="taskClassData.startingDatetime"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" >Ends at: <span class="text-danger">*</span></label>
                    <div class="col-lg-3">
                        <input class="form-control" type="datetime-local" v-model="taskClassData.endingDatetime"/>
                    </div>
                </div>
            
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label"></label>
                    <div class="col-lg-8">
                        <input class="btn btn-primary" type="submit" value="Add New Class" />
                    </div>
                </div>  
        </form>
      
    </div>
</template>

<script>
    export default {
        data(){
            return {
                displayError : false,
                displaySuccess : false,

                taskclass : {taskclass_name: ''},

                location_id : '',
                location: { location_id: '', location_name : ''},
                locations:[],
                selectedValueOfLocation : '',

                teacher_id : '',
                teacher: { teacher_id: '', teacher_name : ''},
                teachers:[],
                selectedValueOfTeacher : '',

                taskClassData: { location_id: '', teacher_id: '', taskclass_name: '', startingDatetime: '', endingDatetime: ''}
            };
        },
        methods:{
            getLocations(){
                var _this = this;
                axios.get('/calendar/get_locations').then(function(response){             
                    _this.locations = response.data.locations; 
                })
            },
            onChangeOfLocation(e){
                this.displaySuccess = false;
                this.location.location_id = event.target.value;
                this.taskClassData.location_id = event.target.value;
                var _this = this;
                axios.post('/getTeachers',this.location).then(function(response){                   
                    if(response.data.response_msg == "No teachers found for this location."){
                        _this.displayError = true;
                    }
                    else{
                        _this.displayError = false;
                        _this.teachers = response.data.teachers; 
                    }      
                })
            },
            onChangeOfTeacher(e){
                this.taskClassData.teacher_id = event.target.value;
            },
            addClass(e){
                e.preventDefault();
                var _this = this;    
                axios.post('/add_task_class',this.taskClassData).then(function(response){
                    _this.taskClassData = { location_id: '', teacher_id: '', taskclass_name: '', startingDatetime: '', endingDatetime: ''}
                    _this.displaySuccess = true;
                    _this.selectedValueOfTeacher = '';
                    _this.selectedValueOfLocation = '';
                })
            },
        },
        created() {
            this.getLocations();
        }
    }
</script>