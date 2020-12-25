@extends('layouts.user_layout')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-4">
                <div class="d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            @if($student->student_picture_url != null)
                                <div class="d-flex justify-content-center">
                                    <div class="crop ">
                                        <img src="{{$student->student_picture_url}}" style="border-radius: 5%" class="card-img-top">
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-center">
                                    <div class="crop ">
                                        <img src="{{ asset('img/default_user.jpg') }}" style="border-radius: 5%" class="card-img-top">
                                    </div>
                                </div>
                            @endif
                            <div class="align-self-center d-flex align-content-center mt-3">
                                <h4 class="mb-0"><b>{{$student->getName()}}</b></h4>
                            </div>
                            <hr>
                            <div class="mb-2 bd-highlight">
                                <a href="{{route('student-profile', ['id' => $student->user_id])}}" class="btn btn-iitucolor btn-block">Profile</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-8">
                <div class="card mb-5">
                    <div class="card-body">
                        <h2 class="mb-0"><b>Online reception</b></h2>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h2>Write your question:</h2>
                        <hr>
                        <form action="{{ route('reception-student-send', ['id' => $student->user_id]) }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <textarea class="form-control" name="message_text" rows="5"></textarea>
                            </div>
                            <div class="bd-highlight row my-2">
                                <div class="col-3 align-self-center">
                                    <button type="submit" class="btn btn-outline-iitucolor btn-block">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(!empty($chats))
                <div class="card mt-3">
                    <div class="card-body">
                        <h2>Recent chats</h2>

                        @foreach($chats as $chat)
                            <hr>
                            <a href="{{route('reception-chat', ['id' => $chat->id])}}">
                                <div class="row">
                                    <div class="my-2 mx-2" >
                                        <div class="my-4 crop__chat" >
                                            @if($chat->latest_message_sender)
                                                <img src="{{$chat->getStudentPic()}}" >
                                            @else
                                                <img src="{{$chat->getAdmissionPic()}}" >
                                            @endif
                                        </div>
                                    </div>
                                    <div class="my-2 d-flex flex-column">
                                        <div class="p-1"><b><h5>
                                                    @if($chat->latest_message_sender)
                                                        {{$chat->getStudentName()}}
                                                    @else
                                                        {{$chat->getAdmissionName()}}
                                                    @endif
                                                </h5></b></div>
                                        <div class="p-1"><p>{{$chat->latest_message_text}}</p></div>
                                    </div>
                                    <div class="my-2 mx-2 ml-auto d-flex flex-column">
                                        <div class="p-1"><label>{{ date_format(date_create($chat->latest_message_time), "M d Y H m") }}</label></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection