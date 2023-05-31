        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-user mr-2px"></i>
                {{$card_title ?? ""}}
            </h5>
          </div>

          <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">

            <div class="row mt-2">
                <div class="col-12 col-md-6 col-lg-6 my-1">
                    <div class="card card-body bg-light">
                        <h4>{{$patient->first_name}}, {{$patient->last_name}} {{$age}}</h4>
                        <div><b>DOB:</b><span>{{$patient->date_of_birth}}</span></div>
                        <div><b>Email:</b><span> {{$patient->email}}</span></div>
                        <div><b>P:</b><span> {{$patient->home_phone}}</span></div>
                        <div><b>C:</b><span> {{$patient->cell_phone}}</span></div>
                        <div><b>Address:</b><span> {{$patient->address}}</span></div>
                        <div><b>City:</b><span> {{$patient->city}}</span></div>
                        <div><b>Zip:</b><span> {{$patient->zip}}</span></div>
                        <div><b>High Blood Pressure:</b> <input disabled  type="checkbox" {{$patient->high_blood_pressure ? "checked" :""}}    /></div>
                        <div><b>High Cholesterol:</b> <input  disabled type="checkbox" {{$patient->high_cholesterol ? "checked" :""}}/></div>
                        <div><b>Diabetes:</b> <input  disabled type="checkbox" {{$patient->diabetes ? "checked" :""}}/></div>
                        <div><b>Patient Note:</b><span> {{$patient->patient_note}}</span></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 my-1">
                    <div class="card card-body bg-white">
                        <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>Quick Stats</b>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>OFFICE VISTS</span>
                            <b class="text-primary">{{$office_visits}}</b>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>AVG ORDER</span>
                            <b class="text-primary">${{$avg_order}}</b>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>LIFETIME SPENT</span>
                            <b class="text-primary">${{$lifetime_spent}}</b>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>CURRENT BALANCE</span>
                            <b class="text-primary">${{$current_balance}}</b>

                        </li>
                        </ul>
                    </div>
                </div>
            </div>

          </div>
        </div>
