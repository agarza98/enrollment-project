<template>
    <div>
        <h3 class="text-center">Обновление записи на курс</h3>
        <div class="row h-100 d-flex align-items-center justify-content-center text-cente">
            <div class="col-md-6">
                <form @submit.prevent="update" >
                    <div class="form-group">
                        <h4>Имя пользователя</h4>
                        <p>{{ enrollment.user.name }}</p>
                    </div>
                    <div class="form-group">
                        <h4>Название курса</h4>
                        <p>{{ enrollment.course.title }}</p>
                    </div>

                    <div class="form-group">
                        <h4>Результaт</h4>
                        <select v-model="enrollment.status" class="form-control">
                            <option value="0" :selected="enrollment.status === 0">in Progress</option>
                            <option value="1" :selected="enrollment.status === 1">Complete</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-5">Редактировать</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            enrollment: {}
        }
    },
    mounted() {
        axios
            .get(`http://localhost:8000/api/enrollment/${this.$route.params.id}`)
            .then((res) => {
                this.enrollment = res.data.json;
            });
    },
    methods: {
        update() {
            axios
                .put(`http://localhost:8000/api/enrollment/${this.$route.params.id}`, this.enrollment)
                .then((res) => {
                    this.$router.push({name: 'home'});
                });
        }
    }
}
</script>
