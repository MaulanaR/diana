
<div class="modal-header">
    <h4 class="modal-title">Edit User</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form action="#" id="formupdate" class="form-horizontal" name="formnih">
        {{-- @method('PUT') --}}
        <input type="hidden" value="{{$data->id}}" name="id" />
        <div class="form-body">
            <div class="form-group">
                <label class="control-label ">Nama Lengkap</label>

                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{$data->name}}">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{$data->email}}">
                <input type="hidden" name="email_lama" class="form-control" placeholder="Email" value="{{$data->email}}">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Password</label>

                <input type="password" name="password" class="form-control" placeholder="Password"
                    value="">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Re-type Password</label>

                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type Password"
                    value="">
                <span class="help-block"></span>

            </div>

            <div class="form-group">
                <label class="control-label ">Avatar</label><br>
                <img src="{{asset('avatar/'.$data->picture)}}" width="100" height="100"><br>
                <input type="file" name="avatar" id="" class="form-control">
                <input type="hidden" name="avatar_lama" value="{{$data->picture}}">
                <span class="help-block"></span>
            </div>
            <label class="control-label " >Groups</label>
                    <div style="border:0px solid #ccc; width:98% ; height: 170px; overflow-y: scroll; padding-left: 10px;">
                      <div class="contain">
                          <table class="table table-striped table-bordered table-hover"> 
                            <thead>
                              <tr>
                                <th width="2%"></th>
                                <th class="text-left">Name</th>
                                <th class="text-center">Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                 $grup = array();   
                                @endphp

                                @foreach ($groups as $item)
                                    @php 
                                    array_push($grup,$item->group_id);
                                    @endphp
                                @endforeach
                               
                               @foreach ($list as $key)
                                    <tr>
                                    <td class="text-right">
                                     <input type="checkbox" class="groups" name="group[]" value="{{$key->id}}" 
                                     @if(in_array($key->id,$grup,true))
                                        checked
                                     @endif
                                     >
                                     </td>
                                    <td>{{$key->name}}</td>
                                   <td>{{$key->description}}</td>
                                   </tr>
                                @endforeach
                            </tbody>
                      </table>
                  </div>
                  </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" onclick="updatefun(this)">Save</button>
</div>
