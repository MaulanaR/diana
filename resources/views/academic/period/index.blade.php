@extends('layouts.backend')

@section('css')
    <link type="text/css" rel="stylesheet" href="{{asset('jsgrid/dist/jsgrid.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('jsgrid/dist/jsgrid-theme.min.css')}}" />
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('jsgrid/dist/jsgrid.min.js')}}"></script>
    <script>
        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",
            sorting: true,
            filtering: true,
            selecting:true,
            paging: true,
            autoload: true,
            pageIndex: 1,
            pageSize: 15,
            pageButtonCount: 5,
            pageLoading: true,
            confirmDeleting: false,
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "{{route('academic_periods.show')}}",
                        data: filter
                    });
                },

                insertItem: function(item) {
                },

                updateItem: function(item) {
                },

                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "{{route('academic_periods.destroy')}}",
                        data: item
                    }).done(function (response) {
                        popup("Berhasil",response.message,'success');
                    }).fail(function(xhr, status, error) {
                        popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
                    });
                },
            },
            onItemInserted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemUpdated: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemDeleting: function (args) {
                if (!args.item.deleteConfirmed) { // custom property for confirmation
                    args.cancel = true;
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Pastikan anda mengetahui apa yang anda lakukan.",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Lanjutkan Hapus.'
                        }).then((result) => {
                        if (result.value) {
                            args.item.deleteConfirmed = true;
                            $("#jsGrid").jsGrid("deleteItem",args.item);
                        }
                    })
                }
            },
            onItemDeleted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            fields: [
                {
                    name: "id",
                    title: "ID",
                    align : "center",
                    type: "text",
                    visible: false,
                    width: 20,
                },
                {
                    name: "name",
                    title: "Tahun Akademik",
                    align : "left",
                    type: "text",
                    width: 100,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "start_date",
                    title: "Tgl Awal",
                    align : "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "end_date",
                    title: "Tgl Akhir",
                    align : "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "total_student",
                    title: "Jumlah Mahasiswa",
                    align : "center",
                    type: "text",
                    width: 50,
                    filtering: false,
                    validate: "required"
                },
                {
                    name: "total_student_female",
                    title: "Perempuan",
                    align : "center",
                    type: "text",
                    width: 50,
                    filtering: false,
                    validate: "required",
                    search:false
                },
                {
                    name: "total_student_male",
                    title: "Laki-laki",
                    align : "center",
                    type: "text",
                    width: 50,
                    filtering: false,
                    validate: "required",
                    search:false
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        var ebuton = '';
                        var dbuton = '';
                        @if (session('can_edit'))
                            ebuton = '<i class="fas fa-pen" style="color:blue" title="Edit"></i>'
                        @endif
                        @if (session('can_delete'))
                            dbuton = ' <i class="fas fa-trash" style="color:red" title="Delete"></i> '
                        @endif
                        var editDeleteBtn = $(ebuton+dbuton)
                            .on('click', function (e) {
                                if (e.target.title == 'Edit') {
                                    e.stopPropagation();
                                    modal_edit(item);
                                } else if (e.target.title == 'Delete'){
                                    $("#jsGrid").jsGrid("deleteItem",item);
                                }
                            });
                        
                        return editDeleteBtn; //
                    },
                    @if (session('can_add'))
                    headerTemplate: function(value, item) {
                        var editDeleteBtn = $('<button type="button" class="btn btn-sm btn-success">+ Tambah</button>')
                            .on('click', function (e) {
                                modal_add();
                            });
                        return editDeleteBtn; //
                    },
                    @endif
                }
            ]
        });

    </script>

    <script>
        function JSONToCSVConvertor(JSONData, Label, ShowLabel) {
            //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
            var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
            var CSV = '';
            //Set Report title in first row or line
            CSV += Label + '\r\n\n';
            //This condition will generate the Label/Header
            if (ShowLabel) {
                var row = "";
                //This loop will extract the label from 1st index of on array
                for (var index in arrData[0]) {
                    //Now convert each value to string and comma-seprated
                    row += index + ',';

                }
                row = row.slice(0, -1);
                //append Label row with line break
                CSV += row + '\r\n';
            }
            //1st loop is to extract each row
            for (var i = 0; i < arrData.length; i++) {
                var row = "";
                //2nd loop will extract each column and convert it in string comma-seprated
                for (var index in arrData[i]) {

                    row += '"' + arrData[i][index] + '",';

                }
                row.slice(0, row.length - 1);
                //add a line break after each row
                CSV += row + '\r\n';
            }
            if (CSV == '') {
                alert("Invalid data");
                return;
            }
            //Generate a file name
            var fileName = "FileTitle";

            //Initialize file format you want csv or xls
            var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
            //alert(uri);
            // Now the little tricky part.
            // you can use either>> window.open(uri);
            // but this will not work in some browsers
            // or you will not get the correct file extension
            //this trick will generate a temp <a /> tag
            var link = document.createElement("a");
            link.href = URL.createObjectURL(new Blob([CSV], { type: "application/octet-stream" })); //added to fix network error problem in chrome
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".csv";
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();
            // document.body.removeChild(link);
        }
        function headerValues(){
        return $( '#jsGrid' ).data( 'JSGrid' )
                       .fields
                       .map( function( d ){
                        let v = String(d.title)
                        v.replace(" ", "___")
                           return d.name+"x"+v
                       })
}

    function dataObjects(){
    return $( '#jsGrid' ).data( 'JSGrid' ).data
    }

    function createJSONForSingle( object ){
    var hash = {};
    
    headerValues().map( function( key ){
        console.log(key)
        hash[ key ] = object[ key ]
    });

    return hash;
    }

    function createJSON(){
    objects = dataObjects().map( function( o ){
        return createJSONForSingle( o )
    });

    return JSON.stringify( objects, null, 2 );
    }

    function modal_edit(itemedit)
    {
        window.location.href = "{{route('academic_periods.edit')}}"+"/"+itemedit.id;
        // console.log(createJSON())
        // JSONToCSVConvertor(createJSON(), "Vehicle Report", true);
    }

    function modal_add(){
        window.location = "{{route('academic_periods.modal')}}";
    }
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="jsGrid"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="modal_edit" tabindex="" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content" id="body_edit">
            
          </div>
        </div>
      </div>
@endsection
