
<template>
  <div>
    <h3 class="text-center py-2 text-primary bg-dark">Stock Detail</h3>
    <div class="bg-primary p-2">
      <span>Product Name: {{ product.name }}</span>
    </div>
    <table class="table table-sm" v-if="stocks">
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Description Id</th>
        <th>Date</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Stock</th>
      </tr>
      <tr v-for="(stock, index) in stocks" :key="index">
        <td>{{ index + 2 }}</td>
        <td>{{ stock.description }}</td>
        <td>{{ stock.source_id }}</td>
        <td>{{ stock.created_at | myDate }}</td>
        <td>{{ stock.debit }}</td>
        <td>{{ stock.credit }}</td>
        <td>{{ getTotal(index) }}</td>
      </tr>
    </table>
  </div>
</template>


<script>
import { constants } from "crypto";
export default {
  props: ["prop_data"],
  data() {
    return {
      stocks: [],
      product: [],
      stockTotal: 0
    };
  },

  methods: {
    getTotal(index) {
      var debit = 0;
      var credit = 0;

      for (let i = 0; i <= index; i++) {
        debit += this.stocks[i].debit;
        credit += this.stocks[i].credit;
      }

      return credit - debit;
    },
    loadProducts() {
      if (this.prop_data) {
        axios
          .get("api/stock/details/" + this.prop_data)
          .then(({ data }) => (this.stocks = data.data))
          .catch(() => {
            console.log("Stock detail error");
          });
        axios
          .get("api/product/single/" + this.prop_data)
          .then(({ data }) => (this.product = data))
          .catch(() => {
            console.log("Product data error");
          });
      }
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