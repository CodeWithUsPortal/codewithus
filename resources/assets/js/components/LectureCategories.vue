<template>
    <div class="container">
        <br/><h1>Lecture Categories</h1><br/><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md col-md-12">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" maxlength = "100" class="form-control" id="name" placeholder="Category Name" v-model="category.category_name" required/>
                    </div>
                </div>
                <div class="col-md col-md-12" v-if="edit">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" class="form-control" placeholder="Password" v-model="category.password" required/>
                    </div>
                </div>
                <div class="col-md-12 text-success">{{message}}</div>
                <div class="col-md-12 text-danger">{{errorMessage}}</div>
            </div>
            <input v-if="!edit" class="btn btn-primary" type="submit" value="submit" />
            <input v-else class="btn btn-primary" type="submit" value="Update" />
            <input class="btn btn-danger" type="button" value="Reset" @click="reset" />
        </form>

         <br/><br/>
        <table class="table" id="table">
            <thead>
                <tr>
                    <td><h5>Category Id</h5></td>
                    <td><h5>Name</h5></td>   
                    <td><h5>Password</h5></td>
                    <td></td>
                    <td></td>  
                    <td><h5>Sort</h5></td> 
                </tr>
            </thead>
             <draggable :list="categories" :options="{animation:200, handle:'.my-handle'}" :element="'tbody'" @change="updateCategoryPriority">
                <tr v-for="(category, index) in categories" v-bind:key="category.id">
                    <td>{{ category.id }}</td>
                    <td>{{ category.name }}</td>
                    <td>{{ category.password }}</td>
                    <td><button @click="editData(category)" class="btn btn-warning">EDIT</button></td>
                    <td><button @click="deleteData(category.id)" class="btn btn-danger">DELETE</button></td>
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
                category_id : '',
                category: { category_id : '', category_name : '', password : ''},
                categories:[],
                edit : false,
                message: '',
                errorMessage : ''
            };
        },
        methods:{
            reset(){
                this.category_id = '';
                this.category= { category_id : '', category_name : '', password : ''};
                this.edit = false;
                this.message= '';
                this.errorMessage = ''
            },
            getData(){
                var _this = this;
                axios.get('/getAllLectureCategories').then(function(response){
                    _this.categories = response.data;
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){    
                    axios.post('/addLectureCategory',this.category).then(function(response){
                        _this.message = response.data.response_msg;
                        if(response.data.reponse_msg == "Category cannot be added."){
                            alert("Category cannot be added.");
                        }
                        else{
                            _this.category = { category_id : '', category_name : ''}
                            _this.getData();
                        }  
                    })              
                } 
                else{
                    axios.put('/category/edit/'+_this.category_id,this.category).then(function(response){
                        _this.category = { category_id : '', category_name : ''}
                        _this.edit = false;
                        _this.getData();  
                    }).catch(function (error) {
                        console.log(error.response.data.response_msg);
                        _this.errorMessage = error.response.data.response_msg;
                    })
                }
            },
            editData(category){
                var _this = this;
                _this.edit = true;
                _this.category_id = category.id;
                _this.category.category_id = category.id;
                _this.category.category_name = category.name;
                _this.category.password = category.password;
            }, // End of Edit Data Method
            deleteData(id){
                var _this = this;
                if(confirm('Are you sure?')){
                    axios.delete('/category/delete/'+id).then(function(response){
                    _this.getData();
                 })
                }
            }, // End of Delete Data Method
            updateCategoryPriority(){
                var _this = this;
              
                _this.categories.map((category,index) => {
                    category.priority = index + 1;
                });
                axios.put('/updateCategoryPriority',{
                    categories : _this.categories
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
