><template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header" style="background: #563D7C">
            <div>
              <!-- Products heading -->
              <div class="card-title text-light">Products Table</div>

              <!-- show item number -->
            </div>

            <div class="card-tools">
              <table>
                <tr>
                  <td>
                    <div class="input-group float-right mx-3">
                      <select
                        class="form-control text-light"
                        v-model="perPage"
                        @change="byPerPage"
                        style="background: transparent"
                      >
                        <option class="bg-white text-dark" value="2">2</option>
                        <option class="bg-white text-dark" value="4">4</option>
                        <option class="bg-white text-dark" value="5">5</option>
                        <option class="bg-white text-dark" value="6">6</option>
                        <option class="bg-white text-dark" value="7">7</option>
                      </select>
                    </div>
                  </td>
                  <td>
                    <!-- Product Search -->
                    <div class="input-group float-right">
                      <input
                        type="text"
                        @keyup="search_product"
                        v-model="search"
                        class="form-control text-light"
                        style="background: #563D7C"
                        placeholder="Search Product"
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
                    >Add Product</button>
                  </td>
                </tr>
              </table>
            </div>
          </div>

          <!-- /.card-header -->
          <div class="card-body table-responsive p-0" v-if="products.data.length">
            <table class="table table-hover">
              <tbody>
                <tr class="bg-primary">
                  <th>ID</th>
                  <th class="cursor" @click="setOrder('name')">
                    Name
                    <i
                      class="fa"
                      :class="{'fa-sort': orderField != 'name', 'fa-sort-up': orderField=='name' && order=='asc', 'fa-sort-down': orderField=='name' && order=='desc'}"
                    ></i>
                  </th>
                  <th class="cursor" @click="setOrder('product_code')">
                    Product Code
                    <i
                      class="fa"
                      :class="{'fa-sort': orderField != 'product_code', 'fa-sort-up': orderField=='product_code' && order=='asc', 'fa-sort-down': orderField=='product_code' && order=='desc'}"
                    ></i>
                  </th>
                  <th class="cursor" @click="setOrder('category')">
                    Category
                    <i
                      class="fa"
                      :class="{'fa-sort': orderField != 'category', 'fa-sort-up': orderField=='category' && order=='asc', 'fa-sort-down': orderField=='category' && order=='desc'}"
                    ></i>
                  </th>
                  <th class="cursor" @click="setOrder('opening_stock')">
                    Opening Stock
                    <i
                      class="fa"
                      :class="{'fa-sort': orderField != 'opening_stock', 'fa-sort-up': orderField=='opening_stock' && order=='asc', 'fa-sort-down': orderField=='opening_stock' && order=='desc'}"
                    ></i>
                  </th>
                  <th class="cursor" @click="setOrder('unit')">
                    Unit
                    <i
                      class="fa"
                      :class="{'fa-sort': orderField != 'unit', 'fa-sort-up': orderField=='unit' && order=='asc', 'fa-sort-down': orderField=='unit' && order=='desc'}"
                    ></i>
                  </th>
                  <th>Action</th>
                </tr>
                <tr v-for="(product, index) in products.data" :key="index">
                  <td>{{ index+1 }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.product_code }}</td>
                  <td>{{ product.category.name }}</td>
                  <td>{{ product.opening_stock }}</td>
                  <td>{{ product.unit.name }}</td>
                  <td>
                    <a href="#" @click="editModal(product)">
                      <i class="fas fa-edit green" style="font-size: 25px;"></i>
                    </a> &nbsp;
                    <a href="#" @click="deleteProduct(product.product_code)">
                      <i class="fas fa-trash red" style="font-size: 25px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- if prodcut empty -->
          <div v-else class="text-center py-3">
            <h3>No Product created.</h3>
            <p
              class="text-primary"
              style="cursor: pointer"
              @click="newModal"
            >Please create a product</p>
          </div>
          <div class="card-footer" v-if="products.data.length > 0">
            From {{ products.from }} To {{ products.to }} of {{ products.total }} items
            <pagination :data="products" @pagination-change-page="getResults" class="float-right"></pagination>
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
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update Product</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add New Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="text-light" aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- END MODAL HEADER -->

          <form @submit.prevent="editMode ? updateProduct() : createProduct()">
            <!-- modal body -->
            <div class="modal-body" style="background: white">
              <div class="row">
                <!-- product name -->
                <div class="form-group col-12">
                  <label for="name">Product Name</label>
                  <input
                    v-model="form.name"
                    title="Product Name Required"
                    type="text"
                    id="name"
                    placeholder="Product Name"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>

                <!-- Category -->
                <label class="ml-2 pl-2">Category</label>
                <div class="input-group col-12 mb-3">
                  <select
                    v-model="form.category_id"
                    id="category_id"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('category_id') }"
                  >
                    <option value>Select Category</option>
                    <option
                      v-for="category in categories"
                      :key="category.id"
                      :value="category.id"
                    >{{ category.name }}</option>
                  </select>
                  <div class="input-group-append">
                    <a href="/category" class="input-group-text">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <has-error :form="form" field="category_id"></has-error>
                </div>

                <!-- Product Unit -->
                <div class="form-group col-md-6">
                  <label for="unit_id">Product Unit</label>
                  <select
                    v-model="form.unit_id"
                    id="unit_id"
                    class="form-control form-control-sm"
                    :class="{ 'is-invalid': form.errors.has('unit_id') }"
                  >
                    <option value>Select Unit</option>
                    <option v-for="unit in units" :key="unit.id" :value="unit.id">{{ unit.name }}</option>
                  </select>
                  <has-error :form="form" field="unit_id"></has-error>
                </div>

                <!-- opening stock -->
                <div class="form-group col-md-6">
                  <label for="opening_stock">Opening Stock</label>
                  <input
                    v-model="form.opening_stock"
                    type="number"
                    min="0"
                    id="opening_stock"
                    placeholder="Opening stock"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- Purchase Price -->
                <div class="form-group col-md-6">
                  <label for="purchase_price">Purchase Price</label>
                  <input
                    v-model="form.purchase_price"
                    id="purchase_price"
                    type="number"
                    min="0"
                    placeholder="Price"
                    class="form-control form-control-sm"
                  />
                </div>

                <!-- Sale Price -->
                <div class="form-group col-md-6">
                  <label for="sale_price">Sale Price</label>
                  <input
                    type="number"
                    min="0"
                    v-model="form.sale_price"
                    id="sale_price"
                    placeholder="Price"
                    class="form-control form-control-sm"
                  />
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
// for autocomplete
import { CoolSelect } from "vue-cool-select";

export default {
  components: {
    CoolSelect
  },
  data() {
    return {
      selected: null,
      category_items: [],

      editMode: false,
      loadCategories: false,
      categories: {},
      products: {},
      units: {},

      perPage: 5,
      order: "asc",
      orderField: "id",

      search: "",

      form: new Form({
        id: "",
        name: "",
        category_id: "",
        opening_stock: 0,
        product_code: "",
        purchase_price: 0,
        sale_price: 0,
        weight: "",
        unit_id: "",
        image: "",
        status: ""
      })
    };
  },

  // methods
  methods: {
    byPerPage() {
      this.orderField = "product_code";
      this.customDatatabe();
    },
    customDatatabe() {
      axios
        .get(
          "/api/product/" +
            this.perPage +
            "/filter/" +
            this.order +
            "/order/" +
            this.orderField +
            "/as"
        )
        // controler e ki json return kore nah
        .then(({ data }) => (this.products = data));
    },
    setOrder(field) {
      if (field == this.orderField) {
        // console.log("reversed");
        if (this.order == "asc") {
          this.order = "desc";
        } else {
          this.order = "asc";
        }
      } else {
        this.orderField = field;
        // console.log("set asc");
        this.order = "asc";
      }
      this.customDatatabe();
      // console.log("Field : " + field + ", Order : " + this.order);
    },
    getResults(page = 1) {
      axios
        .get("api/product/paginate" + this.perPage + "?page=" + page)
        // .get("api/product?page=" + page + "&" + "item=" + this.perPage)
        .then(response => {
          this.products = response.data;
          // console.log("paginate");
        });
    },
    // modal for edit product
    editModal(product) {
      this.editMode = true;
      this.form.reset();
      if (!this.loadCategories) {
        axios
          .get("api/category-list")
          .then(({ data }) => (this.categories = data.data));
        axios.get("api/unit-list").then(({ data }) => (this.units = data.data));
        this.loadCategories = true;
      }
      $("#addModal").modal("show");
      this.form.fill(product);
    },
    // modal for create new product
    newModal() {
      if (!this.loadCategories) {
        axios
          .get("api/category-list")
          .then(({ data }) => (this.categories = data.data));
        axios.get("api/unit-list").then(({ data }) => (this.units = data.data));
        this.loadCategories = true;
      }
      var category = this.categories;
      for (var i = 0; i < Object.keys(category).length; i++) {
        this.category_items.push(category[i]["name"]);
      }

      this.editMode = false;
      this.form.reset();
      $("#addModal").modal("show");
    },
    deleteProduct(product_code) {
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
              .delete("api/product/" + product_code)
              .then(() => {
                toast.fire({
                  type: "success",
                  title: "Product Deleted Successfully"
                });
                Fire.$emit("AfterAction");
              })
              .catch(() => {
                swal.fire("", "Product Deleted Failed", "error");
              });
          }
        });
    },
    // load product when page initial
    loadProducts() {
      axios.get("api/product").then(({ data }) => (this.products = data));
    },
    // Search product
    search_product() {
      if (this.search != "") {
        axios
          .get("api/product/search/" + this.search)
          .then(({ data }) => (this.products = data));
      } else {
        this.loadProducts();
      }
    },
    // Update product
    updateProduct() {
      this.$Progress.start();
      this.form
        .put("api/product/" + this.form.product_code)
        .then(() => {
          // success action
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast.fire({
            type: "success",
            title: "product updated successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          toast.fire({
            type: "fail",
            title: "Product updated failed"
          });
          this.$Progress.fail();
        });
    },
    // create new product
    createProduct() {
      this.$Progress.start();
      this.form
        .post("api/product")
        .then(() => {
          Fire.$emit("AfterAction");
          $("#addModal").modal("hide");
          toast
            .fire({
              type: "success",
              title: "Product created successfully"
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
    this.loadProducts();
    Fire.$on("AfterAction", () => {
      this.loadProducts();
    });
  }
};
</script>

<style>
.cursor {
  cursor: pointer;
}
</style>