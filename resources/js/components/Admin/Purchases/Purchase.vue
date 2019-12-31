<template>
  <div style="margin-top: -40px;">
    <div class="row">
      <div class="col-12 mx-0">
        <!-- purchase heading -->
        <div style="background: #563D7C">
          <h5 class="text-light pl-3" style="line-height: 35px">
            {{ mode == 'purchase' ? "Purchase Product" : "Purchase Edit" }}
            <span
              class="float-right mr-4"
            >Date: {{ currentDate() | digitalDate }}</span>
          </h5>
        </div>

        <!-- Supplier info -->
        <div class="mb-2" style="border-bottom: 3px solid #007ACC">
          <!-- top buttons -->
          <div class="row">
            <div class="col-md-6">
              <el-button
                type="success"
                size="mini"
                class="float-left"
                @click="cashPurchase = !cashPurchase"
                v-show="mode == 'purchase'"
              >{{ cashPurchase ? 'Regular Supplier' : 'Cash' }}</el-button>
              <el-button
                type="success"
                size="mini"
                class="float-left"
                @click="clearAll"
                v-show="mode == 'purchase'"
              >Clear</el-button>
              <el-button type="success" size="mini" class="float-left">
                <router-link
                  to="purchase-list"
                  class="text-light"
                  style="text-decoration: none"
                >All Purchases</router-link>
              </el-button>
            </div>
          </div>

          <!-- For Existing Supplier -->
          <div class="row" v-if="!cashPurchase">
            <!-- supplier info -->
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <label for="supplier">Supplier Name</label>
                  <cool-select
                    :items="suppliers"
                    v-model="supplier"
                    id="supplier"
                    placeholder="Select Supplier"
                  />
                </div>
              </div>
            </div>

            <!-- calculate amount -->
            <div class="col-md-6" v-if="!cashPurchase">
              <div class="row">
                <div class="col-md-12 ml-auto">
                  <table class="payment-area">
                    <tr>
                      <td class="td-left">Previous Due</td>
                      <td class="td-right">
                        <span>{{ supplierInfo.previous_due }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-left">Invoice Total</td>
                      <td class="td-right">
                        <span>{{ supplierInfo.subTotal }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-left">Discount</td>
                      <td class="td-right">
                        <input
                          type="number"
                          id="discount"
                          v-model="supplierInfo.discount"
                          @keyup="updateAmount"
                        />
                      </td>
                    </tr>
                    <tr>
                      <td class="td-left">Payable Amount</td>
                      <td class="td-right">
                        <span>{{ supplierInfo.grandTotal }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-left">Given Amount</td>
                      <td class="td-right">
                        <input
                          type="number"
                          id="given_amount"
                          v-model="supplierInfo.given_amount"
                          @keyup="updateAmount"
                        />
                      </td>
                    </tr>
                    <tr>
                      <td class="td-left">Current Due</td>
                      <td class="td-right">
                        <span>{{ supplierInfo.current_due }}</span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- For Cash customer -->
          <div class="row container" v-if="cashPurchase">
            <div class="col-md-4">
              <label for="name">Supplier Name</label>
              <input
                v-model="supplierInfo.name"
                type="text"
                class="form-control form-control-sm"
                placeholder="Supplier name"
                id="name"
              />
            </div>
            <div class="col-md-4">
              <label for="mobile">Mobile</label>
              <input
                v-model="supplierInfo.mobile"
                type="text"
                class="form-control form-control-sm"
                placeholder="Mobile Number"
                id="mobile"
              />
            </div>
            <div class="col-md-8 form-group">
              <label for="customer_address">Address</label>
              <textarea
                v-model="supplierInfo.address"
                row="2"
                class="form-control form-control-sm"
                height="30px"
                placeholder="Address"
                id="address"
              ></textarea>
            </div>
          </div>
        </div>
        <!-- End customer info -->

        <!-- Add new product item into list -->
        <div class="container">
          <div class="row mb-3">
            <!-- Product -->
            <div class="form-group col-md-4">
              <label for="product">Product Name</label>
              <cool-select
                :items="products"
                id="product"
                v-model="selectedName"
                placeholder="Select Product"
              />
            </div>
            <div class="form-group col-md-4">
              <label for="product_code">Product Code</label>
              <input
                type="text"
                readonly
                v-model="selectedProductCode"
                class="form-control"
                id="product_code"
                placeholder="Product / Bar Code"
              />
            </div>

            <!-- quantity -->
            <div class="form-group col-md-2">
              <label for="quantity">Quantity</label>
              <input
                v-model="selectedQuantity"
                min="0"
                type="number"
                placeholder="Quantity"
                class="form-control"
              />
            </div>

            <!-- price -->
            <div class="form-group col-md-2">
              <label for="price">Purchase Price</label>
              <input
                v-model="selectedPrice"
                @keyup.enter="addItem"
                min="0"
                type="number"
                name="price"
                placeholder="Price"
                class="form-control"
              />
            </div>

            <div class="col-12 text-right">
              <button type="button" @click="addItem" class="btn btn-primary btn-sm">Add Item</button>
            </div>
          </div>

          <div class="row">
            {{ getData }}
            <div class="col-12">
              <table class="table table-sm table-striped">
                <tr class="bg-dark">
                  <th>Sln</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Pricie</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(item, index) in shopItems" :key="index">
                  <td>{{ index+1 }}</td>
                  <td>{{ item.productName }}</td>
                  <td>{{ item.quantity }}</td>
                  <td class="text-left">{{ item.price }} Tk.</td>
                  <td class="text-left">{{ item.quantity * item.price }} Tk.</td>
                  <td>
                    <a href="#" @click="removeItem(index)">
                      <i class="fas fa-times red" style="font-size: 18px;"></i>
                    </a>
                  </td>
                </tr>
                <tfoot>
                  <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th>{{ supplierInfo.grandTotal }} Tk.</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- submit or purchase products -->
          <div class="col-12 text-right">
            <button
              @click="purchaseProduct()"
              class="btn btn-primary btn-sm"
            >{{ mode == "purchase" ? "Purchase" : "Update Purchase" }}</button>
          </div>
        </div>
      </div>
      <!-- End Add new product item into list -->
    </div>
  </div>
</template>



<script>
// for autocomplete
import { CoolSelect } from "vue-cool-select";

export default {
  components: {
    CoolSelect
  },
  props: ["prop_data"],
  // all data
  data() {
    return {
      mode: "purchase",
      suppliers: [],
      suppliers_id: [],
      supplier: "",
      prices: [],
      product_codes: [],
      products: [],
      purchases: [],
      // grandTotal: 0,
      // totalQuantity: 0,

      cashPurchase: false,

      selectedName: "",
      selectedPrice: 0,
      selectedQuantity: 1,
      selectedProductCode: "",

      supplierInfo: {
        cashPurchase: false,
        name: "",
        mobile: "",
        address: "",
        previous_due: 0,
        subTotal: 0,
        discount: 0,
        grandTotal: 0,
        given_amount: 0,
        current_due: 0,
        totalQuantity: 0
      },

      shopItems: [],
      oldPurchase: [],
      form: {
        productName: "",
        product_code: "",
        quantity: 1,
        price: 0.0
      }
    };
  },

  // computed
  computed: {
    getData() {
      if (this.selectedName != null) {
        const index = this.products.findIndex(
          product => product === this.selectedName
        );
        this.selectedPrice = this.prices[index];
        this.selectedProductCode = this.product_codes[index];
      }
    }
  },

  // mounted
  mounted() {
    if (this.prop_data) {
      this.mode = "purchase edit";
      axios.get("api/purchase/invoice/" + this.prop_data).then(({ data }) => {
        this.oldPurchase = data;
        this.populateOldPurchase();
      });
    } else {
      this.mode = "purchase";
    }
    // load suppliers
    axios.get("api/supplier-list").then(({ data }) => {
      for (var i = 0; i < data.length; i++) {
        if (i != 0) {
          this.suppliers.push(data[i].name);
          this.suppliers_id.push(data[i].id);
        }
      }
    });
    // load products
    axios.get("api/product-list").then(({ data }) => {
      for (var i = 0; i < data.length; i++) {
        this.products.push(data[i]["name"]);
        this.prices.push(data[i]["purchase_price"]);
        this.product_codes.push(data[i]["product_code"]);
      }
    });
  },

  watch: {
    supplier: function(val) {
      if (this.supplier == null) {
        this.supplierInfo.previous_due = 0;
        this.supplierInfo.subTotal = 0;
        this.supplierInfo.discount = 0;
        this.supplierInfo.grandTotal = 0;
        this.supplierInfo.given_amount = 0;
        this.supplierInfo.current_due = 0;
      } else {
        var id = this.suppliers_id[this.suppliers.indexOf(this.supplier)];
        axios.get("api/supplier/previous-dues/" + id).then(({ data }) => {
          this.supplierInfo.previous_due = data;
          this.updateAmount();
        });
      }
    }
  },
  // methods
  methods: {
    currentDate() {
      return new Date();
    },
    updateAmount() {
      this.supplierInfo.grandTotal =
        this.supplierInfo.subTotal - this.supplierInfo.discount;
      this.supplierInfo.current_due =
        this.supplierInfo.previous_due +
        this.supplierInfo.grandTotal -
        this.supplierInfo.given_amount;
    },
    populateOldPurchase() {
      this.supplier = this.oldPurchase.name;

      let prop_product = this.oldPurchase.purchase_items;
      for (let i = 0; i < prop_product.length; i++) {
        this.form.productName = prop_product[i].product.name;
        this.form.product_code = prop_product[i].product_code;
        this.form.quantity = prop_product[i].quantity;
        this.form.price = prop_product[i].price;

        this.shopItems.push(this.form);
        this.form = {};
      }
      var total = 0;
      var quantity = 0;
      for (var i = 0; i < Object.keys(this.shopItems).length; i++) {
        total += this.shopItems[i].quantity * this.shopItems[i].price;
        quantity += parseInt(this.shopItems[i].quantity);
      }
      this.supplierInfo.grandTotal = total;
      this.supplierInfo.totalQuantity = quantity;
    },
    // clear all data
    clearAll() {
      // reset all data
      this.shopItems = null;
      // this.grandTotal = 0;

      this.supplier = "";

      this.selectedName = "";
      this.selectedPrice = 0;
      this.selectedQuantity = 0;
      this.selectedProductCode = "";

      this.supplierInfo.name = "";
      this.supplierInfo.mobile = "";
      this.supplierInfo.address = "";
      this.supplierInfo.totalQuantity = 0;
      this.supplierInfo.grandTotal = 0;
    },

    // remove a item from list
    removeItem(index) {
      this.shopItems.splice(index, 1);
      var total = 0;
      var quantity = 0;
      for (var i = 0; i < Object.keys(this.shopItems).length; i++) {
        total += this.shopItems[i].quantity * this.shopItems[i].price;
        quantity += this.shopItems[i].quantity;
      }
      this.supplierInfo.grandTotal = total;
      this.supplierInfo.totalQuantity = quantity;
      this.updateAmount();
    },

    // add new item to list
    addItem() {
      this.form.productName = this.selectedName;
      this.form.product_code = this.selectedProductCode;
      this.form.quantity = this.selectedQuantity;
      this.form.price = this.selectedPrice;

      if (
        this.form.productName != null &&
        this.form.quantity > 0 &&
        this.form.price > 0
      ) {
        this.checkAndAddItem(this.form);
        var total = 0;
        var quantity = 0;
        for (var i = 0; i < Object.keys(this.shopItems).length; i++) {
          total += this.shopItems[i].quantity * this.shopItems[i].price;
          quantity += parseInt(this.shopItems[i].quantity);
        }
        this.supplierInfo.subTotal = total;
        this.supplierInfo.totalQuantity = quantity;
        this.updateAmount();

        this.form = {};
        this.form.quantity = 1;
        this.form.price = 0;

        this.selectedName = "";
        this.selectedProductCode = "";
        this.selectedPrice = 0;
        this.selectedQuantity = 1;
      }
    },

    // first check item exist or not if item exist then add only quantity, if product not exist then add product
    checkAndAddItem(obj) {
      for (var i = 0; i < this.shopItems.length; i++) {
        if (this.shopItems[i].productName === obj.productName) {
          this.shopItems[i].quantity =
            parseInt(this.shopItems[i].quantity) + parseInt(obj.quantity);
          return;
        }
      }
      this.shopItems.push(obj);
    },

    // results for pagination
    getResults(page = 1) {
      axios.get("api/purchase?page=" + page).then(response => {
        this.purchases = response.data;
      });
    },

    // product purchase
    purchaseProduct() {
      this.$Progress.start();
      this.supplierInfo.cashPurchase = this.cashPurchase;

      // this.supplierInfo.grandTotal = this.grandTotal;
      // this.supplierInfo.totalQuantity = this.totalQuantity;

      if (!this.cashPurchase) {
        this.supplierInfo.name = this.supplier;
      }

      if (this.supplierInfo.name == null) {
        toast.fire({
          type: "error",
          title: "Fill Supplier"
        });
      } else if (this.supplierInfo.grandTotal < 1) {
        toast.fire({
          type: "error",
          title: "Item can not be null"
        });
      } else {
        if (this.mode == "purchase") {
          axios({
            method: "post",
            url: "api/purchase",
            data: {
              shopItems: JSON.stringify(this.shopItems),
              supplierInfo: JSON.stringify(this.supplierInfo)
            }
          })
            .then(() => {
              this.$Progress.finish();
              Fire.$emit("AfterAction");
              toast.fire({
                type: "success",
                title: "Purchase create Successfull"
              });

              // reset all data
              this.clearAll();
            })
            .catch(res => {
              this.$Progress.fail();
              toast.fire({
                type: "error",
                title: "Products purchase failed" + res
              });
            });
        } else {
          axios({
            method: "put",
            url: "api/purchase/" + this.prop_data,
            data: {
              shopItems: JSON.stringify(this.shopItems),
              supplierInfo: JSON.stringify(this.supplierInfo),
              oldPurchase: JSON.stringify(this.oldPurchase)
            }
          })
            .then(() => {
              this.$Progress.finish();
              Fire.$emit("AfterAction");
              toast.fire({
                type: "success",
                title: "Purchase Update Successfull"
              });

              // reset all data
              this.clearAll();
            })
            .catch(res => {
              this.$Progress.fail();
              toast.fire({
                type: "error",
                title: "Products purchase failed " + res
              });
            });
        }
      }
    }
  } // end method
};
</script>

<style scoped>
.payment-area {
  margin-top: -30px;
  margin-left: auto;
  padding-left: 10px;
  margin-right: 0;
  padding-bottom: 0;
  width: 250px !important;
}
.payment-area tr {
  margin-top: 0;
  margin-bottom: 0;
}
td input {
  width: 150px;
  font-size: 13px;
  padding-left: 8px;
}
.td-left {
  font-size: 12px;
  width: 100px !important;
}
.td-right {
  font-size: 13px;
}
td span {
  display: inline-block;
  margin-left: 10px;
}
</style>
