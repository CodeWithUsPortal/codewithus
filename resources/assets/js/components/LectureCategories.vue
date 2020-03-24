<template>
    <div class="container">
        <br/><h1>Lecture Categories</h1><br/><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" maxlength = "100" class="form-control" id="name" placeholder="Category Name" v-model="category.category_name" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>

         <br/><br/>
        <table class="table" id="table">
            <thead>
                <tr>
                    <td><h5>Category Id</h5></td>
                    <td><h5>Name</h5></td>   
                    <td></td>
                    <td></td>  
                    <td><h5>Sort</h5></td> 
                </tr>
            </thead>
             <draggable :list="categories" :options="{animation:200, handle:'.my-handle'}" :element="'tbody'" @change="updateCategoryPriority">
                <tr v-for="(category, index) in categories" v-bind:key="category.id">
                    <td>{{ category.id }}</td>
                    <td>{{ category.name }}</td>
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
                category: { category_id : '', category_name : ''},
                categories:[],
                edit : false,    
            };
        },
        methods:{
            getData(){
                var _this = this;
                axios.get('/getAllLectureCategories').then(function(response){                   
                    _this.categories = response.data; 
                    console.log(_this.categories);
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){    
                    axios.post('/addLectureCategory',this.category).then(function(response){
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
                    })
                }
            },
            editData(category){
                var _this = this;
                _this.edit = true;
                _this.category_id = category.id;
                _this.category.category_id = category.id;
                _this.category.category_name = category.name;
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
