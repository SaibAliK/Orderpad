<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Order Pad">
  <meta name="author" content="Creative Tim">
  <title>@yield('title')</title>
  <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
  {{-- External CSS Libraries --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href=" {{ asset('css/dropify.min.css') }}">
  <link rel="stylesheet" href=" {{ asset('css/toastr.min.css') }}">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
  @yield('css')
  <style type="text/css">
    .select2-container--default .select2-selection--single {
      height: calc(2.4375rem + 2px);
      font-size: 14px;
      padding: 4px 0px;
      border: 2px solid #eee;
      border-radius: 8px;
    }
    .autocomplete-suggestions
    {
      overflow-y: auto;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 7px;
    }

    .error {
      color: red;
      font-weight: bold;
    }

    label{
      color: #32325d !important;
      font-size: 14px;
    }

    .row-title.text-warning {
      color: #FFCF40 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
     color: #8898aa; 
  </style>
</head>

<body>
  <!-- SideNav Bar -->
  @include('admin.components.sidebar')
  {{-- end SideNav Bar --}}
  <div class="main-content" id="panel">

    <!-- TopNav Bar -->
    @include('admin.components.navbar')
    <!-- End TopNav Bar -->

    {{-- Dashboard Panel content section --}}
    @yield('dashboard-bar')
    {{-- Dashboard Panel content section --}}

    {{-- Page Content section --}}
    <div class="header pb-6 pt-3">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-body">
                  {{-- content section  --}}
                  @yield('content')
                  {{-- end content body  --}}
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- end Page Content section -->
  </div>
  {{-- External JS Libraries --}}
  <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('assets/js/argon.js') }}"></script>
  <script src="{{ asset('js/dropify.min.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/sweetalert2.js') }}"></script>
  <!--<script type="text/javascript" src="{{ asset('js/jquery.autocomplete.js') }}"></script>-->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

  <!--<script type="text/javascript">

    var search_url = "{{route('autocomplete.drugs')}}";
    var $searchbar = $("input.search");
    function initAutoComplete()
    {
      $('input.search').autocomplete({
        // alert('hi');
        serviceUrl: function(query) {
          query = $searchbar.val();
          q = encodeURIComponent(query);
          console.log(q);
          return search_url+"?q=" + q;
        },
        delimiter: /;/,
        minChars: 2,
        triggerSelectOnValidInput: false,
        showNoSuggestionNotice: true,
        zIndex: 100,
        noCache: true,
        preventBadQueries: false,
        transformResult: function(response) {
          console.log(response);
          return {
            suggestions: JSON.parse(response)
          };
        },
        beforeRender: function(container, suggestions) {
          if ($searchbar.val().length) {
            $in = $searchbar
            .parents(".control-group")
            .find(".input-group-append .choose-search .dropdown-toggle")
            .attr("data-id");
            if (
              $searchbar.val().length >= 2 &&
              $(container).find(".autocomplete-no-suggestion").length
              ) {
              if ($in != "7" && $in != "8") {
                var retval =
                '<div class="add" data-index="0" data-catid="0" data-toggle="modal" style="cursor:pointer;"><div class="truncate" style="padding: 5px; overflow:auto;">' +
                '<div style="float: left;color: #495057"><i class="fas fa-plus"></i></div>' +
                '<div style="float: left; margin-left: 10px">' +
                '<div style="font-weight: bold; color: #333; font-size: 12px; line-height: 11px">No Results Found.</div>' +
                '<div style="color: #999; font-size: 9px" id="add_text">Suggest search term</div>' +
                "</div></div>";
                $(container)
                .find(".autocomplete-no-suggestion")
                .html(retval);
              } else {
                $(container).find(".autocomplete-no-suggestion").remove();
                $(container).hide();
              }
            }
            $(container)
            .find(".autocomplete-suggestion")
            .each(function(index) {
              $(this).attr("data-index", index);
              $(this).css('width', '100% !important');
            });


          }
        },
        formatResult: function(suggestion, currentValue) {
          var retval = '<div class="bg-light truncate" style="padding: 4px;width:100% !important; overflow:hidden;">';
          retval += '<div style="float: left;color: #495057"></div>';
          retval += '<div class="" style="float: left; margin-left: 10px" data-id="' + suggestion["data"]["id"] + '">' +
          '<div class="name mt-2" style="font-weight: bold; color: #333; font-size: 12px; line-height: 11px">' + suggestion["value"] + "</div>" +
          '<div class="name" style="color: #999; font-size: 9px;">' + "</div>" +
          "</div>" +
          "</div>";
          return retval;
        },
        onSelect: function(suggestion) {
          var slt = suggestion.value;
          //$('input[name="drug"]').val(slt);
        }
      });
    }
  </script>-->

  <script>
    $(function() {
      $('#chkToggle2').bootstrapToggle()
    });
    @if(session('success_msg'))
    toastr.success("{{ session('success_msg') }}");
    @elseif(session('error_msg'))
    toastr.error("{{ session('error_msg') }}");
    @endif
    // dropify script
    $('.dropify').dropify();
  </script>
  <script>
    function deleteAlert(url) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to undo this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          location.href = url;
        }
      });
    }
    $(function() {
      $('.action-btn').hide();
      $('tr').hover(function() {
        $(this).find('.action-btn').fadeIn();
      }, function() {
        $(this).find('.action-btn').hide();
      });
    });
  </script>
  <script>
    function initDatepicker(old = false) {
      var date = new Date();
      var dp1 = $(".datepickr").datepicker({
        dateFormat: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        //startDate: date
      });
      if (!old) {
        //dp1.datepicker('update', date);
        //date.setDate(date.getDate() + 1);
        $("[name='date_needed']").datepicker({
          dateFormat: 'dd-mm-yy',
          autoclose: true,
          todayHighlight: true,
        }).datepicker('update', date);
      } else {
        dp1.datepicker('update');
      }
    }
    $(document).ready(function() {
      initDatepicker();
    });

    function selectFunc(){
      $(document).ready(function() {
        $('.select2').select2({
          width: '100%'
        });
      });
    }
    
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.datatables').DataTable({
        "sort": false,
        "ordering": false,
        "pagingType": "full_numbers",
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records",
        }
      });

      function mapDataToFields(data) {
        $.map(data, function(value, index) {
          var input = $('[name="' + index + '"]');
          if ($(input).length && $(input).attr('type') !== 'file') {
            if (($(input).attr('type') == 'radio' || $(input).attr('type') == 'checkbox')) {
              $(input).each(function() {
                if ($(this).val() == value)
                  $(this).prop('checked', true).change();
              })
            } else
            $(input).val(value).change();
          }
        });
      }
      var data = <?php echo json_encode(session()->getOldInput()) ?>;
      mapDataToFields(data);
    });
    $(document).on('click', '.view-rx', function(e) {
      e.preventDefault();
      $.get($(this).attr('href'), function(modal) {
        $('#rx-detail-modal').remove();
        $('body').append(modal);
        $('#rx-detail-modal').modal('show');
      })
    });
  </script>
  @yield('js')
</body>

</html>