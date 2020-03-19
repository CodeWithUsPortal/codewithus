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
                <input type="text" maxlength = "100" class="form-control" id="phone_number" placeholder="Phone Number" v-model="user.phone_number" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>

        <table class="table" id="table" v-if="showDataTable">
            <thead>
                <tr>
                    <td><h5>Full names</h5></td>
                    <td></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="user in users" v-bind:key="user">
                    <td>{{ user.fullname }}</td>
                    <td><button @click="loginWithThisUserName(user.username,user.fullname, user.is_password_available, user.phone_number)" class="btn btn-warning">Login</button></td>
                </tr>      
            </tbody>
        </table>

        <form @submit.prevent="login" v-if="loginForm" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" maxlength = "100" class="form-control" id="user_name" v-model="user.name" required/>
                <label>Password</label>
                <input type="password" maxlength = "100" class="form-control" id="password" placeholder="Password" v-model="user.password" required/>
            </div>
            <input class="btn btn-primary" type="submit" value="submit" />
        </form>
        <form @submit.prevent="setPassword" v-if="setPasswordForm" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" maxlength = "100" class="form-control" v-model="user.user_name" required/>
                <input type="hidden" maxlength = "100" class="form-control" v-model="user.full_name" required/>
                <input type="hidden" maxlength = "100" class="form-control" v-model="user.phone_number" required/>
                <input type="hidden" maxlength = "100" class="form-control" v-model="user.is_password_available" required/>
                <label>You need to setup a Password*</label>
                <input type="password" maxlength = "100" class="form-control" id="password" placeholder="Password" v-model="user.password" required/>
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
                user_id : '',
                user: { _token :'', phone_number : '',user_name :'', full_name : '', is_password_available : '', password:'', newPassword : ''},
                users:[],
                showGetUserNameForm: true,
                showDataTable : false,
                loginForm : false,
                wrongCredentialsError : false,
                displayNoUsernameError : false,
                setPasswordForm : false,
            };
        },
        methods:{
            getUserName(e){
                e.preventDefault();
                var _this = this;      
                axios.post('/usernamesUsingPhoneNumber',this.user).then(function(response){
                    if(response.data.response_msg == "No Usernames exists with this information"){
                        _this.showGetUserNameForm = true;
                        _this.showDataTable = false;
                        _this.loginForm = false;
                        _this.displayNoUsernameError = true;
                    }
                    else{
                        _this.displayNoUsernameError = false;
                        _this.user = { phone_number : '',user_name :'', full_name : '', is_password_available : '', password:'', newPassword : ''}
                        _this.users = response.data;
                        _this.showGetUserNameForm = false;
                        _this.showDataTable = true;
                        _this.loginForm = false;
                    }  
                })                  
            },
            loginWithThisUserName(userName, fullName, isPasswordAvailable, phoneNumber){
                var _this = this;
                if(isPasswordAvailable == "Yes"){
                    _this.user.user_name = userName;
                    _this.user.full_name = fullName;
                    _this.user.is_password_available = isPasswordAvailable;
                    _this.user.phone_number = phoneNumber;
                    _this.showGetUserNameForm = false;
                    _this.showDataTable = false;
                    _this.loginForm = true;
                    _this.setPasswordForm = false;
                }
                else if(isPasswordAvailable == "No"){
                    _this.user.user_name = userName;
                    _this.user.full_name = fullName;
                    _this.user.is_password_available = isPasswordAvailable;
                    _this.user.phone_number = phoneNumber;
                    _this.showGetUserNameForm = false;
                    _this.showDataTable = false;
                    _this.loginForm = false;
                    _this.setPasswordForm = true;
                }
               
            }, 
            login(e){
                e.preventDefault(); 
                var _this = this;
                _this.user._token = _this.csrf;                  
                axios.post('/login',_this.user).then(function(response){
                    if(response.data.user_name == "These credentials do not match our records."){
                        _this.wrongCredentialsError = true;
                    }
                    else{
                        window.location.href = "/";
                        _this.wrongCredentialsError = false;
                    }
                })
            },
            setPassword(e){
                e.preventDefault();    
                var _this = this;
                _this.user._token = _this.csrf;
                axios.post('/setPassword',_this.user).then(function(response){
                    axios.post('/login',_this.user).then(function(response){
                        if(response.data.user_name == "These credentials do not match our records."){
                            _this.wrongCredentialsError = true;
                        }
                        else{
                            window.location.href = "/";
                            _this.wrongCredentialsError = false;
                        }
                    })
                })
            }
        },
        created() {
            console.log('VueJS component created now.');
        }
    }
</script>
