<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card m-b-30">
                <div class="card-header bg-white">
                    <h5 class="card-title text-black">Upcoming Tasks Classes</h5>
                    <h6 class="card-subtitle">A brief summary of the upcoming tasks classes for the next two weeks</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionExample" >
                        <div class="card mb-2" v-for="c in upComingClasses.data" :key="c.id">
                            <div class="card-header p-1" id="headingOne">
                                <h5 class="mb-0 text-black">
                                    <button @click="getStudentsForTaskClass(c.id)" class="btn btn-link text-black collapsed" type="button" data-toggle="collapse" :data-target="'#collapseOne'+c.id" aria-expanded="false" aria-controls="collapseOne"><i class="icon-question text-primary mr-1"></i>
                                        {{c.name}} : <small>{{c.starts_at}} - {{c.ends_at}}</small>
                                    </button>
                                </h5>
                            </div>
                            <div :id="'collapseOne'+c.id" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th class="border-top-0">Sr.No</th>
                                                <th class="border-top-0">Student Name</th>
                                                <th class="border-top-0">Email</th>
                                                <th class="border-top-0">Mobile</th>
                                                <th class="border-top-0">Notes</th>
                                                <th class="border-top-0">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(s, index) in students">
                                                <td>{{++index}}</td>
                                                <td>{{s.user_name}}</td>
                                                <td>{{s.email}}</td>
                                                <td>{{s.phone_number}}</td>
                                                <td>{{s.notes}}</td>
                                                <td>
                                                    <button @click="markTaskClassAsCompleted(c.id, s)" class="btn btn-warning">Mark As Complete</button>
                                                    <button data-toggle="modal" data-target="#studentUpdateModal2" title="SMS Update" class="btn btn-primary" id="studentsUpdate"><i class="icon-envelope"></i></button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <pagination show=4 :data="upComingClasses" @updatePagination="updatePagination"> </pagination>
                </div>
            </div>
        </div>
        <div class="modal fade" id="studentUpdateModal2" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentUpdateModal2Label">Student Updates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <student-updates :student="student"> </student-updates>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                upComingClasses : [],
                pagination: {},
                students : [],
                student : {student_id : ''}
            }
        },
        created()
        {
            this.getUpcomingClasses();
        },
        methods: {
            updateStudentData(student){
                this.student = student
                this.student.student_id = student.id
                console.log('student', this.student);
            },
            markTaskClassAsCompleted(task_class_id, student)
            {
                if(confirm('Are you sure you want to mark this class as completed?'))
                {
                    let _this = this;
                    axios.post('/teacher/mark-task-class-competed/', {id:student.pivot_id}).then(function(response){});
                    this.getStudentsForTaskClass(task_class_id);
                }
            },
            getUpcomingClasses()
            {
                let _this = this;
                axios.get('/teacher/get-all-upcoming-classes?page='+_this.pagination.current_page).then(function(response) {
                    _this.upComingClasses = response.data.upComingClasses
                });
            },
            updatePagination(pagination) {
                this.pagination = pagination;
                this.getUpcomingClasses();
            },
            getStudentsForTaskClass(id)
            {
                let _this = this;
                axios.get('/get-all-student-for-task-class/' + id).then(function(response) {
                    _this.students = response.data.taskClasses
                });
            }
        }
    }
</script>