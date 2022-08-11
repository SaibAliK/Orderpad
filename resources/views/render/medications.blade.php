<div>
    <input type="hidden" name="order_id" value="{{$order_id}}">
    <div class="table-responsive">
        <table class="table table-bordered  table-sm">
            <thead>
                <tr>
                    <th>Medication</th>
                    <th>Quantity</th>
                    <th>
                        Update Collection
                        {{--<input type="checkbox" name="check_all" class=""><! --check-all-- !>--}}
                    </th>
                </tr>
            </thead>
            <input type="hidden" name="rx_id" value="{{ $request->id }}">
            <tbody>
                @foreach($medications as $medication)
                <tr>
                    <td>{{ $medication->drug_name }}</td>
                    <td>{{ $medication->quantity }}</td>
                    <td><input type="checkbox" class="input-medication" value="{{ $medication->id }}" name="medications[]" ></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $('.check-all').on('change', function() {
        if ($(this).is(':checked'))
            $('.input-medication').prop('checked', true);
        else
            $('.input-medication').prop('checked', false);
    })
</script>