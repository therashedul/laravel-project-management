 <!-- jQuery -->
 {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script> --}}
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
 <!-- Bootstrap -->
 <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
 {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}
 <!-- Bootstrap -->
 <!-- filemanage -->
 <script src="{{ asset('js/file-manager-1.2++.js') }}"></script>
 <script src="{{ asset('js/jquery.tagsinput.min.js') }}"></script>

 <!-- NProgress -->
 <script src="{{ asset('vendors/nprogress/nprogress.js') }}"></script>
 {{-- <script src="{{ asset('vendors/dropzone/dist/min/dropzone.min.js') }} "></script> --}}
 {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script> --}}

 <!-- Custom Theme Scripts -->
 <script src="{{ asset('build/js/custom.min.js') }}"></script>
 {{-- <script type="text/javascript">
     $(document).ready(function() {

         var dir = "http://localhost/b2m-project/public/thumbnail/";
         var fileextension = ".png,.jpg,.gif,.bmp,.jpeg";
         $(document).ready(function() {
             $.ajax({
                 url: dir,
                 success: function(data) {
                     $(data).find("a:contains(" + fileextension + ")").each(function() {
                         var filename = this.href.replace(window.location.host, "")
                             .replace(
                                 "http://", "");
                         $("body").append("<img src='" + dir + filename + "'>");
                     });
                 }
             });
         });
     });
 </script> --}}

 <script></script>
