@extends('layouts/backend/main')

@section('title', 'Products')


@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>List Products</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Products</a></div>
                    <div class="breadcrumb-item"><a href="#">List</a></div>
                </div>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="ml-auto"><button id="product" class="btn btn-primary" type="button"><i
                                    class="fa fa-plus"></i>
                                Add</button></h4>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection




<!-- Modal Product-->
<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="modalProductTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="productForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductTitle">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" required="">
                            <div class="invalid-feedback d-name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" name="description" id="description" class="form-control"
                                required="">
                            <div class="invalid-feedback d-description">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Quantity</label>
                        <div class="col-sm-9">
                            <input type="number" name="quantity" id="quantity" class="form-control" required="">
                            <div class="invalid-feedback d-quantity">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Price</label>
                        <div class="col-sm-9">
                            <input type="number" name="price" id="price" class="form-control" required="">
                            <div class="invalid-feedback d-price">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save">Save</button>
                </div>
                <form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            //ajax menampilkan list data product
            getDataProduct()

            $('#product').click(function() {
                $('#modalProduct').modal('show')
            });

            //fucntion untuk save,edit,detail product
            $('.btn-save').click(function(e) {
                e.preventDefault();
                let id = $('#product_id').val();


                let url = id ? `/product-update/${id}` : '/product-save';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    type: method,
                    url: url,
                    data: $('#productForm').serialize(),
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == true) {
                            $('#modalProduct').modal('hide')

                            swetAlert('success', 'success', response.message)
                            $('#example').DataTable().ajax.reload();
                            $('#productForm')[0].reset();
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').empty();


                        } else {

                            swetAlert('error', 'error',
                                'Terjadi kesalahan. Silakan hubungi admin.')

                        }

                    },
                    error: function(xhr) {
                        swetAlert('error', 'error', xhr.responseJSON.message)
                        // Clear previous errors
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').empty();

                        if (xhr.responseJSON.errors) {
                            // Display validation errors
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                ALERT(value);
                                $('#' + key).addClass('is-invalid');
                                $('#error-' + key).text(value[0]);
                            });
                        }
                    }
                })

            });
        });

        //function untuk alert proses data
        function swetAlert(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text
            });

        }

        function getDataProduct() {
            $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.data') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

        }
        // function untuk handle action edit,delete dan detail
        function f_action(element) {
            var id = $(element).data('id');
            var action = $(element).data('action');
            var name = $(element).data('name');
            var price = $(element).data('price');
            var quantity = $(element).data('quantity');
            var description = $(element).data('description');

            if (action === 'detail') {
                $('.btn-save').addClass('d-none');
                $('.modal-title').html('Detail Product: <b>' + name + '</b>');

                $('#modalProduct .modal-body').html(
                    '<p>ID: ' + id + '</p>' +
                    '<p>Name: ' + name + '</p>' +
                    '<p>Price: ' + price + '</p>' +
                    '<p>Quantity: ' + quantity + '</p>' +
                    '<p>Description: ' + description + '</p>'
                );
                $('#modalProduct').modal('show');
            } else if (action === 'edit') {
                $('#product_id').val(id);
                $('#name').val(name);
                $('#price').val(price);
                $('#quantity').val(quantity);
                $('#description').val(description);
                $('.btn-save').removeClass('d-none');
                $('.modal-title').html('Edit Product: <b>' + name + '</b>');
                $('#modalProduct').modal('show');
            } else if (action === 'delete') {
                var url = '{{ route('products.destroy', ':id') }}';
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Apakah Kamu Yakin ?',
                    text: "Data Yang Dihapus Tidak Dapat Dikembalikan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Data Berhasil Dihapus',
                                    'success'
                                );

                                $('#example').DataTable().ajax.reload();

                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Gagal Menghapus Data',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        }
    </script>
@endpush
