<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card">
          <div class="card-header bg-indigo" style="background: #6574CD">
            <h3 class="card-title bg-indigo text-light">Brand Table</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-success" @click="newModal">
                Add new
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-sm p-0" v-if="brands.data.length">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(brand, index) in brands.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ brand.name }}</td>
                  <td>
                    <a href="#" @click="editModal(brand)">
                      <i class="fas fa-edit green" style="font-size: 25px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteBrand(brand.id)">
                      <i class="fas fa-trash red" style="font-size: 25px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- if Brand empty -->
          <div v-else class="text-center py-3">
            <h3>No Brand created.</h3>
            <p class="text-primary" style="cursor: pointer" @click="newModal">Please create a Brand</p>
          </div>

          <!-- Brand pagination -->
          <div class="card-footer">
            <pagination :data="brands" @pagination-change-page="getResults"></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="editMode" class="modal-title">Update Brand</h5>
            <h5 v-show="!editMode" class="modal-title">Add new Brand</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateBrand() : createBrand()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Brand Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
            </div>

            <div class="modal-footer">
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
      brands: {},
      form: new Form({
        id: "",
        name: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/brand?page=" + page).then(response => {
        this.brands = response.data;
      });
    },
    editModal(brand) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(brand);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteBrand(id) {
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
              .delete("api/brand/" + id)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "Brand Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "Brand Deleted Failed", "error");
              });
          }
        });
    },
    loadBrands() {
      axios.get("api/brand").then(({ data }) => (this.brands = data));
    },
    updateBrand() {
      this.$Progress.start();
      this.form
        .put("api/brand/" + this.form.id)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Brand updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          // failed response
          swal.fire("", "Brand Update Failed", "error");
          this.$Progress.fail();
        });
    },
    createBrand() {
      this.$Progress.start();
      this.form
        .post("api/brand")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Brand created successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadBrands();
    Fire.$on("AfterAction", () => {
      this.loadBrands();
    });
  }
};
</script>
