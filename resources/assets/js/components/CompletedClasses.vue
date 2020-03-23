<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h3>
                            Completed Classes
                            <small class="float-right"><a class="btn btn-warning" title="View completed classes" :href="'/edit_student_profile/'+student_id">Back</a></small>
                        </h3>
                        <table class="table" id="table1">
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Starts</td>
                                <td>Ends</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="taskClass in completedTaskClasses" :key="taskClass.id">
                                <td>{{taskClass.name}}</td>
                                <td>{{taskClass.starts_at}}</td>
                                <td>{{taskClass.ends_at}}</td>
                                <td>
                                    <button class="btn btn-warning" @click="markAsIncomplete(taskClass.pivot.id)">Mark As Incomplete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['student_id'],
        data(){
            return {
                completedTaskClasses : []
            }
        },
        methods:{
            getCompletedTaskClasses(){
                var _this = this;
                axios.post('/get-completed-assigned-classes',{'student_id':_this.student_id}).then(function(response){
                    _this.completedTaskClasses = response.data.completedClasses;
                })
            },
            markAsIncomplete(pivot_id){
                if(confirm('Are you sure you want to mark this class as incomplete?'))
                {
                    let _this = this;
                    axios.post('/teacher/mark-task-class-incomplete', {'id':pivot_id}).then(function(response){});
                    this.getCompletedTaskClasses();
                }
            }
        },
        created(){
            this.getCompletedTaskClasses();
        }
    }
</script>
