@extends('layouts.mainlayout')

@section('content')

    <div class="row clearfix customerTb">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-lg-4">
                            <h2>  Roles  </h2>
                        </div>
                        <div class="col-lg-8 align-right">
                            <div class="card-header-right" id="cardRightButton">
                                <a href="{{ url('role/create') }}" class="btn bg-light-blue waves-effect">
                                    <span > {{ __('Create Role') }} </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <div class="table-responsive">
                        <table id="" class="table table-bordered table-striped table-hover dataTable rolesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn custom-btn-small btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
                    <span class="ajax-loading"></span>
                </div>
            </div>
        </div>
    </div>
    
      
@endsection

@section('js')
    <script>
        
        $('.rolesTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: "{{route('rolesdata')}}",
            columns: [
                { data: 'name' },
                { data: 'actions' },
            ],
        });

    </script>
@endsection
