@extends('layouts.app')
@section('style')
	<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">
@endsection

@section('active_user','active')

{{-- @section('page-heading')
	<i class="fas fa-user"></i> Profile
@endsection --}}
@section('content')
	<div class="container container-lg-w12">
		<div class="card mb-5">
			<div class="card-body jsc-profBg">
				<div class="jsc-profImageWrap position-relative jsc-z-5">
					<img src="{{ asset('/images/default.png') }}" alt="personal" class="img-cirle">
				</div>
				<div class="jsc-profContentWrap position-relative jsc-z-5">
					<p class="jsc-profName">
						@if ( Route::currentRouteName() == 'user.profile-show' )
							Jemson A. Sayre
						@else
							@yield('fullname')
						@endif
					</p>
					<p class="jsc-profPosition">Web Developer</p>
				</div>
			</div>
		</div>
		<div class="jsc-infoWrap">
			<div class="row">
				<div class="col-12 col-md-2">
					<div class="nav position-relative flex-md-column nav-pills text-right" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link text-secondary active" id="v-pills-basic-info-tab" data-toggle="pill" href="#v-pills-basic-info" role="tab" aria-controls="v-pills-basic-info" aria-selected="true">Basic Info</a>
						<a class="nav-link text-secondary" id="v-pills-skills-tab" data-toggle="pill" href="#v-pills-skills" role="tab" aria-controls="v-pills-skills" aria-selected="false">Skills</a>
						<a class="nav-link text-secondary" id="v-pills-schools-tab" data-toggle="pill" href="#v-pills-schools" role="tab" aria-controls="v-pills-schools" aria-selected="false">School Attended</a>
						<a class="nav-link text-secondary" id="v-pills-exp-tab" data-toggle="pill" href="#v-pills-exp" role="tab" aria-controls="v-pills-exp" aria-selected="false">Working Experience</a>
						<a class="nav-link text-secondary" id="v-pills-social-media-tab" data-toggle="pill" href="#v-pills-social-media" role="tab" aria-controls="v-pills-social-media" aria-selected="false">Social Media</a>
					</div>
				</div>
				<div class="col-12 col-md-10">
					<form id="profile_update" data-action="{{ route('user.update', ['id'=> $user_id]) }}" method="post">
						@method('PUT')
						@csrf
						<div class="tab-content mb-3" id="v-pills-tabContent">
							<div class="tab-pane fade active show card" id="v-pills-basic-info" role="tabpanel" aria-labelledby="v-pills-basic-info-tab">
								<div class="card-body">
									<div class="mb-3">
										<h1 class="page-heading">Basic Info</h1>
									</div>
									@if ( Route::currentRouteName() == 'user.profile-show' )
										@if ( count($prof) > 0)
											@foreach ($prof as $item)
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Email : </label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $email->email}}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">First Name : </label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->first_name}}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Middle Name : </label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{$item->middle_name}}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Last Name : </label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->last_name}}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Bio: @if ( Route::currentRouteName() == 'user.edit' ) <span class="has-error">*</span> @endif</label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->bio }}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Where do you live? @if ( Route::currentRouteName() == 'user.edit' ) <span class="has-error">*</span> @endif</label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->address }}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">How old are you? @if ( Route::currentRouteName() == 'user.edit' ) <span class="has-error">*</span> @endif</label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->age }}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">Gender: @if ( Route::currentRouteName() == 'user.edit' ) <span class="has-error">*</span> @endif</label>
													<div class="col-sm-6"><p class="form-control-plaintext">{{ $item->gender }}</p></div>
												</div>
												<div class="form-group form-row">
													<label class="col-sm-6 col-form-label text-right">What is your contact number: @if ( Route::currentRouteName() == 'user.edit' ) <span class="has-error">*</span> @endif</label>
													<div class="col-sm-6"><p class="form-control-plaintext">0{{ $item->contact_number }}</p></div>
												</div>
											@endforeach
										@else
											Wala pong laman									
										@endif
									@else
										<div class="form-group form-row">
											<label for="email" class="col-sm-3 col-form-label text-right">Email <span class="has-error">*</span></label>
											<div class="col-sm-9">
												<input type="text" name="email" id="email" class="form-control" value="@yield('email')">
												<span class='email-error has-error'></span>
											</div>
										</div>											
										<div class="form-group form-row">
											<label for="first_name" class="col-sm-3 col-form-label text-right">First Name <span class="has-error">*</span></label>
											<div class="col-sm-9">
												<input type="text" name="first_name" id="first_name" class="form-control" value="@yield('fname')">
												<span class='first_name-error has-error'></span>
											</div>
										</div>											
										<div class="form-group form-row">
											<label for="middle_name" class="col-sm-3 col-form-label text-right">Middle Name</label>
											<div class="col-sm-9">
												<input type="text" name="middle_name" id="middle_name" class="form-control" value="@yield('mname')">
											</div>
										</div>
										<div class="form-group form-row">
											<label for="last_name" class="col-sm-3 col-form-label text-right">Last Name <span class="has-error">*</span></label>
											<div class="col-sm-9">
												<input type="text" name="last_name" id="last_name" class="form-control" value="@yield('lname')">
												<span class='last_name-error has-error'></span>
											</div>
										</div>
										<div class="form-group form-row">
											<label for="address" class="col-sm-3 col-form-label text-right">Bio: <span class="has-error">*</span></label>
											<div class="col-sm-9"><textarea name="bio" id="bio" cols="10" rows="5" class="form-control">@yield('bio')</textarea>
												<span class='bio-error has-error'></span></div>
										</div>
										<div class="form-group form-row">
											<label for="address" class="col-sm-3 col-form-label text-right">Where do you live? <span class="has-error">*</span></label>
											<div class="col-sm-9"><input type="text" name="address" id="address" class="form-control" value="@yield('address')">
											<span class='address-error has-error'></span></div>
										</div>
										<div class="form-group form-row">
											<label for="age" class="col-sm-3 col-form-label text-right">How old are you? <span class="has-error">*</span></label>
											<div class="col-sm-9"><input type="text" name="age" id="age" class="form-control" value="@yield('age')">
											<span class='age-error has-error'></span></div>
										</div>
										<div class="form-group form-row">
											<label for="gender" class="col-sm-3 col-form-label text-right">Gender</label>
											<div class="col-sm-9">
													<select name="gender" id="gender" class="form-control">
													@yield('gender')
												</select>
												<span class='gender-error has-error'></span>
											</div>
										</div>
										<div class="form-group form-row">
											<label for="contact" class="col-sm-3 col-form-label text-right">What is your contact number? <span class="has-error">*</span></label>
											<div class="col-sm-9">
												<input type="text" name="contactMask" id="contactMask" class="form-control" placeholder="9xx-xxx-xxxx" value="@yield('contact')">
												<input type="text" name="contact" id="contact" class="d-none" placeholder="9xx-xxx-xxxx" value="@yield('contact')">
												<span class='contactMask-error has-error'></span>
											</div>
										</div>
									@endif
								</div>
							</div>
							<div class="tab-pane fade card" id="v-pills-skills" role="tabpanel" aria-labelledby="v-pills-skills-tab">
								<div class="card-body">
									<div class="mb-3 d-flex justify-content-between">
										<h1 class="page-heading">Skills</h1>
										@if ( Route::currentRouteName() == 'user.edit' )
											<button type="button" id="addSkills" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Skills</button>
										@endif
									</div>
									@yield('skill_type')
									@if ( Route::currentRouteName() == 'user.edit' )
										<div id="skillsLblWrap">
											<div class="form-row">
												<p class="col-12 col-md-2">Choose icon</p>
												<p class="col-12 col-md-8">What is your skills?</p>
												<p class="col-12 col-md-2">How many %?</p>
											</div>
										</div>
									@endif
									<div id="skillsContentWrap">
										@if ( Route::currentRouteName() == 'user.profile-show' )
											<div id="addskillsItem">
												@if ( count($prof) > 0)
													@foreach ($prof as $item)
													<div class="form-group form-row position-relative removeSkillsWrap">
														@foreach (unserialize($item->skills_meta) as $skillKey => $skillVal)
															<div class="col-6 col-md-3 mb-3">
																@if ( $item->skill_type == 'circle' )
																	<div class="position-relative progressbar circle over-50 p77 prog-{!!$skillVal['icon']!!}">
																		<i class="fab {!!$skillVal['icon']!!} fa-3x"></i>
																		<span>{{$skillVal['percent']}}%</span>
																		<div class="half-clipper">
																			<div class="bar"></div>
																			<div class="bar-value"></div>
																		</div>
																	</div>
																	{{--  {{$skillVal['skill_name']}}  --}}																		
																@endif
															</div>
															@endforeach
														</div>
													@endforeach
												@else
													Wala pong laman
												@endif
											</div>
										@else
											@yield('skills')
										@endif
									</div>
								</div>
							</div>
							<div class="tab-pane fade card" id="v-pills-schools" role="tabpanel" aria-labelledby="v-pills-schools-tab">
								<div class="card-body">
									<div class="mb-3 d-flex justify-content-between">
										<h1 class="page-heading">Schools</h1>
										@if ( Route::currentRouteName() == 'user.edit' )
											<button type="button" id="addSchools" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Schools</button>												
										@endif
									</div>
										@if ( Route::currentRouteName() == 'user.profile-show' )
											@if ( count($prof) > 0)
												@foreach ($prof as $item)
												@php $schoolsCount = 0; $qualify_args = array('none'=>'Select Qualification','vd_scc'=>'Vocational Diploma / Short Course Certificate','bcdegree'=>'Bachelors/College Degree','pgd_master'=>'Post Graduate Diploma / Masters Degree','license'=>'Professional License (Passed Board/Bar/Professional License Exam)','doctorate'=>'Doctorate Degree');  @endphp
													@foreach (unserialize($item->schools_meta) as $schoolsKey => $schoolsVal)
														<div class="position-relative removeSchoolsWrap" id="schoolsWrap_{{$schoolsCount}}">
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Education attainment type: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ ucfirst($schoolsVal['attainment']) }}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Institute/School: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{$schoolsVal['university']}}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Qualification: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">
																	@foreach ($qualify_args as $qArgsKey => $qArgsVal)
																			@if ( $qArgsKey == $schoolsVal['qualify'])
																					{{$qArgsVal}}
																			@endif
																	@endforeach
																</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Course: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{$schoolsVal['course']}}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Major: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ ( ! empty($schoolsVal['major']) ) ? $schoolsVal['major'] : 'N/A' }}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Graduation Date: </label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{$schoolsVal['date']}}</p></div>
															</div>
														</div>
													@php $schoolsCount++; @endphp
													@endforeach
												@endforeach
											@else
												Wala pong laman
											@endif
										@else
												@yield('education')
										@endif
								</div>
							</div>
							<div class="tab-pane fade card" id="v-pills-exp" role="tabpanel" aria-labelledby="v-pills-exp-tab">
								<div class="card-body">
									<div class="mb-3 d-flex justify-content-between">
										<h1 class="page-heading">Working Experience</h1>
										@if ( Route::currentRouteName() == 'user.edit' )
											<button type="button" id="addWorkExp" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Working Experience</button>
										@endif
									</div>
									@if ( Route::currentRouteName() == 'user.profile-show' )
										@if ( count($prof) > 0)
											@foreach ($prof as $item)
												<div id="addWork">
													@foreach (unserialize($item->work_exp_meta) as $workKey => $workVal)
														<div class="position-relative removeWorkWrap">
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Position Title :</label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ $workVal['position'] }}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Company Name :</label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ $workVal['company_name'] }}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Role :</label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ $workVal['role'] }}</p></div>
															</div>
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Start Joined :</label>
																<div class="col-sm-6"><p class="form-control-plaintext">{{ $workVal['start_joined'] }}</p></div>
															</div>
															@if ($workVal['present'] == 'off')
																<div class="form-group form-row">
																	<label class="col-sm-6 col-form-label text-right">To :</label>
																	<div class="col-sm-6"><p class="form-control-plaintext">{{ $workVal['to_joined']}} </p></div>
																</div>
															@endif
															<div class="form-group form-row">
																<label class="col-sm-6 col-form-label text-right">Present :</label>
																<div class="col-sm-6"><p class="form-control-plaintext {{ ( $workVal['present'] == 'on' ) ? 'text-success' : 'text-danger' }}">{{ ( $workVal['present'] == 'on' ) ? 'Active' : 'End' }} </p></div>
															</div>
														</div>
													@endforeach
												</div>
											@endforeach
										@else
											Wala pong laman
										@endif
									@else
										@yield('work')
									@endif
								</div>
							</div>
							<div class="tab-pane fade card" id="v-pills-social-media" role="tabpanel" aria-labelledby="v-pills-social-media-tab">
								<div class="card-body">
									<div class="mb-3 d-flex justify-content-between">
										<h1 class="page-heading">Social Media</h1>
										@if ( Route::currentRouteName() == 'user.edit' )
											<button type="button" id="addSocialMedia" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Social Media</button>
										@endif
									</div>
									@if ( Route::currentRouteName() == 'user.edit' )
										<div class="form-row form-group">
											<p class="col-12 col-md-4 mb-0">Icon</p>
											<p class="col-12 col-md-4 mb-0">Link</p>
											<p class="col-12 col-md-4 mb-0">Tab</p>
										</div>
									@endif
									@if ( Route::currentRouteName() == 'user.profile-show' )
										@if ( count($prof) > 0)
											@foreach ($prof as $item)
												<div id="addSmedia" class="icon-wrap">
													@foreach (unserialize($item->social_meta) as $smKeys => $smVal)
														@switch($smVal['icon'])
																@case('facebook-f') @php $socialBrandHex = 'fb-icon' @endphp @break;
																@case('twitter') @php $socialBrandHex = 'twitter-icon' @endphp @break;
																@case('instagram') @php $socialBrandHex = 'instagram-icon' @endphp @break;
																@case('google-plus-g') @php $socialBrandHex = 'g-plus-icon' @endphp @break;
																@case('linkedin-in') @php $socialBrandHex = 'linkedin-icon' @endphp @break;
																@case('pinterest-p') @php $socialBrandHex = 'pinterest-icon' @endphp @break;
																@case('github-alt') @php $socialBrandHex = 'git-icon' @endphp @break;
																@default
														@endswitch
														<a href="{{$smVal['link']}}" class="icon {{ $socialBrandHex }}" target="{{$smVal['tab']}}">
															<i class="fab fa-{{$smVal['icon']}}"></i>
														</a>
													@endforeach
												</div>
											@endforeach
										@else
											Wala pong laman
										@endif
									@else
										@yield('social_media')
									@endif
								</div>
							</div>
						</div>
						<div class="text-center mb-3">
							@if ( Route::currentRouteName() == 'user.profile-show' )
								<a href="{{ route('user.edit') }}" class="btn btn-warning">Edit</a>
							@else
								<button type="submit" class="btn btn-success">Update</button>
							@endif
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
	<script type="application/javascript">
		jQuery(document).ready(function($){
			/**-skills*/
			let countSkills = $('#addskillsItem').data('length');
			let skillsCounter;
			if(countSkills > 1){
				skillsCounter = countSkills;
			}else{
				skillsCounter = 1;
			}
			$('#addSkills').on('click', function(){
				let skills_output = '<div class="form-group form-row position-relative removeSkillsWrap">';
					skills_output += '<div class="col-12 col-md-2">';
						skills_output += '<select name="skills[' + skillsCounter + '][icon]" id="skills_icon_' + skillsCounter + '" class="skills-icon form-control">';
							skills_output += '<option value="fa-adobe">&#xf778; Adobe</option>';
							skills_output += '<option value="fa-css3-alt">&#xf38b; CSS3</option>';
							skills_output += '<option value="fa-html-5">&#xf13b; HTML</option>';
							skills_output += '<option value="fa-js-square">&#xf3b9; Javascript</option>';
							skills_output += '<option value="fa-laravel">&#xf3bd; laravel</option>';
							skills_output += '<option value="fa-node-js">&#xf3d3; NodeJs</option>';
							skills_output += '<option value="fa-php">&#xf457; PHP</option>';
							skills_output += '<option value="fa-python">&#xf3e2; Python</option>';
							skills_output += '<option value="fa-react">&#xf41b; React</option>';
							skills_output += '<option value="fa-sass">&#xf41e; Sass</option>';
							skills_output += '<option value="fa-wordpress">&#xf19a; Wordpress</option>';
						skills_output += '</select>';
					skills_output += '</div>';
					skills_output += '<div class="col-12 col-md-8">';
						skills_output += '<input type="text" name="skills[' + skillsCounter + '][skill_name]" id="skills_name_' + skillsCounter + '" class="form-control">';
						skills_output += '<span id="skill_name_' + skillsCounter + '_error"></span>';
					skills_output += '</div>';
					skills_output += '<div class="col-12 col-md-2">';
						skills_output += '<input type="number" name="skills[' + skillsCounter + '][percent]" id="skills_percent_' + skillsCounter + '" class="form-control">';
					skills_output += '</div>';
					skills_output += '<div class="row-actions"><span class="text-danger skills_remove" id="skills_remove_' + skillsCounter + '"><i class="far fa-trash-alt"></i></span></div>';
				skills_output += '</div>';
				
				$('#addskillsItem').append(skills_output);
				skillsCounter++;
			});
			
			/*removeSkills*/
			$( document ).on('click', '.skills_remove', function(){
				$(this).parent().parent().remove();
			});

			/**-schools*/
			let counschools = $('#addSchoolItem').data('length');
			let schoolCounter;
			if(counschools > 1){
				schoolCounter = counschools;
			}else{
				schoolCounter = 1;
			}
			profileDate('#graduate');
			selectAttainment(0);
			$('#addSchools').on('click', function(){
				let school_output = '<div class="position-relative removeSchoolsWrap" id="schoolsWrap_' + schoolCounter + '">';
					school_output += '<div class="form-group form-row">';
            school_output += '<label for="attainment_' + schoolCounter + '" class="col-sm-3 col-form-label text-right">Education attainment type</label>';
            school_output += '<div class="col-sm-9"><select name="schools[' + schoolCounter + '][attainment]" id="attainment_' + schoolCounter + '" class="form-control">';
              school_output += '<option value="tertiary">Tertiary</option>';
              school_output += '<option value="secondary">Secondary</option>';
            school_output += '</select></div>';
          school_output += '</div>';
          school_output += '<div class="form-group form-row">';
           school_output += ' <label class="col-sm-3 col-form-label text-right" for="univ_' + schoolCounter + '">Institute/School *</label>';
            school_output += '<div class="col-sm-9"><input type="text" name="schools[' + schoolCounter + '][university]" id="univ_' + schoolCounter + '" class="form-control" placeholder="Institute/University">';
            school_output += '<span id="university_' + schoolCounter + '_error"></span></div>';
          school_output += '</div>';
          school_output += '<div class="form-group form-row">';
            school_output += '<label class="col-sm-3 col-form-label text-right" for="qualify_' + schoolCounter + '">Qualification *</label>';
            school_output += '<div class="col-sm-9"><select name="schools[' + schoolCounter + '][qualify]" id="qualify_' + schoolCounter + '" class="custom-select form-control">';
              school_output += '<option value="none">Select Qualification</option>';
              school_output += '<option value="vd_scc">Vocational Diploma / Short Course Certificate</option>';
              school_output += '<option value="bcdegree">Bachelors/College Degree</option>';
              school_output += '<option value="pgd_master">Post Graduate Diploma / Masters Degree</option>';
              school_output += '<option value="license">Professional License (Passed Board/Bar/Professional License Exam)</option>';
              school_output += '<option value="doctorate">Doctorate Degree</option>';
            school_output += '</select>';
            school_output += '<span id="qualify_' + schoolCounter + '_error"></span></div>';
          school_output += '</div>';
					school_output += '<div id="cbs_' + schoolCounter + '">';
						school_output += '<div class="form-row form-group">';
							school_output += '<label class="col-sm-3 col-form-label text-right" for="course_' + schoolCounter + '">Course *</label>';
							school_output += '<div class="col-sm-9"><input type="text" name="schools[' + schoolCounter + '][course]" id="course_' + schoolCounter + '" class="form-control">';
							school_output += '<span id="course_0_error"></span></div>';
						school_output += '</div>';
						school_output += '<div class="form-row form-group">';
							school_output += '<label class="col-sm-3 col-form-label text-right" for="major_' + schoolCounter + '">Major</label>';
							school_output += '<div class="col-sm-9"><input type="text" name="schools[' + schoolCounter + '][major]" id="major_' + schoolCounter + '" class="form-control"></div>';
						school_output += '</div>';
					school_output += '</div>';
          school_output += '<div class="form-group form-row">';
            school_output += '<label class="col-sm-3 col-form-label text-right" for="date_' + schoolCounter + '">Graduation Date *</label>';
            school_output += '<div id="graduate_' + schoolCounter + '" class="input-group date col-sm-9">';
              school_output += '<input type="text" class="form-control" name="schools[' + schoolCounter + '][date]" id="date_' + schoolCounter + '">';
              school_output += '<div class="input-group-append">';
                school_output += '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>';
              school_output += '</div>';
            school_output += '</div>';
            school_output += '<span id="date_' + schoolCounter + '_error"></span>';
					school_output += '</div>';
					school_output += '<div class="row-actions"><span class="text-danger schools_remove"><i class="far fa-trash-alt"></i></span></div>';
				school_output += '</div>';

				$('#addSchoolItem').append(school_output);

				{{--  schoolDate();  --}}
				if( schoolCounter >= 1 ){ selectAttainment(schoolCounter); }
				profileDate('#graduate',schoolCounter);
				schoolCounter++;
			});

			/**Remove Schools*/
			$( document ).on('click', '.schools_remove', function(){
				$(this).parent().parent().remove();
			});

			/**-work*/
			let countwork = $('#addWork').data('length');
			let workCounter;
			if(countwork > 1){
				workCounter = countwork;
			}else{
				workCounter = 1;
			}
			profileDate('#work_joined');
			profileDate('#work_to_joined');
			workPresent(0); /*dispable-to-joined*/

			$('#addWorkExp').on('click', function(){
				let work_output = '<div class="position-relative removeWorkWrap" data-wId="' + workCounter + '">';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="position_' + workCounter + '">Position Title *</label>';
            work_output += '<div class="col-sm-9"><input type="text" name="work_exp[' + workCounter + '][position]" id="position_' + workCounter + '" class="form-control">';
            work_output += '<span id="position_' + workCounter + '_error"></span></div>';
					work_output += '</div>';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="company_name_' + workCounter + '">Company Name *</label>';
            work_output += '<div class="col-sm-9"><input type="text" name="work_exp[' + workCounter + '][company_name]" id="company_name_' + workCounter + '" class="form-control">';
            work_output += '<span id="company_name_' + workCounter + '_error"></span></div>';
					work_output += '</div>';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="role_' + workCounter + '">Role *</label>';
            work_output += '<div class="col-sm-9"><input type="text" name="work_exp[' + workCounter + '][role]" id="role_' + workCounter + '" class="form-control">';
            work_output += '<span id="role_' + workCounter + '_error"></span></div>';
					work_output += '</div>';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="joined_' + workCounter + '">Start Joined *</label>';
            work_output += '<div id="work_joined_' + workCounter + '" class="input-group date col-sm-9">';
              work_output += '<input type="text" name="work_exp[' + workCounter + '][start_joined]" id="joined_' + workCounter + '" class="form-control">';
              work_output += '<div class="input-group-append">';
                work_output += '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>';
              work_output += '</div>';
            work_output += '</div>';
            work_output += '<span id="start_joined_' + workCounter + '_error"></span>';
          work_output += '</div>';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="to_joined_' + workCounter + '">To *</label>';
            work_output += '<div id="work_to_joined_' + workCounter + '" class="input-group date col-sm-9">';
              work_output += '<input type="text" name="work_exp[' + workCounter + '][to_joined]" id="to_joined_' + workCounter + '" class="form-control">';
              work_output += '<div class="input-group-append">';
                work_output += '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>';
              work_output += '</div>';
            work_output += '</div>';
            work_output += '<span id="to_joined_' + workCounter + '_error"></span>';
          work_output += '</div>';
          work_output += '<div class="form-group form-row">';
            work_output += '<label class="col-sm-3 col-form-label text-right" for="present_' + workCounter + '">Present</label>';
            work_output += '<div class="col-sm-9"><select class="form-control" name="work_exp[' + workCounter + '][present]" id="present_' + workCounter + '">';
              work_output += '<option value="off">End</option>';
              work_output += '<option value="on">Present</option>';
            work_output += '</select></div>';
          work_output += '</div>';
					work_output += '<div class="row-actions"><span class="text-danger work_remove"><i class="far fa-trash-alt"></i></span></div>	';
				work_output += '</div>';

				$('#addWork').append(work_output);
				
				profileDate('#work_joined',workCounter);
				profileDate('#work_to_joined',workCounter);
				workPresent(workCounter); /*dispable-to-joined*/

				workCounter++;
			});
			/**Remove work*/
			$( document ).on('click', '.work_remove', function(){
				$(this).parent().parent().remove();
			});

			/**-social media*/
			let countSM = $('#addSmedia').data('length');
			let smCounter = 1;
			if(countSM > 1){
				smCounter = countSM;
			}else{
				smCounter = 1;
			}
			$('#addSocialMedia').on('click', function(){
				let sm_output = '<div class="form-group form-row position-relative removeSmediaWrap" data-smId="' + smCounter + '">';
					sm_output += '<div class="col-12 col-md-4">';
						sm_output += '<select name="sm[' + smCounter + '][icon]" id="sm_icon_' + smCounter + '" class="social-icon-select form-control">';
							sm_output += '<option value="fb">&#xF39E; Facebook</option>';
							sm_output += '<option value="tw">&#xF099; Twitter</option>';
							sm_output += '<option value="ig">&#xF16D; Instagram</option>';
							sm_output += '<option value="g-plus">&#xF0D5; Google Plus</option>';
							sm_output += '<option value="in">&#xF0E1; Linkedin</option>';
							sm_output += '<option value="pin">&#xF231; Pinterest</option>';
							sm_output += '<option value="git">&#xF113; Github</option>';
						sm_output += '</select>';
					sm_output += '</div>';
					sm_output += '<div class="col-12 col-md-4">';
						sm_output += '<input type="url" name="sm[' + smCounter + '][link]" id="sm_link_' + smCounter + '" class="form-control" placeholder="http://www.example.com">';
						sm_output += '<span id="link_' + smCounter + '_error"></span>';
					sm_output += '</div>';
					sm_output += '<div class="col-12 col-md-4">';
						sm_output += '<select name="sm[' + smCounter + '][tab]" id="sm_tab_' + smCounter + '" class="form-control">';
							sm_output += '<option value="_blank">Open new tab</option>';
							sm_output += '<option value="_self">Self</option>';
						sm_output += '</select>';
					sm_output += '</div>';
					sm_output += '<div class="row-actions"><span class="text-danger" id="sm_remove_' + smCounter + '"><i class="far fa-trash-alt"></i></span></div>';
				sm_output += '</div>';
				
				$('#addSmedia').append(sm_output);
				smCounter++;
			});

			/**-input masking*/
			$('input[name="contactMask"]').on('keyup change', function(){
				$("input[name='contact']").val(destroyMask(this.value));
				this.value = createMask($("input[name='contact']").val());
			});
			
		});

		/**School-attainment*/
		const selectAttainment = (num) => {
			jQuery('#attainment_' + num).on('change', function(){
				let opt = $(this).val();
				if( opt == 'secondary' ){
					$('#cbs_' + num).addClass('d-none');
					$('#qualify_' + num).children().remove();
					$('#qualify_' + num).append(
						$('<option>',{value: 'none', text: 'Select Qualification'},'</option>'),
						$('<option>',{value: 'hsd', text: 'High School Diploma'},'</option>')
					);
				}else{
					$('#cbs_' + num).removeClass('d-none');
					$('#qualify_' + num + ' option[value="hsd"]').remove();
					$('#qualify_' + num).append(
						$('<option>',{value: 'vd_scc', text: 'Vocational Diploma / Short Course Certificate'},'</option>'),
						$('<option>',{value: 'bcdegree', text: 'Bachelors/College Degree'},'</option>'),
						$('<option>',{value: 'pgd_master', text: 'Post Graduate Diploma / Masters Degree'},'</option>'),
						$('<option>',{value: 'license', text: 'Professional License (Passed Board/Bar/Professional License Exam)'},'</option>'),
						$('<option>',{value: 'doctorate', text: 'Doctorate Degree'},'</option>')
					);
				}
			});

			$('#profile_update').on('submit', function(e){
				e.preventDefault();
				var url = $(this).data('action');
				var data = $(this).serialize();

				{{-- console.log(data); --}}

				$.ajax({
					type: 'Post',
					url: url,
					data: data,
					dataType: 'json',
					beforeSend: function(){
						//Show loader
						$('body').addClass('overflow-y-none');
						$('.loader-wrap').show();
					},
					complete: function(){
						//Hide loader
						$('body').removeClass('overflow-y-none');
						$('.loader-wrap').hide();
						console.log('console log complete');
					},
					success: function(res){
						//do something here
						$('label').css({'color':'#212529'});
						$('input, textarea').css({'border-color': '#ced4da'});
						$('.has-error').addClass('d-none');

						if( ! $.isEmptyObject(res.errors) ){
							errorMessages(res.errors);	
						}

					},
					{{--  error: function(err){
						//display error 2-00297040-7
						console.log('console log error');
						var errs = err.responseJSON.errors;
						  if( err.status == 422 ){
							$.each(errs, function(key, value){
								let args = key.split('.'); //split the arr
								let keys = args.reduce((a, s, i) => i === 0 ? s : a + `[${s}]`); //replace . to []
								let key1 = args[0];
								let key2 = args[2] + '_' + args[1];
								
								$('label[for="' + keys + '"]').css({'color':'#e86060'});

								$('input[name="' + keys  + '"], input[name="contactMask"], textarea[name="' + keys  + '"], select[name="' + keys  + '"]').css({'border-color': '#e86060'});

								if( key1 == 'contact'){ key1 = 'contactMask'; }

								$('.' + key1 + '-error').html(value);
								$('#' + key2 + '_error' ).addClass('has-error').html(value);
							});
						}else{
							console.log('Check your form.');
						}
					},  --}}
				});
			});
		}

		/**error-messages*/
		const errorMessages = (error) =>{
			$.each( error, function(key, value){
				console.log('Key: ' + key + ' | Value: ' + value);
				let args = key.split('.'); //split the arr
				let keys = args.reduce((a, s, i) => i === 0 ? s : a + `[${s}]`); //replace . to []
				let key1 = args[0];
				let key2 = args[2] + '_' + args[1];
				
				$('label[for="' + keys + '"]').css({'color':'#e86060'});

				$('input[name="' + keys  + '"], input[name="contactMask"], textarea[name="' + keys  + '"], select[name="' + keys  + '"]').css({'border-color': '#e86060'});

				if( key1 == 'contact'){ key1 = 'contactMask'; }

				$('.' + key1 + '-error').html(value);
				$('#' + key2 + '_error' ).addClass('has-error').html(value);
			});
		}

		/**checbox-present*/
		const workPresent = (num) => {
			$('select#present_' + num ).on('change', function(){
				if($(this).val() == 'on'){
					$('#to_joined_' + num).prop('disabled', true);
					{{-- $(this).attr('checked', true); --}}
				}else{
					$('#to_joined_' + num).prop('disabled', false);
					$(this).attr('checked', false);
				}
			});
		}

		/**Date-picker*/
		const profileDate = (id = null,num = 0) => {
			jQuery( id + '_' + num + '.input-group.date').datepicker({
				format: "M dd,yyyy",
				todayBtn: "linked",
				clearBtn: true,
				todayHighlight: true
			});
		}

		/**Remove Skills*/
		/*const removeSkills = (num) => {
			$( document ).on('click', '#skills_remove_' + num , function(){
			$( '#skills_remove_' + num ).on('click', function(){
				$(this).parent().parent().remove();
				console.log(num);
			});
		}*/

		/**Remove Schools*/
		/*const removeSchools = (num) => {
			$( '#schools_remove_' + num ).on('click', function(){
				$(this).parent().parent().remove();
			});
		}*/

		/**Remove work*/
		const removeWork = (num) => {
			$( '#work_remove_' + num ).on('click', function(){
				$(this).parent().parent().remove();
			});
		}

		/**Remove work*/
		const removeSmedia = (num) => {
			$( '#sm_remove_' + num ).on('click', function(){
				$(this).parent().parent().remove();
			});
		}

		/**Create mask*/
		const createMask = (string) => {
			console.log(string);
			return string.replace(/(\d{3})(\d{3})(\d{4})/,"$1-$2-$3");
		}

		/**Destroy mask*/
		const destroyMask = (string) => {
			console.log(string);
			return string.replace(/\D/g,'').substring(0,10);
		}
	</script>
@endsection
