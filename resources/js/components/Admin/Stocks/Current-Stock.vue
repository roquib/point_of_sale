
<template>
  <div>
    <h3 class="text-center py-2 text-primary bg-dark">Current Stock</h3>
    <table class="table table-sm">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Code</th>
        <th>Purchase Price</th>
        <th>Quantity</th>
        <th>Sub Total</th>
      </tr>
      <tr
        v-for="(product, index) in products"
        :key="index"
        @click="rowClick(product.product_code)"
        style="cursor: pointer"
      >
        <td>{{ index + 1 }}</td>
        <td>{{ product.name }}</td>
        <td>{{ product.product_code }}</td>
        <td>Tk. {{ product.purchase_price }}</td>
        <td>{{ product.current_stock.quantity }}</td>
        <td>{{ product.current_stock.quantity * product.purchase_price }}</td>
      </tr>
    </table>
  </div>
</template>


<script>
export default {
  data() {
    return {
      products: []
    };
  },

  methods: {
    loadProducts() {
      axios
        .get("/api/current/stock")
        .then(({ data }) => (this.products = data));
    },
    rowClick(prop_data) {
      this.$router.push({ name: "StockDetail", params: { prop_data } });
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