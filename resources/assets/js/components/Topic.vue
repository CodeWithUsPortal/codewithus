<template>
    <div class="container">
        <br/><h3>Add a new topic</h3><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" maxlength = "253" class="form-control" placeholder="Topic Name" v-model="topic.topic_name" required/>
            </div>
           
            <input class="btn btn-primary" type="submit" value="Add Topic" />
        </form>
        <br/><br/>
          <table class="table">
            <thead>
                <tr>
                    <td><h5>Topics</h5></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="topic in topics" v-bind:key="topic.topic_id">
                    <td>{{ topic.topic_name }}</td>
                    <td><button @click="editData(topic)" class="btn btn-warning">EDIT</button></td>
                    <td><button @click="deleteData(topic.topic_id)" class="btn btn-danger">DELETE</button></td>
                </tr>      
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                topic_id : '',
                topic: { topic_id : '',topic_name : ''},
                topics:[],
                edit : false,  
            }
        },
        methods:{
             getData(){
                var _this = this;
                axios.get('/get_topics').then(function(response){              
                    _this.topics = response.data.topics;  
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){ 
                    axios.post('/add_topic',this.topic).then(function(response){
                        if(response.data.reponse_msg == "Topic cannot be added."){
                            alert("Topic could not be added.");
                        }
                        else{
                            _this.topic = { topic_id : '',topic_name : ''},
                            _this.getData();
                        }  
                    })  
                } 
                else{
                    axios.put('/topic/edit/'+_this.topic_id,this.topic).then(function(response){
                        _this.topic = { topic_id : '',topic_name : ''};
                        _this.edit = false;
                        _this.getData(); 
                    })
                }
            },
            editData(topic){
                var _this = this;
                _this.edit = true;
                _this.topic_id = topic.topic_id;
                _this.topic.topic_id = topic.topic_id;
                _this.topic.topic_name = topic.topic_name;
            }, // End of Edit Data Method
            deleteData(id){
                var _this = this;
                if(confirm('Are you sure?')){
                    axios.delete('/topic/delete/'+id).then(function(response){
                    _this.getData();
                 })
                }
            }, // End of Delete Data Method
        },
        created() {
            console.log('VueJS component created.');
            this.getData();
        }  
    }
</script>
