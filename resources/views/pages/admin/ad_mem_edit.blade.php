@extends('layouts.admin_layout')

@section('content')
<div class = "container py-5">
    <div class = "col align-self-center">
        <h3>Faculty adding</h3>
        <form action="{{ route('update-ad-mem', ['id' => $admission_member->id]) }}" method="post">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="name" value="{{ $admission_member->getName() }}">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" value="{{ $admission_member->getEmail() }}">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="birthdate" value="{{ $admission_member->getBirthdate() }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="phone_number" value="{{ $admission_member->getPhoneNumber() }}" placeholder="Phone number">
            </div>
            <button type="submit" class="btn btn-outline-success">Save</button>
        </form>
        <form action="{{ route('delete-ad-mem', ['id' => $admission_member->id]) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-danger mt-2">Delete</button>
        </form>
    </div>
</div>
@endsection()