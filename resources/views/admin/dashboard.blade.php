@extends('admin.web')

@section('head')
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
@endsection

@section('container')

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Dashboard</h6>
                </div>
            </div>
            <div class="card-body pb-2">
                Dashboard
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-form-web" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="fweb" class="row g-3 needs-validation" novalidate>
            @csrf
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="menu_id" id="menu_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM APLIKASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-6">
                            <label for="grup" class="form-label" >Grup :</label>
                            <div id="grup-caption"></div>
                        </div>
                        <div class="col-6">
                            <label for="menu" class="form-label" >Menu :</label>
                            <div id="menu-caption"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-3">
                            <label for="grup" class="form-label" >Hak Akses :</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <input type="checkbox" name="create" id="create" value="1"> Create 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <input type="checkbox" name="read" id="read" value="1"> Read 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="checkbox" name="update" id="update" value="1"> Update 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="checkbox" name="delete" id="delete" value="1"> Delete 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="checkbox" name="special" id="special" value="1"> All 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="tutup-modal" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section("scriptJs")
    <script type="text/javascript" src="plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="js/select2lib.js"></script>
    <script type="text/javascript">
    </script>
@endsection
