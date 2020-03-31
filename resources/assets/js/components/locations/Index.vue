<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Locations</label>
                            <select class="form-control" v-model="location" @change="updateLocation(location)">
                                <option disabled selected>Select Location</option>
                                <option v-for="location in locations" :key="location.id" :value="location">{{location.location_name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row" v-if="message">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-success">{{message}}</p>
                    </div>
                </div>
            </div>
        </div>
        <br v-if="message">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-body">
                        <form @submit.prevent="saveLocation">
                            <br/>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Name<span class="text-danger">*</span></label>
                                        <input class="form-control" v-model="location.location_name" required/>
                                        <small v-if="errorMessage.location_name" id="emailHelp" class="form-text text-danger">{{errorMessage.location_name[0]}}</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Secret Code<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="location.secret_code" required/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Classroom URL (Optional)</label>
                                        <input type="text" class="form-control" v-model="location.classroom_url"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" v-model="location.address" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Show Free Session<span class="text-danger">*</span></label> <br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input required v-model="location.show_free_session" type="radio" :value=1 id="customRadioInline1" name="show_free_session" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadioInline1">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input required v-model="location.show_free_session" type="radio" :value=0 id="customRadioInline3" name="show_free_session" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadioInline3">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Online<span class="text-danger">*</span></label> <br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input required v-model="location.online" type="radio" id="customRadioInline4" :value=1 name="online" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadioInline4">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input required v-model="location.online" type="radio" id="customRadioInline5" :value=0 name="online" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadioInline5">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Save" />
                                <input class="btn btn-danger" @click="reset" type="button" value="Reset" />
                            </div>
                        </form>
                    </div>
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
                location : '',
                message : '',
                errorMessage : []
            }
        },
        created(){
            this.reset();
            this.getLocations();
        },
        computed:{
          isEditable(){
              return this.location !== '';
          }
        },
        methods : {
            updateLocation(location)
            {
                console.log(location);
                this.location = location;
            },
            getLocations()
            {
                let _this = this;
                axios.get('/get-locations', _this.location).then(function(response)
                {
                    _this.locations = response.data.locations;
                })
            },
            saveLocation()
            {
                let _this = this;
                _this.message = '';
                axios.post('/locations/store', _this.location).then(function(response)
                {
                    _this.message = response.data.message ? response.data.message : '';
                    _this.location = {};
                    this.getLocations();
                }).catch(function (error) {
                    _this.errorMessage = error.response.data;
                })
            },
            reset(){
                this.location = {};
                this.message = '';
                this.errorMessage = []
            }
        }
    }
</script>