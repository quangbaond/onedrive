<div class="container">
    <h4 class="title" style="text-transform: uppercase;"><i class="fas fa-briefcase"></i>vị trí tuyển dụng</h4>
    <div>
        <div class="d-flex align-items-start industries-nav">
            <div class="nav flex-column nav-pills industries" id="v-pills-tab" role="tablist"
                 aria-orientation="vertical">
                @foreach ($industries as $industry)
                    <button @class(['nav-link', 'text-left' , 'active'=> $industry->id == $industryIdActive])
                            id="{{ 'v-pills'.$industryIdActive.'-tab' }}"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true"
                            wire:click="filterIndustry('{{ $industry->id }}')">
                        {{ $industry->name }}
                    </button>
                @endforeach
            </div>
            <div class="tab-content h-100" id="v-pills-tabContent">
                <div class="tab-pane fade show active h-100" id="v-pills-home" role="tabpanel"
                     aria-labelledby="{{ 'v-pills'.$industryIdActive.'-tab' }}">
                    <div class="job_home h-100">
                        <div class="container p-3 h-100">
{{--                            <div class="d-flex justify-content-between me-2 mx-2">--}}
{{--                                <p class="font-weight-bold">{{ $industryIdActive }}</p>--}}
{{--                                <p class="font-weight-bold">--}}
{{--                                    <a href="{{ route('posts.industry', $industryIdActive) }}">Xem tất cả--}}
{{--                                        <i class="fas fa-caret-right"></i>--}}
{{--                                    </a>--}}
{{--                                </p>--}}
{{--                            </div>--}}
                            <div class="row">
                                @foreach ($listPost as $post)
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card m-2" data-bs-toggle="tooltip"
                                                     data-bs-placement="top"
                                                     title="{{ $post->title }}">
                                                    <div class="card-body">
                                                        <a href="{{ route('posts.show', $post->slug) }}"
                                                           class="title">
                                                            {{ Str::limit($post['title'], 40) }}
                                                        </a>
                                                        <p class="company">
                                                            <i class="fas fa-building"></i>
                                                            {{ $post->company_name }}
                                                        </p>
                                                        <p class="city">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{$post->district->name . ' - ' . $post->province->name  }}
                                                        </p>
                                                        <div class="date_num">
                                                            <p>
                                                                <i class="far fa-user"></i>
                                                                Số lượng {{ $post->limit }} người
                                                            </p>
                                                            <p>
                                                                <i class="far fa-calendar-alt"></i>
                                                                <span class="des">{{ $post->end_date }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card-action">
                                                        <div class="d-flex justify-content-between mx-2">
                                                            <p>
                                                                <a class=""
                                                                   href="{{ route('posts.show', $post['slug']) }}">
                                                                    Xem chi tiết
                                                                    <i class="fas fa-caret-right"></i>
                                                                </a>
                                                            </p>
                                                            <p>
                                                                @if($post->is_new)
                                                                    <span class="new_job">Mới</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
