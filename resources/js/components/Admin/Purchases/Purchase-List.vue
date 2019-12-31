<template>
  <div class="row" id="fullpage">
    <div class="col-12 mb-3 text-right">
      <span
        class="float-right px-3 print-invoice"
        v-show="mode=='invoice'"
        @click="printInvoice('invoice')"
      >Print</span>

      <span
        class="float-right px-2 purchase-list"
        v-show="mode=='invoice'"
        @click="mode= 'purchase'"
      >Purchase List</span>
    </div>
    <!-- Display all purchase in table -->
    <div class="col-12 mx-auto" v-if="mode != 'invoice'">
      <!-- display all purchase -->
      <div class="card">
        <div class="card-header" style="background: #563D7C">
          <!-- purchase heading -->
          <h3 class="card-title text-light">Purchase Table</h3>

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
                  <router-link to="purchase" class="ml-3 btn btn-outline-warning">Purchase Product</router-link>
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
                <th>Purchase No</th>
                <th>Supplier</th>
                <th>Purchase By</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
              <tr v-for="(purchase, index) in purchases.data" :key="index">
                <td
                  class="greenHover"
                  @click="showInvoice(purchase.pur_inv_no)"
                >{{ purchase.pur_inv_no }}</td>
                <td style="text-transform: capitalize;">{{ purchase.name }}</td>
                <td
                  style="text-transform: capitalize;"
                >{{ purchase.user.first_name }} {{ purchase.user.last_name }}</td>
                <td>{{ purchase.totalQty }}</td>
                <td>{{ purchase.grandTotal | bdCurrency }} Tk.</td>
                <td>{{ purchase.created_at | myDate }}</td>
                <td>
                  <a href="#" @click="showInvoice(purchase.pur_inv_no)" title="View Invoice">
                    <i class="fas fa-eye" style="font-size: 20px;"></i>
                  </a>
                  <a href="#" @click="editPurchase(purchase.pur_inv_no)">
                    <i class="fas fa-edit green" style="font-size: 20px;"></i>
                  </a>
                  <a href="#" @click="deletePurchase(purchase.id, purchase.pur_inv_no)">
                    <i class="fas fa-trash red" style="font-size: 20px;"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- pagination -->
        <div class="card-footer">
          <pagination :data="purchases" @pagination-change-page="getResults"></pagination>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- Display all purchase in table -->

    <!-- Invoice -->
    <purchase-invoice v-bind="{invoice}" class="pt-0 mx-3 w-100" v-else></purchase-invoice>
  </div>
</template>








<script>
import jsPDF from "jspdf";
export default {
  data() {
    return {
      scale: 2,
      purchases: [],
      mode: "purchase-list",
      invoice: {},
      showingAddModal: false,
      form: new Form({
        id: 0,
        pur_inv_no: ""
      })
    };
  },

  methods: {
    // results for pagination
    getResults(page = 1) {
      axios.get("api/purchase?page=" + page).then(response => {
        this.purchases = response.data;
      });
    },
    getInvoice(pur_inv_no) {
      axios
        .get("api/purchase/invoice/" + pur_inv_no)
        .then(({ data }) => (this.invoice = data));
    },
    // Individual purchase invoice
    showInvoice(pur_inv_no) {
      this.mode = "invoice";
      this.getInvoice(pur_inv_no);
    },

    editPurchase(prop_data) {
      this.$router.push({ name: "purchase", params: { prop_data } });
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
    // delete purchase
    deletePurchase(id, pur_inv_no) {
      this.form.id = id;
      this.form.pur_inv_no = pur_inv_no;
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
              .delete("api/purchase/" + pur_inv_no)
              .then(() => {
                this.loadPurchases();
                swal.fire(
                  "Deleted!",
                  purchase.pur_inv_no + "Deleted successful.",
                  "success"
                );
              })
              .catch(() => {
                swal.fire("", "Purchase Deleted Failed", "error");
              });
          }
        });
    },
    // load data for table
    loadPurchases() {
      axios.get("api/purchase").then(({ data }) => (this.purchases = data));
    }
  }, // end method

  created() {
    this.loadPurchases();
    Fire.$on("AfterAction", () => {
      this.loadPurchases();
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

.purchase-list {
  background: #3490dc;
  padding: 5px;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}

.purchase-list:hover {
  font-weight: bold;
  background: #35495e;
}
</style>