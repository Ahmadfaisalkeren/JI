@extends('backend.master')

@section('title', 'Home')

@section('content')
    <div class="container">
        <h1>Home CMS</h1>
        <a class="btn btn-sm btn-success mb-2" href="javascript:void(0)" id="createHome"> Create Home</a>
        <table id="homeTable" class="table table-striped table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>Description 2</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('backend.home.form')

@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('home.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'description_2',
                        name: 'description_2'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#createHome').click(function() {
                $('#saveBtnNew').show();
                $('#saveBtnEdit').hide();
                $('#saveBtnNew').val("create-home");
                $('#home_id').val('');
                $('#homeForm').trigger("reset");
                $('#modelHeading').html("Create New Home");
                // Set the modal size to modal-xl
                $('#ajaxModel .modal-dialog');
                $('#ajaxModel').modal('show');
            });

            $('#saveBtnNew').click(function(e) {
                e.preventDefault();

                var formData = new FormData($("#homeForm")[0]);
                // console.log(formData.get('image'));

                var url = "{{ route('home.store') }}";
                var method = 'POST';

                // Add validation checks here

                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(data) {
                        $('#homeForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The home has been saved successfully.",
                            icon: "success",
                            timer: 3000
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtnNew').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.editHome', function() {
                var home_id = $(this).data('id');
                $.get("{{ route('home.index') }}" + '/' + home_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Home");
                    $('#saveBtnNew').hide();
                    $('#saveBtnEdit').show();
                    $('#saveBtnEdit').val("edit-home");
                    // Set the modal size to modal-xl
                    $('#ajaxModel .modal-dialog');
                    $('#ajaxModel').modal('show');
                    $('#home_id').val(data.id);
                    $('#description').val(data.description);
                    $('#description_2').val(data.description_2);
                    $('#image').val(data.image);
                });
            });

            $(document).ready(function() {
                var currentImage = $('#current-image');
                var imageInput = $('#image');

                // When the user selects a new image, show a preview of it
                imageInput.on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        currentImage.attr('src', e.target.result);
                        currentImage.show();
                    };

                    reader.readAsDataURL(file);
                });

                // When the page loads, show the current image if there is one
                if (currentImage.attr('src') !== '') {
                    currentImage.show();
                }
            });

            $('#saveBtnEdit').click(function(e) {
                e.preventDefault();

                var home_id = $('#home_id').val();
                var url = "{{ route('home.update', ':id') }}".replace(':id', home_id);
                var method = 'PUT';

                // Add validation checks here

                var formData = new FormData($('#homeForm')[0]);
                formData.append('_method', method);

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#homeForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The home has been updated successfully.",
                            icon: "success",
                            timer: 3000
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtnEdit').html('Save Changes');
                    }
                });
            });
        });

        function deleteData(url) {
            Swal.fire({
                title: 'Yakin ingin menghapus data terpilih?',
                text: 'Anda tidak dapat mengembalikan data yang telah dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            const dataTable = $('.data-table').DataTable();
                            dataTable.row(`[data-id="${response.id}"]`).remove().draw();
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                            });
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menghapus data');
                            return;
                        });
                }
            });
        }
    </script>
@endpush
