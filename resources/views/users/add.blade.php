<div class="modal-header">
    <h4 class="modal-title">Add new User</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form action="#" id="forminsert" class="form-horizontal" name="formnih">
        <input type="hidden" value="" name="id" />
        <div class="form-body">
            <div class="form-group">
                <label class="control-label ">Nama Lengkap *</label>

                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Email *</label>

                <input type="email" name="email" class="form-control" placeholder="Email" value="">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Password *</label>

                <input type="password" name="password" class="form-control" placeholder="Password"
                    value="">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label ">Re-type Password *</label>

                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type Password"
                    value="">
                <span class="help-block"></span>

            </div>
            <div class="form-group">
                <label class="control-label">Major (Wajib diisi jika role yang dipilih adalah student)</label>
                <select class="sel form-control" name="major_id" id="major_id">
                    <option value="">-- Choose Major --</option>
                   @foreach ($majors as $major)
                       <option value="{{$major->id}}">{{$major->name}}</option>
                   @endforeach
                </select>
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label class="control-label ">Avatar</label>
                <input type="file" name="avatar" id="" class="form-control">
                <span class="help-block"></span>

            </div>
            <label class="control-label " >Groups *</label>
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
                               @foreach ($list as $key)
                                    <tr>
                                    <td class="text-right">
                                     <input type="checkbox" class="groups" name="group[]" value="{{$key->id}}">
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
    <button type="button" class="btn btn-primary" onclick="insertfun(this)">Save</button>
</div>
