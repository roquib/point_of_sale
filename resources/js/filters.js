import Vue from "vue";

// Date format
import moment from "moment";

// filters
Vue.filter("lowercase", function(text) {
    return text == null ? "" : text.toLowerCase();
});

Vue.filter("uppercase", function(text) {
    return text == null ? "" : text.toUpperCase();
});

Vue.filter("capitalize", function(text) {
    return text == null ? "" : text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter("myDate", function(text) {
    return moment(text).format("YYYY-MM-DD");
});

Vue.filter("digitalDate", text => {
    return moment(text).format("MMM, MM YYYY");
});

Vue.filter("bdCurrency", function(number) {
    const formatter = new Intl.NumberFormat("en-BD", {
        minimumFractionDigits: 2
    }).format(number);
    return formatter;
});
