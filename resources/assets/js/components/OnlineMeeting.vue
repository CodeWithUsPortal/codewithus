<template>
   <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <td><h5>Join Meeting as:</h5></td>
                </tr>
            </thead>
             <tbody>
                <tr v-for="userName in userNames" v-bind:key="userName.user_id">
                    <td><a class="btn btn-primary btn-rounded btn-lg btn-block" :href="userName.link_to_meeting">{{ userName.user_name }}</a></td>
                </tr>      
            </tbody>
        </table>
    </div>
</template>


<script>
     export default {
        data(){
            return {
                userNames : [],
            };
        },
        methods:{
            getUsernames(){
                var _this = this;
                axios.get('/get_username_for_online_meeting').then(function(response){     
                    _this.userNames = response.data.userNames; 
                })
            }
        },
        created() {
            this.getUsernames();
            console.log('VueJS component created.');
        }
    }
</script>
