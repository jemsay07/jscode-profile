@extends('admin.user.profile-show')

@if ( count( $user ) > 0 )
  @foreach ($user as $item)
    @php

      $gender_args = array( 'male' => 'Male','female' => 'Female' );
      
      /**Skills Media*/
      $skills = unserialize($item->skills_meta);
      $skils_args = array( 'fa-adobe' => '&#xf778; Adobe','fa-css3-alt' => '&#xf38b; CSS3','fa-html5' => '&#xf13b; HTML','fa-js-square' => '&#xf3b9; Javascript','fa-laravel' => '&#xf3bd; laravel','fa-node-js' => '&#xf3d3; NodeJs','fa-php' => '&#xf457; PHP','fa-python' => '&#xf3e2; Python','fa-react' => '&#xf41b; React','fa-sass' => '&#xf41e; Sass','fa-wordpress' => '&#xf19a; Wordpress' );
      $sk_type_args = array( 'prog_bar' => 'Progress bar','circle' => 'Circle' );
      $c = count($skills);
      $skillsCount = 0;
      
      /**Schools*/
      $schools = unserialize($item->schools_meta);
      $attn_args = array( 'tertiary' => 'Tertiary', 'secondary' => 'Secondary');      
      $schl = count($schools);
      $schoolsCount = 0;

      /**Work Exp*/
      $work = unserialize($item->work_exp_meta);
      $present_args = array( 'off' => 'End', 'on' => 'Present' );
      $wrk = count($work);
      $workCount = 0;

      /**Social Media*/
      $sm = unserialize($item->social_meta);
      $sm_icons_args = array('facebook-f'=>'&#xF39E; Facebook','twitter'=>'&#xF099; Twitter','instagram'=>'&#xF16D; Instagram','google-plus-g'=>'&#xF0D5; Google Plus','linkedin-in'=>'&#xF0E1; Linkedin','pinterest-p'=>'&#xF231; Pinterest','github-alt'=>'&#xF113; Github');
      $sm_tab_args = array('_blank'=>'Open new tab','_self'=>'Self');
      $s = count($sm);
      $smCount = 0;

    @endphp
    @section('fname', $item->first_name )
    @section('mname', $item->middle_name )
    @section('lname', $item->last_name )
    @section('fullname', $item->first_name . ' ' . $item->middle_name . ' ' . $item->last_name)
    @section('email', $email->email )
    @section('bio', $item->bio )
    @section('address', $item->address )
    @section('age', $item->age )
    @section('gender')
      @foreach ($gender_args as $gKey => $gVal)
        <option value="{{ $gKey }}" {{ ( $gKey === $item->gender ) ? 'selected' : '' }}>{{ $gVal }}</option>
      @endforeach
    @endsection
    @section('contact', $item->contact_number )

    @php /**Skills*/  @endphp
    @section('skill_type')
      <div class="form-group">
        <label for="skills_type">What do you preferred?</label>
        <select name="skills_type" id="skills_type" class="form-control">
          @empty($item->skill_type)
            <option value="prog_bar">Progress bar</option>
            <option value="circle">Circle</option>
          @else
            @foreach ($sk_type_args as $typeSkillKey =>$typeSkillVal)
              <option value="{{$typeSkillKey}}" {{( $item->skill_type == $typeSkillKey ) ? 'selected':''}}>{{$typeSkillVal}}</option>
            @endforeach          
          @endempty
        </select>
      </div>
    @endsection
    @section('skills')
      @empty($skills)
        <div class="form-group form-row position-relative removeSkillsWrap">
          <div class="col-12 col-md-2">
            <select name="skills[0][icon]" id="skills_0" class="skills-icon form-control">
              <option value="fa-adobe">&#xf778; Adobe</option>
              <option value="fa-css3-alt">&#xf38b; CSS3</option>
              <option value="fa-html-5">&#xf13b; HTML</option>
              <option value="fa-js-square">&#xf3b9; Javascript</option>
              <option value="fa-laravel">&#xf3bd; laravel</option>
              <option value="fa-node-js">&#xf3d3; NodeJs</option>
              <option value="fa-php">&#xf457; PHP</option>
              <option value="fa-python">&#xf3e2; Python</option>
              <option value="fa-react">&#xf41b; React</option>
              <option value="fa-sass">&#xf41e; Sass</option>
              <option value="fa-wordpress">&#xf19a; Wordpress</option>
            </select>
          </div>
          <div class="col-12 col-md-8">
            <input type="text" name="skills[0][skill_name]" id="skills_name_0" class="form-control">
            <span id="skill_name_0_error"></span>
          </div>
          <div class="col-12 col-md-2">
            <input type="number" name="skills[0][percent]" id="skills_percent_0" class="form-control">
          </div>
          <div class="row-actions"><span class="text-danger skills_remove" id="skills_remove_0"><i class="far fa-trash-alt"></i></span></div>
        </div>
        <div id="addskillsItem" data-length="0"></div>
      @else
      <div id="addskillsItem" data-length="{{ $c }}">
        @foreach ($skills as $skillKey => $skillVal)
          <div class="form-group form-row position-relative removeSkillsWrap" data-count="item">            
            <div class="col-12 col-md-2">
              <select name="skills[{{ $skillsCount }}][icon]" id="skills_{{ $skillsCount }}" class="skills-icon form-control">
                @foreach ($skils_args as $skillsKey => $skillsVal)
                  <option value="{{ $skillsKey }}" {{ ( $skillVal['icon'] === $skillsKey ) ? 'selected': '' }}>{!!$skillsVal!!}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 col-md-8">
              <input type="text" name="skills[{{ $skillsCount }}][skill_name]" id="skills_name_{{ $skillsCount }}" class="form-control" value="{{$skillVal['skill_name']}}">
              <span id="skill_name_{{ $skillsCount }}_error"></span>
            </div>
            <div class="col-12 col-md-2">
              <input type="number" name="skills[{{ $skillsCount }}][percent]" id="skills_percent_{{ $skillsCount }}" class="form-control" value="{{$skillVal['percent']}}">
            </div>
            <div class="row-actions"><span class="text-danger skills_remove" id="skills_remove_{{ $skillsCount }}"><i class="far fa-trash-alt"></i></span></div>
          </div>
          @php
              $skillsCount++;
          @endphp
        @endforeach
      </div>
      @endempty
    @endsection

    @php /**Education*/  @endphp
    @section('education')
      @empty($schools)
        <div class="position-relative removeSchoolsWrap" id="schoolsWrap_0">
          <div class="form-group form-row">
            <label for="attainment_0" class="col-sm-3 col-form-label text-right">Education attainment type</label>
            <div class="col-sm-9"><select name="schools[0][attainment]" id="attainment_0" class="form-control">
              <option value="tertiary">Tertiary</option>
              <option value="secondary">Secondary</option>
            </select></div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="univ_0">Institute/School *</label>
            <div class="col-sm-9"><input type="text" name="schools[0][university]" id="univ_0" class="form-control" placeholder="Institute/University">
            <span id="university_0_error"></span></div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="qualify_0">Qualification *</label>
            <div class="col-sm-9"><select name="schools[0][qualify]" id="qualify_0" class="custom-select form-control">
              <option value="none">Select Qualification</option>
              <option value="vd_scc">Vocational Diploma / Short Course Certificate</option>
              <option value="bcdegree">Bachelors/College Degree</option>
              <option value="pgd_master">Post Graduate Diploma / Masters Degree</option>
              <option value="license">Professional License (Passed Board/Bar/Professional License Exam)</option>
              <option value="doctorate">Doctorate Degree</option>
            </select>
            <span id="qualify_0_error"></span></div>
          </div>
          <div id="cbs_0">
            <div class="form-row form-group">
              <label class="col-sm-3 col-form-label text-right" for="course_0">Course *</label>
              <div class="col-sm-9"><input type="text" name="schools[0][course]" id="course_0" class="form-control">
              <span id="course_0_error"></span></div>
            </div>
            <div class="form-row form-group">
              <label class="col-sm-3 col-form-label text-right" for="major_0">Major</label>
              <div class="col-sm-9"><input type="text" name="schools[0][major]" id="major_0" class="form-control"></div>
            </div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="date_0">Graduation Date *</label>
            <div id="graduate_0" class="input-group date col-sm-9">
              <input type="text" class="form-control" name="schools[0][date]" id="date_0">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
            </div>
            <span id="date_0_error"></span>
          </div>
          <div class="row-actions"><span class="text-danger schools_remove"><i class="far fa-trash-alt"></i></span></div>
        </div>
        <div id="addSchoolItem" data-length="0"></div>
      @else
        <div id="addSchoolItem" data-length="{{ $schl }}">
          @foreach ($schools as $schoolsKey => $schoolsVal)
          @php
            if($schoolsVal['attainment'] == 'tertiary'){
              $qualify_args = array('none'=>'Select Qualification','vd_scc'=>'Vocational Diploma / Short Course Certificate','bcdegree'=>'Bachelors/College Degree','pgd_master'=>'Post Graduate Diploma / Masters Degree','license'=>'Professional License (Passed Board/Bar/Professional License Exam)','doctorate'=>'Doctorate Degree');
            }else{
              $qualify_args = array( 'none'=>'Select Qualification','hsd'=>'High School Diploma' );
            }
          @endphp 
            <div class="position-relative removeSchoolsWrap" id="schoolsWrap_{{$schoolsCount}}">
              <div class="form-group form-row">
                <label for="attainment_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Education attainment type</label>
                <div class="col-sm-9"><select name="schools[{{$schoolsCount}}][attainment]" id="attainment_{{$schoolsCount}}" data-slctattn="" class="form-control">
                  @foreach ($attn_args as $attnArgsKey => $attnArgsVal)
                    <option value="{{ $attnArgsKey }}" {{ ( $attnArgsKey == $schoolsVal['attainment'] ) ? 'selected' : '' }}>{{ $attnArgsVal }}</option>  
                  @endforeach
                </select></div>
              </div>
              <div class="form-group form-row">
                <label for="univ_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Institute/School *</label>
                <div class="col-sm-9"><input type="text" name="schools[{{$schoolsCount}}][university]" id="univ_{{$schoolsCount}}" class="form-control" placeholder="Institute/University" value="{{ $schoolsVal['university'] }}">
                <span id="university_{{$schoolsCount}}_error"></span></div>
              </div>
              <div class="form-group form-row">
                <label for="qualify_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Qualification *</label>
                <div class="col-sm-9"><select name="schools[{{$schoolsCount}}][qualify]" id="qualify_{{$schoolsCount}}" class="custom-select form-control">
                  @foreach ($qualify_args as $qualifyArgsKey => $qualifyArgsVal)
                    <option value="{{ $qualifyArgsKey }}" {{ ( $qualifyArgsKey == $schoolsVal['qualify'] ) ? 'selected' : '' }}>{{ $qualifyArgsVal }}</option> 
                  @endforeach
                </select>
                <span id="qualify_{{$schoolsCount}}_error"></span></div>
              </div>
              @if ($schoolsVal['attainment'] == 'tertiary')                  
                <div id="cbs_{{$schoolsCount}}">
                  <div class="form-row form-group">
                    <label for="course_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Course *</label>
                    <div class="col-sm-9"><input type="text" name="schools[{{$schoolsCount}}][course]" id="course_{{$schoolsCount}}" class="form-control" value="{{$schoolsVal['course']}}">
                    <span id="course_{{$schoolsCount}}_error"></span></div>
                  </div>
                  <div class="form-row form-group">
                    <label for="major_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Major</label>
                    <div class="col-sm-9"><input type="text" name="schools[{{$schoolsCount}}][major]" id="major_{{$schoolsCount}}" class="form-control" value="{{$schoolsVal['major']}}"></div>
                  </div>
                </div>
              @endif
              <div class="form-group form-row">
                <label for="date_{{$schoolsCount}}" class="col-sm-3 col-form-label text-right">Graduation Date *</label>
                <div id="graduate_{{$schoolsCount}}" class="input-group date col-sm-9">
                  <input type="text" class="form-control" name="schools[{{$schoolsCount}}][date]" id="date_{{$schoolsCount}}" value="{{$schoolsVal['date']}}">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                </div>
                <span id="date_{{$schoolsCount}}_error"></span>
              </div>
              <div class="row-actions"><span class="text-danger schools_remove"><i class="far fa-trash-alt"></i></span></div>
            </div>
            @php $schoolsCount++; @endphp
          @endforeach
        </div>
      @endempty
    @endsection
    
    @php /**Work Exp*/  @endphp
    @section('work')
      @empty($work)
        <div class="position-relative removeWorkWrap" data-wId='0'>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="position_0">Position Title *</label>
            <div class="col-sm-9"><input type="text" name="work_exp[0][position]" id="position_0" class="form-control">
            <span id="position_0_error"></span></div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="company_name_0">Company Name *</label>
            <div class="col-sm-9"><input type="text" name="work_exp[0][company_name]" id="company_name_0" class="form-control">
            <span id="company_name_0_error"></span></div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="role_0">Role *</label>
            <div class="col-sm-9"><input type="text" name="work_exp[0][role]" id="role_0" class="form-control">
            <span id="role_0_error"></span></div>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="joined_0">Start Joined *</label>
            <div id="work_joined_0" class="input-group date col-sm-9">
              <input type="text" name="work_exp[0][start_joined]" id="joined_0" class="form-control">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
            </div>
            <span id="start_joined_0_error"></span>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="to_joined_0">To *</label>
            <div id="work_to_joined_0" class="input-group date col-sm-9">
              <input type="text" name="work_exp[0][to_joined]" id="to_joined_0" class="form-control">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
            </div>
            <span id="to_joined_0_error"></span>
          </div>
          <div class="form-group form-row">
            <label class="col-sm-3 col-form-label text-right" for="present_0">Present</label>
            <div class="col-sm-9"><select class="form-control" name="work_exp[0][present]" id="present_0">
              <option value="off">End</option>
              <option value="on">Present</option>
            </select></div>
          </div>
          <div class="row-actions"><span class="text-danger work_remove"><i class="far fa-trash-alt"></i></span></div>	
        </div>
        <div id="addWork" data-length="0"></div>
      @else
        <div id="addWork" data-length="{{$wrk}}">
          @foreach ($work as $workKey => $workVal)
            <div class="position-relative removeWorkWrap" data-wId='{{$workCount}}'>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="position_{{$workCount}}">Position Title *</label>
                <div class="col-sm-9"><input type="text" name="work_exp[{{$workCount}}][position]" id="position_{{$workCount}}" class="form-control" value="{{$workVal['position']}}">
                <span id="position_{{$workCount}}_error"></span></div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="company_name_{{$workCount}}">Company Name *</label>
                <div class="col-sm-9"><input type="text" name="work_exp[{{$workCount}}][company_name]" id="company_name_{{$workCount}}" class="form-control" value="{{$workVal['company_name']}}">
                <span id="company_name_{{$workCount}}_error"></span></div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="role_{{$workCount}}">Role *</label>
                <div class="col-sm-9"><input type="text" name="work_exp[{{$workCount}}][role]" id="role_{{$workCount}}" class="form-control" value="{{$workVal['role']}}">
                <span id="role_{{$workCount}}_error"></span></div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="joined_{{$workCount}}">Start Joined *</label>
                <div id="work_joined_{{$workCount}}" class="input-group date col-sm-9">
                  <input type="text" name="work_exp[{{$workCount}}][start_joined]" id="joined_{{$workCount}}" class="form-control" value="{{$workVal['start_joined']}}">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                </div>
                <span id="start_joined_{{$workCount}}_error"></span>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="to_joined_{{$workCount}}">To *</label>
                <div id="work_to_joined_{{$workCount}}" class="input-group date col-sm-9">
                  <input type="text" name="work_exp[{{$workCount}}][to_joined]" id="to_joined_{{$workCount}}" class="form-control" value="{{$workVal['to_joined']}}" {{( $workVal['present'] === 'on' ) ? 'disabled':''}}>
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                </div>
                <span id="to_joined_{{$workCount}}_error"></span>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-3 col-form-label text-right" for="present_{{$workCount}}">Present</label>
                <div class="col-sm-9"><select class="form-control" name="work_exp[{{$workCount}}][present]" id="present_{{$workCount}}">
                  @foreach ($present_args as $presntArgsKey => $presntArgsVal)
                    <option value="{{ $presntArgsKey }}" {{( $presntArgsKey === $workVal['present'] ) ? 'selected':''}}>{{ $presntArgsVal }}</option>
                  @endforeach
                </select></div>
              </div>
              <div class="row-actions"><span class="text-danger work_remove"><i class="far fa-trash-alt"></i></span></div>	
            </div>
            @php $workCount++; @endphp
          @endforeach
        </div>
      @endempty
    @endsection

    @php /**Work Exp*/  @endphp
    @section('social_media')
      @empty($sm)
        <div class="form-group form-row position-relative removeSmediaWrap" data-smId="0">
          <div class="col-12 col-md-4">
            <select name="sm[0][icon]" id="sm_icon_0" class="social-icon-select form-control">
              <option value="fb">&#xF39E; Facebook</option> 
              <option value="tw">&#xF099; Twitter</option>
              <option value="ig">&#xF16D; Instagram</option>
              <option value="g-plus">&#xF0D5; Google Plus</option>
              <option value="in">&#xF0E1; Linkedin</option>
              <option value="pin">&#xF231; Pinterest</option>
              <option value="git">&#xF113; Github</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <input type="url" name="sm[0][link]" id="sm_link_0" class="form-control" placeholder="http://www.example.com">
            <span id="link_0_error"></span>
          </div>
          <div class="col-12 col-md-4">
            <select name="sm[0][tab]" id="sm_tab_0" class="form-control">
              <option value="_blank">Open new tab</option>
              <option value="_self">Self</option>
            </select>
          </div>
          <div class="row-actions"><span class="text-danger" id="sm_remove_0"><i class="far fa-trash-alt"></i></span></div>	
        </div>
        <div id="addSmedia" data-length="0">></div>
      @else
        <div id="addSmedia" data-length="{{ $s }}">
          @foreach ($sm as $smKeys => $smVal)              
            <div class="form-group form-row position-relative removeSmediaWrap" data-smId="{{$smCount}}">
              <div class="col-12 col-md-4">
                <select name="sm[{{$smCount}}][icon]" id="sm_icon_{{$smCount}}" class="social-icon-select form-control">
                  @foreach ($sm_icons_args as $iconKeyArgs => $iconValArgs)
                    <option value="{{$iconKeyArgs}}" {{($smVal['icon'] == $iconKeyArgs)? 'selected':''}}>{!! $iconValArgs !!}</option> 
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-4">
                <input type="url" name="sm[{{$smCount}}][link]" id="sm_link_{{$smCount}}" class="form-control" placeholder="http://www.example.com" value="{{ $smVal['link'] }}">
                <span id="link_{{$smCount}}_error"></span>
              </div>
              <div class="col-12 col-md-4">
                <select name="sm[{{$smCount}}][tab]" id="sm_tab_{{$smCount}}" class="form-control">
                  @foreach ($sm_tab_args as $tabKeys => $tabVal)
                    <option value="{{$tabKeys}}" {{($smVal['tab'] == $tabKeys)? 'selected':''}}>{{$tabVal}}</option>
                  @endforeach
                </select>
              </div>
              <div class="row-actions"><span class="text-danger" id="sm_remove_{{$smCount}}"><i class="far fa-trash-alt"></i></span></div>	
            </div>
            @php
                $smCount++;
            @endphp
          @endforeach
        </div>
      @endempty
    @endsection
  @endforeach
@endif