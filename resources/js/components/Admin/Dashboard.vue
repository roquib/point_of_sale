<template>
    <div class="container">
      <div class="row">
          <div class="col-lg-3 col-md-6 mb-2">
              <div class="card border-success mx-sm-1 p-3">
                  <div class="card border-success shadow text-success p-3 my-card" >
                    <span class="fa fa-book"  aria-hidden="true"></span>
                  </div>
                  <div class="text-success text-center mt-3">
                    <h4><a style="text-decoration: none" class="text-success" href="">Todays Orders</a></h4>
                  </div>
                  <div class="text-success text-center mt-2">
                    <h2>5</h2>
                  </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-2">
              <div class="card border-info mx-sm-1 p-3">
                  <div class="card border-info shadow text-info p-3 my-card" >
                    <span class="fa fa-user" aria-hidden="true"></span>
                  </div>
                  <div class="text-info text-center mt-3">
                    <h4><a style="text-decoration: none" class="text-info" href="#">Pending Orders</a></h4>
                  </div>
                  <div class="text-info text-center mt-2"><h2>7</h2></div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-2">
              <div class="card border-danger mx-sm-1 p-3">
                  <div class="card border-danger shadow text-danger p-3 my-card" >
                    <span class="fa fa-edit" aria-hidden="true"></span>
                  </div>
                  <div class="text-danger text-center mt-3"><h4>Rejected</h4></div>
                  <div class="text-danger text-center mt-2"><h2>234</h2></div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-2">
              <div class="card border-primary mx-sm-1 p-3">
                  <div class="card border-primary shadow text-primary p-3 my-card" ><span class="fa fa-car" aria-hidden="true"></span></div>
                  <div class="text-primary text-center mt-3"><h4>Completed</h4></div>
                  <div class="text-primary text-center mt-2"><h2>234</h2></div>
              </div>
          </div>
      </div>

    </div>
</template>


<style>
.my-card
  {
    position:absolute;
    left:38%;
    top:-20px;
    border-radius:50%;
  }
</style>
