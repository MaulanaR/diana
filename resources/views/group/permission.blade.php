<div class="modal-header">
    <h4 class="modal-title">Setting Permission</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form action="#" id="forminsert" class="form-horizontal">
        <input type="hidden" name="id_group" class="form-control" value="{{$id}}">
        <div class="form-group">
            <div style="overflow-y:scroll;height:450px;padding:0px;" class="form-control">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <td width="1%" class="bg-head">No</td>
                            <td width="60%" class="bg-head">Nama Menu (URI)</td>
                            <td width="10%" class="bg-head">Can View</td>
                            <td width="10%" class="bg-head">Can Add</td>
                            <td width="10%" class="bg-head">Can Edit</td>
                            <td width="10%" class="bg-head">Can Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        $ar = 0;
                        @endphp
                        
                        @foreach ($result as $rows)
                            @php
                            if (empty($menus[$ar])) {
                                $menus[$ar] = 0;
                            }
                            if (empty($canad[$ar])) {
                                $canad[$ar] = 0;
                            }
                            if (empty($canedit[$ar])) {
                                $canedit[$ar] = 0;
                            }
                            if (empty($candelet[$ar])) {
                                $candelet[$ar] = 0;
                            }
                            if (empty($canview[$ar])) {
                                $canview[$ar] = 0;
                            }
                            @endphp
                            <tr>
                                <td><input type="hidden" name="bot[]" value="{{$no}}"><input type="hidden" name="menu[{{$no}}]" value="{{$rows->menu_id}}">{{$no}}</td>
                                <td>{{ $rows->menu_nama . " ( " . $rows->menu_uri . " )"}}</td>

                                <td class="text-center">

                                    <input type="hidden" name="canview[{{$no}}]" value="0">
                                    <input type="checkbox" name="canview[{{$no}}]" value="1"
                                    @php
                                    for ($x = 0; $x < count($menus); $x++) {
                                        if ($menus[$x] == $rows->menu_id) {
                                            if ($canview[$x] == 1) {
                                                echo "checked='checked'";
                                            };
                                            break;
                                        };
                                    };
                                    @endphp >

                                </td>
                                <td class="text-center">

                                    <input type="hidden" name="canadd[{{$no}}]" value="0">
                                    <input type="checkbox" name="canadd[{{$no}}]" value="1" 
                                    @php
                                        for ($x = 0; $x < count($menus); $x++) {
                                            if ($menus[$x] == $rows->menu_id) {
                                                if ($canad[$x] == 1) {
                                                    echo "checked='checked'";
                                                };
                                                break;
                                            };
                                        };
                                    @endphp >

                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="canedit[{{$no}}]" value="0">
                                    <input type="checkbox" name="canedit[{{$no}}]" value="1" 
                                    @php
                                        for ($x = 0; $x < count($menus); $x++) {
                                            if ($menus[$x] == $rows->menu_id) {
                                                if ($canedit[$x] == 1) {
                                                    echo "checked='checked'";
                                                };
                                                break;
                                            };
                                        };
                                    @endphp >
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="candelete[{{$no}}]" value="0">
                                    <input type="checkbox" name="candelete[{{$no}}]" value="1" 
                                    @php
                                        for ($x = 0; $x < count($menus); $x++) {
                                            if ($menus[$x] == $rows->menu_id) {
                                                if ($candelet[$x] == 1) {
                                                    echo "checked='checked'";
                                                };
                                                break;
                                            };
                                        };
                                    @endphp >

                                </td>
                            </tr>
                        @php
                            $no++;
                            $ar++;
                        @endphp
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" onclick="insertfun(this)">Update</button>
</div>
