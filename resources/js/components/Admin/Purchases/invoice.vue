
<template>
  <div id="invoice">
    <!-- invoice area page 1 -->
    <div class="page w-100 mt-0 pt-0">
      <div class="subpage">
        <div class="header">
          <h2 class="text-center mb-0 pb-0">Invoice</h2>
          <h5>Date: 25 Sept, 2019</h5>
          <h5>Invoice NO# : {{ invoice.pur_inv_no }}</h5>
        </div>

        <!-- supplier details -->
        <div class="row mb-4">
          <div class="col-sm-6">
            <h6 class="mb-3">From:</h6>
            <div v-if="invoice.company_name">
              <strong>Company: {{ invoice.company_name }}</strong>
            </div>
            <div>
              <strong>{{ invoice.company_name !=NUll ? 'Propitor : ' : 'Supplier :' }} {{ invoice.supplier.name }}</strong>
            </div>
            <div v-if="invoice.email">Email: {{ invoice.email }}</div>
            <div v-if="invoice.mobile">Phone: {{ invoice.mobile }}</div>
            <div v-if="invoice.address">{{ invoice.address }}</div>
          </div>

          <!-- supplier details -->
          <div class="col-sm-5 ml-auto bg-danger">
            <div v-if="invoice.company_name">
              <strong>Company: Point Of Sale</strong>
            </div>
            <div>Email: asm.akash@gmail.com</div>
            <div>Phone: 01745501406</div>
            <div>74, KC school road, Ashkona, Airport, Dhaka</div>
          </div>
        </div>

        <!-- item details -->
        <div class="row">
          <div class="table-responsive-sm col-12">
            <table class="table table-sm table-striped">
              <thead class="px-2">
                <tr>
                  <th class="center pl-3">#</th>
                  <th>Product</th>
                  <th>Price</th>
                  <th class="center">Qty</th>
                  <th class="right">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item,index) in invoice.purchase_items" :key="index">
                  <td class="center pl-3">{{ index+1 }}</td>
                  <td class="left strong">{{ item.product.name }}</td>
                  <td class="right">{{ item.price }}</td>
                  <td class="center">{{ item.quantity }}</td>
                  <td class="right">{{ item.price * item.quantity }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <!-- Payment section -->
          <div class="col-lg-4 col-md-4 col-sm-4">
            <table class="table table-sm table-bordered table-clear">
              <tbody>
                <tr>
                  <td class="left" width="130px">
                    <strong>Previous Due</strong>
                  </td>
                  <td class="right" style="width: 140px">Tk. {{ invoice.opening_balance }}</td>
                </tr>
                <tr>
                  <td class="left" width="130px">
                    <strong>Invoice Amount</strong>
                  </td>
                  <td
                    class="right"
                    width="130px"
                  >Tk. {{ invoice.grandTotal - invoice.discount + ((invoice.grandTotal * 4) / 100) }}</td>
                </tr>
                <tr>
                  <td class="left" width="130px">
                    <strong>Payment</strong>
                  </td>
                  <td class="right" width="130px">Tk. (-) {{ invoice.payment }}</td>
                </tr>
                <tr style="border-top: 2px solid gray">
                  <td class="left" width="130px">
                    <strong>Total Due</strong>
                  </td>
                  <td class="right" width="130px">
                    <strong>Tk. {{ invoice.supplier.opening_balance + (invoice.grandTotal - invoice.discount + ((invoice.grandTotal * 4) / 100)) - invoice.payment }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Total calculate section -->
          <div class="col-lg-4 col-md-4 col-sm-4 ml-auto">
            <table class="table table-sm table-bordered table-clear">
              <tbody>
                <tr>
                  <td class="left" style="min-width: 130px">
                    <strong>Subtotal</strong>
                  </td>
                  <td class="right" style="min-width: 140px">Tk. {{ invoice.grandTotal }}</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>Discount</strong>
                  </td>
                  <td class="right">Tk. {{ invoice.discount }}</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>VAT/Tax (4%)</strong>
                  </td>
                  <td class="right">Tk. {{ (invoice.grandTotal * 4) / 100 }}</td>
                </tr>
                <tr style="border-top: 2px solid gray">
                  <td class="left">
                    <strong>Total</strong>
                  </td>
                  <td class="right">
                    <strong>Tk. {{ invoice.grandTotal - invoice.discount + ((invoice.grandTotal * 4) / 100) }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row footer ml-1 pr-2">Authorised Signature</div>
        <div class="powered-by">
          <div>
            <p class="text-center my-0" hidden>
              Point Of Sale Powered By
              <a href>Binary Teach Zone</a>
            </p>
            <small class="text-right">Page 1 of 2</small>
          </div>
        </div>
      </div>
    </div>

    <!-- page 2 -->
    <div class="page" v-if="(invoice.purchase_items).length > 15">
      <div class="subpage">
        <div class="header my-0 py-0">
          <h2 class="text-center mb-0 pb-0">Invoice</h2>
          <p>Invoice Date: 25 Sept, 2019</p>
        </div>

        <!-- item details  -->
        <div class="row">
          <div class="table-responsive-sm col-12">
            <table class="table table-sm table-striped">
              <thead>
                <tr>
                  <th class="center">#</th>
                  <th>Item</th>
                  <th>Description</th>

                  <th class="right">Unit Cost</th>
                  <th class="center">Qty</th>
                  <th class="right">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="center">1</td>
                  <td class="left strong">Origin License</td>
                  <td class="left">Extended License</td>

                  <td class="right">$999,00</td>
                  <td class="center">1</td>
                  <td class="right">$999,00</td>
                </tr>
                <tr>
                  <td class="center">2</td>
                  <td class="left">Custom Services</td>
                  <td class="left">Instalation and Customization (cost per hour)</td>

                  <td class="right">$150,00</td>
                  <td class="center">20</td>
                  <td class="right">$3.000,00</td>
                </tr>
                <tr>
                  <td class="center">3</td>
                  <td class="left">Hosting</td>
                  <td class="left">1 year subcription</td>

                  <td class="right">$499,00</td>
                  <td class="center">1</td>
                  <td class="right">$499,00</td>
                </tr>
                <tr>
                  <td class="center">4</td>
                  <td class="left">Platinum Support</td>
                  <td class="left">1 year subcription 24/7</td>
                  <td class="right">$3.999,00</td>
                  <td class="center">1</td>
                  <td class="right">$3.999,00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-sm-5"></div>

          <div class="col-lg-4 col-sm-6 ml-auto">
            <table class="table table-sm table-bordered table-clear">
              <tbody>
                <tr>
                  <td class="left">
                    <strong>Subtotal</strong>
                  </td>
                  <td class="right">$8.497,00</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>Discount (20%)</strong>
                  </td>
                  <td class="right">$1,699,40</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>VAT (10%)</strong>
                  </td>
                  <td class="right">$679,76</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>Total</strong>
                  </td>
                  <td class="right">
                    <strong>$7.477,36</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row footer2 ml-1 pr-2">Authorised Signature</div>
        <div class="powered-by2">
          <div>
            <p class="text-center my-0">
              Point Of Sale Powered By
              <a href>Binary Teach Zone</a>
            </p>
            <small class="text-right">Page 2 of 2</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["invoice"],
  data() {
    return {
      x: ""
    };
  }
};
</script>

<style scoped>
/* body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  background-color: #fafafa;
  font: 12pt "Tahoma";
}
* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
} */
.footer {
  position: absolute;
  top: 315mm;
  border-top: 1px solid black;
  clear: both;
}
.page {
  width: 210mm;
  /* height: 7in; */
  min-height: 297mm;
  padding: 10mm;
  margin: 10mm auto;
  /* border: 1px #d3d3d3 solid; */
  border-radius: 5px;
  /* background: white; */
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
  padding: 0.5cm;
  /* border: 2px red solid; */
  height: 331mm;
  /* height: 257mm; */
  /* outline: 2cm #ffeaea solid; */
}

@page {
  size: A4;
  margin: 0;
}
@media print {
  html,
  body {
    width: 210mm;
    height: 297mm;
  }
  .page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
}
</style>