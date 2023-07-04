<section id="skills" class="section-padding section-connector">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <h1>Skills</h1>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($skill as $item)
                <div class="col-lg-3 col-sm-6 mt-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="px-5 border rounded-4 text-center card-skills">
                        <img class="p-2 img-skill" src="{{ url(Storage::url($item->image)) }}" alt="">

                        <h6 class="mt-2">{{ $item->skill }}</h6>
                        <p>{{ $item->skill_detail }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
