<script setup>
import axios from 'axios';
import {ref, onMounted, reactive, watch} from 'vue';
import{Form, Field} from 'vee-validate';
import * as yup from 'yup';


// export default {
//   components: {
//     BTable,
//   },
//   data() {
//     return {
//       items: [
//         {
//           age: 40, first_name: 'Dickerson', last_name: 'Macdonald', Occupation: 'Job',
//         },
//         {
//           age: 21, first_name: 'Larsen', last_name: 'Shaw', Occupation: 'Job',
//         },
//         {
//           age: 89, first_name: 'Geneva', last_name: 'Wilson', Occupation: 'Bussiness',
//         },
//         {
//           age: 38, first_name: 'Jami', last_name: 'Carney', Occupation: 'Bussiness',
//         },
//         {
//           age: 40, first_name: 'James', last_name: 'Thomson', Occupation: 'Job',
//         },
//       ],
//     }
//   },
// }

const courses = ref([]);
const getCourses = ()=> {
    axios.get('/courses')
    .then((response)=> {
        courses.value = response.data;
    })
}

// const students = ref([]);
// const getStudents = (courseID)=> {
//     axios.get('/courses/students/'+courseID)
//     .then((response)=> {
//         students.value = response.data;
//     })
// }

const searchQuery = ref(null);
const search = ()=>{
    axios.get('/courses/search', {
        params: {
            query: searchQuery.value
        }
    })
    .then((response)=>{
        courses.value = response.data;
    }).catch(error => {
        console.log(error);
    })
}

watch(searchQuery, ()=>{
    search();
});

const grades = ref([]);
const getGrades = (courseID)=>{
    axios.get('/courses/students/'+courseID)
    .then((response)=> {
        grades.value = response.data;
    })
    
}

onMounted(()=>{
    getCourses();
});
</script>

<template>
    <div class="d-flex justify-content-between -ml-3 -mb-2 -mt-3">
    <div>
                <input type="text" v-model="searchQuery" class="form-control " placeholder="search....."/>
                
            </div>
            </div>
    <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr >
                                <!-- <th><input type="checkbox" v-model="selectAll" @change="selectAllUsers" /></th> -->
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                    <tbody v-if="courses.length > 0">
                        <tr v-for="(course, index) in courses" :key="course.id">
                            <td>{{ index + 1 }} </td>
                            <td> {{ course.name }}</td>
                            <td>{{ course.code }} </td>
                            <td>{{ course.description }} </td>
                            <td><a @click.prevent="getGrades(course.id)" class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1">view Grades</a>     
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No results found...</td>
                            </tr>
                        </tbody>
                    </table>

                     <b-table responsive="sm" :items="items"/>
                </div>
            </div> 



          <div class="card" v-if="grades.length > 0">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr >
                                <!-- <th><input type="checkbox" v-model="selectAll" @change="selectAllUsers" /></th> -->
                                <!-- <th style="width: 10px">#</th> -->
                                
                                    <th v-for="(key,value) in grades[Object.keys(grades)[0]]"> {{value}}</th>
                                
                            </tr>
                        </thead>
                    <tbody v-if="grades.length > 0">
                        <tr v-for="grade in grades" >
                            <td v-for="(key,value) in grade" > {{key}}
                                </td>
                            
                            <!-- <td> {{ grade.student_name }}</td>
                            <td>{{ course.code }} </td>
                            <td>{{ course.description }} </td>
                            <td><a @click.prevent="getStudents(course.id)" class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1">view Grades</a>      -->
                            
                        </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No results found...</td>
                            </tr>
                        </tbody>
                    </table>

                     <b-table responsive="sm" :items="items"/>
                </div>
            </div> 

            <div v-else> <h2> Grades table is empty </h2> </div>





</template>