@extends('app')

@seciton('content')
<table class="table">
    @foreach($user->invoices() as $invoice)
    <tr>
        <td>{{ $invoice->date() }}</td>
        <td>{{ $invoice->total() }}</td>
        <td><a href="/invoices/download/{{ $invoice->id }}">Download Receipt</a></td>
    </tr>
    @endforeach
</table>

{!! Form::open(['route' => 'plans.swap', 'class' => 'form-horizontal']) !!}
<select name="plan_id" class="form-control" id="plan_id">
    <option value="prod_IkvfqOyON7X39V">Trial / $10 per month</option>
    <option value="prod_IkveNDtB8Z7aXU">Regular / $30 per month</option>
    <option value="prod_IkvbNHMMCMYQTM">Premium / $ 50 per month</option>
</select>

<button type="submit" class="btn btn-default">Swap Plans</button>
{!! Form::close() !!}
@endsection
