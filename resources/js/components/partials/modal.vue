<template>
  <transition name="scale">
    <div class="mymodal" v-if="cond">
      <div class="modalContainer">
        <div class="modalHeader">
          <p class="fleft" style="margin-top: 5px; margin-bottom: 0; margin-left: 10px">
            <slot name="header"></slot>
          </p>
          <p
            class="fright modalClose"
            style="margin-top: 5px; margin-bottom: 0"
            @click="$emit('modalClose')"
          >X</p>
          <div class="clear"></div>
        </div>
        <div class="modalContent">
          <slot name="body"></slot>
        </div>
        <div class="modalFooter">
          <slot name="footer"></slot>
        </div>
          <!-- <button class="fright btn btn-sm btn-danger" @click="$emit('modalClose')">Close</!-->
        </div>
      </div>
    </div>
  </transition>
</template>


<<<<<<< HEAD
 
=======
<script type="text/javascript">
export default {
  name: "modal",
  props: ["cond", "modalHeading"]
};
</script>
>>>>>>> akash
