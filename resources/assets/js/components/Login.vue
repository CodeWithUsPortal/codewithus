<template>
    <div class="container">
        <div v-show="displayNoUsernameError">
            <h6 style="color:red">No Users exist with this Phone Number</h6>
        </div>
         <div v-show="wrongCredentialsError">
            <h6 style="color:red">Password is incorrect</h6>
        </div>
        <form v-if="showGetUserNameForm" @submit.prevent="getUserName" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" maxlength = "100" class="form-control" id="phone_number" placeholder="Phone Number" v-model="username.phone_number" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>

        <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Usernames</h5></td>
                    <td><h5>Full names</h5></td>
                    <td></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="username in usernames" v-bind:key="username">
                    <td>{{ username.username }}</td>
                    <td>{{ username.fullname }}</td>
                    <td><button @click="loginWithThisUserName(username.username)" class="btn btn-warning">Login</button></td>
                </tr>      
            </tbody>
        </table>

        <form @submit.prevent="login" v-if="loginForm" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" maxlength = "100" class="form-control" id="user_name" v-model="username.name" required/>
                <label>Password</label>
                <input type="password" maxlength = "100" class="form-control" id="password" placeholder="Password" v-model="username.password" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>
    </div>
</template>

<script>
export default {
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                username_id : '',
                username: { _token :'', phone_number : '', user_name : '', password:''},
                usernames:[],
                showGetUserNameForm: true,
                showDataTable : false,
                loginForm : false,
                wrongCredentialsError : false,
                displayNoUsernameError : false,
            };
        },
        methods:{
            getUserName(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/codewithus/usernamesUsingPhoneNumber',this.username).then(function(response){
                    if(response.data.response_msg == "No Usernames exists with this information"){
                        _this.showGetUserNameForm = true;
                        _this.showDataTable = false;
                        _this.loginForm = false;
                        _this.displayNoUsernameError = true;
                    }
                    else{
                        _this.displayNoUsernameError = false;
                        _this.username = { phone_number : '', user_name : '', password:''}
                        _this.usernames = response.data;
                        _this.showGetUserNameForm = false;
                        _this.showDataTable = true;
                        _this.loginForm = false;
                    }  
                })                  
            },
            loginWithThisUserName(name){
                var _this = this;
                _this.username.user_name = name;
                _this.showGetUserNameForm = false;
                _this.showDataTable = false;
                _this.loginForm = true;
            }, 
            login(e){
                var _this = this;
                _this.username._token = _this.csrf;
                e.preventDefault();              
                 axios.post('/codewithus/login',_this.username).then(function(response){
                     debugger;
                    if(response.data.user_name == "These credentials do not match our records."){
                        _this.wrongCredentialsError = true;
                    }
                    else{
                        window.location.href = "http://localhost/codewithus";
                        _this.wrongCredentialsError = false;
                    }
                })
            }
        },
        created() {
            console.log('VueJS component created.');
        }
    }
</script>
