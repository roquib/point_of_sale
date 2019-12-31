<template>
  <div class>
    <div class="row">
      <div class="col-12 mx-0">
        <!-- sale -->
        <div style="background: #563D7C">
          <h5
            class="text-light pl-3"
            style="line-height: 35px"
          >{{ mode == 'sale' ? "Sale Product" : "Sale Edit" }}</h5>
        </div>

        <!-- Customer info -->
        <div class="container mb-2" style="border-bottom: 3px solid #007ACC">
          <div class="row container">
            <div class="col-md-6">
              <el-button
                type="success"
                size="mini"
                class="float-left"
                @click="cashSale = !cashSale"
              >{{ cashSale ? 'Regular Customer' : 'Cash' }}</el-button>
              <el-button type="success" size="mini" class="float-left" @click="clearAll">Clear</el-button>
              <el-button type="success" size="mini" class="float-left">
                <router-link
                  to="sale-list"
                  class="text-light"
                  style="text-decoration: none"
                >All Sales</router-link>
              </el-button>
            </div>
            <div class="col-md-6 text-right">
              <!-- current date for sale -->
              Date: {{ currentDate() | digitalDate }}
            </div>
          </div>

          <!-- For Existing Customer -->
          <div class="row" v-if="!cashSale">
            <div class="form-group col-md-4">
              <label for="customer">Customer Name</label>
              <cool-select
                :items="customers"
                v-model="customer"
                id="customer"
                placeholder="Select Customer"
              />
            </div>
          </div>

          <!-- For Cash customer -->
          <div class="row container" v-if="cashSale">
            <div class="col-md-4">
              <label for="customer_name">Custoemr Name</label>
              <input
                v-model="customerInfo.customerName"
                type="text"
                class="form-control form-control-sm"
                placeholder="Customer name"
                id="customer_name"
              />
            </div>
            <div class="col-md-4">
              <label for="customer_mobile">Mobile</label>
              <input
                v-model="customerInfo.customerMobile"
                type="text"
                class="form-control form-control-sm"
                placeholder="Mobile Number"
                id="customer_mobile"
              />
            </div>
            <div class="col-md-8 form-group">
              <label for="customer_address">Address</label>
              <textarea
                v-model="customerInfo.customerAddress"
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
              <label for="price">Sale Price</label>
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
                    <th>{{ grandTotal }} Tk.</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- submit or sale products -->
          <div class="col-12 text-right">
            <button
              type="submit"
              @click="saleProduct()"
              class="btn btn-primary btn-sm"
            >{{ mode == "sale" ? "Sale" : "Update Sale" }}</button>
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
      mode: "sale",
      customers: [],
      customer: "",
      prices: [],
      product_codes: [],
      products: [],
      sales: [],
      search: "",
      grandTotal: 0,
      totalQuantity: 0,

      cashSale: false,

      selectedName: "",
      selectedPrice: 0,
      selectedQuantity: 1,
      selectedProductCode: "",

      customerInfo: {
        cashSale: false,
        name: "",
        mobile: "",
        address: "",
        totalQuantity: 0,
        grandTotal: 0
      },

      shopItems: [],
      oldSale: [],
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
      this.mode = "sale edit";
      axios.get("api/sale/invoice/" + this.prop_data).then(({ data }) => {
        this.oldPurchase = data;
        this.populateOldSale();
      });
    } else {
      this.mode = "sale";
    }
    // load customers
    axios.get("api/customer-list").then(({ data }) => {
      for (var i = 0; i < data.length; i++) {
        if (i != 0) {
          this.customers.push(data[i]);
        }
      }
    });
    // load products
    axios.get("api/product-list").then(({ data }) => {
      for (var i = 0; i < data.length; i++) {
        this.products.push(data[i]["name"]);
        this.prices.push(data[i]["sale_price"]);
        this.product_codes.push(data[i]["product_code"]);
      }
    });
  },

  // methods
  methods: {
    currentDate() {
      return new Date();
    },
    populateOldSale() {
      this.customer = this.oldSale.name;

      let prop_product = this.oldSale.sale_items;
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
      this.grandTotal = total;
      this.totalQuantity = quantity;
    },
    // clear all data
    clearAll() {
      // reset all data
      this.shopItems = null;
      this.grandTotal = 0;

      this.customer = "";

      this.selectedName = "";
      this.selectedPrice = 0;
      this.selectedQuantity = 0;
      this.selectedProductCode = "";

      this.customerInfo.name = "";
      this.customerInfo.mobile = "";
      this.customerInfo.address = "";
      this.customerInfo.totalQuantity = 0;
      this.customerInfo.grandTotal = 0;
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
      this.grandTotal = total;
      this.totalQuantity = quantity;
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
        this.grandTotal = total;
        this.totalQuantity = quantity;
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
      axios.get("api/sale?page=" + page).then(response => {
        this.sales = response.data;
      });
    },

    // product sale
    saleProduct() {
      this.$Progress.start();
      this.customerInfo.cashSale = this.cashSale;

      this.customerInfo.grandTotal = this.grandTotal;
      this.customerInfo.totalQuantity = this.totalQuantity;

      if (!this.cashSale) {
        this.customerInfo.name = this.customer;
      }

      if (this.customerInfo.name == null) {
        toast.fire({
          type: "error",
          title: "Fill Customer"
        });
      } else if (this.grandTotal < 1) {
        toast.fire({
          type: "error",
          title: "Item can not be null"
        });
      } else {
        if (this.mode == "sale") {
          axios({
            method: "post",
            url: "api/sale",
            data: {
              shopItems: JSON.stringify(this.shopItems),
              customerInfo: JSON.stringify(this.customerInfo)
            }
          })
            .then(() => {
              this.$Progress.finish();
              Fire.$emit("AfterAction");
              toast.fire({
                type: "success",
                title: "Sale create Successfull"
              });
              // reset all data
              this.clearAll();
            })
            .catch(res => {
              this.$Progress.fail();
              toast.fire({
                type: "error",
                title: "Products Sale failed" + res
              });
            });
        } else {
          axios({
            method: "put",
            url: "api/sale/" + this.prop_data.sale_inv_no,
            data: {
              shopItems: JSON.stringify(this.shopItems),
              customerInfo: JSON.stringify(this.customerInfo),
              oldSale: JSON.stringify(this.oldSale)
            }
          })
            .then(() => {
              this.$Progress.finish();
              Fire.$emit("AfterAction");
              toast.fire({
                type: "success",
                title: "Sale Update Successfull"
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

