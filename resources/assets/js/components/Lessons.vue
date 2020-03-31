<template>
    <div class="container">
        <br/><h1>Lessons</h1><br/><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">

            <div class="form-group">
                <label>Sub Category </label>
                <select  class="option" @change="onChangeOfSubCategory($event)" v-model="selectedValueOfSubCategory" required>
                    <option v-for="subcategory in subcategories" v-bind:key="subcategory.id" :value="subcategory.id"> {{ subcategory.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lesson Name</label>
                <input type="text" maxlength = "50" class="form-control" id="name" placeholder="Lesson name" v-model="lesson.lesson_name" required/>
            </div>
            <div class="form-group">
                <label>Link to lesson</label>
                <input type="text" maxlength = "50" class="form-control" id="link" placeholder="Link" v-model="lesson.lesson_link" required/>
            </div>

            <input class="btn btn-primary" type="submit" :value="submitButton" />
            <button class="btn btn-danger" type="button" @click="reset" >Reset</button>
        </form>
        <br/><br/>
         <table class="table" id="table">
            <thead>
                <tr>
                    <td><h5>Lesson Id</h5></td>
                    <td><h5>Lesson Title</h5></td>
                    <td><h5>Lesson Link</h5></td>
                    <td><h5>Sub Category Id</h5></td>
                    <td><h5>Sub Category Name</h5></td> 
                    <td></td>
                    <td></td>
                    <td><h5>Sort</h5></td>     
                </tr>
            </thead>
             <draggable :list="lessons" :options="{animation:200, handle:'.my-handle'}" :element="'tbody'" @change="updateLessonPriority">
                <tr v-for="(lesson, index) in lessons" v-bind:key="lesson.id">
                    <td>{{ lesson.id }}</td>
                    <td>{{ lesson.name }}</td>
                    <td>{{ lesson.link }}</td>
                    <td>{{ lesson.subcategory_id }}</td>
                    <td>{{ lesson.subcategory_name }}</td>
                    <td><button @click="editData(lesson)" class="btn btn-warning">EDIT</button></td>
                    <td><button @click="deleteData(lesson.id)" class="btn btn-danger">DELETE</button></td>
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
                lesson_id : '',
                lesson: { lesson_id : '',lesson_name : '',lesson_link : '', subcategory_id : '', subcategory_name : ''},
                lessons:[],
                edit : false,  
                subcategory_id : '',     
                subcategory: { subcategory_id : '', subcategory_name : ''},
                subcategories: [],
                selectedValueOfSubCategory : '',
            };
        },
         computed:{
             submitButton(){
                 return !this.edit ? 'Submit' : 'Update'
             }
         },
         created() {
             this.getData();
         },
        methods:{
            reset(){
                this.subcategory = { subcategory_id : '', subcategory_name : ''};
                this.getData();
                this.lesson = { lesson_id : '',lesson_name : '',lesson_link : '', subcategory_id : '', subcategory_name : ''};
                this.selectedValueOfSubCategory = '';
                this.edit = false;
            },
            getData(){
                var _this = this;
                axios.get('/training/getAllLessons').then(function(response){
                    _this.lessons = response.data.lessons;
                    _this.subcategories= response.data.subcategories; 
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){    
                    axios.post('/training/addLesson',this.lesson).then(function(response){
                        if(response.data.reponse_msg == "Lesson cannot be added."){
                            alert("Lesson cannot be added.");
                        }
                        else{
                            _this.lesson = { lesson_id : '',lesson_name : '',lesson_link : '', subcategory_id : '', subcategory_name : ''},
                            _this.subcategory = { subcategory_id : '', subcategory_name : ''}
                            _this.reset();
                        }  
                    })              
                } 
                else{
                    axios.put('/training/lesson/edit/'+_this.lesson_id,this.lesson).then(function(response){
                        _this.lesson = { lesson_id : '',lesson_name : '',lesson_link : '', subcategory_id : '', subcategory_name : ''},
                        _this.subcategory = { subcategory_id : '', subcategory_name : ''}
                        _this.reset();
                    })
                }
            },
            editData(lesson){
                var _this = this;
                _this.edit = true;
                _this.lesson_id = lesson.id;
                _this.lesson.lesson_id = lesson.id;
                _this.lesson.lesson_name = lesson.name;
                _this.lesson.lesson_link = lesson.link;
                _this.lesson.subcategory_id = lesson.subcategory_id;
                _this.lesson.subcategory_name = lesson.subcategory_name;
                _this.selectedValueOfSubCategory = lesson.subcategory_id;
            }, // End of Edit Data Method
            deleteData(id){
                var _this = this;
                if(confirm('Are you sure?')){
                    axios.delete('/training/lesson/delete/'+id).then(function(response){
                    _this.reset();
                 })
                }
            }, // End of Delete Data Method
             onChangeOfSubCategory(event){
                  this.lesson.subcategory_id = event.target.value;
                 console.log(event.target.value)
            },
            updateLessonPriority(){
                var _this = this;
              
                _this.lessons.map((lesson,index) => {
                    lesson.priority = index + 1;
                });
                axios.put('/training/updateLessonPriority',{
                    lessons : _this.lessons
                }).then((response) => {
                    //Success message
                });

            },//End of Update priority method
        }
    }
</script>

