<template>
  <div class="container">
    <div class="row">
      <div class="col-md-9 mx-auto">
        <div class="card">
          <div class="card-header bg-indigo" style="background: #6574CD">
            <h3 class="card-title bg-indigo text-light">Unit Table</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-success" @click="newModal">
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
                  <th>Action</th>
                </tr>
                <tr v-for="(unit, index) in units.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ unit.name }}</td>
                  <td>
                    <a href="#" @click="editModal(unit)">
                      <i class="fas fa-edit green" style="font-size: 20px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteUnit(unit.id)">
                      <i class="fas fa-trash red" style="font-size: 20px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <pagination :data="units" @pagination-change-page="getResults"></pagination>
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
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update Unit</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add new Unit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateUnit() : createUnit()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Unit Name"
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
      units: {},
      form: new Form({
        id: "",
        name: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/unit?page=" + page).then(response => {
        this.units = response.data;
      });
    },
    editModal(unit) {
      this.editMode = true;
      this.form.reset();
      $("#addModal").modal("show");
      this.form.fill(unit);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteUnit(id) {
      if (id == 1) {
        $("#addModal").modal("hide");
        toast.fire({
          type: "success",
          title: "This unit can not be deleted"
        });
      } else {
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
                .delete("api/unit/" + id)
                .then(() => {
                  toast.fire({
                    type: "success",
                    title: "Unit Deleted Successfully"
                  });
                  Fire.$emit("AfterAction");
                })
                .catch(() => {
                  swal.fire("", "Unit Deleted Failed", "error");
                });
            }
          });
      }
    },
    loadUnits() {
      axios.get("api/unit").then(({ data }) => (this.units = data));
    },
    updateUnit() {
      this.$Progress.start();
      if (this.form.id == 1) {
        Fire.$emit("AfterAction");
        $("#addModal").modal("hide");
        toast.fire({
          type: "success",
          title: "This unit can not be edited"
        });
        this.$Progress.finish();
      } else {
        this.form
          .put("api/unit/" + this.form.id)
          .then(() => {
            // success action
            Fire.$emit("AfterAction");
            $("#addModal").modal("hide");
            toast.fire({
              type: "success",
              title: "Unit updated successfully"
            });
            this.$Progress.finish();
          })
          .catch(() => {
            // failed response
            swal.fire("", "Unit Update Failed", "error");
            this.$Progress.fail();
          });
      }
    },
    createUnit() {
      this.$Progress.start();
      this.form
        .post("api/unit")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "Unit created successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadUnits();
    Fire.$on("AfterAction", () => {
      this.loadUnits();
    });
  }
};
</script>
