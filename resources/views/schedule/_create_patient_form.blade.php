             @csrf
            <div class="row">
              <div class="col-12">
                <h4>Personal info</h4>
                <hr>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="first_name" class="mb-0">First Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="first_name" name="first_name" class="form-control"  value="{{old('first_name')}}"  />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="last_name" class="mb-0">Last Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="last_name" name="last_name" class="form-control"  value="{{old('last_name')}}" />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="home_phone" class="mb-0">Home Phone</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="text" id="home_phone" name="home_phone" class="form-control phones"  value="{{old('home_phone')}}"  />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="cell_phone" class="mb-0">Cell Phone</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="text" id="cell_phone" name="cell_phone" class="form-control phones" value="{{old('cell_phone')}}"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="email" class="mb-0">Email</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="email" id="email" name="email" class="form-control" value="{{old('email')}}"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="date_of_birth" class="mb-0">Date of Birth</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="input" autocomplete="0" id="date_of_birth" name="date_of_birth" class="form-control dates"  value="{{old('date_of_birth')}}"    />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Address info</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                  <label for="address" class="mb-0">Address</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="text" id="address" name="address" class="form-control" value="{{old('address')}}"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="city" class="mb-0">City</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="text" id="city" name="city" class="form-control" value="{{old('city')}}"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="state" class="mb-0">State</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <select type="text" id="state" name="state" data-value="{{session('current_location')->state}}" class="form-control" >
                  </select>
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="zip" class="mb-0">Zip</label>
                  {{-- <span class="text-danger">*</span> --}}
                  <input type="text" id="zip" name="zip" class="form-control zips " value="{{old('zip')}}"   />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <h4>Existing Health Conditions</h4>
                <hr>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" {{old('high_blood_pressure') ? 'checked': ''}} value="1" id="high_blood_pressure" name="high_blood_pressure" class="form-control"   />
                    <label for="high_blood_pressure" class="mb-0">High Blood Pressure</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" {{old('high_cholesterol') ? 'checked': ''}} id="high_cholesterol" name="high_cholesterol" class="form-control"   />
                    <label for="high_cholesterol" class="mb-0">High Cholesterol</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" id="diabetes" {{old('diabetes') ? 'checked': ''}} name="diabetes" class="form-control"   />
                    <label for="diabetes" class="mb-0">Diabetes</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Marketing Info</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                    <label for="how_did_hear_about_clinic" class="mb-0">How Did This Patient hear about your clinic ? <span class="text-danger">*</span> </label>
                    <select  id="how_did_hear_about_clinic" name="how_did_hear_about_clinic" class="form-control"  >
                        <option value="">Select Marketing Source</option>
                       @foreach ($marketing_source as $ms)

                         <option value="{{$ms->id}}" {{old('how_did_hear_about_clinic') == $ms->id ? "selected" :""}}>{{$ms->description}}</option>

                       @endforeach
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Patient Note</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                     <textarea  id="patient_note" name="patient_note" class="form-control"  >{{old('patient_note')}}</textarea>
                </div>
              </div>
            </div>

