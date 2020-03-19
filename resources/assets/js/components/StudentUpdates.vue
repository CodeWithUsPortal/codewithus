<template>
    <div>
        <form @submit.prevent="saveUpdate">
            <div class="form-group">
                <label for="update">Update</label>
                <textarea v-model="message" class="form-control" id="update" rows="5" required> </textarea>
                <small id="emailHelp" class="form-text text-muted"></small>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['student'],
        data(){
            return {
                message : ''
            }
        },
        created()
        {
            console.log(this.student);
            this.reset();
        },
        methods: {
            saveUpdate()
            {
                let data = {
                    'student_id' : this.student.student_id,
                    'message' : this.message,
                };
                axios.post('/teacher/store-students-update', data).then(function(response)
                {
                    console.log(response);
                });
                this.$emit('closeModal');
                this.reset();
            },
            reset(){
                this.message = '';
            }
        }
    }
</script>