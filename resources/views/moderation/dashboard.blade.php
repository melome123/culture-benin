@extends('admin.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-6">
            <div class="card bg-primary border-0 rounded-3 welcome-box style-two mb-4 position-relative">
                <div class="card-body py-38 px-4">
                        <div class="mb-5">
                            <h3 class="text-white fw-semibold">Welcome Back, <span class="text-danger-div">{{ Auth::user()->nom }}</span></h3>
                                <p class="text-light">Your progress this week is Awesome.</p>
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-4 gap-xxl-5">
                            <div class="d-flex align-items-center welcome-status-item style-two">
                                <div class="flex-shrink-0">
                                    <i class="material-symbols-outlined">airplay</i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="text-white fw-semibold mb-0 fs-16">75h</h5>
                                    <p class="text-light">Hours Spent</p>
                                </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Contenus</span>
                                            <h3 class="mb-0 fs-20">{{$c}}</h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-primary bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-primary">auto_stories</i>
                                                </div> 
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-success">timeline</i>5
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Media</span>
                                            <h3 class="mb-0 fs-20"> {{$m}} </h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-primary-div bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-primary-div">video_library</i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-danger">trending_down</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Users</span>
                                            <h3 class="mb-0 fs-20"> {{$u}}</h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-danger bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-danger">group</i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-success">trending_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


@endsection

@section('me')
    <h3>{{ Auth::user()->nom }}</h3>
@endsection