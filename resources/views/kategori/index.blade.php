@extends('layouts.master')

@section('title')
    Category List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Category List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('kategori.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Add</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Category</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('kategori.form')
@endsection

@push('scripts')
<script>
    let table;
    let formAction = 'add';

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kategori.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kategori'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            title: "Success",
                            text: formAction === 'add' ? "Successfully added data" : "Successfully updated data",
                            icon: "success"
                        });
                    })
                    .fail((errors) => {
                        Swal.fire({
                            title: "Error",
                            text: "There is something wrong",
                            icon: "error"
                        });
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        formAction = 'add';
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_kategori]').focus();
    }

    function editForm(url) {
        formAction = 'edit';
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_kategori]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
            })
            .fail((errors) => {
                return;
            });
    }

    // function deleteData(url) {
    //     if (confirm('Yakin ingin menghapus data terpilih?')) {
    //         $.post(url, {
    //                 '_token': $('[name=csrf-token]').attr('content'),
    //                 '_method': 'delete'
    //             })
    //             .done((response) => {
    //                 table.ajax.reload();
    //                 Swal.fire({
    //                     title: "Success",
    //                     text: "Successfully deleted data",
    //                     icon: "success"
    //                 });
    //             })
    //             .fail((errors) => {
    //                 Swal.fire({
    //                     title: "Fail",
    //                     text: "Failed to delete data",
    //                     icon: "error"
    //                 });
    //                 return;
    //             });
    //     }
    // }

    function deleteData(url) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete this data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                        Swal.fire({
                            title: "Success",
                            text: "Successfully deleted data",
                            icon: "success"
                        });
                    })
                    .fail((errors) => {
                        Swal.fire({
                            title: "Fail",
                            text: "Failed to delete data",
                            icon: "error"
                        });
                        return;
                    });
            }
        });
    }

</script>
@endpush