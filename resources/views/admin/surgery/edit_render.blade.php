<form action="{{ route('surgery.update',$surgery->id) }}" id="edit_form" method="POST">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" value="{{ $surgery->name ?? '' }}" class="form-control" placeholder="Name" required="">
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Primary Ordering Method *</strong>
                <select class="form-control" name="ordering_method">
                    <option hidden selected="" value="{{ $surgery->ordering_method  ?? ''}}">{{ $surgery->ordering_method  ?? 'Choose Surgery'}}</option>
                    <option value="email">Email</option>
                    <option value="fax">Fax</option>
                    <option value="phone">Phone</option>
                    <option value="other">Other (please state)</option>
                </select>
                <input type="text" name="other_ordering_method" value="" class="form-control" placeholder="Enter Method" style="display: none;">
                @error('ordering_method')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email Address:</strong>
                <input type="text" name="email" value="{{ $surgery->email ?? '' }}" class="form-control" placeholder="Email address">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Phone Number:</strong>
                <input type="text" name="phone" value="{{ $surgery->phone ?? '' }}" class="form-control" placeholder="Phone Number">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Fax Number:</strong>
                <input type="text" name="fax" value="{{ $surgery->fax ?? '' }}" class="form-control" placeholder="Fax Number">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Surgery Address:</strong>
                <input type="text" name="address" value="{{ $surgery->address ?? '' }}" class="form-control" placeholder="Address" required="">
                @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <h2 class="pb-2"> <i class="fas fa-pen"></i> Notes</h2>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered tbll">
                        @if((isset($surgery)))
                        @foreach($surgery->notes as $note)
                        <tr class="cloneme">
                            <td width="92%"><input type="text" value="{{ $note->notes }}" class="form-control" name="notes[]" placeholder="Notes" required=""></td>
                            <td width="8%" class="text-center"><a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a></td>
                        </tr>
                        @endforeach
                        @endif
                        <tr class="cloneme">
                            <td width="92%"><input type="text" class="form-control" name="notes[]" placeholder="Notes"></td>
                            <td width="8%" class="text-center"><a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a></td>
                        </tr>
                    </table>
                    <a class="addjob btn btn-success btn-sm text-white mt-3">+ Notes</a>

                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(".addjob").click(function() {
        var $clone = $('table.tbll tr.cloneme:first').clone().find("input").val("").end();
        var $code_clone = '<tr class="cloneme"><td width="92%"><input type="text" class="form-control" name="notes[]" placeholder="Notes"></td><td width="8%" class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a></td></tr>';
        
        console.log($code_clone);
        $('table.tbll').append($code_clone);
    });
    $('.tbll').on('click', '.rmv', function() {
        $(this).closest('tr').remove();
    });
    $(document).on('change', 'select[name="ordering_method"]', function() {
        var test = $('select[name="ordering_method"] option:selected').val();
        if (test == "other") {
            $('input[name="other_ordering_method"]').show();
        } else {
            $('input[name="other_ordering_method"]').hide();
        }
    });
</script>