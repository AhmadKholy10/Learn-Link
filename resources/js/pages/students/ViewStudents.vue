<script setup>
import axios from 'axios';
import {ref, onMounted, reactive, watch} from 'vue';
import{Form, Field} from 'vee-validate';
import * as yup from 'yup';


const students = ref([]);

const formValues = ref();

const editing = ref(false);
const form = ref(null);

const searchQuery = ref(null);
const search = ()=>{
    axios.get('/students/search', {
        params: {
            query: searchQuery.value
        }
    })
    .then((response)=>{
        students.value = response.data;
    }).catch(error => {
        console.log(error);
    })
}

watch(searchQuery, ()=>{
    search();
});

const getStudents = ()=> {
    axios.get('/students')
    .then((response)=> {
        students.value = response.data;
    })
}

const schema = yup.object({
    name : yup.string().required(),
    email : yup.string().email().required(),
    code : yup.string().required(),
    level: yup.string().required()
});

const createStudent =(values,{resetForm})=> {
    axios.post('/students/create', values)
    .then((response)=> {
        students.value.push(response.data);
        $('#studentFormModal').modal('hide');
        resetForm();
    })
};

// const createStudent = ()=> {
//     axios.post('/students/create', form)
//     .then((response)=>{
//         students.value.push(response.data);
//         form.name = '';
//         form.email = '';
//         form.date_of_birth = '';
//         form.code = '';
//         form.level= '';
//         $('#studentFormModal').modal('hide');
//     });
// };

const addStudent = ()=>{
    editing.value = false;
    $('#studentFormModal').modal('show');
};



const editStudent = (student)=>{
    editing.value = true;
    form.value.resetForm();
    $('#studentFormModal').modal('show');
    formValues.value = {
        id: student.id,
        name: student.full_name,
        code: student.code,
        email: student.email,
        date_of_birth: student.date_of_birth,
        level: student.level
    };
};

const updateStudent = (values)=>{
    axios.put('/students/update/'+formValues.value.id, values)
    .then((response)=>{
        const index = students.value.findIndex(student => student.id === response.data.id);
        students.value[index] = response.data;
        $('#studentFormModal').modal('hide');
    }).catch((error)=>{
        console.log(error);
    }).finally(()=> {
        form.value.resetForm();
    });
}

const handleSubmit = (values)=>{
    if(editing.value){
        updateStudent(values);
    }
    else{
        createStudent(values);
    }
}

//delete user
const deleteStudent = (deleteid)=>{
    console.log(deleteid);
      axios.delete('/students/delete/'+deleteid)
      .then((res) =>{
        students.value = students.value.filter(student => student.id !== deleteid);
        console.log(res);
        this.getStudents();
      })
      .catch((e)=>{
        console.log(e);
      })
    };
   
   const levels =ref([]);
   const getLevels=()=>{
     axios.get('/students/levels')
    .then((response)=> {
        levels.value = response.data;
        console.log(levels.value[0].name);
    })
   }


   
   const onChange = (event)=>{
       console.log(event.target.value);
     axios.post('/students/filter/'+event.target.value)
    .then((response)=> {
        students.value = response.data;
        console.log(event);
    })
   }



onMounted(()=>{
    getStudents();
    getLevels();
});


</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0">Students</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Students</li>
                </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <button type="button" class="mb-2 btn btn-primary" @click="addStudent">
                Add New Student
            </button>

            <div>
                <input type="text" v-model="searchQuery" class="form-control" placeholder="search....."/>
                
            </div>
            <div>
            <label>
                Select to filter by levels
            </label>
           <select  :value="Select" @change="onChange($event)">
                <option v-for="level in levels" :value="level.id"  >{{ level.name }}</option>
            </select>
            </div>

        </div>    
            
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!-- <th><input type="checkbox" v-model="selectAll" @change="selectAllUsers" /></th> -->
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Email</th>
                                <th>Date of birth</th>
                                <th>Level</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                    <tbody v-if="students.length > 0">
                        <tr v-for="(student, index) in students" :key="student.id">
                            <td> {{ index + 1 }}</td>
                            <td> {{ student.full_name }}</td>
                            <td> {{ student.code }}</td>
                            <td> {{ student.email }}</td>
                            <td> {{ student.date_of_birth }}</td>
                            <td> {{ student.level }}</td>
                            <td>
                                <a href="#" @click.prevent="editStudent(student)"><i class="fa fa-edit"></i></a>
                                <a href="#" @click.prevent="deleteStudent(student.id)"><i class="fa fa-trash text-danger ml-2"></i></a>
                            </td>
                        </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No results found...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> 
            <!-- <Bootstrap4Pagination :data="users" @pagination-change-page="getUsers" /> -->

    <!-- <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema"
                    v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" :class="{ 'is-invalid': errors.name }"
                                id="name" aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control "
                                :class="{ 'is-invalid': errors.email }" id="email" aria-describedby="nameHelp"
                                placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" type="password" class="form-control "
                                :class="{ 'is-invalid': errors.password }" id="password" aria-describedby="nameHelp"
                                placeholder="Enter password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div> -->

    <!-- <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this user ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                </div>
            </div>
    </div>
    </div> -->
</div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="studentFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing"> Edit Student</span>
                        <span v-else>Add New Student </span>
                        </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="schema" v-slot="{errors}" :initial-values="formValues">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" :class="{'is-invalid':errors.name}" id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name" />
                                <span class="invalid-feedback"> {{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control" :class="{'is-invalid':errors.email}" id="email"
                                aria-describedby="nameHelp" placeholder="Enter Email"/>
                                <span class="invalid-feedback"> {{ errors.email }}</span>
                        </div>
                        <div class="form-group">
                            <label for="date-of-birth">Date of birth</label>
                            <Field name="date_of_birth" type="date" class="form-control " id="date-of-birth"
                                aria-describedby="nameHelp" placeholder=""/>
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <Field name="code" type="text" class="form-control" :class="{'is-invalid':errors.code}" id="code"
                                aria-describedby="nameHelp" placeholder="Enter code"/>
                                <span class="invalid-feedback"> {{ errors.code }}</span>
                        </div>
                        <div class="form-group">
                            <label for="level">level</label>
                            <Field name="level" type="text" class="form-control" :class="{'is-invalid':errors.level}" id="level"
                                aria-describedby="nameHelp" placeholder="Enter level"/>
                                <span class="invalid-feedback"> {{ errors.level }}</span>
                        </div>
                    

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button  type="submit" class="btn btn-primary">Save</button>
                </div>
                </Form>
            </div>
        </div>
    </div>

</template>