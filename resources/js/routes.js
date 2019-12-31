// import vue components
import Dashboard from "./components/Admin/Dashboard";
import User from "./components/Admin/User";
import Category from "./components/Admin/Category";
import Brand from "./components/Admin/Brand";
import Unit from "./components/Admin/Unit";
import Customer from "./components/Admin/Customer";
import Supplier from "./components/Admin/Supplier";
import Product from "./components/Admin/Product";
// purchase
import Purchase from "./components/Admin/Purchases/Purchase";
import PurchaseL from "./components/Admin/Purchases/Purchase-List";
import PurchaseR from "./components/Admin/Purchases/Purchase-Return";
import PurchaseRL from "./components/Admin/Purchases/Purchase-Return-List";
// sale
import Sale from "./components/Admin/Sales/Sale";
import SaleL from "./components/Admin/Sales/Sale-List";
import SaleR from "./components/Admin/Sales/Sale-Return";
import SaleRL from "./components/Admin/Sales/Sale-Return-List";

// stock
import CurrentStock from "./components/Admin/Stocks/Current-Stock";
import StockDetail from "./components/Admin/Stocks/Stock-Detail";

// create all vue routes
export default [
    // {  set a path,          load component,       set page title         } },

    { path: "/dashboard", component: Dashboard, meta: { title: "Dashboard" } },
    { path: "/user", component: User, meta: { title: "User" } },
    { path: "/category", component: Category, meta: { title: "Category" } },
    { path: "/brand", component: Brand, meta: { title: "Brand" } },
    { path: "/product", component: Product, meta: { title: "Product" } },
    { path: "/supplier", component: Supplier, meta: { title: "Supplier" } },
    { path: "/unit", component: Unit, meta: { title: "Unit" } },
    { path: "/customer", component: Customer, meta: { title: "Customer" } },

    {
        path: "/purchase",
        name: "purchase",
        component: Purchase,
        meta: { title: "Purchase" },
        props: true
    },
    {
        path: "/purchase-list",
        component: PurchaseL,
        meta: { title: "Purchase List" }
    },
    {
        path: "/purchase-return",
        component: PurchaseR,
        meta: { title: "Purchase Return" }
    },
    {
        path: "/sale-return",
        component: SaleR,
        meta: { title: "Sales Return" }
    },
    {
        path: "/sale",
        name: "sale",
        component: Sale,
        meta: { title: "Sale" },
        props: true
    },
    {
        path: "/sale-list",
        component: SaleL,
        meta: { title: "Sale List" }
    },
    {
        path: "/purchase-return-list",
        component: PurchaseRL,
        meta: { title: "Purchase Return List" }
    },
    {
        path: "/sale-return-list",
        component: SaleRL,
        meta: { title: "Sales Return List" }
    },
    {
        path: "/current-stock",
        component: CurrentStock,
        meta: { title: "Current Stocks" }
    },
    {
        path: "/stock-detail",
        name: "StockDetail",
        component: StockDetail,
        meta: { title: "Stock Detail" },
        props: true
    },

    { path: "*", redirect: "dashboard" }
];
