

<template>
  <modal name="item-modal">
    <h1 class="text-center my-2 text-success">This is vue modal</h1>
    <b>{{item.name}}</b>
  </modal>
</template>
<script>
export default {
  name: "item-modal",
  props: ["item"]
};
</script>