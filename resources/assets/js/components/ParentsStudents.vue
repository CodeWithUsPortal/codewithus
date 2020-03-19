<template>
    <div class="container">
        <div v-show="displayNoStudentsError">
            <h6 style="color:red">No Students exist for this Parent</h6>
        </div>

        <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Full Name</h5></td>
                    <td><h5>Location</h5></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="student in students" v-bind:key="student">
                    <td>{{ student.student_name}}</td>
                    <td>{{ student.location }}</td>
                </tr>      
            </tbody>
        </table>

    </div>
</template>

<script>
export default {
        data(){
            return {
                students : [],
                student : {fullname : '', location :''},
                displayNoStudentsError : false,
                showDataTable : false,
            };
        },
        methods:{
            getStudents(){
                var _this = this;      
                axios.get('/parent/getStudents').then(function(response){
                    
                    if(response.data.response_msg == "No Students exist for this parent."){
                        _this.displayNoStudentsError = true;
                    }
                    else{
                        _this.students = response.data.students;
                        _this.displayNoStudentsError = false;
                        _this.showDataTable = true;
                    }  
                })                  
            },
        },
        created() {
            this.getStudents();
            console.log('VueJS component created now.');
        }
    }
</script>
