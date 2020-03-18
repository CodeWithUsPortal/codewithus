<template>
<div class="container">
  <div v-show="displayError">
    <h6 style="color:red">No scheduled classes are found for this Location.</h6>
  </div><br/>
  <div class="form-group row">
    <label class="col-lg-3 col-form-label" >Select a Location<span class="text-danger">*</span></label>
    <div class="col-lg-3">      
        <select  class="option form-control" @change="onChangeOfLocation($event)" v-model="selectedValueOfLocation" required>
            <option v-for="location in locations" v-bind:key="location.location_id" :value="location.location_id"> {{ location.location_name }}</option>
        </select>
    </div>
  </div> 

  <vue-cal :events="events" style="height: 950px" :time="false" 
         :disable-views="['years', 'year']"/>
</div>
</template>

<script>
export default {
  name: 'calendar',
  components:{
    'vue-cal': vuecal
  },
  data(){
     return {
        displayError : false,

        location_id : '',
        location: { location_id: '', location_name : ''},
        locations:[],
        selectedValueOfLocation : '',

        events: [],
     };
  },
  methods:{
    getLocations(){
      var _this = this;
      axios.get('/calendar/get_locations').then(function(response){         
           if(response.data.response_msg == "No Locations found."){
              _this.displayError = true;
            }
            else{
              _this.locations = response.data.locations; 
            }
      })
    },
    onChangeOfLocation(e){
      this.location.location_id = event.target.value;
      var _this = this;
      _this.getEvents();
    },
    getEvents(){
      var _this = this;
      axios.post('/calendar/get_calendar_events',this.location).then(function(response){                   
          if(response.data.response_msg == "No scheduled classes are found for this Location."){
              _this.displayError = true;
              _this.events = [];
          }
          else{
            _this.displayError = false;
            _this.events = response.data.events; 
          }
         
      })
    }, // End of Get Data Method

  },
  created() {
    console.log('VueJS component created.');
    this.getLocations();
  }
}
</script>
