<template>
    <div class="container">
        <br/><h1>Lecture Sub Categories</h1><br/><br/>
        <form @submit.prevent="addData" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category </label>
                <select  class="option" @change="onChangeOfCategory($event)" v-model="selectedValueOfCategory" required>
                    <option v-for="category in categories" v-bind:key="category.id" :value="category.id"> {{ category.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sub Category Name</label>
                <input type="text" maxlength = "50" class="form-control" id="name" placeholder="Sub category name" v-model="subcategory.sub_category_name" required/>
            </div>
           
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>
        <br/><br/>
         <table class="table" id="table">
            <thead>
                <tr>
                    <td><h5>Sub Category Id</h5></td>
                    <td><h5>Sub Category Name</h5></td>   
                    <td><h5>Category Id</h5></td>
                    <td><h5>Category Name</h5></td> 
                    <td></td>
                    <td></td>  
                    <td><h5>Sort</h5></td>  
                </tr>
            </thead>
            <draggable :list="subcategories" :options="{animation:200, handle:'.my-handle'}" :element="'tbody'" @change="updateSubCategoryPriority">
                <tr v-for="subcategory in subcategories" v-bind:key="subcategory.id">
                    <td>{{ subcategory.id }}</td>
                    <td>{{ subcategory.name }}</td>
                     <td>{{ subcategory.category_id }}</td>
                    <td>{{ subcategory.category_name }}</td>
                    <td><button @click="editData(subcategory)" class="btn btn-warning">EDIT</button></td>
                    <td><button @click="deleteData(subcategory.id)" class="btn btn-danger">DELETE</button></td>
                    <td><i class="fa fa-arrows  my-handle"></i></td>
                </tr>      
            </draggable>
        </table>
    </div>
</template>


<script>
     export default {
        data(){
            return {
                subcategory_id : '',
                subcategory: { sub_category_id : '',sub_category_name : '', category_id : '', category_name : ''},
                subcategories:[],
                edit : false,  
                category_id : '',     
                category: { category_id : '', category_name : ''},
                categories: [],
                selectedValueOfCategory : '',
            };
        },
        methods:{
            getData(){
                var _this = this;
              
                axios.get('/getAllLectureSubCategories').then(function(response){                   
                    _this.subcategories = response.data.subcategories; 
                    _this.categories= response.data.categories; 
                })
            }, // End of Get Data Method
            addData(e){
                e.preventDefault();
                var _this = this;
                if(_this.edit == false){    
                    axios.post('/addLectureSubCategory',this.subcategory).then(function(response){
                        if(response.data.reponse_msg == "Category cannot be added."){
                            alert("Category cannot be added.");
                        }
                        else{
                            _this.category = { category_id : '', category_name : ''}
                            _this.subcategory = { sub_category_id : '',sub_category_name : '', category_id : '', category_name : ''}
                            _this.getData();
                        }  
                    })              
                } 
                else{
                    axios.put('/subcategory/edit/'+_this.subcategory_id,this.subcategory).then(function(response){
                        _this.category = { category_id : '', category_name : ''}
                        _this.subcategory = { sub_category_id : '',sub_category_name : '', category_id : '', category_name : ''}
                        _this.edit = false;
                        _this.getData(); 
                        _this.selectedValueOfCategory = '';  
                    })
                }
            },
            editData(subcategory){
                var _this = this;
                _this.edit = true;
                _this.subcategory_id = subcategory.id;
                _this.subcategory.sub_category_id = subcategory.id;
                _this.subcategory.sub_category_name = subcategory.name;
                _this.selectedValueOfCategory = subcategory.category_id;
                _this.subcategory.category_id = subcategory.category_id;
            }, // End of Edit Data Method
            deleteData(id){
                debugger;
                var _this = this;
                if(confirm('Are you sure?')){
                    axios.delete('/subcategory/delete/'+id).then(function(response){
                    _this.getData();
                 })
                }
            }, // End of Delete Data Method
             onChangeOfCategory(event){
                  this.subcategory.category_id = event.target.value;
                 console.log(event.target.value)
            },
            updateSubCategoryPriority(){
                var _this = this;
              
                _this.subcategories.map((subcategory,index) => {
                    subcategory.priority = index + 1;
                });debugger;
                axios.put('/updateSubCategoryPriority',{
                    subcategories : _this.subcategories
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
