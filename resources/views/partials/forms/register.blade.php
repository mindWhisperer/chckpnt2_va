<div class="col-md-12">
    <div class="col-md-6">
        <label for="inputEmail4" class="form-label">* Email </label>
        <input type="email" class="form-control" id="inputEmail4" value="{{$user->email ?? ''}}" name="email" {{ isset($user) && isset($user->email) ? 'readonly' : '' }}>
    </div>
    <div class="col-md-6">
        <label for="inputName4" class="form-label">* Meno</label>
        <input type="text" class="form-control" id="inputName4" value="{{$user->name ?? ''}}" name="name">
    </div>
    <div class="col-md-6">
        <label for="inputPic" class="form-label">Link na profilovu fotku</label>
        <input type="url" class="form-control" id="inputPic" value="{{$user->profile_pic ?? ''}}" name="profile_pic">
    </div>
    <div class="col-md-6">
        <label for="inputPassword4" class="form-label">* Heslo</label>
        <input type="password" class="form-control" id="inputPassword4" name="password">
    </div>
</div>

<div class="form-error-container"></div>

<div class="col-12">
    <p>* povinné vyplniť</p>
</div>


