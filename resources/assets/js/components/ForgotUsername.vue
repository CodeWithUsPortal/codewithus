<template>
    <div class="container">
        <br/>
        <form @submit.prevent="getUserName" enctype="multipart/form-data">

            <div class="form-group">
                <!-- <label>Phone Number</label> -->
                <input type="text" maxlength = "100" class="form-control" id="phone_number" placeholder="Phone Number" v-model="username.phone_number" required/>
            </div>
            <div class="form-group">
                <!-- <label>Full Name or First Name</label> -->
                <input type="text" maxlength = "100" class="form-control" id="name" placeholder="Full Name or First Name" v-model="username.name" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>

         <br/><br/>
        <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Usernames</h5></td>
                    <td><h5>Full names</h5></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="username in usernames" v-bind:key="username">
                    <td>{{ username.username }}</td>
                    <td>{{ username.fullname }}</td>
                </tr>      
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
        data(){
            return {
                username_id : '',
                username: { phone_number : '', name : ''},
                usernames:[],
                showDataTable : false,
            };
        },
        methods:{
            getUserName(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/usernames',this.username).then(function(response){
                    debugger;
                    if(response.data.reponse_msg == "No Usernames exists with this information"){
                        alert("No Usernames exists with this information");
                        _this.showDataTable = false;
                    }
                    else{
                        _this.username = { phone_number : '', name : ''}
                        _this.usernames = response.data;
                        _this.showDataTable = true;
                    }  
                })                  
            },
        },
        created() {
            console.log('VueJS component created.');
        }
    }
</script>

