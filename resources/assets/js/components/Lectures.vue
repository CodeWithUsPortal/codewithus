<template>
    <div class="container">
        <br/><h1>Lectures</h1><br/><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">

            <div class="form-group">
                <label>Sub Category </label>
                <select  class="option" @change="onChangeOfSubCategory($event)" v-model="selectedValueOfSubCategory" required>
                    <option v-for="subcategory in subcategories" v-bind:key="subcategory.id" :value="subcategory.id"> {{ subcategory.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lecture Name</label>
                <input type="text" maxlength = "253" class="form-control" id="name" placeholder="Lecture name" v-model="lecture.lecture_name" required/>
            </div>
            <div class="form-group">
                <label>Link to lecture</label>
                <input type="text" class="form-control" id="link" placeholder="Link" v-model="lecture.lecture_link" required/>
            </div>
           
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>
        <br/><br/>
         <table class="table" id="table">
            <thead>
                <tr>
                    <td><h5>Lecture Id</h5></td>
                    <td><h5>Lecture Title</h5></td>   
                    <td><h5>Lecture Link</h5></td>   
                    <td><h5>Sub Category Id</h5></td>
                    <td><h5>Sub Category Name</h5></td> 
                    <td></td>
                    <td></td>
                    <td><h5>Sort</h5></td>     
                </tr>
            </thead>
             <draggable :list="lectures" :options="{animation:200, handle:'.my-handle'}" :element="'tbody'" @change="updateLecturePriority">
                <tr v-for="(lecture, index) in lectures" v-bind:key="lecture.id">
                    <td>{{ lecture.id }}</td>
                    <td>{{ lecture.name }}</td>
                    <td>{{ lecture.link }}</td>
                    <td>{{ lecture.subcategory_id }}</td>
                    <td>{{ lecture.subcategory_name }}</td>
                    <td><button @click="editData(lecture)" class="btn btn-warning">EDIT</button></td>
                    <td><button @click="deleteData(lecture.id)" class="btn btn-danger">DELETE</button></td>
                    <td><i class="fa fa-arrows  my-handle"></i></td>
                </tr>      
            </draggable>
        </table>
    </div>
</template>


<script>
    import draggable from 'vuedraggable';
     export default {
         components:{
            draggable
        },
        data(){
            return {
                lecture_id : '',
                lecture: { lecture_id : '',lecture_name : '',lecture_link : '', subcategory_id : '', subcategory_name : ''},
                lectures:[],
                edit : false,  
                subcategory_id : '',     
                subcategory: { subcategory_id : '', subcategory_name : ''},
                subcategories: [],
                selectedValueOfSubCategory : '',
            };
        },
        methods:{
            getData(){
                var _this = this;
                axios.get('/codewithus/getAllLectures').then(function(response){              
                    _this.lectures = response.data.lectures; 
                    _this.subcategories= response.data.subcategories; 
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){    
                    axios.post('/codewithus/addLecture',this.lecture).then(function(response){
                        if(response.data.reponse_msg == "Lecture cannot be added."){
                            alert("Lecture cannot be added.");
                        }
                        else{
                            _this.lecture = { lecture_id : '',lecture_name : '',lecture_link : '', subcategory_id : '', subcategory_name : ''},
                            _this.subcategory = { subcategory_id : '', subcategory_name : ''}
                            _this.selectedValueOfSubCategory = "";
                            _this.getData();
                        }  
                    })              
                } 
                else{
                    axios.put('/codewithus/lecture/edit/'+_this.lecture_id,this.lecture).then(function(response){
                        _this.lecture = { lecture_id : '',lecture_name : '',lecture_link : '', subcategory_id : '', subcategory_name : ''},
                        _this.subcategory = { subcategory_id : '', subcategory_name : ''}
                        _this.edit = false;
                        _this.getData(); 
                        _this.selectedValueOfSubCategory = '';  
                    })
                }
            },
            editData(lecture){
                var _this = this;
                _this.edit = true;
                _this.lecture_id = lecture.id;
                _this.lecture.lecture_id = lecture.id;
                _this.lecture.lecture_name = lecture.name;
                _this.lecture.lecture_link = lecture.link;
                _this.lecture.subcategory_id = lecture.subcategory_id;
                _this.lecture.subcategory_name = lecture.subcategory_name;
                _this.selectedValueOfSubCategory = lecture.subcategory_id;
            }, // End of Edit Data Method
            deleteData(id){
                debugger;
                var _this = this;
                if(confirm('Are you sure?')){
                    axios.delete('/codewithus/lecture/delete/'+id).then(function(response){
                    _this.getData();
                 })
                }
            }, // End of Delete Data Method
             onChangeOfSubCategory(event){
                  this.lecture.subcategory_id = event.target.value;
                 console.log(event.target.value)
            },
            updateLecturePriority(){
                var _this = this;
              
                _this.lectures.map((lecture,index) => {
                    lecture.priority = index + 1;
                });debugger;
                axios.put('/codewithus/updateLecturePriority',{
                    lectures : _this.lectures
                }).then((response) => {
                    //Success message
                });

            },//End of Update priority method
        },
        created() {
            console.log('VueJS component created.');
            this.getData();
        }
    }
</script>

