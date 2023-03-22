<template>
    <div>
        <h2 class="text-center">Список записей</h2>
        <div class="row">
            <div class="col-4">
                <label>Поиск по имейлу или имени пользователя</label>
                <input v-model="user" placeholder="Поиск по имейлу или имени пользователя" class="form-control"></div>
            <div class="col-3">
                <label>Поиск по названию курса</label>
                <input v-model="courseName" placeholder="Поиск по названию курса" class="form-control">
            </div>
            <div class="col-2">
                <label>Поиск по статусу</label>
                <select v-model="status" class="form-control">
                    <option value="">Все</option>
                    <option value="0">in Progress</option>
                    <option value="1">Complete</option>
                </select>
            </div>
            <div class="col-2">
                <button class="btn btn-danger mt-4" @click="getResults()">Поиск</button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th @click="sortByColumn('course_title')" class="text-primary">Название курса
                    <span v-if='sortedColumn=="course_title"'>
                            <i v-if="order === 'asc'">&#8593</i>
                            <i v-else  >&#8595</i>
                    </span>
                    <span v-else>
                            <i>&#8597;</i>
                    </span>
                </th>
                <th> Имя пользователя</th>
                <th> Результат</th>
                <th @click="sortByColumn('created_at')" class="text-primary">Дата записи на курс
                    <span v-if='sortedColumn=="created_at"'>
                            <i v-if="order === 'asc' ">&#8593</i>
                            <i v-else >&#8595</i>
                    </span>
                    <span v-else>
                            <i>&#8597;</i>
                    </span>
                </th>
                <th @click="sortByColumn('updated_at')" class="text-primary"> Дата завершения курса
                    <span v-if='sortedColumn=="updated_at"'>
                            <i v-if="order === 'asc' " > &#8593</i>
                            <i v-else >&#8595</i>
                    </span>
                    <span v-else>
                            <i>&#8597;</i>
                    </span>
                </th>
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody>
            <tr v-for="enrollment in enrollments.data">
                <td>{{ enrollment.id }}</td>
                <td>{{ enrollment['course']['title'] }}</td>
                <td>{{ enrollment['user']['name'] }}</td>
                <td>{{ enrollment.status == 0 ? 'in progress' : 'complete' }}</td>
                <td>{{ formatDate(enrollment.created_at) }}</td>
                <td>{{ formatDate(enrollment.updated_at) }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit', params: { id: enrollment.id }}" class="btn btn-success">
                            Редактировать
                        </router-link>
                        <button class="btn btn-danger" @click="deleteEnrollment(enrollment.id)">Удалить</button>
                    </div>
                </td>
            </tr>
            </tbody>

        </table>

    </div>
    <Bootstrap4Pagination
        :data="enrollments"
        :limit="5"
        size="small"
        align="left"
        show-disabled="false"
        @pagination-change-page="getResults"
    />
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            enrollments: {},
            sortedColumn: '',
            order: '',
            courseName: '',
            status: '',
            user: ''
        }
    },
    mounted() {
        this.getResults()
    },
    methods: {
        getResults(page = 1) {
            let dataFetchUrl = `http://localhost:8000/api/enrollments?page=${page}&sort=${this.sortedColumn}&order=${this.order}&status=${this.status}&user=${this.user}&courseName=${this.courseName}`
            axios
                .get(dataFetchUrl)
                .then(response => {
                    this.enrollments = response.data.json;
                });
        },
        deleteEnrollment(id) {
            axios
                .delete(`http://localhost:8000/api/enrollment/${id}`)
                .then(response => {
                    let i = this.enrollments.data.map(data => data.id).indexOf(id);
                    this.enrollments.data.splice(i, 1)
                });
        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
        sortByColumn(column) {
            if (column === this.sortedColumn) {
                this.order = (this.order === 'asc') ? 'desc' : 'asc'
            } else {
                this.sortedColumn = column
                this.order = 'asc'
            }
            this.getResults()
        },

    }
}
</script>
