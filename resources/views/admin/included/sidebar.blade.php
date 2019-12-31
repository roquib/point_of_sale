<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" id="vue-user">


  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{!! asset('images/admin-avatar.png') !!}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" style="text-transform:capitalize" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
        <a href="#" class="d-block"></a>
      </div>
    </div>




    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <router-link to="/dashboard" active-class="active" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt teal"></i>
            <p>
              Dashboard
            </p>
          </router-link>
        </li>

        <!-- Users -->
        <li class="nav-item">
          <router-link to="/user" class="nav-link">
            <i class="nav-icon fas fa-users teal"></i>
            <p>
              Users
            </p>
          </router-link>
        </li>
        
        <!-- Product -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fab fa-product-hunt teal"></i>
            <p>
              Prodcut
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview ml-3">
            {{-- Unit --}}
            <li class="nav-item">
              <router-link to="unit" class="nav-link">
                <i class="fas fa-users"></i>
                <p>Unit</p>
              </router-link>
            </li>
            <!-- Point -->
            <li class="nav-item">
              <router-link to="brand" class="nav-link">
                <i class="nav-icon fas fa-warehouse teal"></i>
                <p>
                  Brands
                </p>
              </router-link>
            </li>
            {{-- Categories --}}
            <li class="nav-item">
              <router-link to="category" class="nav-link">
                <i class="fab fa-first-order"> </i>
                <p>Categories</p>
              </router-link>
            </li>

            {{-- Prodcut --}}
            <li class="nav-item">
              <router-link to="product" class="nav-link">
                <i class="fab fa-product-hunt"></i>
                <p>Product</p>
              </router-link>
            </li>

          </ul>
        </li>



        <!-- Purchase -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-shopping-cart teal"></i>
            <p>
              Purchase
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ml-3">
            <!-- Purchase -->
            <li class="nav-item">
              <router-link to="purchase" class="nav-link">
                <i class="fa fa-shopping-basket"></i>
                <p>
                  Purchase
                </p>
              </router-link>
            </li>
            <!-- All Purchase -->
            <li class="nav-item">
              <router-link to="purchase-list" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Purchases List
                </p>
              </router-link>
            </li>
            <!-- Purchase Return -->
            <li class="nav-item">
              <router-link to="purchase-return" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Purchase Return
                </p>
              </router-link>
            </li>
            <!-- All Purchase Return -->
            <li class="nav-item">
              <router-link to="purchase-return-list" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Purchase Return List
                </p>
              </router-link>
            </li>

          </ul>
        </li>


        <!-- Sale -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-shopping-cart teal"></i>
            <p>
              Sale Product
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ml-3">
            <!-- Sale -->
            <li class="nav-item">
              <router-link to="sale" class="nav-link">
                <i class="fa fa-shopping-basket"></i>
                <p>
                  Sale
                </p>
              </router-link>
            </li>
            <!-- All Sales -->
            <li class="nav-item">
              <router-link to="sale-list" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Sales List
                </p>
              </router-link>
            </li>
            <!-- Sale Return -->
            <li class="nav-item">
              <router-link to="sale-return" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Sales Return
                </p>
              </router-link>
            </li>
            <!-- All Sales Return -->
            <li class="nav-item">
              <router-link to="sale-return-list" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Sales Return List
                </p>
              </router-link>
            </li>

          </ul>
        </li>


        <!-- Suppliers -->
        <li class="nav-item">
          <router-link to="supplier" class="nav-link">
            <i class="nav-icon fas fa-object-group teal"></i>
            <p>
              Suppliers
            </p>
          </router-link>
        </li>

        <!-- Customer -->
        <li class="nav-item">
          <router-link to="/customer" class="nav-link">
            <i class="nav-icon fas fa-object-group teal"></i>
            <p>
              Customer
            </p>
          </router-link>
        </li>

        <!-- Reports -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-shopping-cart teal"></i>
            <p>
              Reports
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ml-3">
            <!-- Current Stock -->
            <li class="nav-item">
              <router-link to="current-stock" class="nav-link">
                <i class="fa fa-shopping-basket"></i>
                <p>
                  Current Stock
                </p>
              </router-link>
            </li>
            <!-- Stock Detail -->
            <li class="nav-item">
              <router-link to="reports" class="nav-link">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <p>
                  Reports
                </p>
              </router-link>
            </li>

          </ul>
        </li>



        <!-- Logout -->
        <li class="nav-item">
          <a data-toggle="modal" href="#" data-target="#logout" class="nav-link">
            <i class="nav-icon fas fa-power-off red"></i>
            <p>Logout</p>
          </a>
        </li>



      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>