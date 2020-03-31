<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-body">
                        <form @submit.prevent="sendMessage">
                            <br/>
                            <div class="row">
                                <div class="col-md col-sm-12">
                                    <div class="form-group">
                                        <label>Location<span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="bulk.location" required>
                                            <option disabled selected>Select Location</option>
                                            <option v-for="location in locations" :key="location.id" :value="location.id">{{location.location_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md col-sm-12" v-if="!students">
                                    <div class="form-group">
                                        <label>Topic</label>
                                        <select class="form-control" v-model="bulk.topic">
                                            <option disabled selected>Select Topics</option>
                                            <option v-for="topic in topics" :key="topic.id" :value="topic.id">{{topic.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md col-sm-12" v-if="students">
                                    <div class="form-group">
                                        <label>Age (Separate by comma, ex: 6,7,9)</label>
                                        <input class="form-control" v-model="bulk.age" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" v-model="bulk.message" required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input v-model="bulk.type" type="radio" id="customRadio1" value="sms" name="customRadio" class="custom-control-input" required>
                                    <label class="custom-control-label" for="customRadio1">SMS</label>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <div class="custom-control custom-radio">
                                    <input v-model="bulk.type" type="radio" id="customRadio2" value="email" name="customRadio" class="custom-control-input" required    >
                                    <label class="custom-control-label" for="customRadio2">Email</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Send Message" />
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
                bulk : {},
                locations:[],
                topics:[],
                errors : {}
            }
        },
        computed:{
          students(){
              return window.location.pathname === "/bulk-messages/students";
            }
        },
        created() {
            this.getBulkMessageData();
        },
        methods:{
            sendMessage()
            {
                let _this = this;
                _this.bulk['students'] = _this.students;
                axios.post('/bulk-messages/send-message', _this.bulk).then(function(response)
                {
                    console.log(response);
                })
            },
            getBulkMessageData()
            {
                let _this = this;
                axios.get('/bulk-messages/get-bulk-message-data').then(function(response)
                {
                    _this.locations = response.data.data.locations;
                    _this.topics = response.data.data.topics;
                }).catch()
            }
        }
    }
</script>