<template>
    <div>
        <h3 class="text-center">Создание записи на курс</h3>
        <div class="row h-100 d-flex align-items-center justify-content-center text-center">
            <div class="col-md-4">
                <form @submit.prevent="addProduct">
                    <div class="form-group">
                        <label>Название курса</label>
                        <select v-model="enrollment.course_id" class="form-control" required>
                            <option value="" disabled>Выберите</option>
                            <option
                                v-for="(course, i) in courses"
                                :value="course['id']"
                                :key="i"
                            >{{ course['title'] }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Имя пользователя</label>
                        <input type="text" v-model="enrollment.user_name" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input  type="email" v-model="enrollment.user_email" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Результaт</label>
                        <select v-model="enrollment.status" class="form-control" required>
                            <option value="" disabled>Выберите</option>
                            <option value="0" :selected="enrollment.status === 0">in Progress</option>
                            <option value="1" :selected="enrollment.status === 1">Complete</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Создать</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            enrollment: {},
            courses: {},
        }
    },
    mounted() {
        axios
            .get('http://localhost:8000/api/enrollment/create')
            .then(response => {
                this.courses = response.data.json.courses;
            });
    },
    methods: {
        addProduct() {
            axios
                .post('http://localhost:8000/api/enrollment/create', this.enrollment)
                .then(response => (
                    this.$router.push({name: 'home'})
                ))
                .catch(err => console.log(err))
                .finally(() => this.loading = false)
        }
    }
}
</script>
