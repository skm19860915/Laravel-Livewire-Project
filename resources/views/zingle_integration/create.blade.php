@extends('layouts.auth')

@section('content')

<div role="main" class="main-content">
    <div class="page-content container container-plus">
        <div class="page-header">
            <h2 class="page-title text-primary-d2 text-150">
                Zingle SMS Integration
            </h2>
        </div>


        @include('includes.alerts')

        <div class="cards-container">
            <div class="card border-0 shadow-sm radius-0">
                <div class="card-header bgc-primary-d1">
                    <h5 class="card-title text-white">
                        <i class="fa fa-edit mr-2px"></i>
                        Create Integration
                    </h5>
                </div>

                <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
                    <form  id="zingleForm" class="mt-lg-3 forms" autocomplete="off" method="POST" action="{{url('/zingle-integration/store')}}" >
                        @csrf

                        <input type="hidden" name="location_id" value="{{session('current_location')->id}}">

                        <div class="row mt-2">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label  class="mb-0">Enable/Disable Integration</label>
                                    <select name="enabled" class="form-control" required>
                                        <option value="0">Disabled</option>
                                        <option value="1">Enabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label  class="mb-0">Your Zingle Username</label>
                                    <input
                                        type="text"
                                        name="zingle_username"
                                        class="form-control"
                                        value="{{ old('zingle_username')}}"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label  class="mb-0">Your Zingle Password</label>
                                    <input
                                        type="password"
                                        name="zingle_password"
                                        class="form-control"
                                        value="{{ old('zingle_password')}}"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label  class="mb-0">Your Zingle Phone Number</label>
                                    <input
                                        type="text"
                                        name="zingle_phone_number"
                                        class="form-control phones"
                                        value="{{ old('zingle_phone_number')}}"
                                        required
                                    />
                                </div>
                            </div>
                        </div> -->

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary submit-buttons">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script src="{{mix('js/zingle.js')}}" defer></script>
@endsection

@endsection
