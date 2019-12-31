<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header" style="background: #563D7C">
            <h3 class="card-title text-light">Customer Table</h3>

            <div class="card-tools">
              <table>
                <tr>
                  <td>
                    <!-- Customer Search -->
                    <div class="input-group float-right">
                      <input
                        type="text"
                        @keyup="search_customer"
                        v-model="search"
                        class="form-control text-light"
                        style="background: #563D7C"
                        placeholder="Search Customer"
                      />
                      <div class="input-group-append">
                        <span class="btn btn-outline-light">Search</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      class="ml-3 btn btn-outline-warning"
                      @click="newModal"
                    >Add Customer</button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-sm table-hover">
              <tbody>
                <tr class="bg-primary">
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Address</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(customer, index) in customers.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ customer.name | capitalize }}</td>
                  <td>{{ customer.email }}</td>
                  <td>{{ customer.mobile }}</td>
                  <td>{{ customer.address }}</td>
                  <td>
                    <a href="#" @click="editModal(customer)">
                      <i class="fas fa-edit green" style="font-size: 25px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteCustomer(customer.id)">
                      <i class="fas fa-trash red" style="font-size: 25px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <pagination :data="customers" @pagination-change-page="getResults"></pagination>
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
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #563D7C">
          <!-- MODAL HEADER -->
          <div class="modal-header text-light">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update Customer</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add New Customer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="text-light" aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- END MODAL HEADER -->

          <form @submit.prevent="editMode ? updateCustomer() : createCustomer()">
            <!-- modal body -->
            <div class="modal-body" style="background: white">
              <div class="row">
                <!-- first name -->
                <div class="form-group col-12">
                  <input
                    v-model="form.name"
                    title="Name Required"
                    type="text"
                    name="name"
                    placeholder="Customer Name"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>

                <!-- email -->
                <div class="form-group col-12">
                  <input
                    v-model="form.email"
                    title="Email Required"
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('email') }"
                  />
                  <has-error :form="form" field="email"></has-error>
                </div>

                <!-- mobile number -->
                <div class="form-group col-12">
                  <input
                    v-model="form.mobile"
                    title="Mobile Number Required"
                    type="text"
                    name="mobile"
                    placeholder="Mobile Number"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('mobile') }"
                  />
                  <has-error :form="form" field="mobile"></has-error>
                </div>

                <!-- address  -->
                <div class="form-group col-12">
                  <textarea
                    v-model="form.address"
                    class="form-control form-control-sm"
                    title="Address Required"
                    id="address"
                    placeholder="Address"
                    rows="3"
                  ></textarea>
                  <has-error :form="form" field="address"></has-error>
                </div>
              </div>
            </div>
            <!-- END modal body -->

            <!-- modal footer -->
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              <button v-show="editMode" type="submit" class="btn btn-success btn-sm">Update</button>
              <button v-show="!editMode" type="submit" class="btn btn-primary btn-sm">Create</button>
            </div>
            <!-- END modal footer -->
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
      customers: {},
      search: "",

      form: new Form({
        id: "",
        name: "",
        email: "",
        mobile: "",
        address: "",
        user_id: "1"
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/customer?page=" + page).then(response => {
        this.customers = response.data;
      });
    },
    editModal(customer) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(customer);
      this.showImage = "customer/" + this.form.image;
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteCustomer(id) {
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
              .delete("api/customer/" + id)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "Customer Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "Customer Deleted Failed", "error");
              });
          }
        });
    },
    loadCustomers() {
      axios.get("api/customer").then(({ data }) => (this.customers = data));
    },
    search_customer() {
      if (this.search != "") {
        axios
          .get("api/customer/search/" + this.search)

          .then(({ data }) => (this.customers = data));
      } else {
        this.loadCustomers();
      }
    },
    updateCustomer() {
      this.$Progress.start();
      this.form
        .put("api/customer/" + this.form.id)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "customer updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          // failed response
          swal.fire("", "Customer Update Failed", "error");
          this.$Progress.fail();
        });
    },
    createCustomer() {
      this.$Progress.start();
      this.form
        .post("api/customer")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast
            .fire({
              type: "success",
              title: "Customer created successfully"
            })
            .catch(err => {
              console.log(err);
            });

          this.$Progress.finish();
          this.showImage = "";
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadCustomers();
    Fire.$on("AfterAction", () => {
      this.loadCustomers();
    });
  }
};
</script>
