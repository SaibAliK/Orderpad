<form action="{{ route('pharmacy.update',$pharmacy->id) }}" id="edit_form" method="POST">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Pharmacy Name:</strong>
                <input type="text" name="name" value="{{ $pharmacy->name ?? '' }}" class="form-control" placeholder="Name" required>
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Email Address:</strong>
                <input type="text" name="email" value="{{ $pharmacy->email ?? '' }}" class="form-control" placeholder="Email address" required>
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Address:</strong>
                <input type="text" name="address" value="{{ $pharmacy->pharmacyProfile->address ?? '' }}" class="form-control" placeholder="Address" required="">
                @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
    </div>
</form>