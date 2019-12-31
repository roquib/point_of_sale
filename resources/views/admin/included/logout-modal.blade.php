<!-- The Modal -->
<div class="modal fade" id="logout">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-danger">
        <h4 class="modal-title text-center py-0">Logout</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body text-center">
        <h5>Are you sure to logout from here?</h5>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer text-right">
          <a class="btn btn-danger btn-sm" href="{{ route('logout') }}" style="text-decoration: none; color: white;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Yes, Logout
          </a>
          <a href="" class="btn btn-success btn-sm px-4" data-dismiss="modal">No, Thanks</a>


          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
      </div>
    </div>
  </div>
</div>
