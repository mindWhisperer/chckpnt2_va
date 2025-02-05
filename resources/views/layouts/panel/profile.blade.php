@extends('index')

@section('title', 'Profil - ' . $user->name)


    @section('content')
        <div class="container emp-profile">
            <div class="row">
                <div class="col-md-4 profile-img">
                    <img src="{{$user->profile_pic}}" alt=""/>
                </div>
                <div class="col-md-4 profile-head">
                    <h3>
                        {{ $user->name }}
                    </h3>
                </div>
                <div class="col-md-2">
                   <button type="button" class="profile-edit-btn"><a href="{{route('edit-profile', ['id' => $user->id])}}" class="profile-edit-btn">Upraviť profil</a></button>
                </div>
                <div class="col-md-2">
                    <button type="button" data-id="{{$user->id}}" id="deleteProfile" class="profile-edit-btn"> Odstrániť profil
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <!--volnicko-->
                </div>
                <div class="col-md-8 tab-content profile-tab" id="myTabContent">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Prezývka</h5>
                        </div>
                        <div class="col-md-6">
                            <p>{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Email</h5>
                        </div>
                        <div class="col-md-6">
                            <p>{{$user->email}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
