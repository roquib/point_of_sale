<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-indigo pt-2 pb-0 clearfix" style="background: #6574CD">
            <h3 class="card-title bg-indigo text-light">Supplier Table</h3>

            <div class="card-tools mr-5 text-left pr-5">
              <!-- Supplier Search -->
              <input
                type="text"
                @keyup="search_supplier"
                v-model="search"
                class="text-light form-control mr-5 form-control-sm"
                style="background: #6574CD"
                placeholder="Search Supplier"
              />
            </div>
            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" @click="newModal">
                Add new
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table table-sm table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Company</th>
                  <th>Mobile</th>
                  <th>Address</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(supplier, index) in suppliers" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td style="text-transform: capitalize">{{ supplier.name | lowercase }}</td>
                  <td>{{ supplier.email }}</td>
                  <td style="text-transform: capitalize">{{ supplier.company_name | lowercase }}</td>
                  <td>{{ supplier.mobile }}</td>
                  <td>{{ supplier.address }}</td>
                  <td>
                    <a href="#" @click="editModal(supplier)">
                      <i class="fas fa-edit green" style="font-size: 20px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteSupplier(supplier.id)">
                      <i class="fas fa-trash red" style="font-size: 20px;"></i>
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
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: #563D7C">
          <div class="modal-header text-light py-3">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update supplier</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add new supplier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateSupplier() : createSupplier()">
            <div class="modal-body" style="background: white">
              <div class="row">
                <!-- supplier entry fields -->
                <div class="form-group col-md-6">
                  <label for="name">Supplier Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    id="name"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                    placeholder="First Name"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>

                <div class="form-group col-md-6">
                  <label for="email">Email Address</label>
                  <input
                    v-model="form.email"
                    type="text"
                    name="email"
                    id="email"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                    placeholder="Email Address"
                    :class="{ 'is-invalid': form.errors.has('email') }"
                  />
                  <has-error :form="form" field="email"></has-error>
                </div>

                <div class="form-group col-md-6">
                  <label for="mobile">Mobile Number</label>
                  <input
                    v-model="form.mobile"
                    type="text"
                    name="mobile"
                    id="mobile"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                    placeholder="Mobile number"
                    :class="{ 'is-invalid': form.errors.has('mobile') }"
                  />
                  <has-error :form="form" field="mobile"></has-error>
                </div>

                <div class="form-group col-md-6">
                  <label for="company_name">Company Name</label>
                  <input
                    v-model="form.company_name"
                    type="text"
                    name="company_name"
                    id="company_name"
                    placeholder="company name"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                  />
                </div>

                <div class="form-group col-md-6">
                  <label for="opening_balance">Opening Balance</label>
                  <input
                    v-model="form.opening_balance"
                    type="text"
                    name="opening_balance"
                    placeholder="Opening Balance"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                  />
                </div>

                <div class="form-group col-md-6">
                  <label for="city">City</label>
                  <input
                    v-model="form.city"
                    type="text"
                    name="city"
                    id="city"
                    placeholder="Type a city name"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                  />
                </div>

                <div class="form-group col-md-6">
                  <label for="state">State</label>
                  <input
                    v-model="form.state"
                    type="text"
                    name="state"
                    id="state"
                    placeholder="Type a state name"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                  />
                </div>

                <div class="form-group col-md-6">
                  <label for="zip">Zip Code</label>
                  <input
                    v-model="form.zip"
                    type="text"
                    name="zip"
                    id="zip"
                    placeholder="Type a zip code"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                  />
                </div>

                <div class="form-group col-md-6 my-0">
                  <label for="address">Address</label>
                  <textarea
                    v-model="form.address"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                    name="address"
                    id="address"
                    placeholder="Supplier Address"
                    rows="3"
                  ></textarea>
                  <has-error :form="form" field="address"></has-error>
                </div>

                <div class="form-group col-md-6 mt-0">
                  <label for="comments">Comments</label>
                  <textarea
                    v-model="form.comments"
                    class="form-control form-control-sm"
                    style="margin-top: -8px;"
                    name="comments"
                    id="comments"
                    placeholder="Type any comment"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>

            <div class="modal-footer py-2">
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
      suppliers: {},
      search: "",
      form: new Form({
        id: "",
        name: "",
        email: "",
        mobile: "",
        address: "",
        city: "",
        state: "",
        zip: "",
        country: "",
        comments: "",
        company_name: "",
        opening_balance: 0
      })
    };
  },
  methods: {
    search_supplier() {
      if (this.search != "") {
        axios
          .get("api/supplier/search/" + this.search)
          .then(({ data }) => (this.suppliers = data.data));
      } else {
        this.loadSuppliers();
      }
    },
    editModal(supplier) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(supplier);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteSupplier(id) {
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
              .delete("api/supplier/" + id)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "Supplier Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "Supplier Deleted Failed", "error");
              });
          }
        });
    },
    loadSuppliers() {
      axios
        .get("api/supplier")
        .then(({ data }) => (this.suppliers = data.data));
    },
    updateSupplier() {
      this.$Progress.start();
      this.form
        .put("api/supplier/" + this.form.id)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Supplier updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          // failed response
          swal.fire("", "Supplier Update Failed", "error");
          this.$Progress.fail();
        });
    },
    createSupplier() {
      this.$Progress.start();
      this.form
        .post("api/supplier")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Supplier created successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },

  created() {
    this.loadSuppliers();
    Fire.$on("AfterAction", () => {
      this.loadSuppliers();
    });
  }
};
</script>
