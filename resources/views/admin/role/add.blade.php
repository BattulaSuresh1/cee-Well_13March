@extends('layouts.mainlayout')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            @php
                                $action = "Create";
                                $name = "";
                                if(!empty($role)){
                                     $action = "Update";
                                     $name = $role->name;
                                }

                                // dd($activePermissions)
                            @endphp
                            <h2> {{ $action }}  Role  </h2>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                                    
                            <form id="roleForm" action="{{ ($action == "Create")? url('role/save') : url('role/update') }}" method="post">
                                    {!! csrf_field() !!}

                                    @php
                                        if(!empty($role)){
                                            echo ' <input type="hidden" name="id" id="id" value="'.$role->id.'">';                            
                                        }        
                                    @endphp

                                <div class="form-group ">
                                    <label class="form-label">Role Name</label>
                                    <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Role Name" value="{{$name}}" require>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>

                                <div class="">
                                    <label class="form-label">Give Permissions</label>
                                    <table class="table table-bordered">
                                    
                                        @foreach ($groups as $k =>  $group)

                                            <tr class="bg-cyan">
                                                <td colspan="2"> {{$group['permission_group']}} </td>

                                                    @foreach ($permissions as $j =>  $permission)
                                                        @if($group['permission_group'] == $permission['permission_group'])

                                                        @php
                                                            $checked = 0;

                                                            if($activePermissions->contains('permission_id',$permission['id']))
                                                            {   
                                                                $checked = 1;
                                                            }
                                                        @endphp     
                                                            <tr> 
                                                                <td>  <b>{{ $permission['name'] }}</b></td>
                                                                <td> 
                                                                    <input type="hidden" name="permission_label[{{ $loop->index}}]"  value="{{ $permission['name']}}">
                                                                    <div class="switch">
                                                                        <label>
                                                                            <input class="permissionchecks" type="checkbox" id="permission_id_{{ $permission['id'] }}" name="permissions[{{ $loop->index}}]" value="{{ $permission['id']}}"  {{($checked)? "checked": ""}}>
                                                                            <span class="lever switch-col-blue"></span>
                                                                        </label>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 align-right">
                                        <a href="{{ url('role/list') }}" class="btn btn-danger waves-effect">{{ __('Cancel') }}</a>
                                        <button class="btn bg-light-blue waves-effect">{{ $action }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('dist/js/custom/role.js') }}"></script>
@endsection