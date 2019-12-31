<template>
  <div>
    <div class="row">
      <div class="col-12 mb-3 text-right">
        <span
          class="float-right px-3 print-invoice"
          v-show="mode=='invoice'"
          @click="printInvoice('invoice')"
        >Print</span>

        <span
          class="float-right px-2 sale-list"
          v-show="mode=='invoice'"
          @click="mode= 'sale'"
        >Sale List</span>
      </div>
      <!-- Display all sale in table -->
      <div class="col-12 mx-auto" v-if="mode != 'invoice'">
        <!-- display all sale -->
        <div class="card">
          <div class="card-header" style="background: #563D7C">
            <!-- sale heading -->
            <h3 class="card-title text-light">Sale Table</h3>

            <div class="card-tools">
              <table>
                <tr>
                  <td>
                    <!-- Product Search -->
                    <div class="input-group float-right">
                      <input
                        type="text"
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
                    <router-link to="sale" class="ml-3 btn btn-outline-warning">Sale Product</router-link>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table table-hover">
              <tbody>
                <tr class="bg-primary">
                  <th>Invoice No</th>
                  <th>Customer</th>
                  <th>Sale By</th>
                  <th>Total Quantity</th>
                  <th>Total Price</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(sale, index) in sales.data" :key="index">
                  <td
                    class="greenHover"
                    @click="showInvoice(sale.sale_inv_no)"
                  >{{ sale.sale_inv_no }}</td>
                  <td
                    style="text-transform: capitalize;"
                  >{{ sale.customer_id == 1 ? sale.name + ' (Cash)' : sale.customer.name }}</td>
                  <td
                    style="text-transform: capitalize;"
                  >{{ sale.user.first_name }} {{ sale.user.last_name }}</td>
                  <td>{{ sale.totalQty }}</td>
                  <td>{{ sale.grandTotal | bdCurrency }} Tk.</td>
                  <td>{{ sale.created_at | myDate }}</td>
                  <td>
                    <a href="#" @click="showInvoice(sale.sale_inv_no)" title="View Invoice">
                      <i class="fas fa-eye" style="font-size: 20px;"></i>
                    </a>
                    <a href="#" @click="editSale(sale.sale_inv_no)">
                      <i class="fas fa-edit green" style="font-size: 20px;"></i>
                    </a>
                    <a href="#" @click="deleteSale(sale.id, sale.sale_inv_no)">
                      <i class="fas fa-trash red" style="font-size: 20px;"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- pagination -->
          <div class="card-footer">
            <pagination :data="sales" @pagination-change-page="getResults"></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- Display all sale in table -->

      <!-- Invoice -->
      <div class="col-12 mb-3" id="invoice" v-else>
        <div class="card p-5">
          <div>
            <div class="row">
              <div class="col-md-6">
                <h4 class="font-weight-bold">Point Of Sale</h4>
                <p>
                  Ashkona, Airport,
                  <br />
                  <b>Dhaka, Bangladesh</b>
                  <br />
                  <b>01976829262</b>
                </p>
              </div>
              <div class="col-md-6 text-right">
                <h4 class="font-weight-bold text-primary" style="display: inline-block">INVOICE</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <h4 class="bg-primary p-1 pl-3">Bill From</h4>
                <strong
                  class="d-block"
                >Name: {{ invoice.customer_id == 1 ? invoice.name + ' (Cash)' : invoice.customer.name }}</strong>
                <h6
                  class="d-block"
                >Phone Number: {{ invoice.customer_id == 1 ? invoice.mobile : invoice.customer.mobile }}</h6>
                <h6
                  class="d-block"
                  v-if="invoice.customer_id !=1 && invoice.customer.email"
                >Email: {{ invoice.customer.email }}</h6>
                <h6
                  class="d-block"
                >Address: {{ invoice.customer_id == 1 ? invoice.address : invoice.customer.address }}</h6>
              </div>

              <div class="col-md-4 ml-auto">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="bg-primary p-1 pl-3" style="letter-spacing: 1.5px">
                      Invoice#
                      <span class="float-right mr-4">Date</span>
                    </h4>
                    <h6 class="px-2">
                      {{ invoice.sale_inv_no }}
                      <span
                        class="float-right"
                      >{{ invoice.created_at | myDate}}</span>
                    </h6>
                  </div>

                  <div class="col-md-12 mt-4">
                    <h4 class="bg-primary p-1 pl-3" style="letter-spacing: 1.5px">Sold by</h4>
                    <h6 class="px-2">
                      <strong>
                        {{ invoice.user.first_name | capitalize }}
                        {{ invoice.user.last_name | capitalize }}
                      </strong>
                      <small>({{ invoice.user.role | capitalize }})</small>
                    </h6>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mx-2 mt-5">
              <table class="table table-sm mb-0">
                <tr>
                  <th>Sln</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th class="text-right pr-4">Total</th>
                </tr>

                <tr v-for="(item, c) in invoice.sale_items" :key="c">
                  <td>{{ c+1 }}</td>
                  <td>{{ item.product.name }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>{{ item.price | bdCurrency }} Tk.</td>
                  <td
                    class="text-right pr-4"
                  >{{ Math.ceil(item.quantity * item.price) | bdCurrency }} Tk.</td>
                </tr>

                <tr>
                  <td colspan="5"></td>
                </tr>
              </table>
            </div>
            <div class="row mx-2">
              <div class="ml-auto mr-3">
                <strong>Subtotal &nbsp; &nbsp; :</strong>
                {{ invoice.subTotal |bdCurrency }} Tk.
                <br />
                <p style="border-bottom: 1px solid gray; margin-bottom: 0">
                  <strong>Discount &nbsp; &nbsp; :</strong>
                  {{ invoice.discount }} Tk.
                </p>
                <strong>GrandTotal :</strong>
                {{ (invoice.subTotal - invoice.discount) | bdCurrency }} Tk.
                <br />
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Invoice -->
    </div>
  </div>
</template>








<script>
export default {
  data() {
    return {
      sales: [],
      mode: "sale-list",
      invoice: {},
      showingAddModal: false,
      form: new Form({
        id: 0,
        sale_inv_no: ""
      })
    };
  },

  methods: {
    // results for pagination
    getResults(page = 1) {
      axios.get("api/sale?page=" + page).then(response => {
        this.sales = response.data;
      });
    },
    getInvoice(sale_inv_no) {
      axios
        .get("api/sale/invoice/" + sale_inv_no)
        .then(({ data }) => (this.invoice = data));
    },
    // Individual sale invoice
    showInvoice(sale_inv_no) {
      this.mode = "invoice";
      this.getInvoice(sale_inv_no);
    },
    editSale(prop_data) {
      this.$router.push({ name: "sale", params: { prop_data } });
    },
    printInvoice(el) {
      var restorepage = $("body").html();
      var printcontent = $("#" + el).clone();
      var enteredtext = $("#pirntInvoice").val();
      $("body")
        .empty()
        .html(printcontent);
      window.print();
    },

    // delete sale
    deleteSale(id, sale_inv_no) {
      this.form.id = id;
      this.form.sale_inv_no = sale_inv_no;
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
              .delete("api/sale/" + sale_inv_no)
              .then(() => {
                this.loadSales();
                swal.fire(
                  "Deleted!",
                  sale_inv_no + "Deleted successful.",
                  "success"
                );
              })
              .catch(() => {
                swal.fire("", "Sale Deleted Failed", "error");
              });
          }
        });
    },

    // load data for table
    loadSales() {
      axios.get("api/sale").then(({ data }) => (this.sales = data));
    }
  }, // end method

  created() {
    this.loadSales();
    Fire.$on("AfterAction", () => {
      this.loadSales();
    });
  }
};
</script>

<style scoped>
.green {
  background: white;
  color: green;
}
.greenHover:hover {
  color: green;
  cursor: pointer;
  font-size: 15px;
}

.print-invoice {
  background: green;
  padding: 5px;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  margin-left: 10px;
}

.print-invoice:hover {
  font-weight: bold;
  background: #41b883;
}

.sale-list {
  background: #3490dc;
  padding: 5px;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}

.sale-list:hover {
  font-weight: bold;
  background: #35495e;
}
</style>