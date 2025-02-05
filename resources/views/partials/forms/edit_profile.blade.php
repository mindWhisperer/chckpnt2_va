<div class="col-md-12">
    <div class="col-md-6">
        <label for="inputName4" class="form-label">Meno</label>
        <input type="text" class="form-control" id="inputName4" name="name" value="{{$user->name}}">
    </div>
    <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Heslo</label>
        <input type="password" name="password" class="form-control" id="inputPassword4">
    </div>
    <div class="col-md-6">
        <label for="inputPic" class="form-label">Link na profilovu fotku</label>
        <input type="url" name="profile_pic" value="{{$user->profile_pic ?? ''}}" class="form-control" id="inputPic">
    </div>
</div>


