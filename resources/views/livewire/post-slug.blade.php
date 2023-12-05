<div>
   <div class="container">
       <div class="row">
           <div class="col-md-8">
               <div class="card">
                   <div class="card-body">
                       <h2 class="title" style="text-transform: uppercase;">{{ $post->title }}</h2>
                       <div class="row">
                           <div class="col-md-6">
                               <p class="my-2">
                                   <span><i class="far fa-user"></i></span>
                                   <span>Số lượng tuyển: {{ $post->limit }}</span>
                               </p>
                           </div>
                           <div class="col-md-6">
                               <p class="my-2">
                                   <span><i class="far fa-user"></i></span>
                                   <span>Số lượng tuyển: {{ $post->limit }}</span>
                               </p>
                           </div>
                           <div class="col-md-6">
                               <p class="my-2">
                                   <span><i class="far fa-calendar-alt"></i></span>
                                   <span>Hết hạn vào: {{ $post->end_date }}</span>
                               </p>
                           </div>
                           <div class="col-md-6">
                               <p class="my-2">
                                   <span><i class="fas fa-map-marker-alt"></i></span>
                                   <span>Địa điểm: {{ $post->address }}</span>
                               </p>
                           </div>
                       </div>
                          <div class="row">
                            <div class="col-md-12">
                                <h5 class="title">Mô tả công việc</h5>
                                <div class="content">
                                    {!! $post->content !!}
                                </div>
                            </div>
                          </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
