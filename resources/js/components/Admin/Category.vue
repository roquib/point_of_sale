<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card">
          <div class="card-header bg-indigo" style="background: #6574CD">
            <h3 class="card-title bg-indigo text-light">Category Table</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-success" @click="newModal">
                Add new
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-sm p-0" v-if="categories.data.length">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(category, index) in categories.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ category.name }}</td>
                  <td>
                    <a href="#" @click="editModal(category)">
                      <i class="fas fa-edit green" style="font-size: 25px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteCategory(category.id)">
                      <i class="fas fa-trash red" style="font-size: 25px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- if category empty -->
          <div v-else class="text-center py-3">
            <h3>No category created.</h3>
            <p
              class="text-primary"
              style="cursor: pointer"
              @click="newModal"
            >Please create a category</p>
          </div>

          <!-- category pagination -->
          <div class="card-footer">
            <pagination :data="categories" @pagination-change-page="getResults"></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="addModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update Category</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add new Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateCategory() : createCategory()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Category Name"
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
      categories: {},
      form: new Form({
        id: "",
        name: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/category?page=" + page).then(response => {
        this.categories = response.data;
      });
    },
    editModal(category) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(category);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteCategory(id) {
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
              .delete("api/category/" + id)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "Category Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "Category Deleted Failed", "error");
              });
          }
        });
    },
    loadCategories() {
      axios.get("api/category").then(({ data }) => (this.categories = data));
    },
    updateCategory() {
      this.$Progress.start();
      this.form
        .put("api/category/" + this.form.id)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Category updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          // failed response
          swal.fire("", "Category Update Failed", "error");
          this.$Progress.fail();
        });
    },
    createCategory() {
      this.$Progress.start();
      this.form
        .post("api/category")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Category created successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadCategories();
    Fire.$on("AfterAction", () => {
      this.loadCategories();
    });
  }
};
</script>
