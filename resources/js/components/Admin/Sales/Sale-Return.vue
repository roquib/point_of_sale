<template>
  <div class>
    <div class="row">
      <div class="col-12 mx-0">
        <!-- sale -->
        <div style="background: #563D7C">
          <h5 class="text-light pl-3" style="line-height: 35px">Sales Return</h5>
        </div>

        <div class="container">
          <div class="row">
            <!-- Customer -->
            <div class="form-group col-md-4">
              <label for="customer">Customer Name</label>
              <cool-select
                :items="customers"
                v-model="customer"
                id="customer"
                placeholder="Select Customer"
              />
            </div>
            <!-- purchase date -->
            <div class="col-md-3 ml-auto text-right">
              <label for="date">Date: {{ currentDate() | digitalDate }}</label>
            </div>
          </div>
        </div>

        <div class="container">
          <div class="row mb-3">
            <!-- Product -->
            <div class="form-group col-md-6">
              <label for="product">Product Name</label>
              <cool-select
                :items="products"
                id="product"
                v-model="selectedProduct"
                placeholder="Select Product"
              />
            </div>

            <!-- quantity -->
            <div class="form-group col-md-3">
              <label for="quantity">Add Quantity</label>
              <input
                v-model="selectedQuantity"
                min="0"
                type="number"
                placeholder="Product Quantity"
                class="form-control form-control-sm"
              />
              <small
                class="text-danger"
                v-if="invalidQuantityMessage != '' && returnableQuantity > 0"
              >{{ invalidQuantityMessage }}</small>
            </div>

            <!-- price -->
            <div class="form-group col-md-3">
              <label for="price">Purchase Price</label>
              <input
                v-model="selectedPrice"
                @keyup.enter="addItem"
                min="0"
                type="number"
                name="price"
                placeholder="Product Price"
                class="form-control form-control-sm"
              />
            </div>

            <div class="col-12 text-right">
              <button type="button" @click="addItem" class="btn btn-primary btn-sm">Add Item</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end v-else -->
    </div>

    <div class="row">
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
          <tr v-for="(item, index) in returnItems" :key="index">
            <td>{{ index+1 }}</td>
            <td>{{ item.productName }}</td>
            <td>{{ item.productQuantity }}</td>
            <td class="text-left">{{ item.productPrice }} Tk.</td>
            <td class="text-left">{{ item.productQuantity * item.productPrice }} Tk.</td>
            <td>
              <a href="#" @click="removeItem(index)">
                <i class="fas fa-times red" style="font-size: 20px;"></i>
              </a>
            </td>
          </tr>
          <tfoot>
            <tr>
              <th colspan="4" class="text-right">Total</th>
              <th>{{ grandTotal }} Tk.</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="col-12 text-right mb-3">
      <button type="submit" @click="returnProducts" class="btn btn-primary btn-sm">Return Products</button>
    </div>
  </div>
</template>



<script>
// for autocomplete
import { CoolSelect } from "vue-cool-select";

export default {
  // cool select component
  components: {
    CoolSelect
  },
  mounted() {
    // load customers
    axios.get("api/customer-list").then(({ data }) => {
      for (var i = 0; i < data.length; i++) {
        this.customers.push(data[i]);
      }
    });
  },

  data() {
    return {
      customers: [],
      customer: "",
      products: [],
      productsDetail: [],

      selectedProduct: "",
      selectedProductCode: "",
      selectedQuantity: 0,
      selectedPrice: 0,

      // items for return
      returnItems: [],
      grandTotal: 0,
      totalQty: 0,

      returnInfo: {
        customer: "",
        totalQty: 0,
        grandTotal: 0
      },

      // for checking
      returnableQuantity: 0,
      invalidQuantityMessage: ""
    };
  },

  watch: {
    customer: function(val) {
      // load returnable products for this customer

      if (this.customer != null) {
        axios
          .get("api/sale/returnable/products/" + this.customer)
          .then(({ data }) => {
            this.productsDetail = [];
            this.products = [];

            for (var inv = 0; inv < data.length; inv++) {
              for (
                var item = 0;
                item < data[inv]["sale_items"].length;
                item++
              ) {
                var product = data[inv]["sale_items"][item]["product"]["name"];
                var productCode =
                  data[inv]["sale_items"][item]["product"]["product_code"];
                var productQuantity = data[inv]["sale_items"][item]["quantity"];
                var productPrice = data[inv]["sale_items"][item]["price"];

                // push item to object or update quantity if exist
                var newProduct = {
                  productName: product,
                  productCode: productCode,
                  productQuantity: productQuantity,
                  productPrice: productPrice
                };

                if (this.products.indexOf(product) == -1) {
                  this.products.push(product);
                  this.productsDetail.push(newProduct);
                } else {
                  for (var i = 0; i < this.productsDetail.length; i++) {
                    if (
                      this.productsDetail[i].productName ===
                      newProduct.productName
                    ) {
                      this.productsDetail[i].productQuantity +=
                        newProduct.productQuantity;
                    } else {
                    }
                  }
                }
                // end
              }
            }
          });
      }
    },

    selectedProduct: function(val) {
      if (this.selectedProduct != null) {
        this.returnableQuantity = this.productsDetail[
          this.products.indexOf(this.selectedProduct)
        ].productQuantity;
        this.selectedPrice = this.productsDetail[
          this.products.indexOf(this.selectedProduct)
        ].productPrice;
        this.selectedProductCode = this.productsDetail[
          this.products.indexOf(this.selectedProduct)
        ].productCode;

        this.invalidQuantityMessage = "";
        this.selectedQuantity = 0;
      }
    },

    selectedQuantity: function(val) {
      if (this.selectedQuantity != null) {
        if (this.selectedQuantity > this.returnableQuantity) {
          this.selectedQuantity = this.returnableQuantity;
        }
        if (this.selectedQuantity >= this.returnableQuantity) {
          this.invalidQuantityMessage =
            "not more than " + this.returnableQuantity;
        } else {
          this.invalidQuantityMessage = "";
        }
      }
    }
  },

  methods: {
    currentDate() {
      return new Date();
    },

    removeItem(index) {
      this.returnItems.splice(index, 1);
      this.totalAmount();
    },

    totalAmount() {
      var total = 0;
      var qty = 0;
      for (var i = 0; i < Object.keys(this.returnItems).length; i++) {
        total +=
          this.returnItems[i].productQuantity *
          this.returnItems[i].productPrice;
        qty += this.returnItems[i].productQuantity;
      }
      this.grandTotal = total;
      this.totalQty = qty;
    },

    addItem() {
      // console.log("add item");
      var itemExist = false;
      if (
        this.selectedProduct != null &&
        this.selectedQuantity > 0 &&
        this.selectedPrice > 0
      ) {
        var newProduct = {
          productName: this.selectedProduct,
          productCode: this.selectedProductCode,
          productQuantity: parseInt(this.selectedQuantity),
          productPrice: this.selectedPrice
        };

        for (var i = 0; i < this.returnItems.length; i++) {
          if (this.returnItems[i].productName === this.selectedProduct) {
            if (
              parseInt(this.returnItems[i].productQuantity) +
                parseInt(newProduct.productQuantity) >
              parseInt(this.returnableQuantity)
            ) {
              toast.fire({
                type: "warning",
                title: "Return quantity not more than purchase quantity"
              });
              itemExist = true;
            } else {
              this.returnItems[i].productQuantity += parseInt(
                newProduct.productQuantity
              );
              itemExist = true;
              break;
            }
          }
        }

        if (!itemExist) {
          this.returnItems.push(newProduct);
        }
        this.totalAmount();
        this.selectedProduct = null;
      }
    },

    // product return method
    returnProducts() {
      if (this.customer == null) {
        // customer empty
        toast.fire({
          type: "warning",
          title: "Customer cann't be empty"
        });
      } else {
        if (this.returnItems.length) {
          this.returnInfo.customer = this.customer;
          this.returnInfo.totalQty = this.totalQty;
          this.returnInfo.grandTotal = this.grandTotal;

          axios({
            method: "post",
            url: "api/sale-return/",
            data: {
              returnInfo: JSON.stringify(this.returnInfo),
              returnItems: JSON.stringify(this.returnItems)
            }
          })
            .then(() => {
              toast.fire({
                type: "success",
                title: "Product return Successfully"
              });
              this.returnInfo.totalQty = 0;
              this.returnInfo.grandTotal = 0;
              this.returnInfo.customer = 0;
              this.returnItems = null;
              this.products = null;
              this.productsDetail = null;
              this.selectedPrice = 0;
              this.returnableQuantity = 0;
              this.totalQty = 0;
              this.totalAmount = 0;
              this.grandTotal = 0;
              this.customer = null;
            })
            .catch(() => {
              swal.fire("", "Product Return Failed", "error");
            });
        } else {
          toast.fire({
            type: "warning",
            title: "At least return 1 item"
          });
        }
      }
    }
  } // end method
};
</script>
