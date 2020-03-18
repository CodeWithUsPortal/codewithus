<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div v-show="gotoSignUpPageLink">
                   <p style="color:green">User Record has been found</p>
                   <a href="/students/register">Please, Fill out the necessary details.</a>
                </div>
                <div v-show="noRecordFound">
                   <p style="color:red">No User Record has been found with this Username for a Free Session.</p><br/>
                </div>
                <form @submit.prevent="signIn" enctype="multipart/form-data" >
                    <div class="form-group">
                        <input type="text" placeholder="Student's Name" maxlength = "100" class="form-control" v-model="studentData.student_name" required/>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Sign In" />
                </form>
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                studentData : { student_name :''},
                gotoSignUpPageLink : false,
                noRecordFound : false,
            };
        },
        methods:{
            signIn(){
                var _this = this;
                axios.post('/find_user_record_for_free_session', _this.studentData).then(function(response){         
                    if(response.data.response_msg == "Record found"){
                        _this.gotoSignUpPageLink = true;
                        _this.noRecordFound = false;
                    }
                    else if(response.data.response_msg == "Record not found"){
                        _this.gotoSignUpPageLink = false;
                        _this.noRecordFound = true;
                    }
                })
            },
        },
        mounted() {
            console.log('Component mounted and then created.')
        }
        
    }
</script>
