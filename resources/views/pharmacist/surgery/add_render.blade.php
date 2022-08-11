<div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
			<strong>Name:</strong>
			<input type="text" name="name" value="{{ $surgery->name ?? '' }}" class="form-control" placeholder="Name" required="">
			@error('name')
			<div class="alert alert-danger">{{ $message }}</div>
			@enderror
		</div>
	</div>
</div>