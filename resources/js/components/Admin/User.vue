<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-indigo" style="background: #6574CD">
            <h3 class="card-title bg-indigo text-light">Users Table</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" @click="newModal">
                Add new
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Address</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.first_name | capitalize }} {{ user.last_name | capitalize }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.phone_number }}</td>
                  <td>{{ user.address_1 }}</td>
                  <td>{{ user.role }}</td>
                  <td>
                    <a href="#" title="Edit User" @click="editModal(user)">
                      <i class="fas fa-edit green" style="font-size: 18px;"></i>
                    </a> &nbsp;
                    <a href="#" title="Delete User" @click="deleteUser(user.id)">
                      <i class="fas fa-trash red" style="font-size: 18px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- Modal -->
    <div
      style="z-index: 30000"
      class="modal fade"
      id="addModal"
      tabindex="1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #563D7C">
          <div class="modal-header text-light">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update user</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add new user</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateUser() : createUser()">
            <div class="modal-body" style="background: white">
              <div class="row">
                <!-- user entry fields -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.first_name"
                    type="text"
                    placeholder="First Name"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('first_name') }"
                  />
                  <has-error :form="form" field="first_name"></has-error>
                </div>

                <!-- last name -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.last_name"
                    type="text"
                    placeholder="Last Name"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- email -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.email"
                    type="text"
                    placeholder="Email Address"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('email') }"
                  />
                  <has-error :form="form" field="email"></has-error>
                </div>

                <!-- phone number -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.phone_number"
                    type="text"
                    placeholder="Mobile number"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('phone_number') }"
                  />
                  <has-error :form="form" field="phone_number"></has-error>
                </div>

                <!-- address 1 -->
                <div class="form-group col-md-6">
                  <textarea
                    v-model="form.address_1"
                    class="form-control form-control-sm"
                    id="address_1"
                    placeholder="Address1"
                    rows="3"
                  ></textarea>
                  <has-error :form="form" field="address_1"></has-error>
                </div>

                <!-- address 2 -->
                <div class="form-group col-md-6">
                  <textarea
                    v-model="form.address_2"
                    class="form-control form-control-sm"
                    id="address_2"
                    placeholder="Address2"
                    rows="3"
                  ></textarea>
                </div>

                <!-- gender -->
                <div class="form-group col-md-6">
                  <select
                    title="Status Required"
                    v-model="form.gender"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('gender') }"
                  >
                    <option value>Gender</option>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Other</option>
                  </select>
                  <has-error :form="form" field="gender"></has-error>
                </div>

                <!-- role -->
                <div class="form-group col-md-6">
                  <select
                    v-model="form.role"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('role') }"
                  >
                    <option value>Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Branch Manager">Branch Manager</option>
                    <option value="Sales Man">Sales Man</option>
                    <option value="Employee">Employee</option>
                  </select>
                  <has-error :form="form" field="role"></has-error>
                </div>

                <!-- city -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.city"
                    type="text"
                    placeholder="Type a city name"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- state -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.state"
                    type="text"
                    placeholder="Type a state name"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- zip -->
                <div class="form-group col-md-6">
                  <input
                    v-model="form.zip"
                    type="text"
                    placeholder="Type a zip code"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- password -->
                <div class="form-group col-md-6" v-if="!editMode">
                  <input
                    v-model="form.password"
                    type="password"
                    placeholder="Type a password"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('password') }"
                  />
                  <has-error :form="form" field="password"></has-error>
                </div>

                <!-- comments -->
                <div class="form-group col-md-6">
                  <textarea
                    v-model="form.comments"
                    class="form-control form-control-sm"
                    id="comments"
                    placeholder="Type any comment"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- modal footer -->
            <div class="modal-footer" style="background: white">
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              <button v-show="editMode" type="submit" class="btn btn-success btn-sm">Update</button>
              <button v-show="!editMode" type="submit" class="btn btn-primary btn-sm">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End modal -->
  </div>
</template>



<script>
export default {
  data() {
    return {
      editMode: false,
      users: {},
      form: new Form({
        id: "",
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        phone_number: "",
        address_1: "",
        address_2: "",
        gender: "",
        role: "",
        city: "",
        state: "",
        zip: "",
        country: "",
        comments: ""
      })
    };
  },
  methods: {
    editModal(user) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteUser(id) {
      swal
        .fire({
          title: "Are you sure?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          if (result.value) {
            this.form
              .delete("api/user/" + id)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "User Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "User Deleted Failed", "error");
              });
          }
        });
    },
    loadUsers() {
      axios.get("api/user").then(({ data }) => (this.users = data.data));
    },
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "User updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          // failed response
          swal.fire("", "User Update Failed", "error");
          this.$Progress.fail();
        });
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "User created successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadUsers();
    Fire.$on("AfterAction", () => {
      this.loadUsers();
    });
  }
};
</script>
